<?php

namespace App\Http\Middleware;

use App\Enums\RoleName;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole{
/**
     * Usage in routes:
     *   ->middleware('role:Admin,Vendor Manager')
     *   ->middleware('role:Super Admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
 
        if (! $user) {
            return redirect()->route('login');
        }
 
        $allowed = array_map(
            fn(string $r) => RoleName::from(trim($r))->value,
            $roles
        );
 
        if (! in_array($user->role?->name, $allowed, strict: true)) {
            abort(403, 'You do not have access to this area.');
        }
 
        return $next($request);
    }
}