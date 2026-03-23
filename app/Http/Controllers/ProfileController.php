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

        // Ensure a legacy teacher profile exists for any existing code paths
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'EMP' . $user->id,
                'status' => $user->status ?? 'Active',
                'campus' => $user->campus ?? 'Victorias Campus',
            ]
        );

        // Keep teacher profile fields in sync for backward compatibility
        $teacher->fill([
            'qualification' => $user->qualification,
            'bio' => $user->bio,
            'specialization' => $user->specialization,
            'department' => $user->department,
            'connected_school' => $user->connected_school,
        ])->save();

        $schoolRequest = \App\Models\SchoolRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('teacher.profile.show', compact('user', 'teacher', 'schoolRequest'));
    }

    /**
     * Edit teacher profile form
     */
    public function editTeacherProfile()
    {
        $user = Auth::user();

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'EMP' . $user->id,
                'status' => 'Active',
            ]
        );

        // Sync backward compatibility fields
        $teacher->fill([
            'qualification' => $user->qualification,
            'bio' => $user->bio,
            'specialization' => $user->specialization,
            'department' => $user->department,
            'connected_school' => $user->connected_school,
        ])->save();

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
            'campus' => ['nullable', 'string', 'max:255'],
            'connected_school' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update user fields (primary storage for profile details)
        $userUpdateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'department' => $validated['department'] ?? null,
            'campus' => $validated['campus'] ?? null,
            'connected_school' => $validated['connected_school'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $userUpdateData['password'] = Hash::make($validated['password']);
        }

        $user->update($userUpdateData);

        // Keep legacy teacher profile in sync (for any remaining code paths)
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'EMP' . $user->id,
                'status' => $user->status ?? 'Active',
            ]
        );

        $teacher->update([
            'bio' => $user->bio,
            'specialization' => $user->specialization,
            'department' => $user->department,
            'campus' => $user->campus,
            'connected_school' => $user->connected_school,
        ]);

        return redirect()->route('teacher.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show admin profile
     */
    public function showAdminProfile()
    {
        $user = Auth::user();

        // Ensure a legacy admin profile exists for backward compatibility
        $admin = Admin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'ADM' . $user->id,
                'status' => $user->status ?? 'Active',
            ]
        );

        // Keep admin profile fields in sync with the user record
        $admin->fill([
            'department' => $user->department,
            'bio' => $user->bio,
        ])->save();

        return view('admin.profile.show', compact('user', 'admin'));
    }

    /**
     * Edit admin profile form
     */
    public function editAdminProfile()
    {
        $user = Auth::user();

        $admin = Admin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'ADM' . $user->id,
                'status' => $user->status ?? 'Active',
            ]
        );

        $admin->fill([
            'department' => $user->department,
            'bio' => $user->bio,
        ])->save();

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

        // Update user record with profile fields
        $userUpdateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'department' => $validated['department'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $userUpdateData['password'] = Hash::make($validated['password']);
        }

        $user->update($userUpdateData);

        // Keep legacy admin profile in sync
        $admin = Admin::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'ADM' . $user->id,
                'status' => $user->status ?? 'Active',
            ]
        );

        $admin->update([
            'department' => $user->department,
            'bio' => $user->bio,
        ]);

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

