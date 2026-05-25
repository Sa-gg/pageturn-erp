<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'book_id', 'book_title', 'quantity_on_hand', 'quantity_reserved',
        'reorder_level', 'reorder_quantity', 'location',
    ];

    protected $casts = [
        'book_id'            => 'integer',
        'quantity_on_hand'   => 'integer',
        'quantity_reserved'  => 'integer',
        'reorder_level'      => 'integer',
        'reorder_quantity'   => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ── Computed ────────────────────────────────────────────────────

    /**
     * Available stock = on hand - reserved.
     */
    public function getAvailableQuantityAttribute()
    {
        return $this->quantity_on_hand - $this->quantity_reserved;
    }

    /**
     * Is stock below the reorder threshold?
     */
    public function getIsLowStockAttribute()
    {
        return $this->quantity_on_hand <= $this->reorder_level;
    }

    /**
     * Is completely out of stock?
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->quantity_on_hand <= 0;
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity_on_hand', '<=', 'reorder_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_on_hand', '<=', 0);
    }
}
