<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuperAdmin;
use App\Notifications\NewSchoolConnectionRequest;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

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
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'campus' => 'required|string|max:255',
            'campus_other' => 'required_if:campus,Other|string|max:255',
            'school_name' => 'nullable|string|max:255',
            'school_email' => 'nullable|email|max:255',
            'school_phone' => 'nullable|string|max:20',
            'school_address' => 'nullable|string|max:500',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, and numbers.',
            'campus_other.required_if' => 'Please specify the institution when choosing Other.',
        ]);

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        $user = User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'teacher',
            'password' => Hash::make($validated['password']),
        ]);

        // Create or update teacher profile
        $teacherData = [
            'user_id' => $user->id,
            'employee_id' => 'EMP' . $user->id,
            'status' => 'Pending',
            'department' => $validated['department'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'campus' => $validated['campus'] === 'Other' ? ($validated['campus_other'] ?? null) : ($validated['campus'] ?? null),
        ];

        $teacher = \App\Models\Teacher::updateOrCreate(
            ['user_id' => $user->id],
            $teacherData
        );

        // Create school connection request for admin approval
        if (!empty($validated['school_name'])) {
            $schoolRequest = \App\Models\SchoolRequest::create([
                'user_id' => $user->id,
                'school_name' => $validated['school_name'],
                'school_email' => $validated['school_email'] ?? null,
                'school_phone' => $validated['school_phone'] ?? null,
                'school_address' => $validated['school_address'] ?? null,
                'note' => 'New teacher signup request',
                'status' => 'pending',
            ]);

            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewSchoolConnectionRequest($schoolRequest));
            }

            DashboardController::clearCache();
        }

        return redirect('/login')->with('success', 'Account created successfully. Please log in.');
    }

    /* ---------- LOGOUT ---------- */

    public function logout()
    {
        Auth::guard('super')->logout();
        Auth::logout();

        return redirect('/login');
    }
}
