<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Mail\PaymentSuccessMail;

class TripayController extends Controller
{
    public function thankYou(Request $request)
    {
        $merchantRef = $request->query('merchant_ref');
        $transaction = null;
        if ($merchantRef) {
            $transaction = Transaction::where('merchant_ref', $merchantRef)->first();
        }
        return view('thank-you', [
            'transaction' => $transaction,
            'merchant_ref' => $merchantRef,
        ]);
    }

    public function getPaymentChannels()
    {
        $apiKey = env('TRIPAY_API_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        $decoded = json_decode($response);

        if (isset($decoded->data)) {
            return $decoded->data;
        } else {
            // Optionally, log $decoded or $error for debugging
            return [
                'error' => $error ?: ($decoded->message ?? 'Unknown error'),
                'response' => $decoded
            ];
        }
    }

    /**
     * Membuat transaksi ke Tripay
     */
    public function createTransaction(Request $request)
    {
        // Validasi input yang diperlukan
        $requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'amount', 'product_sku', 'product_name', 'quantity'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($request->input($field))) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return response()->json([
                'success' => false,
                'error' => 'Parameter yang diperlukan tidak lengkap: ' . implode(', ', $missingFields)
            ], 400);
        }

        // Validasi quantity
        $quantity = (int) $request->quantity;
        if ($quantity < 1) {
            return response()->json([
                'success' => false,
                'error' => 'Quantity minimal 1'
            ], 400);
        }

        if ($quantity > 100) { // Batas maksimal 100 per transaksi untuk mencegah abuse
            return response()->json([
                'success' => false,
                'error' => 'Quantity maksimal 100 per transaksi'
            ], 400);
        }

