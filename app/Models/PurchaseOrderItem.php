<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'book_id', 'book_title',
        'quantity', 'unit_cost', 'subtotal', 'quantity_received',
    ];

    protected $casts = [
        'unit_cost'          => 'decimal:2',
        'subtotal'           => 'decimal:2',
        'quantity'           => 'integer',
        'quantity_received'  => 'integer',
        'book_id'            => 'integer',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
