<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TripayService;

class CheckoutController extends Controller
{
    public function __construct(private TripayService $tripay) {}

    public function beli(Request $request, $id)
    {
        $product = \App\Models\Product::find($id);
        if (!$product) {
            return redirect('/dashboard')->with('error', 'Produk tidak ditemukan.');
        }

        if (!$product->isInStock()) {
            return redirect('/dashboard')->with('error', 'Maaf, produk "' . $product->name . '" sedang habis stok.');
        }

        $user = Auth::user();

        $missingFields = [];
        if (empty($user->name))  $missingFields[] = 'Nama Lengkap';
        if (empty($user->email)) $missingFields[] = 'Email';
        if (empty($user->phone)) $missingFields[] = 'No HP';

        if (!empty($missingFields)) {
            return redirect()->route('profile')->with('error', 'Mohon lengkapi data profile terlebih dahulu: ' . implode(', ', $missingFields));
        }

        // Cek apakah ada transaksi pending yang menghabiskan stok
        $pendingTransactionCount = \App\Models\Transaction::where('product_id', $product->id)
            ->where('customer_email', $user->email)
            ->whereIn('status', ['UNPAID', 'PROCESSING'])
            ->count();

        if ($pendingTransactionCount > 0 && $product->stock <= $pendingTransactionCount) {
            return redirect('/dashboard')->with('error', 'Anda memiliki transaksi pending untuk produk "' . $product->name . '" yang belum selesai.');
        }

        $channels = $this->tripay->getPaymentChannels();

        // Jika service mengembalikan array kosong atau error, tetap lanjut dengan channels kosong
        $error = null;
        if (empty($channels)) {
            $error = 'Gagal memuat metode pembayaran. Silakan coba lagi.';
            Log::warning('Failed to load payment channels for checkout', ['product_id' => $id]);
        }

        $maxQty          = min($product->stock, 100);
        $initialQuantity = max(1, min($maxQty, (int) $request->get('quantity', 1)));

        return view('checkout', compact('product', 'channels', 'error', 'user', 'initialQuantity'));
    }
}
