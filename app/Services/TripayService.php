<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TripayService
{
    /**
     * Ambil daftar payment channels dari TriPay.
     */
    public function getPaymentChannels(): array
    {
        $apiKey = config('services.tripay.api_key');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => config('services.tripay.base_url') . '/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_TIMEOUT        => 15,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('TripayService::getPaymentChannels curl error', ['error' => $error]);
            return [];
        }

        $decoded = json_decode($response, true);

        if (isset($decoded['data']) && is_array($decoded['data'])) {
            return $decoded['data'];
        }

        Log::warning('TripayService::getPaymentChannels unexpected response', ['response' => $response]);
        return [];
    }

    /**
     * Buat transaksi ke TriPay payment gateway.
     */
    public function createTransaction(array $payload): array
    {
        $apiKey = config('services.tripay.api_key');

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
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('TripayService::createTransaction curl error', ['error' => $error]);
            return ['success' => false, 'message' => $error];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('TripayService::createTransaction JSON parse error', ['raw' => $response]);
            return ['success' => false, 'message' => 'Invalid response from payment gateway'];
        }

        return $decoded ?? [];
    }

    /**
     * Verifikasi signature callback dari TriPay.
     */
    public function verifyCallbackSignature(string $rawBody, string $signature): bool
    {
        $expected = hash_hmac('sha256', $rawBody, config('services.tripay.private_key'));
        return hash_equals($expected, $signature);
    }
}
