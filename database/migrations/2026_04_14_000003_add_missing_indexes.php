<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('status', 'idx_transactions_status');
            $table->index('product_id', 'idx_transactions_product_id');
        });

        Schema::table('pulsa_transactions', function (Blueprint $table) {
            $table->index('status', 'idx_pulsa_transactions_status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_status');
            $table->dropIndex('idx_transactions_product_id');
        });

        Schema::table('pulsa_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_pulsa_transactions_status');
        });
    }
};
