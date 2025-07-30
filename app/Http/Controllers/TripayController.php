<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Mail\PaymentSuccessMail;

class TripayController extends Controller
{
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
        $apiKey = env('TRIPAY_API_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $privateKey = env('TRIPAY_PRIVATE_KEY');

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
                    'price' => (int) $request->amount,
                    'quantity' => 1
                ]
            ],
            'return_url'     => url('/dashboard'),
            'callback_url'   => url('/tripay/callback'),
            'expired_time'   => (time() + (24 * 60 * 60)),
            'signature'      => hash_hmac('sha256', $merchantCode . $merchant_ref . $request->amount, $privateKey)
        ];

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
            // Simpan transaksi ke database
            Transaction::create([
                'merchant_ref'   => $merchant_ref,
                'product_id'     => $request->product_sku,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'amount'         => (int) $request->amount,
                'payment_method' => $request->payment_method,
                'status'         => 'UNPAID',
                'payment_url'    => $paymentUrl,
                'response'       => $decoded,
            ]);
            return response()->json(['success' => true, 'data' => $decoded->data, 'payment_url' => $paymentUrl]);
        } else {
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
        $rawBody = $request->getContent();
        
        // Log signature verification
        Log::info('Tripay callback signature verification', [
            'received_signature' => $callbackSignature,
            'raw_body_length' => strlen($rawBody),
            'private_key_exists' => !empty($privateKey)
        ]);

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