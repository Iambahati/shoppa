<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    protected $fillable = ['name'];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}