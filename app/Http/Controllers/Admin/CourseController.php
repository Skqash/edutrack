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
        $courses = Course::with('head')->get();

        return view('admin.courses', compact('courses'));
    }

    public function create()
    {
        $heads = User::where('role', 'teacher')->get();
        $colleges = [
            'College of Engineering and Information Technology',
            'College of Education',
            'College of Agriculture',
            'College of Business and Management',
            'College of Arts and Sciences',
            'College of Nursing',
            'College of Hospitality Management',
        ];

        return view('admin.courses.create', compact('heads', 'colleges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code|string|max:10',
            'program_name' => 'required|string|max:255',
            'college' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
            'status' => 'required|in:Active,Inactive',
            'duration' => 'required|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
        ]);

        // Set default values
        $validated['current_students'] = $validated['current_students'] ?? 0;
        $validated['max_students'] = $validated['max_students'] ?? 50;

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Degree program created successfully');
    }

    public function edit(Course $course)
    {
        $heads = User::where('role', 'teacher')->get();
        $colleges = [
            'College of Engineering and Information Technology',
            'College of Education',
            'College of Agriculture',
            'College of Business and Management',
            'College of Arts and Sciences',
            'College of Nursing',
            'College of Hospitality Management',
        ];

        return view('admin.courses.edit', compact('course', 'heads', 'colleges'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'program_code' => 'required|unique:courses,program_code,'.$course->id.'|string|max:10',
            'program_name' => 'required|string|max:255',
            'college' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
            'status' => 'required|in:Active,Inactive',
            'duration' => 'required|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'current_students' => 'nullable|integer|min:0',
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
        if ($course->current_students > 0) {
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
        $availableSubjects = Subject::whereNull('course_id')
            ->orWhere('course_id', '!=', $course->id)
            ->get();

        return view('admin.courses.subjects', compact('course', 'availableSubjects'));
    }
}
