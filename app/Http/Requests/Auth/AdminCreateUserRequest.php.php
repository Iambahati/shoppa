<?php

namespace App\Http\Requests\Auth;

use App\Enums\RoleName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdminCreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_users');
    }

    public function rules(): array
    {
        $staffRoles = collect(RoleName::staffRoles())->map->value->implode(',');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'role'     => ['required', 'string', "in:{$staffRoles}"],
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Please select a valid staff role.',
        ];
    }
}