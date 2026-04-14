<?php

use Illuminate\Database\Migrations\Migration;

// Migration ini di-skip karena tabel 'product' dikelola bersama dengan admin app.
// Rename dibatalkan agar admin app tidak rusak.
return new class extends Migration
{
    public function up(): void {}
    public function down(): void {}
};
