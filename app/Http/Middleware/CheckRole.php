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
        // Check if user is super admin
        if (Auth::guard('super')->check()) {
            return $next($request);
        }

        // Check if user is logged in via web guard and has the required role
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role, $roles)) {
                return $next($request);
            }
        }

        return redirect('/login')->with('error', 'Unauthorized access.');
    }
}
