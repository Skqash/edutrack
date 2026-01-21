<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with('student', 'class')->paginate(20);
        return view('admin.attendance.index', compact('attendance'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $classes = ClassModel::all();
        return view('admin.attendance.create', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,Leave',
            'notes' => 'nullable|string|max:500'
        ]);

        Attendance::create($validated);
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully');
    }

    public function edit(Attendance $attendance)
    {
        $students = Student::with('user')->get();
        $classes = ClassModel::all();
        return view('admin.attendance.edit', compact('attendance', 'students', 'classes'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,Leave',
            'notes' => 'nullable|string|max:500'
        ]);

        $attendance->update($validated);
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted successfully');
    }
}
