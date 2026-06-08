<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderShipmentStatus extends Model
{
    protected $fillable = ['name'];

    public function shipments(): HasMany
    {
        return $this->hasMany(OrderShipment::class, 'order_shipment_status_id');
    }
}
