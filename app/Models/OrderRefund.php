<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    protected $fillable = [
        'order_return_id',
        'amount',
        'order_refund_status_id',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function orderReturn(): BelongsTo
    {
        return $this->belongsTo(OrderReturn::class, 'order_return_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderRefundStatus::class, 'order_refund_status_id');
    }


    public function isProcessed(): bool
    {
        return $this->status?->name === OrderRefundStatus::PROCESSED;
    }

    public function formattedAmount(): string
    {
        return 'KSh ' . number_format($this->amount);
    }
}
