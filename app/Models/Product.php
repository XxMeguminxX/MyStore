<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table   = 'products';
    protected $fillable = [
        'image',
        'name',
        'price',
        'description',
        'stock',
        'category_id',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Check if product has enough stock for quantity
     */
    public function hasEnoughStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock by quantity
     */
    public function reduceStock($quantity = 1)
    {
        if ($this->hasEnoughStock($quantity)) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Get stock status text
     */
    public function getStockStatus()
    {
        if ($this->stock > 10) {
            return '🟢 Tersedia';
        } elseif ($this->stock > 0) {
            return '🟡 Stok Terbatas';
        } else {
            return '🔴 Stok Habis';
        }
    }

    /**
     * Get stock status color for UI
     */
    public function getStockStatusColor()
    {
        if ($this->stock > 10) {
            return 'stock-available';
        } elseif ($this->stock > 0) {
            return 'stock-limited';
        } else {
            return 'stock-out';
        }
    }
}
