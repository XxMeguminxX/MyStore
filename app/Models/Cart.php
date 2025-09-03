<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    /**
     * Relationship with User model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Product model
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Check if product is still available in stock
     */
    public function isAvailable(): bool
    {
        return $this->product && $this->product->stock >= $this->quantity;
    }
}
