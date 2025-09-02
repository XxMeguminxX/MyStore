<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update semua produk yang sudah ada dengan stock default 10
        $products = Product::all();

        foreach ($products as $product) {
            // Jika stock belum di-set atau null, set ke default value 10
            if ($product->stock === null || $product->stock === 0) {
                $product->stock = 10;
                $product->save();

                $this->command->info("Updated stock for product: {$product->name} (ID: {$product->id}) to 10");
            } else {
                $this->command->info("Product {$product->name} (ID: {$product->id}) already has stock: {$product->stock}");
            }
        }

        $this->command->info('Product stock update completed!');
    }
}
