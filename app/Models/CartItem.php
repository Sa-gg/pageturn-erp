<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'book_title', 'unit_price', 'quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity'   => 'integer',
        'book_id'    => 'integer',
        'user_id'    => 'integer',
    ];

    /**
     * Computed subtotal for this cart item.
     */
    public function getSubtotalAttribute()
    {
        return round($this->unit_price * $this->quantity, 2);
    }
}
