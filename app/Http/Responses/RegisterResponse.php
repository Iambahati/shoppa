<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['status' => 'registered'], 201);
        }

        $user = auth()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $role = $user?->roleName();
        $redirectUrl = $role ? route($role->dashboardRoute()) : route('buyer.dashboard');

        return redirect()->intended($redirectUrl);
    }
}
