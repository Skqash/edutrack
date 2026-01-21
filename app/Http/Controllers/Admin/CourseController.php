<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor')->get();
        return view('admin.courses', compact('courses'));
    }

    public function create()
    {
        $instructors = User::where('role', 'teacher')->get();
        return view('admin.courses.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses',
            'course_name' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'credit_hours' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive'
        ]);

        Course::create($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
    }

    public function edit(Course $course)
    {
        $instructors = User::where('role', 'teacher')->get();
        return view('admin.courses.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string',
            'instructor_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'credit_hours' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive'
        ]);

        $course->update($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully');
    }
}
