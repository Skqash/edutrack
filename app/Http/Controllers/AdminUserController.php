<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Only show Teachers and Students, NOT Admins or SuperAdmins
    public function index()
    {
        $users = User::whereIn('role', ['teacher', 'student'])
            ->paginate(15);

        // Get total counts
        $totalStudents = Student::with('user')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = Subject::count();

        return view('admin.users.index', compact('users', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:teacher,student', // Only teacher or student
            'password' => 'required|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        // Only allow editing teachers and students
        if (! in_array($user->role, ['teacher', 'student'])) {
            abort(403, 'Unauthorized');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Only allow updating teachers and students
        if (! in_array($user->role, ['teacher', 'student'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:teacher,student', // Only teacher or student
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Only allow deleting teachers and students
        if (! in_array($user->role, ['teacher', 'student'])) {
            abort(403, 'Unauthorized');
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
