<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return response()->json(['status' => 'ok']);
    }
}
