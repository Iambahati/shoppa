<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Auth\RoleAssignmentService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function __construct(
        private readonly RoleAssignmentService $roleAssignment
    ) {}

    public function create(array $input): User
    {
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'phone'    => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
        ]);

        // Every public registration gets Buyer role
        $this->roleAssignment->assignDefaultBuyerRole($user);

        return $user->fresh();
    }
}