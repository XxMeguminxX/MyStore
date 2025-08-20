<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TripayController;

class DonasiController extends Controller
{
    public function index(Request $request)
    {
        $donasis = Donasi::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donasi', compact('donasis'));
    }

    public function beli(Request $request, $id)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan checkout.');
        }
        
        $donasi = Donasi::findOrFail($id);

        // Ambil channel pembayaran Tripay
        $tripay = new TripayController();
        $channels = $tripay->getPaymentChannels();

        $error = null;
        if (is_array($channels) && isset($channels['error'])) {
            $error = $channels['error'];
            $channels = [];
        }

        // Bentuk objek mirip Product agar bisa pakai view checkout yang sama
        $product = (object) [
            'id' => $donasi->id,
            'name' => $donasi->title,
            'price' => (int) $donasi->amount,
            'image' => $donasi->image ?? asset('assets/img/icon.png'),
            'description' => $donasi->description ?? '',
            'quantity' => null,
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

        return view('checkout', compact('product', 'channels', 'error', 'user'));
    }
}


