<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan halaman detail produk (untuk pembelian).
     */
    public function show(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('dashboard')->with('error', 'Produk tidak ditemukan.');
        }

        $relatedProducts = Product::where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
