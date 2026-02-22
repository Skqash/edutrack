<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->paginate(20);
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = \App\Models\ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();
        return view('admin.teachers', compact('teachers', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'teacher';

        User::create($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully');
    }

    public function edit(User $teacher)
    {
        // Ensure only teachers can be edited
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        // Ensure only teachers can be updated
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $teacher->update($validated);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully');
    }

    public function destroy(User $teacher)
    {
        // Ensure only teachers can be deleted
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully');
    }
}
