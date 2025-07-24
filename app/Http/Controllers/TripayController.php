<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

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
  CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
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
        $privateKey = env('TRIPAY_PRIVATE_KEY');
        $callbackSignature = $request->header('X-Callback-Signature');
        $rawBody = $request->getContent();
        $computedSignature = hash_hmac('sha256', $rawBody, $privateKey);

        // Verifikasi signature
        if ($callbackSignature !== $computedSignature) {
            // Signature tidak valid
            Log::warning('Tripay callback signature mismatch', [
                'expected' => $computedSignature,
                'received' => $callbackSignature,
                'body' => $rawBody
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $data = $request->all();
        // Update status pembayaran di database
        $trx = Transaction::where('merchant_ref', $data['merchant_ref'] ?? null)->first();
        if ($trx) {
            $trx->status = $data['status'] ?? $trx->status;
            $trx->response = $data;
            $trx->save();
        }
        Log::info('Tripay callback received', $data);

        // Contoh response sukses
        return response()->json(['success' => true]);
    }
}
