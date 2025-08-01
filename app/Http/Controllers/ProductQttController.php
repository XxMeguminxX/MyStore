<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan kamu mengimpor model Product

class ProductController extends Controller
{
    // ... fungsi-fungsi lain yang sudah ada ...

    /**
     * Memperbarui quantity produk di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, $id)
    {
        // 1. Validasi request
        $request->validate([
            'quantity' => 'required|integer|min:0', // Memastikan quantity adalah angka dan tidak negatif
        ]);

        // 2. Cari produk berdasarkan ID
        $product = Product::find($id);

        // 3. Jika produk tidak ditemukan, kembalikan respons error
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        // 4. Perbarui nilai quantity
        $product->quantity = $request->input('quantity');
        $product->save();

        // 5. Kirim respons sukses
        return response()->json(['message' => 'Jumlah produk berhasil diperbarui.'], 200);
    }
}