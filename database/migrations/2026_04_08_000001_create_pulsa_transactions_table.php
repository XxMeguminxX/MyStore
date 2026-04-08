<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pulsa_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('merchant_ref')->unique();
            $table->string('product_code');
            $table->string('product_name');
            $table->string('phone');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->unsignedBigInteger('amount');
            $table->string('payment_method');
            $table->string('payment_url')->nullable();
            $table->string('tripay_reference')->nullable();
            $table->string('status')->default('UNPAID');
            $table->string('supplier_status')->nullable();
            $table->json('tripay_response')->nullable();
            $table->json('callback_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pulsa_transactions');
    }
};
