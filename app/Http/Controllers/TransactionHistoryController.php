<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi user yang sedang login berdasarkan email dengan eager loading product dan donasi
        $userEmail = Auth::user()->email;
        $transactions = Transaction::with(['product', 'donasi'])
            ->where('customer_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaction-history', compact('transactions'));
    }

    public function updateStatus(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Tambahkan logika ini untuk mengurangi stok
        if ($request->status === 'PAID' && $transaction->status !== 'PAID') {
            // Asumsi model Transaction memiliki relasi ke model Product
            // atau memiliki kolom product_id
            $product = Product::find($transaction->product_id); // Asumsi kolom product_id ada di tabel transactions

            if ($product) {
                // Pastikan stock tidak menjadi negatif
                if ($product->stock > 0) {
                    $oldStock = $product->stock;
                    $product->stock = $product->stock - 1; // Kurangi 1 dari stok
                    $product->save(); // Simpan perubahan ke database

                    Log::info("Stock produk berhasil dikurangi setelah pembayaran", [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'old_stock' => $oldStock,
                        'new_stock' => $product->stock,
                        'transaction_id' => $transaction->id
                    ]);
                } else {
                    // Log atau tangani jika stok habis
                    Log::warning("Stok produk ID {$product->id} habis saat transaksi ID {$transaction->id} dibayar.");
                }
            } else {
                Log::error("Produk dengan ID {$transaction->product_id} tidak ditemukan.");
            }
        }
        
        // Update status pembayaran
        $transaction->update([
            'status' => $request->status,
            'response' => $request->all() // Simpan response dari payment gateway
        ]);

        return response()->json(['success' => true]);
    }

    public function manualUpdateStatus(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);
        $oldStatus = $transaction->status;
        $newStatus = $request->status;
        
        // Update status pembayaran
        $transaction->update([
            'status' => $newStatus,
            'response' => array_merge($transaction->response ?? [], [
                'manual_update' => true,
                'updated_at' => now(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status transaksi ID {$transaction->id} berhasil diupdate dari {$oldStatus} ke {$newStatus}",
            'transaction' => $transaction
        ]);
    }

    public function viewCallbackLogs()
    {
        // Ambil transaksi dengan response yang berisi callback data
        $transactions = Transaction::where('customer_email', Auth::user()->email)
            ->whereNotNull('response')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('callback-logs', compact('transactions'));
    }

    public function testCallback($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        // Pastikan transaksi milik user yang sedang login
        if ($transaction->customer_email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        // Simulasi callback data
        $callbackData = [
            'merchant_ref' => $transaction->merchant_ref,
            'status' => 'PAID',
            'amount' => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'customer_name' => $transaction->customer_name,
            'customer_email' => $transaction->customer_email,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ];

        // Generate signature
        $privateKey = env('TRIPAY_PRIVATE_KEY', 'test_private_key');
        $rawBody = json_encode($callbackData);
        $signature = hash_hmac('sha256', $rawBody, $privateKey);

        // Simulasi HTTP request
        $url = url('/tripay/callback');
        $headers = [
            'Content-Type: application/json',
            'X-Callback-Signature: ' . $signature
        ];

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

        return response()->json([
            'success' => $httpCode === 200,
            'http_code' => $httpCode,
            'response' => $response,
            'error' => $error,
            'callback_data' => $callbackData,
            'signature' => $signature
        ]);
    }
}