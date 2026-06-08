<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function withRole(string $roleName): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('name', $roleName)->value('id'),
        ]);
    }

    public function asSuperAdmin(): static   { return $this->withRole('Super Admin'); }
    public function asAdmin(): static        { return $this->withRole('Admin'); }
    public function asVendorManager(): static { return $this->withRole('Vendor Manager'); }
    public function asVerifier(): static     { return $this->withRole('Verifier'); }
    public function asCustomerService(): static { return $this->withRole('Customer Service'); }
    public function asContentManager(): static { return $this->withRole('Content Manager'); }
    public function asVendor(): static       { return $this->withRole('Vendor'); }
    public function asBuyer(): static        { return $this->withRole('User'); }
    public function asGuest(): static        { return $this->withRole('Guest'); }

    // Legacy aliases kept for backwards compatibility
    public function superAdmin(): static { return $this->asSuperAdmin(); }
    public function vendor(): static     { return $this->asVendor(); }
    public function user(): static       { return $this->asBuyer(); }
}
