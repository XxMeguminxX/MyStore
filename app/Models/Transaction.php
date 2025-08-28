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
}
