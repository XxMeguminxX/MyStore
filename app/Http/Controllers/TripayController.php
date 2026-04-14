<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Transaction;
use App\Models\Product;
use App\Mail\PaymentSuccessMail;

class TripayController extends Controller
{
    public function thankYou(Request $request)
    {
        $merchantRef = $request->query('merchant_ref');
        $transaction = null;
        if ($merchantRef) {
            $transaction = Transaction::with('product')->where('merchant_ref', $merchantRef)->first();
        }
        return view('thank-you', [
            'transaction' => $transaction,
            'merchant_ref' => $merchantRef,
        ]);
    }

    public function checkPaymentStatus(Request $request)
    {
        $merchantRef = $request->query('merchant_ref');
        if (!$merchantRef) {
            return response()->json(['error' => 'merchant_ref diperlukan'], 400);
        }

        $transaction = Transaction::where('merchant_ref', $merchantRef)->first();
        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'status'  => $transaction->status,
            'paid_at' => $transaction->updated_at?->toISOString(),
        ]);
    }

    /**
     * Membuat transaksi ke Tripay
     */
    public function createTransaction(Request $request)
    {
        $requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'amount', 'product_sku', 'product_name', 'quantity'];
        $missingFields  = [];

        foreach ($requiredFields as $field) {
            if (empty($request->input($field))) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return response()->json([
                'success' => false,
                'error'   => 'Parameter yang diperlukan tidak lengkap: ' . implode(', ', $missingFields),
            ], 400);
        }

        $quantity = (int) $request->quantity;
        if ($quantity < 1) {
            return response()->json(['success' => false, 'error' => 'Quantity minimal 1'], 400);
        }
        if ($quantity > 100) {
            return response()->json(['success' => false, 'error' => 'Quantity maksimal 100 per transaksi'], 400);
        }

        $product = Product::find($request->product_sku);
        if (!$product) {
            return response()->json(['success' => false, 'error' => 'Produk tidak ditemukan.'], 404);
        }

        if (!$product->hasEnoughStock($quantity)) {
            Log::warning('Payment attempt for insufficient stock', [
                'product_id'         => $product->id,
                'product_name'       => $product->name,
                'current_stock'      => $product->stock,
                'requested_quantity' => $quantity,
            ]);
            $message = $product->stock <= 0
                ? 'Maaf, produk "' . $product->name . '" sedang habis stok.'
                : 'Maaf, stok produk "' . $product->name . '" tidak mencukupi. Stok tersedia: ' . $product->stock . ', diminta: ' . $quantity;

            return response()->json(['success' => false, 'error' => $message], 400);
        }

        $originalStock = $product->stock;
        $reservedStock = null;

        try {
            DB::transaction(function () use ($product, &$reservedStock, $quantity) {
                $product = $product->lockForUpdate()->find($product->id);

                if (!$product->hasEnoughStock($quantity)) {
                    throw new \Exception('Stok produk tidak mencukupi untuk quantity ' . $quantity . '. Stok tersedia: ' . $product->stock);
                }

                $product->stock -= $quantity;
                $product->save();
                $reservedStock = $product->stock;
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }

        $apiKey       = config('services.tripay.api_key');
        $merchantCode = config('services.tripay.merchant_code');
        $privateKey   = config('services.tripay.private_key');

        if (empty($apiKey) || empty($merchantCode) || empty($privateKey)) {
            // Kembalikan stok jika konfigurasi tidak lengkap
            DB::transaction(function () use ($product, $quantity) {
                $product->lockForUpdate()->find($product->id)->increment('stock', $quantity);
            });
            return response()->json([
                'success' => false,
                'error'   => 'Konfigurasi Tripay tidak lengkap. Silakan hubungi administrator.',
            ], 500);
        }

        $merchant_ref = 'INV-' . time();
        $data = [
            'method'         => $request->payment_method,
            'merchant_ref'   => $merchant_ref,
            'amount'         => (int) $request->amount,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'order_items'    => [
                [
                    'sku'      => $request->product_sku,
                    'name'     => $request->product_name,
                    'price'    => (int) ($request->amount / $quantity),
                    'quantity' => $quantity,
                ],
            ],
            'return_url'   => url('/payment/thank-you?merchant_ref=' . $merchant_ref),
            'callback_url' => url('/tripay/callback'),
            'expired_time' => (time() + (24 * 60 * 60)),
            'signature'    => hash_hmac('sha256', $merchantCode . $merchant_ref . $request->amount, $privateKey),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => config('services.tripay.base_url') . '/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        $decoded = json_decode($response);

        if ($error) {
            $this->returnStock($product, $quantity);
            return response()->json(['success' => false, 'error' => $error], 500);
        }

        $paymentUrl = $decoded->data->payment_url ?? $decoded->data->checkout_url ?? null;

        if (isset($decoded->success) && $decoded->success && $paymentUrl) {
            $transaction = Transaction::create([
                'merchant_ref'        => $merchant_ref,
                'product_id'          => $request->product_sku,
                'customer_name'       => $request->customer_name,
                'customer_email'      => $request->customer_email,
                'customer_phone'      => $request->customer_phone,
                'amount'              => (int) $request->amount,
                'quantity'            => $quantity,
                'payment_method'      => $request->payment_method,
                'status'              => 'UNPAID',
                'payment_url'         => $paymentUrl,
                'response'            => $decoded,
                'type'                => $request->transaction_type ?? 'product',
                'stock_snapshot'      => $originalStock,
                'reserved_stock'      => $reservedStock,
                'checkout_created_at' => now(),
            ]);

            Log::info('Transaction created', [
                'merchant_ref'   => $merchant_ref,
                'product_id'     => $request->product_sku,
                'original_stock' => $originalStock,
                'reserved_stock' => $reservedStock,
            ]);

            return response()->json(['success' => true, 'data' => $decoded->data, 'payment_url' => $paymentUrl]);
        }

        // Gagal buat transaksi — kembalikan stok
        $this->returnStock($product, $quantity);

        Log::error('Tripay create transaction failed', [
            'decoded'      => $decoded,
            'raw_response' => $response,
        ]);

        return response()->json([
            'success'  => false,
            'error'    => $decoded->message ?? 'Gagal membuat transaksi',
            'response' => $decoded,
        ], 400);
    }

    /**
     * Handle callback notifikasi dari Tripay
     */
    public function handleCallback(Request $request)
    {
        $privateKey        = config('services.tripay.private_key');
        $callbackSignature = $request->header('X-Callback-Signature');
        $callbackEvent     = $request->header('X-Callback-Event');
        $rawBody           = $request->getContent();

        Log::info('Tripay callback received', [
            'event'           => $callbackEvent,
            'raw_body_length' => strlen($rawBody),
        ]);

        if (!empty($callbackEvent) && strtolower($callbackEvent) !== 'payment_status') {
            return response()->json(['success' => true, 'message' => 'Event ignored']);
        }

        if (!empty($privateKey)) {
            $computedSignature = hash_hmac('sha256', $rawBody, $privateKey);
            if (!hash_equals($computedSignature, (string) $callbackSignature)) {
                Log::warning('Tripay callback signature mismatch');
                return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
            }
        } else {
            Log::warning('Tripay private key not found, skipping signature verification');
        }

        $data = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        $merchantRef = $data['merchant_ref'] ?? null;
        if (!$merchantRef) {
            return response()->json(['success' => false, 'message' => 'Missing merchant_ref'], 400);
        }

        $trx = Transaction::where('merchant_ref', $merchantRef)->first();
        if (!$trx) {
            Log::error('Tripay callback transaction not found', ['merchant_ref' => $merchantRef]);
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        $oldStatus = $trx->status;
        $newStatus = $data['status'] ?? $oldStatus;

        // Validasi jumlah pembayaran
        $callbackAmount = $data['amount'] ?? ($data['total_amount'] ?? null);
        if ($callbackAmount !== null && (int) $callbackAmount !== (int) $trx->amount) {
            Log::warning('Tripay callback amount mismatch', [
                'merchant_ref'    => $merchantRef,
                'expected_amount' => $trx->amount,
                'received_amount' => $callbackAmount,
            ]);
            return response()->json(['success' => true, 'message' => 'Amount mismatch, ignored']);
        }

        $trx->status   = $newStatus;
        $trx->response = array_merge($trx->response ?? [], [
            'callback_data'       => $data,
            'callback_received_at' => now()->toISOString(),
            'status_updated_from' => $oldStatus,
            'status_updated_to'   => $newStatus,
        ]);
        $trx->save();

        // Kirim email jika baru dibayar
        if (in_array($newStatus, ['PAID', 'SETTLED']) && !in_array($oldStatus, ['PAID', 'SETTLED'])) {
            $product = Product::find($trx->product_id);

            if ($product) {
                Log::info('Payment confirmed - stock already reserved during checkout', [
                    'product_id'           => $product->id,
                    'current_stock'        => $product->stock,
                    'reserved_stock'       => $trx->reserved_stock,
                    'transaction_quantity' => $trx->quantity ?? 1,
                    'merchant_ref'         => $merchantRef,
                ]);
            } else {
                Log::error('Product not found during payment callback', [
                    'product_id'     => $trx->product_id,
                    'transaction_id' => $trx->id,
                ]);
            }

            if ($product && $trx->customer_email) {
                try {
                    Mail::to($trx->customer_email)->send(new PaymentSuccessMail($trx, $product));
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email', [
                        'transaction_id' => $trx->id,
                        'error'          => $e->getMessage(),
                    ]);
                }
            }
        }

        Log::info('Tripay callback processed', [
            'merchant_ref'   => $merchantRef,
            'status_updated' => $oldStatus . ' -> ' . $newStatus,
        ]);

        return response()->json(['success' => true, 'message' => 'Callback processed successfully']);
    }

    /**
     * Kembalikan stok produk secara aman dalam DB transaction
     */
    private function returnStock(Product $product, int $quantity): void
    {
        try {
            DB::transaction(function () use ($product, $quantity) {
                $product->lockForUpdate()->find($product->id)->increment('stock', $quantity);
            });
            Log::info('Stock returned after failed transaction', [
                'product_id' => $product->id,
                'quantity'   => $quantity,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to return stock after failed transaction', [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
