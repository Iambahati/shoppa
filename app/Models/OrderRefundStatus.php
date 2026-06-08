<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderRefundStatus extends Model
{
    protected $fillable = ['name'];

    const PENDING   = 'pending';
    const PROCESSED = 'processed';
    const FAILED    = 'failed';

    public function refunds(): HasMany
    {
        return $this->hasMany(OrderRefund::class, 'order_refund_status_id');
    }
}
