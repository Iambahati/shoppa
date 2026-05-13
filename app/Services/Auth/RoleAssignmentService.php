<?php

namespace App\Services\Auth;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class RoleAssignmentService
{
    public function assignRole(User $user, RoleName $roleName): void
    {
        $role = $this->findOrFail($roleName);
        $user->update(['role_id' => $role->id]);
    }

    public function assignDefaultBuyerRole(User $user): void
    {
        $this->assignRole($user, RoleName::User);
    }

    public function promoteToVendor(User $user): void
    {
        $this->assignRole($user, RoleName::Vendor);
    }

    private function findOrFail(RoleName $roleName): Role
    {
        $role = Cache::remember(
            "role.{$roleName->value}",
            now()->addHour(),
            fn() => Role::where('name', $roleName->value)->first()
        );

        if (! $role) {
            throw new RuntimeException("Role [{$roleName->value}] not found. Run db:seed first.");
        }

        return $role;
    }
}