<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $user = auth()->user();
        $role = $user?->roleName();

        $redirectUrl = $role
            ? route($role->dashboardRoute())
            : route('buyer.dashboard');

        return redirect()->to($redirectUrl);
    }
}
