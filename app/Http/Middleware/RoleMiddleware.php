<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
{
    // If Super Admin is logged in → allow EVERYTHING
    if (Auth::guard('super')->check()) {
        return $next($request);
    }

    // Normal user must be logged in
    if (!Auth::check()) {
        return redirect('/login')->with('error', 'Please login to access this page.');
    }

    // Check role from users table
    if (in_array(Auth::user()->role, $roles)) {
        return $next($request);
    }

    return redirect('/login')->with('error', 'Access Denied.');
    }
}