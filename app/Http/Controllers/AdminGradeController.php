<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdminGradeController extends Controller
{
    public function index()
    {
        $grades = Grade::with('student', 'subject')->paginate(15);
        return view('admin.grades.index', compact('grades'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $subjects = Subject::where('status', 'Active')->get();
        return view('admin.grades.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'required|in:A,B,C,D,F',
            'semester' => 'required|in:1,2,3,4,5,6,7,8',
            'academic_year' => 'required|string'
        ]);

        Grade::create($validated);
        return redirect()->route('admin.grades.index')->with('success', 'Grade recorded successfully');
    }

    public function edit(Grade $grade)
    {
        $students = Student::with('user')->get();
        $subjects = Subject::where('status', 'Active')->get();
        return view('admin.grades.edit', compact('grade', 'students', 'subjects'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'grade' => 'required|in:A,B,C,D,F',
            'semester' => 'required|in:1,2,3,4,5,6,7,8',
            'academic_year' => 'required|string'
        ]);

        $grade->update($validated);
        return redirect()->route('admin.grades.index')->with('success', 'Grade updated successfully');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('admin.grades.index')->with('success', 'Grade deleted successfully');
    }
}
