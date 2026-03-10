<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('teacher')->get();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();

        return view('admin.classes', compact('classes', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::orderBy('department')->get();
        $subjects = Subject::with('course')->get();
        $departments = Course::distinct()->pluck('department')->filter();
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        return view('admin.classes.create', compact(
            'teachers',
            'courses',
            'subjects',
            'departments',
            'academicYears',
            'semesters'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string',
            'class_level' => 'required|integer',
            'section' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'teacher_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'create_assignment' => 'boolean',
            'assignment_teachers' => 'nullable|array',
            'assignment_teachers.*' => 'exists:users,id',
            'assignment_subjects' => 'nullable|array',
            'assignment_subjects.*' => 'exists:subjects,id',
            'assignment_department' => 'nullable|string',
            'assignment_notes' => 'nullable|string',
            'assigned_students' => 'nullable|string',
        ]);

        // Create the class
        $class = ClassModel::create($validated);

        // Create teacher assignments if requested
        if ($request->boolean('create_assignment') && $request->filled('assignment_teachers')) {
            $assignmentIds = [];

            foreach ($request->assignment_teachers as $teacherId) {
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $teacherId,
                    'class_id' => $class->id,
                    'course_id' => $request->course_id,
                    'department' => $request->assignment_department,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester,
                    'status' => 'active',
                    'notes' => $request->assignment_notes,
                ]);
                $assignmentIds[] = $assignment->id;

                // Assign subjects to teacher if provided
                if ($request->filled('assignment_subjects')) {
                    foreach ($request->assignment_subjects as $subjectId) {
                        $subjectAssignment = TeacherAssignment::create([
                            'teacher_id' => $teacherId,
                            'class_id' => $class->id,
                            'subject_id' => $subjectId,
                            'course_id' => $request->course_id,
                            'department' => $request->assignment_department,
                            'academic_year' => $request->academic_year,
                            'semester' => $request->semester,
                            'status' => 'active',
                            'notes' => $request->assignment_notes,
                        ]);
                        $assignmentIds[] = $subjectAssignment->id;
                    }
                }
            }

            // Assign students to the assignments if provided
            if ($request->filled('assigned_students')) {
                $studentIds = explode(',', $request->assigned_students);
                foreach ($assignmentIds as $assignmentId) {
                    foreach ($studentIds as $studentId) {
                        // Use the pivot table to assign students
                        \DB::table('assignment_students')->insert([
                            'assignment_id' => $assignmentId,
                            'student_id' => trim($studentId),
                            'status' => 'active',
                            'assigned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully'.
            ($request->boolean('create_assignment') ? ' with teacher assignments' : ''));
    }

    public function edit(ClassModel $class)
    {
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::orderBy('department')->get();
        $subjects = Subject::with('course')->get();
        $departments = Course::distinct()->pluck('department')->filter();
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        // Get existing assignments for this class
        $existingAssignments = TeacherAssignment::where('class_id', $class->id)
            ->with(['teacher', 'subject'])
            ->get();

        return view('admin.classes.edit', compact(
            'class',
            'teachers',
            'courses',
            'subjects',
            'departments',
            'academicYears',
            'semesters',
            'existingAssignments'
        ));
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'class_name' => 'required|string',
            'class_level' => 'required|integer',
            'section' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'teacher_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'update_assignments' => 'boolean',
            'assignment_teachers' => 'nullable|array',
            'assignment_teachers.*' => 'exists:users,id',
            'assignment_subjects' => 'nullable|array',
            'assignment_subjects.*' => 'exists:subjects,id',
            'assignment_department' => 'nullable|string',
            'assignment_notes' => 'nullable|string',
            'assigned_students' => 'nullable|string',
        ]);

        // Update the class
        $class->update($validated);

        // Update teacher assignments if requested
        if ($request->boolean('update_assignments')) {
            // Remove existing assignments for this class
            TeacherAssignment::where('class_id', $class->id)->delete();

            // Create new assignments if teachers are selected
            if ($request->filled('assignment_teachers')) {
                $assignmentIds = [];

                foreach ($request->assignment_teachers as $teacherId) {
                    $assignment = TeacherAssignment::create([
                        'teacher_id' => $teacherId,
                        'class_id' => $class->id,
                        'course_id' => $request->course_id,
                        'department' => $request->assignment_department,
                        'academic_year' => $request->academic_year,
                        'semester' => $request->semester,
                        'status' => 'active',
                        'notes' => $request->assignment_notes,
                    ]);
                    $assignmentIds[] = $assignment->id;

                    // Assign subjects to teacher if provided
                    if ($request->filled('assignment_subjects')) {
                        foreach ($request->assignment_subjects as $subjectId) {
                            $subjectAssignment = TeacherAssignment::create([
                                'teacher_id' => $teacherId,
                                'class_id' => $class->id,
                                'subject_id' => $subjectId,
                                'course_id' => $request->course_id,
                                'department' => $request->assignment_department,
                                'academic_year' => $request->academic_year,
                                'semester' => $request->semester,
                                'status' => 'active',
                                'notes' => $request->assignment_notes,
                            ]);
                            $assignmentIds[] = $subjectAssignment->id;
                        }
                    }
                }

                // Assign students to the assignments if provided
                if ($request->filled('assigned_students')) {
                    $studentIds = explode(',', $request->assigned_students);
                    foreach ($assignmentIds as $assignmentId) {
                        foreach ($studentIds as $studentId) {
                            // Use the pivot table to assign students
                            \DB::table('assignment_students')->insert([
                                'assignment_id' => $assignmentId,
                                'student_id' => trim($studentId),
                                'status' => 'active',
                                'assigned_at' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully'.
            ($request->boolean('update_assignments') ? ' with teacher assignments' : ''));
    }

    public function getStudents(Request $request)
    {
        $query = Student::with('course');

        // Apply filters
        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->department) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                })->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name ?? 'Unknown',
                'student_id' => $student->student_id,
                'course_id' => $student->course_id,
                'course_name' => $student->course->program_name ?? 'Unknown',
                'department' => $student->course->department ?? 'Unknown',
                'year' => $student->year,
                'section' => $student->section,
            ];
        });

        return response()->json(['students' => $students]);
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully');
    }
}
