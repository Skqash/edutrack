<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('teacher')->get();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();
        return view('admin.classes', compact('classes', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string',
            'class_level' => 'required|integer',
            'section' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        ClassModel::create($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully');
    }

    public function edit(ClassModel $class)
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'class_name' => 'required|string',
            'class_level' => 'required|integer',
            'section' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'teacher_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $class->update($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully');
    }
}
