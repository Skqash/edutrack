<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        // Get all classes with their attendance records
        $classes = ClassModel::with(['attendance' => function ($query) {
            $query->with('student')->orderBy('date', 'desc');
        }, 'course'])->get();

        // Group attendance by class and add statistics
        $attendanceByClass = [];
        foreach ($classes as $class) {
            $attendances = $class->attendance;

            // Load students by course_id to include non-primary class students
            if ($class->course_id) {
                $students = \App\Models\Student::where('course_id', $class->course_id)
                    ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
                    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
                    ->orderBy('last_name')->orderBy('first_name')
                    ->get();
            } else {
                $students = $class->students()->orderBy('last_name')->orderBy('first_name')->get();
            }

            $stats = [
                'total'   => $attendances->count(),
                'present' => $attendances->where('status', 'Present')->count(),
                'absent'  => $attendances->where('status', 'Absent')->count(),
                'late'    => $attendances->where('status', 'Late')->count(),
                'leave'   => $attendances->where('status', 'Leave')->count(),
            ];
            $attendanceByClass[$class->id] = [
                'class'      => $class,
                'students'   => $students,
                'attendance' => $attendances,
                'stats'      => $stats,
            ];
        }

        // Get total counts
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses  = ClassModel::count();
        $totalSubjects = Subject::count();

        return view('admin.attendance.index', compact('attendanceByClass', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        $students = Student::all();
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
            'notes' => 'nullable|string|max:500',
        ]);

        Attendance::create($validated);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully');
    }

    public function edit(Attendance $attendance)
    {
        $students = Student::all();
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
            'notes' => 'nullable|string|max:500',
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