        // Validasi produk dan stock sebelum memproses pembayaran
        $product = Product::find($request->product_sku);
        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => 'Produk tidak ditemukan.'
            ], 404);
        }

        // Cek stock dengan validasi ketat berdasarkan quantity yang diminta
        if (!$product->isInStock()) {
            \Illuminate\Support\Facades\Log::warning('Payment attempt for out of stock product', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'current_stock' => $product->stock,
                'requested_quantity' => $quantity,
                'user_email' => $request->customer_email,
                'attempted_at' => now()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Maaf, produk "' . $product->name . '" sedang habis stok.'
            ], 400);
        }

        // Pastikan stock cukup untuk quantity yang diminta
        if (!$product->hasEnoughStock($quantity)) {
            \Illuminate\Support\Facades\Log::warning('Payment attempt for insufficient stock', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'current_stock' => $product->stock,
                'requested_quantity' => $quantity,
                'user_email' => $request->customer_email,
                'attempted_at' => now()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Maaf, stok produk "' . $product->name . '" tidak mencukupi. Stok tersedia: ' . $product->stock . ', diminta: ' . $quantity
            ], 400);
        }

        // Gunakan database transaction dan lock untuk mencegah race condition
        $reservedStock = null;
        $originalStock = $product->stock;

        // Gunakan database transaction untuk atomic operation
        \Illuminate\Support\Facades\DB::transaction(function () use ($product, &$reservedStock) {
            // Lock row produk untuk mencegah concurrent access
            $product = $product->lockForUpdate()->find($product->id);

            // Double-check stock setelah lock berdasarkan quantity
            if (!$product->isInStock()) {
                throw new \Exception('Produk habis stok saat pemesanan.');
            }

            if (!$product->hasEnoughStock($quantity)) {
                throw new \Exception('Stok produk tidak mencukupi untuk quantity ' . $quantity . '. Stok tersedia: ' . $product->stock);
            }

            // Reserve stock dengan mengurangi sesuai quantity
            $product->stock -= $quantity;
            $product->save();

            $reservedStock = $product->stock;
        });

        $apiKey = env('TRIPAY_API_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $privateKey = env('TRIPAY_PRIVATE_KEY');

        // Validasi environment variables
        if (empty($apiKey) || empty($merchantCode) || empty($privateKey)) {
            return response()->json([
                'success' => false, 
                'error' => 'Konfigurasi Tripay tidak lengkap. Silakan hubungi administrator.'
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
                    'sku'   => $request->product_sku,
                    'name'  => $request->product_name,
                    'price' => (int) ($request->amount / $quantity), // Harga per item
                    'quantity' => $quantity
                ]
            ],
            'return_url'     => url('/payment/thank-you?merchant_ref=' . $merchant_ref),
            'callback_url'   => url('/tripay/callback'),
            'expired_time'   => (time() + (24 * 60 * 60)),
            'signature'      => hash_hmac('sha256', $merchantCode . $merchant_ref . $request->amount, $privateKey)
        ];

        // Log data yang akan dikirim untuk debugging
        Log::info('Creating Tripay transaction', [
            'merchant_ref' => $merchant_ref,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'amount' => $request->amount
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data)
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        $decoded = json_decode($response);
        if ($error) {
            return response()->json(['success' => false, 'error' => $error], 500);
        }
        $paymentUrl = $decoded->data->payment_url ?? $decoded->data->checkout_url ?? null;

        if (isset($decoded->success) && $decoded->success && $paymentUrl) {
            // Tentukan type transaksi berdasarkan parameter atau logika bisnis
            $transactionType = $request->transaction_type ?? 'product';

            // Simpan transaksi ke database dengan stock snapshot
            $transaction = Transaction::create([
                'merchant_ref'   => $merchant_ref,
                'product_id'     => $request->product_sku,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'amount'         => (int) $request->amount,
                'quantity'       => $quantity,
                'payment_method' => $request->payment_method,
                'status'         => 'UNPAID',
                'payment_url'    => $paymentUrl,
                'response'       => $decoded,
                'type'           => $transactionType,
                'stock_snapshot' => $originalStock, // Simpan stock sebelum reservasi
                'reserved_stock' => $reservedStock, // Simpan stock setelah reservasi
                'checkout_created_at' => now(),
            ]);

            Log::info('Transaction created with stock reservation', [
                'merchant_ref' => $merchant_ref,
                'product_id' => $request->product_sku,
                'original_stock' => $originalStock,
                'reserved_stock' => $reservedStock,
                'checkout_created_at' => now()
            ]);
            return response()->json(['success' => true, 'data' => $decoded->data, 'payment_url' => $paymentUrl]);
        } else {
            // Jika transaksi gagal, kembalikan stock yang sudah di-reserve sesuai quantity
            if ($reservedStock !== null) {
                try {
                    $product->stock += $quantity; // Kembalikan stock sesuai quantity yang di-reserve
                    $product->save();

                    Log::info('Stock returned after failed transaction creation', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity_returned' => $quantity,
                        'returned_stock' => $product->stock,
                        'original_stock' => $originalStock
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to return stock after failed transaction', [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Tambahkan log untuk debug
            Log::error('Tripay create transaction failed', [
                'decoded' => $decoded,
                'raw_response' => $response,
                'data_sent' => $data
            ]);
            return response()->json([
                'success' => false,
                'error' => $decoded->message ?? 'Gagal membuat transaksi',
                'response' => $decoded,
                'raw_response' => $response,
                'data_sent' => $data
            ], 400);
        }
    }

    /**
     * Handle callback notifikasi dari Tripay
     */
    public function handleCallback(Request $request)
    {
        // Log semua data yang diterima untuk debugging
        Log::info('Tripay callback received - Raw data', [
            'headers' => $request->headers->all(),
            'body' => $request->getContent(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $privateKey = env('TRIPAY_PRIVATE_KEY');
        $callbackSignature = $request->header('X-Callback-Signature');
        $callbackEvent = $request->header('X-Callback-Event');
        $rawBody = $request->getContent();
        
        // Log signature verification
        Log::info('Tripay callback signature verification', [
            'received_signature' => $callbackSignature,
            'raw_body_length' => strlen($rawBody),
            'private_key_exists' => !empty($privateKey),
            'event' => $callbackEvent
        ]);

        // Opsional: Abaikan event selain payment_status (sesuai dokumentasi Tripay)
        if (!empty($callbackEvent) && strtolower($callbackEvent) !== 'payment_status') {
            Log::info('Tripay callback ignored due to non payment_status event', [
                'event' => $callbackEvent
            ]);
            return response()->json(['success' => true, 'message' => 'Event ignored']);
        }

        // Verifikasi signature jika private key ada
        if (!empty($privateKey)) {
            $computedSignature = hash_hmac('sha256', $rawBody, $privateKey);
            
            if ($callbackSignature !== $computedSignature) {
                Log::warning('Tripay callback signature mismatch', [
                    'expected' => $computedSignature,
                    'received' => $callbackSignature,
                    'body' => $rawBody
                ]);
                return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
            }
        } else {
            Log::warning('Tripay private key not found, skipping signature verification');
        }

        // Parse JSON body
        $data = json_decode($rawBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Tripay callback JSON parse error', [
                'error' => json_last_error_msg(),
                'raw_body' => $rawBody
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }

        Log::info('Tripay callback parsed data', $data);

        // Cari transaksi berdasarkan merchant_ref
        $merchantRef = $data['merchant_ref'] ?? null;
        if (!$merchantRef) {
            Log::error('Tripay callback missing merchant_ref', $data);
            return response()->json(['success' => false, 'message' => 'Missing merchant_ref'], 400);
        }

        $trx = Transaction::where('merchant_ref', $merchantRef)->first();
        if (!$trx) {
            Log::error('Tripay callback transaction not found', [
                'merchant_ref' => $merchantRef,
                'callback_data' => $data
            ]);
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }

        // Update status pembayaran
        $oldStatus = $trx->status;
        $newStatus = $data['status'] ?? $oldStatus;

        // Validasi jumlah pembayaran jika tersedia di payload
        $callbackAmount = $data['amount'] ?? ($data['total_amount'] ?? null);
        if ($callbackAmount !== null && (int) $callbackAmount !== (int) $trx->amount) {
            Log::warning('Tripay callback amount mismatch', [
                'merchant_ref' => $merchantRef,
                'expected_amount' => $trx->amount,
                'received_amount' => $callbackAmount
            ]);
            // Tetap kembalikan 200 agar Tripay tidak retry terus, tetapi jangan update status
            return response()->json(['success' => true, 'message' => 'Amount mismatch, ignored']);
        }

        Log::info('Tripay callback updating transaction status', [
            'transaction_id' => $trx->id,
            'merchant_ref' => $merchantRef,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'callback_data' => $data
        ]);

        // Update transaksi
        $trx->status = $newStatus;
        $trx->response = array_merge($trx->response ?? [], [
            'callback_data' => $data,
            'callback_received_at' => now()->toISOString(),
            'status_updated_from' => $oldStatus,
            'status_updated_to' => $newStatus
        ]);
        $trx->save();

        // Kirim email jika status berubah menjadi PAID atau SETTLED
        if (($newStatus === 'PAID' || $newStatus === 'SETTLED') && !in_array($oldStatus, ['PAID', 'SETTLED'])) {
            $product = Product::find($trx->product_id);

            if (!$product) {
                Log::error('Product not found during payment callback', [
                    'product_id' => $trx->product_id,
                    'transaction_id' => $trx->id
                ]);
            } else {
                // Double-check stock sebelum mengurangi (extra validation)
                if (!$product->isInStock()) {
                    Log::error('Payment completed but product is out of stock', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'current_stock' => $product->stock,
                        'transaction_id' => $trx->id
                    ]);

                    // TODO: Implementasi refund otomatis atau notifikasi admin
                    // Karena ini adalah kasus yang jarang terjadi, untuk sementara kita log saja
                } elseif ($product->stock < 1) {
                    Log::error('Payment completed but insufficient stock', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'current_stock' => $product->stock,
                        'transaction_id' => $trx->id
                    ]);
                } else {
                    // Validasi stock snapshot sebelum mengurangi stock
                    $stockSnapshot = $trx->stock_snapshot;
                    $currentStock = $product->stock;

                    // Jika stock snapshot tidak ada atau berbeda dari current stock,
                    // ada kemungkinan perubahan stock antara checkout dan pembayaran
                    if ($stockSnapshot !== null && $stockSnapshot !== $currentStock) {
                        Log::warning('Stock mismatch detected during payment callback', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'stock_snapshot' => $stockSnapshot,
                            'current_stock' => $currentStock,
                            'difference' => $currentStock - $stockSnapshot,
                            'transaction_id' => $trx->id,
                            'merchant_ref' => $merchantRef,
                            'checkout_created_at' => $trx->checkout_created_at
                        ]);

                        // Jika current stock masih cukup, lanjutkan dengan current stock
                        if ($currentStock >= 1) {
                            Log::info('Proceeding with current stock despite snapshot mismatch', [
                                'product_id' => $product->id,
                                'current_stock' => $currentStock
                            ]);
                        } else {
                            Log::error('Stock insufficient even with snapshot mismatch', [
                                'product_id' => $product->id,
                                'stock_snapshot' => $stockSnapshot,
                                'current_stock' => $currentStock,
                                'transaction_id' => $trx->id
                            ]);

                            // TODO: Implementasi refund atau notifikasi admin
                            // Untuk sementara kita lanjutkan tapi dengan flag khusus
                        }
                    }

                    // Kurangi stok produk dengan validasi ketat berdasarkan quantity
                    // Untuk transaksi yang sudah di-reserve, stok sudah dikurangi sebelumnya
                    // di createTransaction method, jadi tidak perlu dikurangi lagi
                    $transactionQuantity = $trx->quantity ?? 1; // Default ke 1 untuk backward compatibility

                    Log::info('Payment confirmed - stock already reserved during checkout', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'transaction_quantity' => $transactionQuantity,
                        'current_stock' => $product->stock,
                        'stock_snapshot' => $stockSnapshot,
                        'reserved_stock' => $trx->reserved_stock,
                        'transaction_id' => $trx->id,
                        'merchant_ref' => $merchantRef
                    ]);
                }
            }
            if ($product && $trx->customer_email) {
                try {
                    Mail::to($trx->customer_email)->send(new PaymentSuccessMail($trx, $product));
                    Log::info('Payment success email sent', [
                        'transaction_id' => $trx->id,
                        'merchant_ref' => $merchantRef,
                        'customer_email' => $trx->customer_email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment success email', [
                        'transaction_id' => $trx->id,
                        'merchant_ref' => $merchantRef,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        Log::info('Tripay callback processed successfully', [
            'transaction_id' => $trx->id,
            'merchant_ref' => $merchantRef,
            'status_updated' => $oldStatus . ' -> ' . $newStatus
        ]);

        return response()->json(['success' => true, 'message' => 'Callback processed successfully']);
    }
}