<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_to',
        'usage_limit',
        'type',         // fixed | percentage
    ];

    protected function casts(): array
    {
        return [
            'valid_from'  => 'datetime',
            'valid_to'    => 'datetime',
            'usage_limit' => 'integer',
        ];
    }

    public function isValid(): bool
    {
        $now = now();

        return $now->between($this->valid_from, $this->valid_to);
    }

    /**
     * Calculate the discount amount for a given cart total.
     */
    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percentage') {
            return round($total * ($this->discount / 100), 2);
        }

        // Fixed — discount is a KSh amount
        return min((float) $this->discount, $total);
    }

    public function formattedDiscount(): string
    {
        if ($this->type === 'percentage') {
            return $this->discount . '%';
        }

        return 'KSh ' . number_format($this->discount);
    }

    public function scopeActive($query)
    {
        return $query->where('valid_from', '<=', now())
                     ->where('valid_to', '>=', now());
    }
}
