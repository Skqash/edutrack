<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            return redirect('/login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Normalize super role naming for backwards compatibility
        $normalizedRoles = array_map(function ($role) {
            return $role === 'super' ? 'superadmin' : $role;
        }, $roles);

        if (in_array($user->role, $normalizedRoles)) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Access Denied.');
    }
}
