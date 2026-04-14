<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Strip domain prefix dari URL absolut, simpan hanya path relatif
        // Contoh: https://admin.erikwahyusaputra.my.id/uploads/products/file.png
        //      → uploads/products/file.png
        DB::table('product')->get()->each(function ($row) {
            $updated = preg_replace('#^https?://[^/]+/#', '', $row->image);
            if ($updated !== $row->image) {
                DB::table('product')->where('id', $row->id)->update(['image' => $updated]);
            }
        });
    }

    public function down(): void
    {
        // Tidak bisa otomatis revert karena domain asal tidak disimpan
    }
};
