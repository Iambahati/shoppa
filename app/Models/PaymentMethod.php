<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = ['name'];

    // Canonical names used in Sprint 6 payment routing
    const MPESA      = 'M-Pesa';
    const CARD       = 'Card';
    const BANK       = 'Bank Transfer';

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
