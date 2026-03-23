<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['head', 'department'])->get();

        return view('admin.courses', compact('courses'));
    }

    public function create()
    {
        $heads = User::where('role', 'teacher')->get();
        $departments = \App\Models\Department::orderBy('department_name')->pluck('department_name', 'id');

        return view('admin.courses.create', compact('heads', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code|string|max:10',
            'program_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'program_head_id' => 'nullable|exists:users,id',
            'total_years' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Degree program created successfully');
    }

    public function edit(Course $course)
    {
        $heads = User::where('role', 'teacher')->get();
        $departments = \App\Models\Department::orderBy('department_name')->pluck('department_name', 'id');

        return view('admin.courses.edit', compact('course', 'heads', 'departments'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code,'.$course->id.'|string|max:10',
            'program_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'program_head_id' => 'nullable|exists:users,id',
            'total_years' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Degree program updated successfully');
    }

    public function destroy(Course $course)
    {
        // Check if course has subjects
        if ($course->subjects()->count() > 0) {
            return redirect()->route('admin.courses.index')->with('error', 'Cannot delete program with existing subjects');
        }

        // Check if course has students
        if ($course->students()->count() > 0) {
            return redirect()->route('admin.courses.index')->with('error', 'Cannot delete program with enrolled students');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Degree program deleted successfully');
    }

    public function show(Course $course)
    {
        $course->load('subjects', 'head', 'classes');

        return view('admin.courses.show', compact('course'));
    }

    public function manageSubjects(Course $course)
    {
        $course->load('subjects');

        // Subjects are linked via program_id
        $availableSubjects = Subject::whereNull('program_id')
            ->orWhere('program_id', '!=', $course->id)
            ->get();

        return view('admin.courses.subjects', compact('course', 'availableSubjects'));
    }
}
