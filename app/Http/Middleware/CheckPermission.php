<?php

namespace App\Http\Middleware;

use App\Enums\RoleName;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Usage:
     *   ->middleware('permission:approve_vendors')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super admin bypasses all checks
        if ($user->hasRole(RoleName::SuperAdmin)) {
            return $next($request);
        }

        if (! $user->hasPermission($permission)) {
            abort(403, "Missing permission: {$permission}");
        }

        return $next($request);
    }
}