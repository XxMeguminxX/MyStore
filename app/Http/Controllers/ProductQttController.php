<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan kamu mengimpor model Product

class ProductQttController extends Controller
{
    // ... fungsi-fungsi lain yang sudah ada ...

    /**
     * Memperbarui stock produk di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, $id)
    {
        // 1. Validasi request
        $request->validate([
            'quantity' => 'required|integer|min:0|max:9999', // Memastikan quantity adalah angka, tidak negatif, max 9999
        ]);

        // 2. Cari produk berdasarkan ID
        $product = Product::find($id);

        // 3. Jika produk tidak ditemukan, kembalikan respons error
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        // 4. Simpan nilai stock lama untuk logging
        $oldStock = $product->stock;

        // 5. Perbarui nilai stock (menggunakan field stock yang baru)
        $product->stock = $request->input('quantity');
        $product->save();

        // 6. Log perubahan stock
        \Illuminate\Support\Facades\Log::info('Product stock updated', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_stock' => $oldStock,
            'new_stock' => $product->stock,
            'updated_by' => auth()->user() ? auth()->user()->name : 'system'
        ]);

        // 7. Kirim respons sukses dengan informasi tambahan
        return response()->json([
            'message' => 'Stock produk berhasil diperbarui.',
            'product_id' => $product->id,
            'new_stock' => $product->stock,
            'stock_status' => $product->getStockStatus()
        ], 200);
    }

    /**
     * Mendapatkan informasi stock produk
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStockInfo($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        return response()->json([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'stock' => $product->stock,
            'is_in_stock' => $product->isInStock(),
            'stock_status' => $product->getStockStatus(),
            'can_purchase' => $product->isInStock()
        ], 200);
    }

    /**
     * Menambah stock produk
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addStock(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:1000', // Minimal 1, maksimal 1000 per transaksi
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        $oldStock = $product->stock;
        $product->stock += $request->input('amount');
        $product->save();

        \Illuminate\Support\Facades\Log::info('Product stock added', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_stock' => $oldStock,
            'added_amount' => $request->input('amount'),
            'new_stock' => $product->stock,
            'updated_by' => auth()->user() ? auth()->user()->name : 'system'
        ]);

        return response()->json([
            'message' => 'Stock produk berhasil ditambahkan.',
            'product_id' => $product->id,
            'added_amount' => $request->input('amount'),
            'new_stock' => $product->stock,
            'stock_status' => $product->getStockStatus()
        ], 200);
    }

    /**
     * Mengurangi stock produk
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reduceStock(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:1000', // Minimal 1, maksimal 1000 per transaksi
        ]);

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        if (!$product->hasEnoughStock($request->input('amount'))) {
            return response()->json([
                'message' => 'Stock produk tidak mencukupi.',
                'current_stock' => $product->stock,
                'requested_amount' => $request->input('amount')
            ], 400);
        }

        $oldStock = $product->stock;
        $product->stock -= $request->input('amount');
        $product->save();

        \Illuminate\Support\Facades\Log::info('Product stock reduced', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'old_stock' => $oldStock,
            'reduced_amount' => $request->input('amount'),
            'new_stock' => $product->stock,
            'updated_by' => auth()->user() ? auth()->user()->name : 'system'
        ]);

        return response()->json([
            'message' => 'Stock produk berhasil dikurangi.',
            'product_id' => $product->id,
            'reduced_amount' => $request->input('amount'),
            'new_stock' => $product->stock,
            'stock_status' => $product->getStockStatus()
        ], 200);
    }
}