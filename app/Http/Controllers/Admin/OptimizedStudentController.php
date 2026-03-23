<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Services\AdminStudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptimizedStudentController extends Controller
{
    protected $studentService;

    public function __construct(AdminStudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display students with campus filtering
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $filters = $request->only(['search', 'campus', 'course_id', 'class_id', 'status']);
        
        // Apply campus restriction for campus admins
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        $students = $this->studentService->getFilteredStudents($filters);
        $statistics = $this->studentService->getStudentStatistics($adminCampus);
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();
        $classes = ClassModel::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('class_name')
            ->get();

        return view('admin.students.index', compact(
            'students', 
            'statistics', 
            'courses', 
            'classes', 
            'adminCampus'
        ));
    }

    /**
     * Show student creation form
     */
    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();
        $classes = ClassModel::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->with('course')
            ->orderBy('class_name')
            ->get();

        return view('admin.students.create', compact('courses', 'classes', 'adminCampus'));
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'student_id' => 'required|string|unique:students,student_id',
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'nullable|exists:classes,id',
            'year_level' => 'required|in:1,2,3,4',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Verify course and class belong to admin's campus
        if ($adminCampus) {
            $course = Course::find($validated['course_id']);
            if ($course->campus !== $adminCampus) {
                return back()->withErrors(['course_id' => 'Course does not belong to your campus.']);
            }

            if ($validated['class_id']) {
                $class = ClassModel::find($validated['class_id']);
                if ($class->campus !== $adminCampus) {
                    return back()->withErrors(['class_id' => 'Class does not belong to your campus.']);
                }
            }
        }

        $student = $this->studentService->createStudent($validated, $adminCampus);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Show student details
     */
    public function show(Student $student)
    {
        $this->authorizeStudentAccess($student);
        
        $student->load(['user', 'course', 'class.teacher', 'grades.subject']);
        $statistics = $this->studentService->getStudentDetailStatistics($student);

        return view('admin.students.show', compact('student', 'statistics'));
    }

    /**
     * Show student edit form
     */
    public function edit(Student $student)
    {
        $this->authorizeStudentAccess($student);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();
        $classes = ClassModel::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->with('course')
            ->orderBy('class_name')
            ->get();

        return view('admin.students.edit', compact('student', 'courses', 'classes', 'adminCampus'));
    }

    /**
     * Update student
     */
    public function update(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);
        
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'nullable|exists:classes,id',
            'year_level' => 'required|in:1,2,3,4',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Verify course and class belong to admin's campus
        if ($adminCampus) {
            $course = Course::find($validated['course_id']);
            if ($course->campus !== $adminCampus) {
                return back()->withErrors(['course_id' => 'Course does not belong to your campus.']);
            }

            if ($validated['class_id']) {
                $class = ClassModel::find($validated['class_id']);
                if ($class->campus !== $adminCampus) {
                    return back()->withErrors(['class_id' => 'Class does not belong to your campus.']);
                }
            }
        }

        $this->studentService->updateStudent($student, $validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student
     */
    public function destroy(Student $student)
    {
        $this->authorizeStudentAccess($student);
        
        $this->studentService->deleteStudent($student);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Bulk Actions
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete,transfer_class',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $admin = Auth::user();
        $result = $this->studentService->performBulkAction(
            $validated['action'],
            $validated['student_ids'],
            $admin,
            $validated['class_id'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Export students data
     */
    public function export(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $format = $request->input('format', 'csv');
        $filters = $request->only(['campus', 'course_id', 'class_id', 'status']);
        
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        return $this->studentService->exportStudents($format, $filters);
    }

    /**
     * Import students from file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx|max:2048',
            'course_id' => 'required|exists:courses,id',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $admin = Auth::user();
        $result = $this->studentService->importStudents(
            $request->file('file'),
            $admin,
            $request->input('course_id'),
            $request->input('class_id')
        );

        return redirect()->route('admin.students.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Get students by class for AJAX
     */
    public function getStudentsByClass(Request $request)
    {
        $classId = $request->input('class_id');
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $students = Student::where('class_id', $classId)
            ->when($adminCampus, function ($q) use ($adminCampus) {
                $q->whereHas('course', fn($cq) => $cq->where('campus', $adminCampus));
            })
            ->with(['user', 'course'])
            ->get();

        return response()->json($students);
    }

    /**
     * Transfer student to different class
     */
    public function transferClass(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $admin = Auth::user();
        $result = $this->studentService->transferStudent(
            $student,
            $validated['class_id'],
            $admin->id,
            $validated['reason'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Authorize student access based on campus
     */
    private function authorizeStudentAccess(Student $student)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        // Campus admins can only manage students from their campus
        if ($adminCampus && $student->course->campus !== $adminCampus) {
            abort(403, 'You can only manage students from your campus');
        }
    }
}