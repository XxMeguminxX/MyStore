<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TripayController;

class CheckoutController extends Controller
{
    public function beli(Request $request, $id)
    {
        // Debug: Log authentication status
        \Illuminate\Support\Facades\Log::info('Checkout access attempt', [
            'user_id' => Auth::id(),
            'is_authenticated' => Auth::check(),
            'session_id' => session()->getId(),
            'product_id' => $id,
            'timestamp' => now()
        ]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            \Illuminate\Support\Facades\Log::warning('Unauthenticated user trying to access checkout', [
                'product_id' => $id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }

        $product = \App\Models\Product::where('id', '=', $id)->first();
        if ($product == null) {
            return redirect('/dashboard')->with('error', 'Produk tidak ditemukan.');
        }

        // Validasi stok yang lebih ketat
        if (!$product->isInStock()) {
            return redirect('/dashboard')->with('error', 'Maaf, produk "' . $product->name . '" sedang habis stok.');
        }

        // Pastikan stock minimal 1 untuk pembelian
        if ($product->stock < 1) {
            return redirect('/dashboard')->with('error', 'Maaf, stok produk "' . $product->name . '" tidak mencukupi untuk pembelian.');
        }

        // Cek apakah ada transaksi pending untuk produk ini oleh user yang sama
        // Hanya blokir jika stok akan menjadi tidak mencukupi
        $pendingTransactionCount = \App\Models\Transaction::where('product_id', $product->id)
            ->where('customer_email', Auth::user()->email)
            ->whereIn('status', ['UNPAID', 'PROCESSING'])
            ->count();

        // Jika ada transaksi pending dan stok akan menjadi tidak mencukupi setelah transaksi ini
        if ($pendingTransactionCount > 0 && $product->stock <= $pendingTransactionCount) {
            return redirect('/dashboard')->with('error', 'Anda memiliki transaksi pending untuk produk "' . $product->name . '" yang belum selesai. Silakan selesaikan pembayaran terlebih dahulu atau tunggu beberapa saat.');
        }

        // Log aktivitas checkout untuk monitoring
        \Illuminate\Support\Facades\Log::info('User accessing checkout', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'current_stock' => $product->stock,
            'timestamp' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $tripay = new TripayController();
        $channels = $tripay->getPaymentChannels();

        $error = null;
        // If Tripay returns an error, set $channels to empty array and pass error message
        if (is_array($channels) && isset($channels['error'])) {
            $error = $channels['error'];
            $channels = [];
        }

        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Log data user untuk debugging
        \Illuminate\Support\Facades\Log::info('User data in checkout', [
            'user_id' => $user->id ?? 'null',
            'user_name' => $user->name ?? 'null',
            'user_email' => $user->email ?? 'null',
            'user_phone' => $user->phone ?? 'null'
        ]);
        
        // Validasi apakah semua data user sudah lengkap
        $missingFields = [];
        if (empty($user->name)) {
            $missingFields[] = 'Nama Lengkap';
        }
        if (empty($user->email)) {
            $missingFields[] = 'Email';
        }
        if (empty($user->phone)) {
            $missingFields[] = 'No HP';
        }

        // Log user data for debugging
        \Illuminate\Support\Facades\Log::info('User profile validation', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone,
            'missing_fields' => $missingFields,
            'missing_count' => count($missingFields)
        ]);

        // Jika ada field yang kosong, redirect ke profile dengan pesan error
        if (!empty($missingFields)) {
            $missingFieldsText = implode(', ', $missingFields);
            \Illuminate\Support\Facades\Log::warning('Incomplete user profile, redirecting to profile page', [
                'user_id' => $user->id,
                'missing_fields' => $missingFields,
                'redirect_reason' => 'incomplete_profile'
            ]);
            return redirect()->route('profile')->with('error', "Mohon lengkapi data profile terlebih dahulu: {$missingFieldsText}");
        }
        
        return view('checkout', compact('product', 'channels', 'error', 'user'));
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }
        
        $channels = [
            (object)[
                'code' => 'PERMATAVA',
                'name' => 'Permata VA',
                'active' => true,
                'icon' => asset('assets/img/permata.png')
            ],
            (object)[
                'code' => 'BNIVA',
                'name' => 'BNI VA',
                'active' => true,
                'icon' => asset('assets/img/bni.png')
            ],
            (object)[
                'code' => 'QRIS',
                'name' => 'QRIS ShopeePay',
                'active' => true,
                'icon' => asset('assets/img/qris.png')
            ],
        ];
        
        // Ambil data user yang sedang login
        $user = Auth::user();
        
        // Validasi apakah semua data user sudah lengkap
        $missingFields = [];
        if (empty($user->name)) {
            $missingFields[] = 'Nama Lengkap';
        }
        if (empty($user->email)) {
            $missingFields[] = 'Email';
        }
        if (empty($user->phone)) {
            $missingFields[] = 'No HP';
        }

        // Log user data for debugging
        \Illuminate\Support\Facades\Log::info('User profile validation', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->phone,
            'missing_fields' => $missingFields,
            'missing_count' => count($missingFields)
        ]);

        // Jika ada field yang kosong, redirect ke profile dengan pesan error
        if (!empty($missingFields)) {
            $missingFieldsText = implode(', ', $missingFields);
            \Illuminate\Support\Facades\Log::warning('Incomplete user profile, redirecting to profile page', [
                'user_id' => $user->id,
                'missing_fields' => $missingFields,
                'redirect_reason' => 'incomplete_profile'
            ]);
            return redirect()->route('profile')->with('error', "Mohon lengkapi data profile terlebih dahulu: {$missingFieldsText}");
        }
        
        // Pastikan juga $product dikirim
        return view('checkout', compact('channels', 'user'));
    }
}