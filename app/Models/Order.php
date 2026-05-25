<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_state',
        'shipping_zip', 'shipping_country',
        'subtotal', 'shipping_fee', 'tax', 'discount', 'total',
        'status', 'payment_method', 'payment_status', 'notes',
        'confirmed_at', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'shipping_fee'  => 'decimal:2',
        'tax'           => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        'confirmed_at'  => 'datetime',
        'shipped_at'    => 'datetime',
        'delivered_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];

    /**
     * Boot: auto-generate order_number on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'PT-' . strtoupper(uniqid());
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────────

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ── Helpers ─────────────────────────────────────────────────────

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancellable()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function markConfirmed()
    {
        $this->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function markShipped()
    {
        $this->update([
            'status'     => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markDelivered()
    {
        $this->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function markCancelled()
    {
        $this->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}
