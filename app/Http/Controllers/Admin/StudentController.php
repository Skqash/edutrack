<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\AdminStudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(AdminStudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Build filters with campus isolation
        $filters = [
            'search' => $request->get('search'),
            'course_id' => $request->get('course_id'),
            'class_id' => $request->get('class_id'),
            'status' => $request->get('status'),
        ];

        // Apply campus isolation
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }
        if ($adminSchoolId) {
            $filters['school_id'] = $adminSchoolId;
        }

        $students = $this->studentService->getFilteredStudents($filters);
        $statistics = $this->studentService->getStudentStatistics($adminCampus);

        // Get campus-specific counts
        $totalStudents = $statistics['total'];
        $totalTeachers = Teacher::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();
        $totalClasses = \App\Models\ClassModel::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();
        $totalSubjects = \App\Models\Subject::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->count();

        return view('admin.students', compact('students', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects', 'statistics'));
    }

    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Get campus-specific courses for dropdown
        $courses = \App\Models\Course::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->orderBy('program_name')
            ->get();

        return view('admin.students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:6|confirmed',
            'student_id' => 'required|unique:students,student_id',
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|integer|min:1|max:4',
            'section' => 'required|string|max:10',
        ]);

        // Verify course belongs to admin's campus
        $course = \App\Models\Course::findOrFail($validated['course_id']);
        if ($adminCampus && $course->campus !== $adminCampus) {
            return redirect()->back()->withErrors(['course_id' => 'Selected course is not available in your campus.']);
        }
        if ($adminSchoolId && $course->school_id !== $adminSchoolId) {
            return redirect()->back()->withErrors(['course_id' => 'Selected course is not available in your school.']);
        }

        // Create student record with campus isolation
        Student::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'student_id' => $validated['student_id'],
            'course_id' => $validated['course_id'],
            'year' => $validated['year'],
            'section' => $validated['section'],
            'status' => 'Active',
            'campus' => $adminCampus, // Inherit admin's campus
            'school_id' => $adminSchoolId, // Inherit admin's school
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully');
    }

    public function edit($id)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Get student with campus isolation
        $student = Student::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->findOrFail($id);

        // Get campus-specific courses for dropdown
        $courses = \App\Models\Course::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->orderBy('program_name')
            ->get();

        return view('admin.students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Get student with campus isolation
        $student = Student::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|unique:students,email,'.$id,
            'student_id' => 'required|unique:students,student_id,'.$id,
            'course_id' => 'required|exists:courses,id',
            'year' => 'required|integer|min:1|max:4',
            'section' => 'required|string|max:10',
        ]);

        // Verify course belongs to admin's campus
        $course = \App\Models\Course::findOrFail($validated['course_id']);
        if ($adminCampus && $course->campus !== $adminCampus) {
            return redirect()->back()->withErrors(['course_id' => 'Selected course is not available in your campus.']);
        }
        if ($adminSchoolId && $course->school_id !== $adminSchoolId) {
            return redirect()->back()->withErrors(['course_id' => 'Selected course is not available in your school.']);
        }

        // Update student directly
        $student->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'email' => $validated['email'],
            'student_id' => $validated['student_id'],
            'course_id' => $validated['course_id'],
            'year' => $validated['year'],
            'section' => $validated['section'],
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;

        // Get student with campus isolation
        $student = Student::query()
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->findOrFail($id);

        // Delete the student record directly (no user relationship)
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }
}
