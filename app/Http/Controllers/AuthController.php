<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewSchoolConnectionRequest;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

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

        // Authenticate user via the main users table
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Redirect based on role
            if (in_array($user->role, ['super', 'superadmin'])) {
                return redirect('/super/dashboard');
            }

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
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'campus' => 'nullable|string|max:255',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, and numbers.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $fullName = trim($validated['first_name'] . ' ' . $validated['last_name']);

        try {
            // Find school_id if campus is selected
            $schoolId = null;
            if (!empty($validated['campus'])) {
                $school = \App\Models\School::where('school_name', 'LIKE', '%' . $validated['campus'] . '%')
                    ->orWhere('short_name', 'LIKE', '%' . $validated['campus'] . '%')
                    ->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            }

            // Create user account (teacher role)
            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'role' => 'teacher',
                'password' => Hash::make($validated['password']),
                'status' => 'Active', // Teachers are active by default
                'campus' => $validated['campus'] ?? null,
                'school_id' => $schoolId,
                'campus_status' => !empty($validated['campus']) ? 'pending' : 'approved', // Pending if campus selected, approved if independent
            ]);

            // Update employee_id with actual user ID (ensures uniqueness)
            $employeeId = 'EMP' . str_pad($user->id, 6, '0', STR_PAD_LEFT);
            $user->update(['employee_id' => $employeeId]);

            // Create corresponding teacher record for additional teacher-specific data
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $employeeId,
                'qualification' => null, // Will be filled later in profile
                'connected_school' => $validated['campus'] ?? null,
                'campus' => $validated['campus'] ?? null,
                'bio' => null,
                'specialization' => null,
                'department' => null,
                'status' => 'Active',
            ]);

            return redirect('/login')->with('success', 'Account created successfully! Please log in to continue.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'email' => $validated['email'],
                'name' => $fullName,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Registration failed. Please try again or contact support.')->withInput();
        }
    }

    /* ---------- LOGOUT ---------- */

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
