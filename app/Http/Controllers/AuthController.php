<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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

        $email = $request->email;
        $password = $request->password;
        $remember = $request->has('remember');

        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            if ($user->status !== 'active' && $user->status !== 'Active') {
                return back()->with('error', 'Your account has been deactivated.');
            }

            Auth::guard('web')->login($user, $remember);

            return $this->redirectUserByRole($user);
        }

        $legacyAccount = $this->findLegacyAccountByEmail($email);
        if ($legacyAccount && Hash::check($password, $legacyAccount->password)) {
            if ($legacyAccount->status !== 'active' && $legacyAccount->status !== 'Active') {
                return back()->with('error', 'Your account has been deactivated.');
            }

            $user = $this->migrateLegacyAccountToUser($legacyAccount);
            Auth::guard('web')->login($user, $remember);

            return $this->redirectUserByRole($user);
        }

        return back()->with('error', 'Invalid email or password.')->withInput();
    }

    private function findLegacyAccountByEmail(string $email)
    {
        // Safely try to find in legacy tables, but only if columns exist
        try {
            if (Schema::hasColumn('super_admins', 'email')) {
                $account = \App\Models\SuperAdmin::where('email', $email)->first();
                if ($account) {
                    return $account;
                }
            }
        } catch (\Exception $e) {
            // SuperAdmins table query failed, continue
        }

        try {
            if (Schema::hasColumn('admins', 'email')) {
                $account = \App\Models\Admin::where('email', $email)->first();
                if ($account) {
                    return $account;
                }
            }
        } catch (\Exception $e) {
            // Admins table query failed, continue
        }

        try {
            if (Schema::hasColumn('teachers', 'email')) {
                $account = \App\Models\Teacher::where('email', $email)->first();
                if ($account) {
                    return $account;
                }
            }
        } catch (\Exception $e) {
            // Teachers table query failed, continue
        }

        try {
            if (Schema::hasColumn('students', 'email')) {
                $account = \App\Models\Student::where('email', $email)->first();
                if ($account) {
                    return $account;
                }
            }
        } catch (\Exception $e) {
            // Students table query failed, continue
        }

        return null;
    }

    private function migrateLegacyAccountToUser($legacyAccount)
    {
        $role = strtolower($legacyAccount->role ?? $legacyAccount->getRoleAttribute() ?? 'student');
        $name = trim(($legacyAccount->first_name ?? '').' '.($legacyAccount->last_name ?? ''));

        $user = User::updateOrCreate(
            ['email' => $legacyAccount->email],
            [
                'name' => $name ?: $legacyAccount->email,
                'password' => $legacyAccount->password,
                'phone' => $legacyAccount->phone ?? null,
                'role' => $role,
                'status' => $legacyAccount->status ?? 'Active',
                'school_id' => $legacyAccount->school_id ?? null,
                'campus' => $legacyAccount->campus ?? null,
            ]
        );

        if (property_exists($legacyAccount, 'user_id') && empty($legacyAccount->user_id)) {
            $legacyAccount->user_id = $user->id;
            $legacyAccount->save();
        }

        return $user;
    }

    private function redirectUserByRole(User $user)
    {
        switch (strtolower($user->role)) {
            case 'super':
            case 'superadmin':
                return redirect('/super/dashboard');
            case 'admin':
                return redirect('/admin/dashboard');
            case 'teacher':
                return redirect('/teacher/dashboard');
            case 'student':
                return redirect('/student/dashboard');
            default:
                return redirect('/login')->with('error', 'Your account role is not configured correctly.');
        }
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
            'email.unique' => 'This email is already registered in the system.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            // Find school_id if campus is selected
            $schoolId = null;
            if (! empty($validated['campus'])) {
                $school = \App\Models\School::where('school_name', 'LIKE', '%'.$validated['campus'].'%')
                    ->orWhere('short_name', 'LIKE', '%'.$validated['campus'].'%')
                    ->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            }

            $user = User::create([
                'name' => trim($validated['first_name'].' '.$validated['last_name']),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'school_id' => $schoolId,
                'campus' => $validated['campus'] ?? null,
                'role' => 'teacher',
                'status' => 'Active',
            ]);

            // Generate unique employee_id for teacher
            $maxId = \App\Models\Teacher::max('id') ?? 0;
            $employeeId = 'TEACHER_'.str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);

            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $employeeId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'school_id' => $schoolId,
                'qualification' => null,
                'status' => 'Active',
            ]);

            return redirect('/login')->with('success', 'Teacher account created successfully! Please log in to continue.');

        } catch (\Exception $e) {
            Log::error('Registration failed: '.$e->getMessage(), [
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Registration failed. Please try again or contact support.')->withInput();
        }
    }

    /* ---------- LOGOUT ---------- */

    public function logout()
    {
        // Logout from all possible guards
        $guards = ['web', 'student', 'teacher', 'admin', 'superadmin'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        // Invalidate the session completely
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
