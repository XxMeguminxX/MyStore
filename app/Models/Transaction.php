<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'response' => 'array',
    ];

    /**
     * Relasi dengan model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi dengan model Donasi
     */
    public function donasi()
    {
        return $this->belongsTo(Donasi::class, 'product_id');
    }

    /**
     * Mendapatkan nama produk/donasi berdasarkan type
     */
    public function getProductName()
    {
        if ($this->type === 'donation') {
            return $this->donasi ? $this->donasi->title : 'Donasi Tidak Ditemukan';
        } else {
            return $this->product ? $this->product->name : 'Produk Tidak Ditemukan';
        }
    }

    /**
     * Menentukan apakah transaksi ini untuk donasi
     */
    public function isDonation()
    {
        return $this->type === 'donation';
    }
}
