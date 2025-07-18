<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function beli(Request $request, $id)
    {
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
        return view('checkout', compact('product', 'channels', 'error'));
    }

    public function index()
    {
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
        // Pastikan juga $product dikirim
        return view('checkout', compact('channels', 'product'));
    }
}