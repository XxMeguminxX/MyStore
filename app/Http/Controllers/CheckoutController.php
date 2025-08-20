<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TripayController;

class CheckoutController extends Controller
{
    public function beli(Request $request, $id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }
        
        $tripay = new TripayController();
        $channels = $tripay->getPaymentChannels();

        $error = null;
        // If Tripay returns an error, set $channels to empty array and pass error message
        if (is_array($channels) && isset($channels['error'])) {
            $error = $channels['error'];
            $channels = [];
        }

        $product = \App\Models\Product::where('id', '=', $id)->first();
        if ($product == null) {
            return redirect('/dashboard');
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
        
        // Jika ada field yang kosong, redirect ke profile dengan pesan error
        if (!empty($missingFields)) {
            $missingFieldsText = implode(', ', $missingFields);
            return redirect()->route('login')->with('error', "Mohon lengkapi data profile terlebih dahulu: {$missingFieldsText}");
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
        
        // Jika ada field yang kosong, redirect ke profile dengan pesan error
        if (!empty($missingFields)) {
            $missingFieldsText = implode(', ', $missingFields);
            return redirect()->route('profile')->with('error', "Mohon lengkapi data profile terlebih dahulu: {$missingFieldsText}");
        }
        
        // Pastikan juga $product dikirim
        return view('checkout', compact('channels', 'user'));
    }
}