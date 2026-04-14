<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK dari carts yang referens ke tabel lama 'product'
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        // Rename tabel
        Schema::rename('product', 'products');

        // Recreate FK ke tabel baru 'products'
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::rename('products', 'product');

        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
        });
    }
};
