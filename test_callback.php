<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Transaction;
use App\Models\User;

// Ambil user pertama
$user = User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}

// Ambil transaksi terbaru
$transaction = Transaction::where('customer_email', $user->email)->latest()->first();
if (!$transaction) {
    echo "No transaction found\n";
    exit;
}

echo "Testing callback for transaction ID: {$transaction->id}\n";
echo "Merchant Ref: {$transaction->merchant_ref}\n";
echo "Current Status: {$transaction->status}\n\n";

// Simulasi data callback dari Tripay
$callbackData = [
    'merchant_ref' => $transaction->merchant_ref,
    'status' => 'PAID', // Ubah status menjadi PAID
    'amount' => $transaction->amount,
    'payment_method' => $transaction->payment_method,
    'customer_name' => $transaction->customer_name,
    'customer_email' => $transaction->customer_email,
    'created_at' => now()->toISOString(),
    'updated_at' => now()->toISOString()
];

// Simulasi signature (gunakan private key dari .env)
$privateKey = env('TRIPAY_PRIVATE_KEY', 'test_private_key');
$rawBody = json_encode($callbackData);
$signature = hash_hmac('sha256', $rawBody, $privateKey);

echo "Callback Data:\n";
echo json_encode($callbackData, JSON_PRETTY_PRINT) . "\n\n";

echo "Generated Signature: {$signature}\n\n";

// Simulasi HTTP request ke callback endpoint
$url = 'http://127.0.0.1:8000/tripay/callback';
$headers = [
    'Content-Type: application/json',
    'X-Callback-Signature: ' . $signature
];

echo "Sending POST request to: {$url}\n";
echo "Headers: " . json_encode($headers) . "\n\n";

// Gunakan cURL untuk simulasi request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $rawBody);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Response Code: {$httpCode}\n";
echo "Response: {$response}\n";

if ($error) {
    echo "cURL Error: {$error}\n";
}

// Cek status transaksi setelah callback
$updatedTransaction = Transaction::find($transaction->id);
echo "\nTransaction status after callback: {$updatedTransaction->status}\n";

echo "\nTest completed!\n"; 