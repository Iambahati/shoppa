<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderReturnStatus extends Model
{
    protected $fillable = ['name'];

    // Canonical status names — avoids magic strings in application code
    const REQUESTED = 'requested';
    const APPROVED  = 'approved';
    const REJECTED  = 'rejected';
    const RECEIVED  = 'received';
    const COMPLETED = 'completed';

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class, 'order_return_status_id');
    }
}
