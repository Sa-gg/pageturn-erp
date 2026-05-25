<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'status',
        'total_amount', 'notes',
        'submitted_at', 'received_at',
    ];

    protected $casts = [
        'total_amount'  => 'decimal:2',
        'submitted_at'  => 'datetime',
        'received_at'   => 'datetime',
    ];

    /**
     * Auto-generate PO number on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            if (empty($po->po_number)) {
                $po->po_number = 'PO-' . strtoupper(uniqid());
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────────

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────

    public function recalculateTotal()
    {
        $this->update([
            'total_amount' => $this->items()->sum('subtotal'),
        ]);
    }

    public function isReceivable()
    {
        return in_array($this->status, ['draft', 'submitted']);
    }
}
