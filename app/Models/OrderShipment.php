<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'order_shipment_status_id',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'shipped_at'   => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderShipmentStatus::class, 'order_shipment_status_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function isDelivered(): bool
    {
        return ! is_null($this->delivered_at);
    }

    public function trackingUrl(): ?string
    {
        // Sprint 6: map carrier slugs to tracking URL templates
        return null;
    }
}
