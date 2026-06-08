<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vendor extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',  // pending | approved | rejected | suspended
        'rejected_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
     {
         return $this->hasMany(Product::class);
     }

      public function earnings(): HasMany
     {
         return $this->hasMany(VendorEarning::class);
     }

      public function payments(): HasMany
     {
         return $this->hasMany(VendorPayment::class);
     }

      public function reviews(): HasMany
     {
         return $this->hasMany(VendorReview::class);
     }

      public function settings(): HasMany
     {
         return $this->hasMany(VendorSetting::class);
     }
    

     public function isApproved(): bool
     {
         return $this->status === 'approved';
     }


     public function isPending(): bool
     {
         return $this->status === 'pending';
     }
     

     public function getSetting(string $key, mixed $default = null): mixed
     {
        return $this->settings()->where('key', $key)->value('value') ?? $default;
     }
     

    //  Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('kyc_documents')
            ->singleFile();
        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
