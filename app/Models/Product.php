<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Number;

class Products extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'vendor_id',
        'product_category_id',
        'product_status_id',
        'name',
        'description',
        'price',
        'quantity',
        // Shoppa trust fields
        'imei',
        'serial_number',
        'verification_status',      // pending | in_review | verified | rejected
        'verifier_id',
        'condition_grade',          // premium(Like New) | Excellent(Very Good) | good
        'device_type',              // phone | laptop | tablet | other
        'trust_cert_uuid',
        'cert_issued_at',
        'battery_health',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cert_issued_at' => 'datetime',
            'battery_health' => 'integer',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class, 'product_status_id');
    }

     public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }


     public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

     public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }


     public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

     public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag', 'product_id', 'product_tag_id');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePendingVerification($query)
    {
        return $query->whereIn('verification_status', ['pending', 'in_review']);
    }


    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function formattedPrice(): string
    {
        return Number::currency($this->price, config('app.default_currency_code'));
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('device_photos')
            ->useDisk('public');

        $this->addMediaCollection('trust_certificate')
            ->singleFile()
            ->useDisk('public');
    }
}
