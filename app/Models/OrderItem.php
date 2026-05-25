<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'book_id', 'book_title', 'book_isbn',
        'unit_price', 'quantity', 'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
        'quantity'   => 'integer',
        'book_id'    => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
