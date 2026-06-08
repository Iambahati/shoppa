<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function total(): float
    {
        return $this->items->sum(fn($item) => $item->product->price * $item->quantity);
    }

    public function formattedTotal(): string
    {
        return  config('app.default_currency_code') . ' '  . number_format($this->total());
    }
}