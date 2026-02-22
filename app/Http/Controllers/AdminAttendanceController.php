<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        // Get all classes with their attendance records and students
        $classes = ClassModel::with(['attendance' => function($query) {
            $query->with('student.user')->orderBy('date', 'desc');
        }, 'students' => function($query) {
            $query->with('user');
        }])->get();

        // Group attendance by class and add statistics
        $attendanceByClass = [];
        foreach($classes as $class) {
            $attendances = $class->attendance;
            $stats = [
                'total' => $attendances->count(),
                'present' => $attendances->where('status', 'Present')->count(),
                'absent' => $attendances->where('status', 'Absent')->count(),
                'late' => $attendances->where('status', 'Late')->count(),
                'leave' => $attendances->where('status', 'Leave')->count(),
            ];
            $attendanceByClass[$class->id] = [
                'class' => $class,
                'attendance' => $attendances,
                'stats' => $stats
            ];
        }

        // Get total counts
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = Subject::count();

        return view('admin.attendance.index', compact('attendanceByClass', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
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
