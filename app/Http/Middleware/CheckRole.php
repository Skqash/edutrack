<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ensure the user is authenticated
        if (! Auth::check()) {
            return redirect('/login')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Support both `super` and `superadmin` role naming for backward compatibility
        // Expand roles to include both variants
        $normalizedRoles = [];
        foreach ($roles as $role) {
            $normalizedRoles[] = $role;
            if ($role === 'super') $normalizedRoles[] = 'superadmin';
            if ($role === 'superadmin') $normalizedRoles[] = 'super';
        }

        if (in_array($user->role, $normalizedRoles)) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}
