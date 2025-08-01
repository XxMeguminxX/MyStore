<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('product', function (Blueprint $table) {
        $table->integer('quantity')->default(0)->after('price'); // Tambahkan kolom quantity setelah price
    });
}

public function down()
{
    Schema::table('product', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
}
};
