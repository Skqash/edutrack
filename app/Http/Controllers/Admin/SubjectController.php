<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('course', 'instructor')->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function create()
    {
        $courses = Course::all();
        $instructors = User::where('role', 'teacher')->get();
        return view('admin.subjects.create', compact('courses', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects',
            'subject_name' => 'required|string',
            'category' => 'required|string',
            'credit_hours' => 'required|integer|min:1',
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:users,id',
            'description' => 'nullable|string'
        ]);

        Subject::create($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully');
    }

    public function edit(Subject $subject)
    {
        $courses = Course::all();
        $instructors = User::where('role', 'teacher')->get();
        return view('admin.subjects.edit', compact('subject', 'courses', 'instructors'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code' => 'required|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string',
            'category' => 'required|string',
            'credit_hours' => 'required|integer|min:1',
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:users,id',
            'description' => 'nullable|string'
        ]);

        $subject->update($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully');
    }
}
