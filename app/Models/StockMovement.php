<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'inventory_item_id', 'book_id', 'type',
        'quantity', 'quantity_before', 'quantity_after',
        'reason', 'reference',
    ];

    protected $casts = [
        'quantity'         => 'integer',
        'quantity_before'  => 'integer',
        'quantity_after'   => 'integer',
        'book_id'          => 'integer',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
