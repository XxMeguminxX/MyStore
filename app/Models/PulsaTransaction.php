<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PulsaTransaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tripay_response' => 'array',
        'callback_data'   => 'array',
        'paid_at'         => 'datetime',
    ];

    public function getProductName(): string
    {
        return $this->product_name . ' → ' . $this->phone;
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['PAID', 'SETTLED']);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'UNPAID');
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', ['PAID', 'SETTLED']);
    }
}
