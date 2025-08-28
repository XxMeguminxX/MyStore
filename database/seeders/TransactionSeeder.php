<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama untuk testing
        $user = User::first();
        
        if (!$user) {
            return;
        }

        // Hapus transaksi yang sudah ada untuk menghindari duplikasi
        Transaction::where('customer_email', $user->email)->delete();

        // Buat beberapa transaksi dengan status berbeda (produk dan donasi)
        $transactions = [
            [
                'merchant_ref' => 'REF' . time() . '001',
                'product_id' => 1, // Product ID (POCO F6)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 4612000,
                'payment_method' => 'QRIS',
                'status' => 'UNPAID',
                'payment_url' => 'https://example.com/payment1',
                'response' => json_encode(['status' => 'pending'])
            ],
            [
                'merchant_ref' => 'REF' . time() . '002',
                'product_id' => 2, // Donasi ID (Fanta)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 10000,
                'payment_method' => 'QRIS',
                'status' => 'PAID',
                'payment_url' => 'https://example.com/payment_donasi2',
                'response' => json_encode(['status' => 'success'])
            ],
            [
                'merchant_ref' => 'REF' . time() . '003',
                'product_id' => 2, // Product ID (POCO F6)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 4612000,
                'payment_method' => 'BNI',
                'status' => 'PAID',
                'payment_url' => 'https://example.com/payment2',
                'response' => json_encode(['status' => 'success'])
            ],
            [
                'merchant_ref' => 'REF' . time() . '004',
                'product_id' => 3, // Donasi ID (Boba)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 15000,
                'payment_method' => 'Permata',
                'status' => 'EXPIRED',
                'payment_url' => 'https://example.com/payment_donasi3',
                'response' => json_encode(['status' => 'expired'])
            ],
            [
                'merchant_ref' => 'REF' . time() . '005',
                'product_id' => 1, // Product ID (POCO F6)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 4612000,
                'payment_method' => 'QRIS',
                'status' => 'CANCELLED',
                'payment_url' => 'https://example.com/payment4',
                'response' => json_encode(['status' => 'cancelled'])
            ],
            [
                'merchant_ref' => 'REF' . time() . '006',
                'product_id' => 4, // Donasi ID (Sandwich)
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'amount' => 25000,
                'payment_method' => 'BNI',
                'status' => 'PENDING',
                'payment_url' => 'https://example.com/payment_donasi4',
                'response' => json_encode(['status' => 'pending'])
            ]
        ];

        foreach ($transactions as $transactionData) {
            $transaction = new Transaction();
            $transaction->merchant_ref = $transactionData['merchant_ref'];
            $transaction->product_id = $transactionData['product_id'];
            $transaction->customer_name = $transactionData['customer_name'];
            $transaction->customer_email = $transactionData['customer_email'];
            $transaction->customer_phone = $transactionData['customer_phone'];
            $transaction->amount = $transactionData['amount'];
            $transaction->payment_method = $transactionData['payment_method'];
            $transaction->status = $transactionData['status'];
            $transaction->payment_url = $transactionData['payment_url'];
            $transaction->response = $transactionData['response'];
            $transaction->save();
        }
    }
} 