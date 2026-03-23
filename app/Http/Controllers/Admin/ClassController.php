<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Department;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        // Apply campus filtering for campus admins
        $query = ClassModel::with(['teacher', 'course', 'subject', 'school']);
        
        if ($adminCampus) {
            $query->where('campus', $adminCampus);
        }
        
        $classes = $query->get();
        
        // Group classes by course
        $classesByCourse = $classes->groupBy(function($class) {
            return $class->course ? $class->course->program_name : 'No Course Assigned';
        });
        
        // Get statistics with campus filtering
        $totalStudents = Student::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))->count();
        $totalTeachers = User::where('role', 'teacher')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->count();
        $totalClasses = ClassModel::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))->count();
        $totalSubjects = Subject::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))->count();
        
        $courses = Course::when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->orderBy('program_name')
            ->get();

        // Get departments (we'll use a simple array since departments table might not exist)
        $departments = collect([
            (object)['id' => 1, 'department_name' => 'College of Engineering and Technology'],
            (object)['id' => 2, 'department_name' => 'College of Agriculture'],
            (object)['id' => 3, 'department_name' => 'College of Education'],
            (object)['id' => 4, 'department_name' => 'College of Business'],
            (object)['id' => 5, 'department_name' => 'College of Arts and Sciences'],
            (object)['id' => 6, 'department_name' => 'College of Criminal Justice'],
        ]);

        return view('admin.classes', compact(
            'classes',
            'classesByCourse',
            'totalStudents', 
            'totalTeachers', 
            'totalClasses', 
            'totalSubjects', 
            'courses',
            'departments',
            'adminCampus'
        ));
    }

    public function create()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;
        
        // Filter data by campus for campus admins
        $teachers = User::where('role', 'teacher')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->get();
            
        $courses = Course::with('department')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->orderBy('program_name')
            ->get();
            
        $subjects = Subject::with('program')
            ->when($adminCampus, fn($q) => $q->where('campus', $adminCampus))
            ->when($adminSchoolId, fn($q) => $q->where('school_id', $adminSchoolId))
            ->get();
        
        // Add departments (empty for now, can be populated later if needed)
        $departments = [];
            
        $academicYears = ['2024-2025', '2025-2026', '2026-2027'];
        $semesters = ['First', 'Second', 'Summer'];

        return view('admin.classes.create', compact(
            'teachers',
            'courses',
            'subjects',
            'departments',
            'academicYears',
            'semesters',
            'adminCampus'
        ));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;
        
        $validated = $request->validate([
            'class_name' => 'required|string',
            'year_level' => 'required|int|in:1,2,3,4',
            'section' => 'required|string',
            'total_students' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
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
        
        // Add campus isolation to class
        $validated['campus'] = $adminCampus;
        $validated['school_id'] = $adminSchoolId;
        
        // Create the class
        $class = ClassModel::create($validated);

        // Assign students directly to the class if provided
        if ($request->filled('assigned_students')) {
            $studentIds = array_filter(explode(',', $request->assigned_students));
            
            if (!empty($studentIds)) {
                // Update students to assign them to this class
                Student::whereIn('id', $studentIds)
                    ->where('campus', $adminCampus)
                    ->where('school_id', $adminSchoolId)
                    ->update(['class_id' => $class->id]);
            }
        }

        // Create teacher assignments if requested
        if ($request->boolean('create_assignment') && $request->filled('assignment_teachers')) {
            $assignmentIds = [];

            foreach ($request->assignment_teachers as $teacherId) {
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $teacherId,
                    'class_id' => $class->id,
                    'subject_id' => $request->subject_id,
                    'course_id' => $request->course_id,
                    'department' => $request->assignment_department,
                    'campus' => $adminCampus,
                    'school_id' => $adminSchoolId,
                    'academic_year' => $request->academic_year,
                    'semester' => $request->semester,
                    'status' => 'active',
                    'notes' => $request->assignment_notes,
                ]);
                $assignmentIds[] = $assignment->id;

                // Assign additional subjects to teacher if provided
                if ($request->filled('assignment_subjects')) {
                    foreach ($request->assignment_subjects as $subjectId) {
                        if ($subjectId != $request->subject_id) {
                            $subjectAssignment = TeacherAssignment::create([
                                'teacher_id' => $teacherId,
                                'class_id' => $class->id,
                                'subject_id' => $subjectId,
                                'course_id' => $request->course_id,
                                'department' => $request->assignment_department,
                                'campus' => $adminCampus,
                                'school_id' => $adminSchoolId,
                                'academic_year' => $request->academic_year,
                                'semester' => $request->semester,
                                'status' => 'active',
                                'notes' => $request->assignment_notes,
                            ]);
                            $assignmentIds[] = $subjectAssignment->id;
                        }
                    }
                }
            }

            // Also assign students to teacher assignments if provided
            if ($request->filled('assigned_students') && !empty($assignmentIds)) {
                $studentIds = array_filter(explode(',', $request->assigned_students));
                
                // Validate that all student IDs exist and belong to this campus
                $validStudentIds = Student::whereIn('id', $studentIds)
                    ->where('campus', $adminCampus)
                    ->where('school_id', $adminSchoolId)
                    ->pluck('id')
                    ->toArray();

                foreach ($assignmentIds as $assignmentId) {
                    foreach ($validStudentIds as $studentId) {
                        $exists = DB::table('assignment_students')
                            ->where('assignment_id', $assignmentId)
                            ->where('student_id', $studentId)
                            ->exists();

                        if (!$exists) {
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

        $message = 'Class created successfully';
        if ($request->filled('assigned_students')) {
            $studentCount = count(array_filter(explode(',', $request->assigned_students)));
            $message .= " with {$studentCount} student(s) assigned";
        }
        if ($request->boolean('create_assignment')) {
            $message .= ' and teacher assignments';
        }

        return redirect()->route('admin.classes.index')->with('success', $message);
    }

    public function edit(ClassModel $class)
    {
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::with('department')->orderBy('program_name')->get();
        $subjects = Subject::with('course')->get();
        $departments = Department::orderBy('department_name')->pluck('department_name');
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
            'year_level' => 'required|int|in:1,2,3,4', // Use year_level as integer
            'section' => 'required|string',
            'total_students' => 'required|integer|min:1', // Changed from capacity
            'teacher_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id', // Added subject selection
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

        // Update the class with validated data
        $class->update([
            'class_name' => $validated['class_name'],
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'total_students' => $validated['total_students'],
            'teacher_id' => $validated['teacher_id'],
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
        ]);

        // Remove existing assignments for this class
        TeacherAssignment::where('class_id', $class->id)->delete();

        // Create new assignments if teachers are selected
        if ($request->filled('assignment_teachers')) {
                $assignmentIds = [];

                foreach ($request->assignment_teachers as $teacherId) {
                    $assignment = TeacherAssignment::create([
                        'teacher_id' => $teacherId,
                        'class_id' => $class->id,
                        'subject_id' => $request->subject_id, // Use the main subject
                        'course_id' => $request->course_id,
                        'department' => $request->assignment_department,
                        'academic_year' => $request->academic_year,
                        'semester' => $request->semester,
                        'status' => 'active',
                        'notes' => $request->assignment_notes,
                    ]);
                    $assignmentIds[] = $assignment->id;

                    // Assign additional subjects to teacher if provided
                    if ($request->filled('assignment_subjects')) {
                        foreach ($request->assignment_subjects as $subjectId) {
                            if ($subjectId != $request->subject_id) { // Don't duplicate main subject
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

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully'.
            ($request->boolean('update_assignments') ? ' with teacher assignments' : ''));
    }

    public function show(ClassModel $class)
    {
        $class->load('students.user', 'course.department');
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::with('department')->orderBy('program_name')->get();
        $departments = Department::orderBy('department_name')->pluck('department_name');

        // Count total students enrolled
        $totalStudentsEnrolled = $class->students()->count();

        return view('admin.classes.show', compact('class', 'teachers', 'courses', 'departments', 'totalStudentsEnrolled'));
    }

    public function getStudents(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        $adminSchoolId = $admin->school_id;
        
        // Fast query - get students with their class/course info
        $query = Student::with(['class.course']);
        
        // Apply campus isolation - CRITICAL
        if ($adminCampus) {
            $query->where('campus', $adminCampus);
        }
        if ($adminSchoolId) {
            $query->where('school_id', $adminSchoolId);
        }

        // Apply year filter if provided
        if ($request->year) {
            $query->where('year_level', $request->year);
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
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        // Map to the expected format
        $studentData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'student_id' => $student->student_id,
                'course_id' => $student->class->course->id ?? null,
                'program_name' => $student->class->course->program_name ?? 'Unknown',
                'department' => $student->class->course->department ?? 'Unknown',
                'year' => $student->year_level,
                'section' => $student->section,
                'class_id' => $student->class_id ?? null,
                'class_name' => $student->class->class_name ?? 'Unassigned',
                'campus' => $student->campus,
                'school_id' => $student->school_id,
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
        if (! empty($toRemove)) {
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

        // Update total_students count
        $totalStudents = Student::where('class_id', $classId)->count();
        ClassModel::where('id', $classId)->update(['total_students' => $totalStudents]);

        return response()->json([
            'success' => true,
            'message' => count($validStudentIds).' students successfully assigned to class',
        ]);
    }

    /**
     * Add a single student to a class manually
     */
    public function addStudentManually(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id|unique:students,id,NULL,id,class_id,'.$class->id,
        ], [
            'student_id.unique' => 'This student is already enrolled in this class.',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($validated['student_id']);

            // Ensure student doesn't already have a class
            if ($student->class_id !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student is already enrolled in another class. Please remove them first.',
                ], 422);
            }

            // Update student's class
            $student->update(['class_id' => $class->id]);

            // Create assignment record if needed
            $assignment = DB::table('teacher_assignments')
                ->where('class_id', $class->id)
                ->first();

            if (! $assignment) {
                $assignmentId = DB::table('teacher_assignments')->insertGetId([
                    'teacher_id' => $class->teacher_id ?? Auth::id(),
                    'class_id' => $class->id,
                    'subject_id' => null,
                    'status' => 'active',
                    'notes' => 'Auto-created for manual student assignment',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $assignmentId = $assignment->id;
            }

            // Add to assignment_students
            DB::table('assignment_students')->insert([
                'assignment_id' => $assignmentId,
                'student_id' => $student->id,
                'status' => 'assigned',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update total_students count
            $totalStudents = Student::where('class_id', $class->id)->count();
            $class->update(['total_students' => $totalStudents]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student '.$student->name.' successfully added to class',
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'student_id' => $student->student_id,
                    'email' => $student->email,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error adding student: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a student from a class
     */
    public function removeStudentFromClass(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($validated['student_id']);

            if ($student->class_id !== $class->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student is not in this class.',
                ], 422);
            }

            // Remove from assignment_students
            $assignment = DB::table('teacher_assignments')
                ->where('class_id', $class->id)
                ->first();

            if ($assignment) {
                DB::table('assignment_students')
                    ->where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->delete();
            }

            // Update student's class
            $student->update(['class_id' => null]);

            // Update total_students count
            $totalStudents = Student::where('class_id', $class->id)->count();
            $class->update(['total_students' => $totalStudents]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student successfully removed from class',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error removing student: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Import students from Excel or CSV file
     */
    public function importStudentsExcel(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'excel_file' => 'required|file|mimes:csv,txt|max:2048',
            'confirm' => 'required|in:yes',
        ], [
            'excel_file.mimes' => 'Only CSV files are supported. Please convert your Excel file to CSV and try again.',
            'confirm.required' => 'You must confirm before importing students.',
        ]);

        if ($validated['confirm'] !== 'yes') {
            return response()->json([
                'success' => false,
                'message' => 'Import confirmation required',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $path = $file->store('imports', 'local');

            // Parse CSV file
            $students = $this->parseCSV(storage_path('app/'.$path));

            if (empty($students)) {
                @unlink(storage_path('app/'.$path));

                return response()->json([
                    'success' => false,
                    'message' => 'No valid student records found in the file.',
                ], 422);
            }

            $added = 0;
            $errors = [];
            $studentIds = [];

            // Get or create teacher assignment
            $assignment = DB::table('teacher_assignments')
                ->where('class_id', $class->id)
                ->first();

            if (! $assignment) {
                $assignmentId = DB::table('teacher_assignments')->insertGetId([
                    'teacher_id' => $class->teacher_id ?? Auth::id(),
                    'class_id' => $class->id,
                    'subject_id' => null,
                    'status' => 'active',
                    'notes' => 'Auto-created for bulk student import',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $assignmentId = $assignment->id;
            }

            // Process each student
            foreach ($students as $index => $studentData) {
                try {
                    // Find student by student_id or ID
                    $student = Student::where('student_id', trim($studentData['student_id']))
                        ->orWhere('id', trim($studentData['student_id']))
                        ->first();

                    if (! $student) {
                        $errors[] = 'Row '.($index + 2).": Student ID '".trim($studentData['student_id'])."' not found";

                        continue;
                    }

                    // Check if already in a class
                    if ($student->class_id !== null && $student->class_id !== $class->id) {
                        $errors[] = 'Row '.($index + 2).": {$student->name} is already in another class";

                        continue;
                    }

                    // Add student to class
                    if ($student->class_id !== $class->id) {
                        $student->update(['class_id' => $class->id]);

                        // Add to assignment_students
                        DB::table('assignment_students')->insertOrIgnore([
                            'assignment_id' => $assignmentId,
                            'student_id' => $student->id,
                            'status' => 'assigned',
                            'assigned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $added++;
                        $studentIds[] = $student->id;
                    }
                } catch (\Exception $e) {
                    $errors[] = 'Row '.($index + 2).': Error processing student - '.$e->getMessage();
                }
            }

            // Update total_students count
            $totalStudents = Student::where('class_id', $class->id)->count();
            $class->update(['total_students' => $totalStudents]);

            // Delete temp file
            @unlink(storage_path('app/'.$path));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $added.' students successfully imported',
                'added' => $added,
                'errors' => $errors,
                'error_count' => count($errors),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error importing students: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCSV($path)
    {
        $students = [];
        if (! file_exists($path)) {
            return $students;
        }

        $handle = fopen($path, 'r');
        if (! $handle) {
            return $students;
        }

        $headers = null;
        $row_num = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $row_num++;

            if ($headers === null) {
                // First row is header
                $headers = array_map(function ($h) {
                    return strtolower(trim($h));
                }, $row);

                continue;
            }

            // Combine headers with values
            $data = array_combine($headers, array_map('trim', $row));

            // Look for student_id column
            $studentId = null;
            if (isset($data['student_id'])) {
                $studentId = $data['student_id'];
            } elseif (isset($data['student id'])) {
                $studentId = $data['student id'];
            } elseif (isset($data['id'])) {
                $studentId = $data['id'];
            } else {
                // Use first column if no ID column found
                $studentId = reset($row);
            }

            if (! empty($studentId)) {
                $students[] = [
                    'student_id' => $studentId,
                ];
            }
        }

        fclose($handle);

        return $students;
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully');
    }
}
