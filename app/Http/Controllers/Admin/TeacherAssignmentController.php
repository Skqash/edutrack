<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherAssignment::with(['teacher', 'class', 'subject', 'course', 'students']);

        // Filters
        if ($request->filled('teacher_id')) {
            $query->byTeacher($request->teacher_id);
        }

        if ($request->filled('department')) {
            $query->byDepartment($request->department);
        }

        if ($request->filled('academic_year')) {
            $query->byAcademicYear($request->academic_year);
        }

        if ($request->filled('semester')) {
            $query->bySemester($request->semester);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest()->paginate(15);

        // Get filter options
        $teachers = Teacher::orderBy('last_name')->get();
        $departments = Department::orderBy('department_name')->pluck('department_name');
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        return view('admin.teacher-assignments.index', compact(
            'assignments',
            'teachers',
            'departments',
            'academicYears',
            'semesters'
        ));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('last_name')->get();
        $classes = ClassModel::with('course')->get();
        $subjects = Subject::with('course')->get();
        $courses = Course::with('department')->orderBy('program_name')->get();
        $departments = Department::orderBy('department_name')->pluck('department_name');
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        return view('admin.teacher-assignments.create', compact(
            'teachers',
            'classes',
            'subjects',
            'courses',
            'departments',
            'academicYears',
            'semesters'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'nullable|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'course_id' => 'nullable|exists:courses,id',
            'department' => 'nullable|string|max:255',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'status' => 'required|in:active,inactive,completed',
            'notes' => 'nullable|string',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        // Ensure at least one assignment type is selected
        if (!$request->filled('class_id') && !$request->filled('subject_id') && 
            !$request->filled('course_id') && !$request->filled('department')) {
            return back()->withErrors(['assignment_type' => 'Please select at least one assignment type (class, subject, course, or department).']);
        }

        $assignment = TeacherAssignment::create($validated);

        // Assign students if provided
        if ($request->filled('student_ids')) {
            $assignment->assignStudents($request->student_ids);
        }

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment created successfully.');
    }

    public function show(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->load(['teacher', 'class', 'subject', 'course', 'students.user']);

        return view('admin.teacher-assignments.show', compact('teacherAssignment'));
    }

    public function edit(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->load('students');

        $teachers = Teacher::orderBy('last_name')->get();
        $classes = ClassModel::with('course')->get();
        $subjects = Subject::with('course')->get();
        $courses = Course::with('department')->orderBy('program_name')->get();
        $departments = Department::orderBy('department_name')->pluck('department_name');
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        // Get available students based on assignment criteria
        $availableStudents = $this->getAvailableStudents($teacherAssignment);

        return view('admin.teacher-assignments.edit', compact(
            'teacherAssignment',
            'teachers',
            'classes',
            'subjects',
            'courses',
            'departments',
            'academicYears',
            'semesters',
            'availableStudents'
        ));
    }

    public function update(Request $request, TeacherAssignment $teacherAssignment)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'class_id' => 'nullable|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'course_id' => 'nullable|exists:courses,id',
            'department' => 'nullable|string|max:255',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'status' => 'required|in:active,inactive,completed',
            'notes' => 'nullable|string',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        // Ensure at least one assignment type is selected
        if (!$request->filled('class_id') && !$request->filled('subject_id') && 
            !$request->filled('course_id') && !$request->filled('department')) {
            return back()->withErrors(['assignment_type' => 'Please select at least one assignment type (class, subject, course, or department).']);
        }

        $teacherAssignment->update($validated);

        // Sync students if provided
        if ($request->has('student_ids')) {
            $teacherAssignment->syncStudents($request->student_ids ?? []);
        }

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment updated successfully.');
    }

    public function destroy(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->delete();

        return redirect()->route('admin.teacher-assignments.index')
            ->with('success', 'Teacher assignment deleted successfully.');
    }

    public function getAvailableStudents(?TeacherAssignment $assignment = null)
    {
        $query = Student::active();

        // Filter by class if assignment has class
        if ($assignment && $assignment->class_id) {
            $query->where('class_id', $assignment->class_id);
        }

        // Filter by course/department if assignment has course
        if ($assignment && $assignment->course_id) {
            $query->whereHas('class', function ($q) use ($assignment) {
                $q->where('course_id', $assignment->course_id);
            });
        }

        // Filter by department if assignment has department
        if ($assignment && $assignment->department) {
            $query->whereHas('class.course.department', function ($q) use ($assignment) {
                $q->where('department_name', $assignment->department);
            });
        }

        return $query->orderBy('user.name')->get();
    }

    public function getStudentsByFilter(Request $request)
    {
        $query = Student::active();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('course_id')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('class.course.department', function ($q) use ($request) {
                $q->where('department_name', $request->department);
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $students = $query->orderBy('user.name')->get();

        return response()->json([
            'students' => $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'student_id' => $student->student_id,
                    'year' => $student->year,
                    'section' => $student->section,
                    'class_name' => $student->class?->class_name ?? 'N/A',
                ];
            })
        ]);
    }
}
