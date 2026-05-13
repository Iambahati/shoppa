<?php
 
namespace App\Models;
 
use App\Enums\RoleName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
 
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function vendor(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    // Role helpers
     public function hasRole(RoleName $role): bool
    {
        return $this->role?->name === $role->value;
    }

    public function hasAnyRole(RoleName ...$roles): bool
    {
        return in_array($this->role?->name, array_map(fn($r) => $r->value, $roles), strict: true);
    }

    public function roleName(): ?RoleName
    {
        if (! $this->role) {
            return null;
        }
 
        return RoleName::tryFrom($this->role->name);
    }
 
    public function isStaff(): bool
    {
        return $this->roleName()?->isStaff() ?? false;
    }

    /**
     * Check a single permission by value string or PermissionName enum.
     * Super Admin bypasses every check.
     */
    public function can($ability, $arguments = []): bool
    {
        if ($this->hasRole(RoleName::SuperAdmin)) {
            return true;
        }
 
        // Delegate to standard Laravel Gate for policies
        return parent::can($ability, $arguments);
    }
 
    public function hasPermission(string $permission): bool
    {
        if ($this->hasRole(RoleName::SuperAdmin)) {
            return true;
        }
 
        return $this->role?->permissions()->where('name', $permission)->exists() ?? false;
    }
 
 
    public function scopeByRole($query, RoleName $role)
    {
        return $query->whereHas('role', fn($q) => $q->where('name', $role->value));
    }

    
}
