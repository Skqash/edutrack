<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->paginate(20);
        $totalStudents = Student::count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = \App\Models\ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();

        return view('admin.students', compact('students', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'student_id' => 'required|unique:students,student_id',
            'year' => 'required|integer|min:1|max:4',
            'section' => 'required|string|max:10',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
            'status' => 'Active',
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'year' => $validated['year'],
            'section' => $validated['section'],
            'status' => 'Active',
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully');
    }

    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);

        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'student_id' => 'required|unique:students,student_id,'.$id,
            'year' => 'required|integer|min:1|max:4',
            'section' => 'required|string|max:10',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'student_id' => $validated['student_id'],
            'year' => $validated['year'],
            'section' => $validated['section'],
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Delete the user account as well
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }
}
