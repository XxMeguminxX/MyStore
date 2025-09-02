<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            // Rename kolom quantity menjadi stock
            $table->renameColumn('quantity', 'stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            // Rename kembali kolom stock menjadi quantity
            $table->renameColumn('stock', 'quantity');
        });
    }
};
