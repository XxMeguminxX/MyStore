<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;

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

        return view('checkout', compact('product', 'channels', 'error'));
    }
}


