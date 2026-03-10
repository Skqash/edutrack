<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
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
            'email' => 'required|email|unique:users,email,'.$teacher->id,
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

    /**
     * Show subjects assigned to a teacher
     */
    public function subjects(User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $assignedSubjects = $teacher->subjects()->with('course')->get();
        $availableSubjects = Subject::whereDoesntHave('teachers', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with('course')->get();

        return view('admin.teachers.subjects', compact('teacher', 'assignedSubjects', 'availableSubjects'));
    }

    /**
     * Assign subjects to a teacher
     */
    public function assignSubjects(Request $request, User $teacher)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        // Assign new subjects
        foreach ($validated['subject_ids'] as $subjectId) {
            $teacher->subjects()->attach($subjectId, [
                'status' => 'active',
                'assigned_at' => now(),
            ]);
        }

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subjects assigned successfully');
    }

    /**
     * Remove subject assignment from teacher
     */
    public function removeSubject(User $teacher, Subject $subject)
    {
        if ($teacher->role !== 'teacher') {
            abort(403, 'Unauthorized');
        }

        $teacher->subjects()->detach($subject->id);

        return redirect()->route('admin.teachers.subjects', $teacher->id)
            ->with('success', 'Subject removed successfully');
    }
}
