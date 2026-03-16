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
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('teacher')->get();
        $totalStudents = Student::with('user')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses = ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();
        $courses = Course::orderBy('department')->get();
        $departments = Course::distinct()->pluck('department')->filter();

        return view('admin.classes', compact('classes', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects', 'courses', 'departments'));
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

                // Validate that all student IDs exist
                $validStudentIds = Student::whereIn('id', $studentIds)->pluck('id')->toArray();

                foreach ($assignmentIds as $assignmentId) {
                    foreach ($validStudentIds as $studentId) {
                        // Check if assignment already exists to avoid duplicates
                        $exists = DB::table('assignment_students')
                            ->where('assignment_id', $assignmentId)
                            ->where('student_id', $studentId)
                            ->exists();

                        if (! $exists) {
                            // Use the pivot table to assign students
                            DB::table('assignment_students')->insert([
                                'assignment_id' => $assignmentId,
                                'student_id' => $studentId,
                                'status' => 'assigned',
                                'assigned_at' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
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
                            DB::table('assignment_students')->insert([
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

    public function show(ClassModel $class)
    {
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::orderBy('department')->get();
        $departments = Course::distinct()->pluck('department')->filter();

        return view('admin.classes.show', compact('class', 'teachers', 'courses', 'departments'));
    }

    public function getStudents(Request $request)
    {
        // Fast query - get students with their user data and class/course info
        $query = \App\Models\Student::with(['user', 'class.course']);

        // Apply year filter if provided
        if ($request->year) {
            $query->where('year', $request->year);
        }

        // Apply course filter if provided
        if ($request->course_id) {
            $courseId = $request->course_id;
            $query->whereHas('class', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        // Apply department filter if provided
        if ($request->department) {
            $department = $request->department;
            $query->whereHas('class.course', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        // Apply search filter if provided
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $students = $query->get();

        // Map to the expected format
        $studentData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'student_id' => $student->student_id,
                'course_id' => $student->class->course->id ?? null,
                'course_name' => $student->class->course->program_name ?? 'Unknown',
                'department' => $student->class->course->department ?? 'Unknown',
                'year' => $student->year,
                'section' => $student->section,
                'class_id' => $student->class_id ?? null,
                'class_name' => $student->class->class_name ?? 'Unassigned',
            ];
        });

        return response()->json(['students' => $studentData]);
    }

    public function assignStudentsToClass(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $classId = $validated['class_id'];
        $studentIds = $validated['student_ids'];

        // Validate that all student IDs exist
        $validStudentIds = Student::whereIn('id', $studentIds)->pluck('id')->toArray();

        // Make sure there is an assignment record for this class (used by the pivot table)
        $class = ClassModel::findOrFail($classId);
        $teacherId = $class->teacher_id ?? 1;

        $assignment = DB::table('teacher_assignments')
            ->where('class_id', $classId)
            ->first();

        if (! $assignment) {
            $assignmentId = DB::table('teacher_assignments')->insertGetId([
                'teacher_id' => $teacherId,
                'class_id' => $classId,
                'subject_id' => null,
                'status' => 'active',
                'notes' => 'Auto-created for student assignment',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $assignmentId = $assignment->id;
        }

        // Determine which students should be added / removed based on the selected list
        $currentlyAssigned = DB::table('assignment_students')
            ->where('assignment_id', $assignmentId)
            ->pluck('student_id')
            ->toArray();

        $toAdd = array_values(array_diff($validStudentIds, $currentlyAssigned));
        $toRemove = array_values(array_diff($currentlyAssigned, $validStudentIds));

        // Remove students that were deselected
        if (!empty($toRemove)) {
            DB::table('assignment_students')
                ->where('assignment_id', $assignmentId)
                ->whereIn('student_id', $toRemove)
                ->delete();

            Student::whereIn('id', $toRemove)->update(['class_id' => null]);
        }

        // Add new students
        foreach ($toAdd as $studentId) {
            DB::table('assignment_students')->insert([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'status' => 'assigned',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Student::where('id', $studentId)->update(['class_id' => $classId]);
        }

        return response()->json([
            'success' => true,
            'message' => count($validStudentIds).' students successfully assigned to class',
        ]);
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully');
    }
}
