<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* ---------- LOGIN ---------- */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // 1️⃣ Try SUPER ADMIN first (highest priority)
        if (Auth::guard('super')->attempt($credentials, $remember)) {
            return redirect('/super/dashboard');
        }

        // 2️⃣ Try regular USERS table (admin / teacher / student)
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            if ($user->role === 'teacher') {
                return redirect('/teacher/dashboard');
            }

            return redirect('/student/dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    /* ---------- REGISTER ---------- */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,teacher,student',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password,
        ]);

        return redirect('/login')->with('success', 'Account created successfully.');
    }

    /* ---------- LOGOUT ---------- */

    public function logout()
    {
        Auth::guard('super')->logout();
        Auth::logout();

        return redirect('/login');
    }
}
