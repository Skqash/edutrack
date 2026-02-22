<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show teacher profile
     */
    public function showTeacherProfile()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . $user->id,
                'status' => 'Active',
            ]);
        }
        
        return view('teacher.profile.show', compact('user', 'teacher'));
    }

    /**
     * Edit teacher profile form
     */
    public function editTeacherProfile()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'employee_id' => 'EMP' . $user->id,
            'status' => 'Active',
        ]);
        
        return view('teacher.profile.edit', compact('user', 'teacher'));
    }

    /**
     * Update teacher profile
     */
    public function updateTeacherProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Validate user data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update user
        $userUpdateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        if (!empty($validated['phone'])) {
            $userUpdateData['phone'] = $validated['phone'];
        }
        
        if (!empty($validated['password'])) {
            $userUpdateData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($userUpdateData);

        // Get or create teacher profile
        $teacher = Teacher::where('user_id', $user->id)->first();
        if (!$teacher) {
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . $user->id,
                'status' => 'Active',
            ]);
        }

        // Update teacher profile
        $teacherUpdateData = [];
        if (isset($validated['bio'])) {
            $teacherUpdateData['bio'] = $validated['bio'];
        }
        if (isset($validated['specialization'])) {
            $teacherUpdateData['specialization'] = $validated['specialization'];
        }
        if (isset($validated['department'])) {
            $teacherUpdateData['department'] = $validated['department'];
        }

        if (!empty($teacherUpdateData)) {
            $teacher->update($teacherUpdateData);
        }

        return redirect()->route('teacher.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show admin profile
     */
    public function showAdminProfile()
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->first();

        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $user->id,
                'employee_id' => 'ADM' . $user->id,
                'status' => 'Active',
            ]);
        }
        
        return view('admin.profile.show', compact('user', 'admin'));
    }

    /**
     * Edit admin profile form
     */
    public function editAdminProfile()
    {
        $user = Auth::user();
        $admin = Admin::where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'employee_id' => 'ADM' . $user->id,
            'status' => 'Active',
        ]);
        
        return view('admin.profile.edit', compact('user', 'admin'));
    }

    /**
     * Update admin profile
     */
    public function updateAdminProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Validate user data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update user
        $userUpdateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        if (!empty($validated['phone'])) {
            $userUpdateData['phone'] = $validated['phone'];
        }
        
        if (!empty($validated['password'])) {
            $userUpdateData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($userUpdateData);

        // Get or create admin profile
        $admin = Admin::where('user_id', $user->id)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $user->id,
                'employee_id' => 'ADM' . $user->id,
                'status' => 'Active',
            ]);
        }

        // Update admin profile
        $adminUpdateData = [];
        if (isset($validated['bio'])) {
            $adminUpdateData['bio'] = $validated['bio'];
        }
        if (isset($validated['department'])) {
            $adminUpdateData['department'] = $validated['department'];
        }

        if (!empty($adminUpdateData)) {
            $admin->update($adminUpdateData);
        }

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}

