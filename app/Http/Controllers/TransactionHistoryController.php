<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\PulsaTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransactionHistoryController extends Controller
{
    public function index()
    {
        $userEmail = Auth::user()->email;
        $transactions = Transaction::with(['product'])
            ->where('customer_email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaction-history', compact('transactions'));
    }

    public function show($merchantRef)
    {
        $userEmail = Auth::user()->email;

        $transaction = Transaction::with('product')
            ->where('merchant_ref', $merchantRef)
            ->where('customer_email', $userEmail)
            ->first();

        $isPulsa = false;
        if (!$transaction) {
            $transaction = PulsaTransaction::where('merchant_ref', $merchantRef)
                ->where('customer_email', $userEmail)
                ->first();
            if ($transaction) $isPulsa = true;
        }

        abort_if(!$transaction, 404);

        $response     = $isPulsa ? ($transaction->tripay_response ?? []) : ($transaction->response ?? []);
        $data         = $response['data'] ?? [];
        $callbackData = $isPulsa ? ($transaction->callback_data ?? []) : ($response['callback_data'] ?? []);

        $tripayRef   = $data['reference'] ?? ($callbackData['reference'] ?? '-');
        $paymentName = $data['payment_name'] ?? $transaction->payment_method ?? '-';

        $expiredTimestamp = $data['expired_time'] ?? null;
        $expiredTime = $expiredTimestamp
            ? Carbon::createFromTimestamp($expiredTimestamp)->format('d-m-Y H:i:s')
            : null;

        if ($isPulsa && $transaction->paid_at) {
            $paidAt = $transaction->paid_at->format('d-m-Y H:i:s');
        } elseif (!empty($callbackData['paid_at'])) {
            $paidAt = Carbon::createFromTimestamp($callbackData['paid_at'])->format('d-m-Y H:i:s');
        } else {
            $paidAt = null;
        }

        $feeMerchant    = $callbackData['fee_merchant']   ?? $data['fee_merchant']   ?? null;
        $feeCustomer    = $callbackData['fee_customer']   ?? $data['fee_customer']   ?? null;
        $amountReceived = $callbackData['amount_received'] ?? $data['amount_received'] ?? null;
        $totalFee       = ($feeMerchant !== null && $feeCustomer !== null)
            ? ($feeMerchant + $feeCustomer)
            : ($data['total_fee'] ?? null);

        $orderItems = $data['order_items'] ?? [];

        $statusMap = [
            'PAID'    => 'Lunas',
            'UNPAID'  => 'Menunggu Pembayaran',
            'FAILED'  => 'Gagal',
            'EXPIRED' => 'Kedaluwarsa',
            'SETTLED' => 'Selesai',
        ];
        $statusLabel = $statusMap[strtoupper($transaction->status)] ?? $transaction->status;

        return view('transaction-detail', compact(
            'transaction', 'isPulsa', 'tripayRef', 'paymentName',
            'expiredTime', 'paidAt', 'feeMerchant', 'feeCustomer',
            'totalFee', 'amountReceived', 'statusLabel', 'orderItems'
        ));
    }

    public function updateStatus(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Pastikan transaksi milik user yang sedang login
        if ($transaction->customer_email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        if ($request->status === 'PAID' && $transaction->status !== 'PAID') {
            $product = Product::find($transaction->product_id);

            if ($product) {
                $quantity = $transaction->quantity ?? 1;

                if ($product->stock >= $quantity) {
                    $oldStock = $product->stock;
                    $product->stock -= $quantity;
                    $product->save();

                    Log::info('Stock produk berhasil dikurangi setelah pembayaran', [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'old_stock'    => $oldStock,
                        'new_stock'    => $product->stock,
                        'quantity'     => $quantity,
                        'transaction_id' => $transaction->id,
                    ]);
                } else {
                    Log::warning('Stok produk tidak mencukupi saat update status', [
                        'product_id'     => $product->id,
                        'current_stock'  => $product->stock,
                        'quantity'       => $quantity,
                        'transaction_id' => $transaction->id,
                    ]);
                }
            } else {
                Log::error('Produk tidak ditemukan saat update status', [
                    'product_id'     => $transaction->product_id,
                    'transaction_id' => $transaction->id,
                ]);
            }
        }

        $transaction->update([
            'status'   => $request->status,
            'response' => array_merge($transaction->response ?? [], [
                'manual_status_update' => [
                    'status'     => $request->status,
                    'updated_at' => now()->toISOString(),
                    'updated_by' => Auth::user()->email,
                ],
            ]),
        ]);

        return response()->json(['success' => true]);
    }

    public function manualUpdateStatus(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Pastikan transaksi milik user yang sedang login
        if ($transaction->customer_email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        $transaction->update([
            'status'   => $newStatus,
            'response' => array_merge($transaction->response ?? [], [
                'manual_update' => true,
                'updated_at'    => now()->toISOString(),
                'old_status'    => $oldStatus,
                'new_status'    => $newStatus,
                'updated_by'    => Auth::user()->email,
            ]),
        ]);

        return response()->json([
            'success'     => true,
            'message'     => "Status transaksi ID {$transaction->id} berhasil diupdate dari {$oldStatus} ke {$newStatus}",
            'transaction' => $transaction,
        ]);
    }

    public function viewCallbackLogs()
    {
        $transactions = Transaction::where('customer_email', Auth::user()->email)
            ->whereNotNull('response')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('callback-logs', compact('transactions'));
    }

    public function testCallback($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->customer_email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        $callbackData = [
            'merchant_ref'   => $transaction->merchant_ref,
            'status'         => 'PAID',
            'amount'         => $transaction->amount,
            'payment_method' => $transaction->payment_method,
            'customer_name'  => $transaction->customer_name,
            'customer_email' => $transaction->customer_email,
            'created_at'     => now()->toISOString(),
            'updated_at'     => now()->toISOString(),
        ];

        $privateKey = config('services.tripay.private_key');
        $rawBody    = json_encode($callbackData);
        $signature  = hash_hmac('sha256', $rawBody, $privateKey);

        $url     = url('/tripay/callback');
        $headers = [
            'Content-Type: application/json',
            'X-Callback-Signature: ' . $signature,
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
        $error    = curl_error($ch);
        curl_close($ch);

        return response()->json([
            'success'       => $httpCode === 200,
            'http_code'     => $httpCode,
            'response'      => $response,
            'error'         => $error,
            'callback_data' => $callbackData,
            'signature'     => $signature,
        ]);
    }
}
