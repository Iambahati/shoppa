<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderReturn extends Model
{
    protected $fillable = [
        'order_item_id',
        'reason',
        'order_return_status_id',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderReturnStatus::class, 'order_return_status_id');
    }

    /**
     * A return may eventually produce a refund once the item is received
     * and inspected by the Shoppa team.
     */
    public function refund(): HasOne
    {
        return $this->hasOne(OrderRefund::class, 'order_return_id');
    }

    public function isApproved(): bool
    {
        return $this->status?->name === OrderReturnStatus::APPROVED;
    }

    public function hasRefund(): bool
    {
        return $this->refund()->exists();
    }
}
