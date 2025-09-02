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
        Schema::table('transactions', function (Blueprint $table) {
            // Tambahkan field untuk menyimpan snapshot stock pada saat checkout
            $table->integer('stock_snapshot')->nullable()->after('amount');
            $table->timestamp('checkout_created_at')->nullable()->after('stock_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['stock_snapshot', 'checkout_created_at']);
        });
    }
};
