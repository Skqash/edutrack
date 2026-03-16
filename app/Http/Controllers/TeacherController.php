<?php

namespace App\Http\Controllers;

use App\Models\AssessmentRange;
use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Grade;
use App\Models\GradeEntry;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\SchoolRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /**
     * Show teacher dashboard
     */
    public function dashboard()
    {
        $teacherId = Auth::id();

        // Get teacher's classes with proper relationships
        $myClasses = ClassModel::where('teacher_id', $teacherId)
            ->with('course', 'students')
            ->get();

        // Get teacher's assigned subjects
        /** @var \App\Models\User $teacher */
        $teacher = Auth::user();
        $assignedSubjects = $teacher->subjects()->with('course')->get();

        // Get total students in teacher's classes
        $totalStudents = Student::whereIn('class_id',
            ClassModel::where('teacher_id', $teacherId)->pluck('id')
        )->with('user')->count();

        // Get grades posted by this teacher (total count)
        $gradesPosted = Grade::where('teacher_id', $teacherId)
            ->whereNotNull('final_grade')
            ->distinct('student_id', 'class_id', 'term')
            ->count();

        // Get classes without complete grades (pending tasks)
        $classesWithoutGrades = [];
        foreach ($myClasses as $class) {
            $studentCount = $class->students()->count();
            $gradesCount = Grade::where('class_id', $class->id)
                ->where('teacher_id', $teacherId)
                ->whereNotNull('final_grade')
                ->distinct('student_id')
                ->count();

            if ($gradesCount < $studentCount) {
                $classesWithoutGrades[] = $class->id;
            }
        }
        $pendingTasks = count($classesWithoutGrades);

        // Get recent grades by class (grouped summary)
        $recentGradesByClass = ClassModel::where('teacher_id', $teacherId)
            ->with(['grades' => function ($query) {
                $query->whereNotNull('final_grade')->latest('updated_at')->limit(1);
            }])
            ->whereHas('grades', function ($query) {
                $query->whereNotNull('final_grade');
            })
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Transform to get class data with grade statistics
        $recentGrades = $recentGradesByClass->map(function ($class) {
            $classGrades = Grade::where('class_id', $class->id)
                ->whereNotNull('final_grade')
                ->get();

            return (object) [
                'class_id' => $class->id,
                'class_name' => $class->class_name,
                'course_name' => $class->course->course_name ?? 'N/A',
                'student_count' => $classGrades->count(),
                'avg_knowledge' => $classGrades->avg('knowledge_average') ?? 0,
                'avg_skills' => $classGrades->avg('skills_average') ?? 0,
                'avg_attitude' => $classGrades->avg('attitude_average') ?? 0,
                'avg_final_grade' => $classGrades->avg('final_final_grade') ?? $classGrades->avg('final_grade') ?? 0,
                'updated_at' => $classGrades->max('updated_at'),
            ];
        })->sortByDesc('updated_at')->values();

        // Get teacher's courses (unique from their classes)
        $myCourses = Course::whereIn('id', $myClasses->pluck('course_id')->unique())->get();

        // Latest school request that has been approved or rejected (for dashboard alert)
        $latestSchoolRequest = SchoolRequest::where('user_id', $teacherId)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('teacher.dashboard', compact(
            'myClasses',
            'totalStudents',
            'gradesPosted',
            'pendingTasks',
            'recentGrades',
            'myCourses',
            'assignedSubjects',
            'latestSchoolRequest'
        ));
    }

    /**
     * Show teacher's classes
     */
    public function classes()
    {
        $teacherId = Auth::id();

        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->paginate(10);

        // Provide course list for filtering (all courses in the system)
        $courses = Course::orderBy('course_name')->get();

        return view('teacher.classes.index', compact('classes', 'courses'));
    }

    /**
    /**
     * Show teacher's assigned courses
     */
    public function subjectsIndex()
    {
        $teacherId = Auth::id();

        // Get all unique courses from this teacher's classes
        $courseIds = ClassModel::where('teacher_id', $teacherId)
            ->distinct()
            ->pluck('course_id');

        $courses = Course::whereIn('id', $courseIds)
            ->orderBy('course_name')
            ->get();

        // Enhance course data with class and student counts
        $coursesData = $courses->map(function ($course) use ($teacherId) {
            $classes = ClassModel::where('course_id', $course->id)
                ->where('teacher_id', $teacherId)
                ->with('students')
                ->get();

            return [
                'id' => $course->id,
                'name' => $course->course_name,
                'description' => $course->description ?? null,
                'code' => $course->course_code ?? null,
                'class_count' => $classes->count(),
                'student_count' => $classes->sum(fn ($c) => $c->students->count()),
                'classes' => $classes->map(fn ($c) => ['id' => $c->id, 'class_name' => $c->class_name])->toArray(),
            ];
        });

        $totalClasses = ClassModel::where('teacher_id', $teacherId)->count();
        $totalStudents = ClassModel::where('teacher_id', $teacherId)
            ->with('students')
            ->get()
            ->sum(fn ($c) => $c->students->count());

        return view('teacher.subjects.index', [
            'courses' => $coursesData,
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Request a subject from admin
     */
    public function requestSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50',
            'reason' => 'required|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $teacher = User::find($teacherId);

        // Create a notification for admin
        \App\Models\Notification::create([
            'user_id' => 1, // Assuming admin user ID is 1, adjust as needed
            'title' => 'Subject Request from ' . $teacher->name,
            'message' => "Teacher {$teacher->name} has requested to be assigned: {$validated['subject_name']}" . 
                        ($validated['subject_code'] ? " ({$validated['subject_code']})" : '') . 
                        ". Reason: {$validated['reason']}",
            'type' => 'info',
            'icon' => 'paper-plane',
            'action_url' => route('admin.teachers.subjects', $teacherId),
        ]);

        return redirect()->route('teacher.subjects')->with('success', 'Subject request sent to admin successfully!');
    }

    /**
     * Create a new subject (for independent teachers)
     */
    public function createSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'credit_hours' => 'required|integer|min:1|max:6',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $teacherId = Auth::id();

        // Create the subject
        $subject = \App\Models\Subject::create([
            'subject_name' => $validated['subject_name'],
            'subject_code' => $validated['subject_code'],
            'credit_hours' => $validated['credit_hours'],
            'category' => $validated['category'] ?? 'General',
            'description' => $validated['description'],
            'program' => 'Independent',
            'type' => 'Core',
            'instructor_id' => $teacherId,
        ]);

        return redirect()->route('teacher.subjects')->with('success', 'Subject created successfully! You can now create classes for this subject.');
    }

    /**
     * Show class details with students
     */
    public function classDetail($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'course', 'attendance', 'grades')
            ->firstOrFail();

        return view('teacher.classes.show', compact('class'));
    }

    /**
     * Show teacher grade dashboard (midterm/final entry + summary)
     */
    public function grades()
    {
        $teacherId = Auth::id();

        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->paginate(10);

        // Provide course list for filtering (all courses in the system)
        $courses = Course::orderBy('course_name')->get();

        return view('teacher.grades.index', compact('classes', 'courses'));
    }

    /**
     * Show grade entry form for a class
     */
    public function gradeEntry($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->firstOrFail();

        // Get existing grades for this class
        $existingGrades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->get()
            ->keyBy('student_id');

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        return view('teacher.grades.entry', compact('class', 'existingGrades', 'range'));
    }

    /**
     * Show UNIFIED grade entry form (New Comprehensive Form)
     */
    public function showGradeEntryUnified($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store or update grades (KSA Grading System)
     */
    public function storeGrades(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Validate teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)->firstOrFail();

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.knowledge_score' => 'required|numeric|min:0|max:100',
            'grades.*.skills_score' => 'required|numeric|min:0|max:100',
            'grades.*.attitude_score' => 'required|numeric|min:0|max:100',
            'grades.*.remarks' => 'nullable|string|max:255',
        ], [
            'grades.*.knowledge_score.required' => 'Knowledge score is required',
            'grades.*.skills_score.required' => 'Skills score is required',
            'grades.*.attitude_score.required' => 'Attitude score is required',
        ]);

        $gradesCreated = 0;
        $gradesUpdated = 0;

        foreach ($validated['grades'] as $gradeData) {
            $knowledge = (float) $gradeData['knowledge_score'];
            $skills = (float) $gradeData['skills_score'];
            $attitude = (float) $gradeData['attitude_score'];

            // Calculate final grade using KSA formula
            $finalGrade = Grade::calculateFinalGrade($knowledge, $skills, $attitude);

            // Update or create grade
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                ],
                [
                    'knowledge_score' => $knowledge,
                    'skills_score' => $skills,
                    'attitude_score' => $attitude,
                    'final_grade' => $finalGrade,
                    'remarks' => $gradeData['remarks'] ?? null,
                    'grading_period' => date('Y-m').'-'.(intdiv((int) date('m') - 1, 3) + 1),
                ]
            );

            if ($grade->wasRecentlyCreated) {
                $gradesCreated++;
            } else {
                $gradesUpdated++;
            }
        }

        return redirect()->route('teacher.grades')
            ->with('success', "Grades saved! Created: {$gradesCreated}, Updated: {$gradesUpdated}");
    }

    /**
     * Show attendance page
     */
    public function attendance()
    {
        $teacherId = Auth::id();
        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students', 'course')
            ->get();

        return view('teacher.attendance.index', compact('classes'));
    }

    /**
     * Manage attendance for a specific class
     */
    public function manageAttendance($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::with('course')->findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $students = $class->students()->with('user')->orderBy('student_id')->get();
        $today = now()->format('Y-m-d');

        // Get today's attendance
        $attendances = Attendance::where('class_id', $classId)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        return view('teacher.attendance.manage', compact('class', 'students', 'attendances', 'today'));
    }

    /**
     * Record attendance
     */
    public function recordAttendance(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $date = $validated['date'];
        $attendanceData = $request->input('attendance', []);

        if (empty($attendanceData)) {
            return redirect()->back()->with('error', 'No attendance data submitted. Please mark at least one student.');
        }

        $saved = 0;
        foreach ($attendanceData as $studentId => $data) {
            $status = $data['status'] ?? null;
            if (!$status || !in_array($status, ['Present', 'Absent', 'Late', 'Leave'])) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id'   => $classId,
                    'date'       => $date,
                ],
                ['status' => $status]
            );
            $saved++;
        }

        if ($saved === 0) {
            return redirect()->back()->with('error', 'No valid attendance records were saved.');
        }

        return redirect()->back()->with('success', "Attendance saved for {$saved} student(s) on {$date}.");
    }

    /**
     * Attendance history / search for a class
     */
    public function attendanceHistory(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);

        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $students = $class->students()->with('user')->get();

        $query = Attendance::where('class_id', $classId);

        // Filters
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $studentId = $request->input('student_id');

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        if ($start && $end) {
            // ensure proper ordering
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end));
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($start) {
            $query->where('date', '>=', date('Y-m-d', strtotime($start)));
        } elseif ($end) {
            $query->where('date', '<=', date('Y-m-d', strtotime($end)));
        }

        $attendances = $query->with('student')->orderBy('date', 'desc')->paginate(25)->appends($request->query());

        return view('teacher.attendance.history', compact('class', 'students', 'attendances', 'start', 'end', 'studentId'));
    }

    /**
     * Get KSA grading information
     */
    public function ksaInfo()
    {
        return response()->json([
            'knowledge' => [
                'weight' => 40,
                'description' => 'Quizzes 40% + Exams 60% = 40% of term',
            ],
            'skills' => [
                'weight' => 50,
                'description' => 'Output 40% + Class Part 30% + Activities 15% + Assignments 15% = 50% of term',
            ],
            'attitude' => [
                'weight' => 10,
                'description' => 'Behavior 50% + Awareness 50% = 10% of term',
            ],
            'formula' => 'Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)',
        ]);
    }

    /**
     * Show student addition form
     */
    public function showAddStudent()
    {
        $teacherId = Auth::id();
        $myClasses = ClassModel::where('teacher_id', $teacherId)->get();
        $students = Student::whereIn('class_id', $myClasses->pluck('id'))
            ->with('user')
            ->paginate(20);

        return view('teacher.students.add', compact('myClasses', 'students'));
    }

    /**
     * Store manually added student
     */
    public function storeStudent(Request $request)
    {
        $teacherId = Auth::id();

        // Verify class belongs to teacher
        $class = ClassModel::where('teacher_id', $teacherId)
            ->findOrFail($request->class_id);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'year' => 'required|integer|in:1,2,3,4',
            'section' => 'required|string|in:A,B,C,D,E',
        ]);

        // Combine firstname and surname
        $fullName = trim($validated['firstname']).' '.trim($validated['surname']);

        // Create user
        $user = \App\Models\User::create([
            'name' => $fullName,
            'email' => $validated['email'],
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Generate student ID in format: YYYY-XXXX-S (e.g., 2022-0233-A)
        $enrollmentYear = date('Y');
        $nextStudentNumber = Student::count() + 1;
        $sequentialNumber = str_pad($nextStudentNumber, 4, '0', STR_PAD_LEFT);
        $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $validated['section']);

        // Create student
        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $validated['class_id'],
            'student_id' => $studentId,
            'year' => $validated['year'],
            'section' => $validated['section'],
            'status' => 'Active',
        ]);

        return redirect()->back()->with('success', "Student {$studentId} added successfully!");
    }

    /**
     * Search for existing students to add to class
     */
    public function searchStudents(Request $request)
    {
        $teacherId = Auth::id();
        $classId = $request->input('class_id');
        $searchTerm = $request->input('search', '');
        $unassignedOnly = $request->input('unassigned_only', false);
        $sameYearOnly = $request->input('same_year_only', false);

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        // Build query
        $query = Student::with('user', 'class');

        // Search by name, email, or student ID
        if (! empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($subQ) use ($searchTerm) {
                    $subQ->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                })
                    ->orWhere('student_id', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by same year level if requested
        if ($sameYearOnly && $class->year) {
            $query->where('year', $class->year);
        }

        // Filter unassigned if requested
        if ($unassignedOnly) {
            $query->whereNull('class_id')
                ->orWhere('class_id', '!=', $classId);
        }

        // Exclude students already in this class
        $query->where(function ($q) use ($classId) {
            $q->whereNull('class_id')
                ->orWhere('class_id', '!=', $classId);
        });

        $students = $query->limit(50)->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name ?? 'N/A',
                'email' => $student->user->email ?? 'N/A',
                'student_id' => $student->student_id,
                'year' => $student->year,
                'section' => $student->section,
                'current_class' => $student->class ? $student->class->class_name : null,
                'status' => $student->status,
            ];
        });

        return response()->json(['students' => $students]);
    }

    /**
     * Add existing students to class
     */
    public function addExistingStudents(Request $request)
    {
        $teacherId = Auth::id();
        $classId = $request->input('class_id');
        $studentIds = $request->input('student_ids', []);

        // Validate input
        if (empty($classId) || empty($studentIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Class and students are required',
            ], 400);
        }

        // Verify teacher owns this class
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found or access denied',
            ], 404);
        }

        $addedCount = 0;
        $errors = [];

        foreach ($studentIds as $studentId) {
            try {
                $student = Student::findOrFail($studentId);

                // Check if student is already in this class
                if ($student->class_id == $classId) {
                    $errors[] = "Student {$student->student_id} is already in this class";

                    continue;
                }

                // Update student's class assignment + school/department metadata
                $studentUpdate = ['class_id' => $classId];
                if (!empty($class->course->college)) {
                    $studentUpdate['school'] = $class->course->college;
                }
                if (!empty($class->course->department)) {
                    $studentUpdate['department'] = $class->course->department;
                }
                $student->update($studentUpdate);
                $addedCount++;

            } catch (\Exception $e) {
                $errors[] = "Error adding student ID {$studentId}: ".$e->getMessage();
            }
        }

        if ($addedCount > 0) {
            $message = "Successfully added {$addedCount} student(s) to {$class->class_name}";
            if (! empty($errors)) {
                $message .= '. Some errors occurred: '.implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'added_count' => $addedCount,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No students were added. '.implode(', ', $errors),
            ], 400);
        }
    }

    /**
     * Show CHED grade entry form
     */
    public function showGradeEntryChed($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Show term selection view (Step 1: Choose midterm or final)
     */
    public function showGradeEntryByTerm($classId)
    {
        $teacherId = Auth::id();

        // Get term from query parameter
        $term = request()->query('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            abort(400, 'Invalid term');
        }

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user', 'course')
            ->firstOrFail();

        $students = $class->students()->with('user')->get();

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Load existing grade entries for this term
        $entries = GradeEntry::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades.grade_entry', compact('class', 'students', 'term', 'entries', 'range'));
    }

    /**
     * Store grade entry for a specific term (using query parameter)
     */
    public function storeGradeEntryByTerm(Request $request, $classId)
    {
        $teacherId = Auth::id();

        // Get term from query parameter
        $term = $request->query('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            abort(400, 'Invalid term');
        }

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $saved = 0;
        $errors = [];

        foreach ($request->input('grades', []) as $studentId => $gradeData) {
            try {
                // Clean and validate the data
                $data = [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => $term,
                ];

                // Knowledge Component
                $data['exam_pr'] = floatval($gradeData['exam_pr'] ?? 0);
                $data['exam_md'] = floatval($gradeData['exam_md'] ?? 0);
                $data['exam_fn'] = floatval($gradeData['exam_fn'] ?? 0);
                $data['quiz_1'] = floatval($gradeData['quiz_1'] ?? 0);
                $data['quiz_2'] = floatval($gradeData['quiz_2'] ?? 0);
                $data['quiz_3'] = floatval($gradeData['quiz_3'] ?? 0);
                $data['quiz_4'] = floatval($gradeData['quiz_4'] ?? 0);
                $data['quiz_5'] = floatval($gradeData['quiz_5'] ?? 0);

                // Skills Component
                $data['output_1'] = floatval($gradeData['output_1'] ?? 0);
                $data['output_2'] = floatval($gradeData['output_2'] ?? 0);
                $data['output_3'] = floatval($gradeData['output_3'] ?? 0);
                $data['classpart_1'] = floatval($gradeData['classpart_1'] ?? 0);
                $data['classpart_2'] = floatval($gradeData['classpart_2'] ?? 0);
                $data['classpart_3'] = floatval($gradeData['classpart_3'] ?? 0);
                $data['activity_1'] = floatval($gradeData['activity_1'] ?? 0);
                $data['activity_2'] = floatval($gradeData['activity_2'] ?? 0);
                $data['activity_3'] = floatval($gradeData['activity_3'] ?? 0);
                $data['assignment_1'] = floatval($gradeData['assignment_1'] ?? 0);
                $data['assignment_2'] = floatval($gradeData['assignment_2'] ?? 0);
                $data['assignment_3'] = floatval($gradeData['assignment_3'] ?? 0);

                // Attitude Component
                $data['behavior_1'] = floatval($gradeData['behavior_1'] ?? 0);
                $data['behavior_2'] = floatval($gradeData['behavior_2'] ?? 0);
                $data['behavior_3'] = floatval($gradeData['behavior_3'] ?? 0);
                $data['attendance_1'] = floatval($gradeData['attendance_1'] ?? 0);
                $data['attendance_2'] = floatval($gradeData['attendance_2'] ?? 0);
                $data['attendance_3'] = floatval($gradeData['attendance_3'] ?? 0);
                $data['awareness_1'] = floatval($gradeData['awareness_1'] ?? 0);
                $data['awareness_2'] = floatval($gradeData['awareness_2'] ?? 0);
                $data['awareness_3'] = floatval($gradeData['awareness_3'] ?? 0);

                // Use GradeEntry model with computeAverages method
                $entry = GradeEntry::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    $data
                );

                // Compute the averages and get computed values
                $weights = [
                    'knowledge' => 40,
                    'skills' => 50,
                    'attitude' => 10,
                ];
                $computedValues = $entry->computeAverages($weights);

                // Update the entry with computed values
                $entry->update($computedValues);

                $saved++;
            } catch (\Exception $e) {
                Log::error('Grade entry error for student '.$studentId.': '.$e->getMessage());
                $errors[] = "Error saving grades for student ID {$studentId}";

                continue;
            }
        }

        if ($saved > 0) {
            return redirect()->route('teacher.grades')->with('success', "✅ Saved {$saved} grade records for ".ucfirst($term).' term');
        } else {
            return back()->with('error', 'No grades were saved. '.implode(', ', $errors));
        }
    }

    /**
     * Show the new professional grade entry page with selectable grading schemes
     */
    public function showGradeEntryAdvanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->query('term', 'midterm'); // Get from query parameter, default to midterm

        $class = ClassModel::where('teacher_id', $teacherId)
            ->with('students.user', 'course')
            ->findOrFail($classId);

        $students = $class->students()->with('user')->get();

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Load existing grade entries for this term from GradeEntry model
        $entries = GradeEntry::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades.grade_entry', compact('class', 'students', 'term', 'entries', 'range'));
    }

    /**
     * Store grades from the grade entry form
     */
    public function storeGradeEntryAdvanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->query('term', 'midterm');

        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $saved = 0;
        $errors = [];

        foreach ($request->input('grades', []) as $studentId => $gradeData) {
            try {
                // Prepare data for GradeEntry
                $data = [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => $term,
                ];

                // Knowledge Component
                $data['exam_pr'] = floatval($gradeData['exam_pr'] ?? 0);
                $data['exam_md'] = floatval($gradeData['exam_md'] ?? 0);
                $data['quiz_1'] = floatval($gradeData['quiz_1'] ?? 0);
                $data['quiz_2'] = floatval($gradeData['quiz_2'] ?? 0);
                $data['quiz_3'] = floatval($gradeData['quiz_3'] ?? 0);
                $data['quiz_4'] = floatval($gradeData['quiz_4'] ?? 0);
                $data['quiz_5'] = floatval($gradeData['quiz_5'] ?? 0);

                // Skills Component
                $data['output_1'] = floatval($gradeData['output_1'] ?? 0);
                $data['output_2'] = floatval($gradeData['output_2'] ?? 0);
                $data['output_3'] = floatval($gradeData['output_3'] ?? 0);
                $data['classpart_1'] = floatval($gradeData['classpart_1'] ?? 0);
                $data['classpart_2'] = floatval($gradeData['classpart_2'] ?? 0);
                $data['classpart_3'] = floatval($gradeData['classpart_3'] ?? 0);
                $data['activity_1'] = floatval($gradeData['activity_1'] ?? 0);
                $data['activity_2'] = floatval($gradeData['activity_2'] ?? 0);
                $data['activity_3'] = floatval($gradeData['activity_3'] ?? 0);
                $data['assignment_1'] = floatval($gradeData['assignment_1'] ?? 0);
                $data['assignment_2'] = floatval($gradeData['assignment_2'] ?? 0);
                $data['assignment_3'] = floatval($gradeData['assignment_3'] ?? 0);

                // Attitude Component
                $data['behavior_1'] = floatval($gradeData['behavior_1'] ?? 0);
                $data['behavior_2'] = floatval($gradeData['behavior_2'] ?? 0);
                $data['behavior_3'] = floatval($gradeData['behavior_3'] ?? 0);
                $data['awareness_1'] = floatval($gradeData['awareness_1'] ?? 0);
                $data['awareness_2'] = floatval($gradeData['awareness_2'] ?? 0);
                $data['awareness_3'] = floatval($gradeData['awareness_3'] ?? 0);

                // Update or create the entry
                $entry = GradeEntry::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    $data
                );

                // Compute averages and save
                $weights = [
                    'knowledge' => 40,
                    'skills' => 50,
                    'attitude' => 10,
                ];
                $computedValues = $entry->computeAverages($weights);
                $entry->update($computedValues);

                $saved++;
            } catch (\Exception $e) {
                Log::error('Grade entry error for student '.$studentId.': '.$e->getMessage());
                $errors[] = "Error saving grades for student ID {$studentId}";

                continue;
            }
        }

        if ($saved > 0) {
            return redirect()->route('teacher.grades')->with('success', "✅ Saved {$saved} grade records for ".ucfirst($term).' term');
        } else {
            return back()->with('error', 'No grades were saved. '.implode(', ', $errors));
        }
    }

    /**
     * Compute grades for a specific period (midterm or final)
     */
    private function computePeriodGrades(array &$data, string $period, array $weights)
    {
        // EXAM AVERAGES
        $examPr = $data["{$period}_exam_pr"] ?? 0;
        $examMd = $data["{$period}_exam_md"] ?? 0;
        $examAve = ($examPr + $examMd) / 2;

        // QUIZ AVERAGES
        $quizzes = [
            $data["{$period}_quiz_1"] ?? 0,
            $data["{$period}_quiz_2"] ?? 0,
            $data["{$period}_quiz_3"] ?? 0,
            $data["{$period}_quiz_4"] ?? 0,
            $data["{$period}_quiz_5"] ?? 0,
        ];
        $quizAve = array_sum($quizzes) / count($quizzes);

        // KNOWLEDGE = (Exam 60% + Quiz 40%)
        $knowledge = ($examAve * 0.60) + ($quizAve * 0.40);
        $data["{$period}_knowledge_average"] = $knowledge;

        // OUTPUT AVERAGE
        $outputs = [
            $data["{$period}_output_1"] ?? 0,
            $data["{$period}_output_2"] ?? 0,
            $data["{$period}_output_3"] ?? 0,
        ];
        $outputAve = array_sum($outputs) / count($outputs);

        // CLASS PARTICIPATION AVERAGE
        $classparts = [
            $data["{$period}_classpart_1"] ?? 0,
            $data["{$period}_classpart_2"] ?? 0,
            $data["{$period}_classpart_3"] ?? 0,
        ];
        $classpartAve = array_sum($classparts) / count($classparts);

        // ACTIVITIES AVERAGE
        $activities = [
            $data["{$period}_activity_1"] ?? 0,
            $data["{$period}_activity_2"] ?? 0,
            $data["{$period}_activity_3"] ?? 0,
        ];
        $activityAve = array_sum($activities) / count($activities);

        // ASSIGNMENTS AVERAGE
        $assignments = [
            $data["{$period}_assignment_1"] ?? 0,
            $data["{$period}_assignment_2"] ?? 0,
            $data["{$period}_assignment_3"] ?? 0,
        ];
        $assignmentAve = array_sum($assignments) / count($assignments);

        // SKILLS = (Output 40% + ClassPart 30% + Activities 15% + Assignments 15%)
        $skills = ($outputAve * 0.40) + ($classpartAve * 0.30) + ($activityAve * 0.15) + ($assignmentAve * 0.15);
        $data["{$period}_skills_average"] = $skills;

        // BEHAVIOR AVERAGE
        $behaviors = [
            $data["{$period}_behavior_1"] ?? 0,
            $data["{$period}_behavior_2"] ?? 0,
            $data["{$period}_behavior_3"] ?? 0,
        ];
        $behaviorAve = array_sum($behaviors) / count($behaviors);

        // AWARENESS AVERAGE
        $awareness_arr = [
            $data["{$period}_awareness_1"] ?? 0,
            $data["{$period}_awareness_2"] ?? 0,
            $data["{$period}_awareness_3"] ?? 0,
        ];
        $awarenessAve = array_sum($awareness_arr) / count($awareness_arr);

        // ATTITUDE = (Behavior 50% + Awareness 50%)
        $attitude = ($behaviorAve * 0.50) + ($awarenessAve * 0.50);
        $data["{$period}_attitude_average"] = $attitude;

        // PERIOD FINAL GRADE = (Knowledge % + Skills % + Attitude %)
        $k = $weights['knowledge'] / 100;
        $s = $weights['skills'] / 100;
        $a = $weights['attitude'] / 100;
        $periodGrade = ($knowledge * $k) + ($skills * $s) + ($attitude * $a);
        $data["{$period}_final_grade"] = $periodGrade;
    }

    /**
     * Get 5-point grade scale
     */
    private function getGrade5ptScale(float $grade): string
    {
        if ($grade >= 90) {
            return '5.0';
        }
        if ($grade >= 80) {
            return '4.0';
        }
        if ($grade >= 70) {
            return '3.0';
        }
        if ($grade >= 60) {
            return '2.0';
        }
        if ($grade >= 50) {
            return '1.0';
        }

        return '0.0';
    }

    /**
     * Get grade remarks
     */
    private function getGradeRemark(float $grade): string
    {
        if ($grade >= 90) {
            return 'Excellent';
        }
        if ($grade >= 80) {
            return 'Very Good';
        }
        if ($grade >= 70) {
            return 'Good';
        }
        if ($grade >= 60) {
            return 'Fair';
        }
        if ($grade >= 50) {
            return 'Poor';
        }

        return 'Fail';
    }

    /**
     * Recalculate scores using a provided scheme weights array
     */
    private function recalculateGradeScoresWithScheme($grade, array $weights)
    {
        // Knowledge Score (default internal: exams 60%, quizzes 40%)
        $quizzes = array_filter([$grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5]);
        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $exams = array_filter([$grade->exam_prelim ?? null, $grade->midterm_exam ?? null, $grade->final_exam ?? null]);
        $examAvg = count($exams) > 0 ? array_sum($exams) / count($exams) : 0;
        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills (internal component weights kept as standard)
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : ($grade->output_1 ?? 0);

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : ($grade->class_participation_1 ?? 0);

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : ($grade->activities_1 ?? 0);

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : ($grade->assignments_1 ?? 0);

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : ($grade->behavior_1 ?? 0);
        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : ($grade->awareness_1 ?? 0);
        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final grade using provided KSA weights (percent values expected)
        $k = ($weights['knowledge'] ?? 40) / 100;
        $s = ($weights['skills'] ?? 50) / 100;
        $a = ($weights['attitude'] ?? 10) / 100;

        $grade->final_grade = (
            ($grade->knowledge_score * $k) +
            ($grade->skills_score * $s) +
            ($grade->attitude_score * $a)
        );

        $grade->save();
    }

    /**
     * Store CHED grades with improved validation and error handling
     * Handles both unified and legacy form formats
     */
    public function storeGradesChed(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        $grades = $request->input('grades', []);
        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return redirect()->back()->with('error', 'Invalid term selected');
        }

        $successCount = 0;
        $errors = [];

        foreach ($grades as $index => $gradeData) {
            try {
                // Handle both numeric keys (from form submission) and student_id keys
                $studentId = $gradeData['student_id'] ?? $index;

                $student = Student::findOrFail($studentId);

                // Prepare update data
                $updateData = [
                    'subject_id' => $class->course_id,
                    'teacher_id' => $teacherId,
                ];

                // Knowledge component - always at base level
                $quizzes = [
                    floatval($gradeData['q1'] ?? 0),
                    floatval($gradeData['q2'] ?? 0),
                    floatval($gradeData['q3'] ?? 0),
                    floatval($gradeData['q4'] ?? 0),
                    floatval($gradeData['q5'] ?? 0),
                ];

                $exams = [
                    'midterm' => floatval($gradeData['midterm_exam'] ?? 0),
                    'final' => floatval($gradeData['final_exam'] ?? 0),
                ];

                // Store quiz and exam scores
                $updateData['q1'] = $quizzes[0];
                $updateData['q2'] = $quizzes[1];
                $updateData['q3'] = $quizzes[2];
                $updateData['q4'] = $quizzes[3];
                $updateData['q5'] = $quizzes[4];
                $updateData['midterm_exam'] = $exams['midterm'];
                $updateData['final_exam'] = $exams['final'];

                // Skills entries - handle both formats (with and without term suffix)
                $skillFields = [
                    'output_1', 'output_2', 'output_3',
                    'class_participation_1', 'class_participation_2', 'class_participation_3',
                    'activities_1', 'activities_2', 'activities_3',
                    'assignments_1', 'assignments_2', 'assignments_3',
                ];

                foreach ($skillFields as $field) {
                    // Try without suffix first (unified form), then with suffix (legacy form)
                    $value = $gradeData[$field] ?? $gradeData[$field.'_'.$term] ?? null;
                    if ($value !== null) {
                        $updateData[$field] = floatval($value);
                    }
                }

                // Attitude entries - handle both formats
                $attitudeFields = [
                    'behavior_1', 'behavior_2', 'behavior_3',
                    'awareness_1', 'awareness_2', 'awareness_3',
                ];

                foreach ($attitudeFields as $field) {
                    // Try without suffix first (unified form), then with suffix (legacy form)
                    $value = $gradeData[$field] ?? $gradeData[$field.'_'.$term] ?? null;
                    if ($value !== null) {
                        $updateData[$field] = floatval($value);
                    }
                }

                // Create or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    $updateData
                );

                // Calculate component scores
                $this->recalculateGradeScores($grade);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Student {$index}: ".$e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "✓ {$successCount} grades saved successfully for {$term} term!";
            if (! empty($errors)) {
                $message .= ' ('.count($errors).' errors encountered)';
            }

            return redirect()->route('teacher.grades')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No grades were saved. Please check for errors.');
        }
    }

    /**
     * Recalculate all grade scores and update the database
     */
    private function recalculateGradeScores($grade)
    {
        // Knowledge Score (40%)
        $quizzes = array_filter([
            $grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5,
            $grade->q6, $grade->q7, $grade->q8, $grade->q9, $grade->q10,
        ]);

        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $examAvg = (($grade->midterm_exam ?? 0) + ($grade->final_exam ?? 0)) / 2;

        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills Score (50%) - Calculate from individual entries
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : 0;
        $grade->output_score = $outputAvg;

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : 0;
        $grade->class_participation_score = $cpAvg;

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : 0;
        $grade->activities_score = $actAvg;

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : 0;
        $grade->assignments_score = $asgAvg;

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude Score (10%) - Calculate from individual entries
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : 0;
        $grade->behavior_score = $behaviorAvg;

        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : 0;
        $grade->awareness_score = $awarenessAvg;

        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final Grade (K=40%, S=50%, A=10%)
        $grade->final_grade = (
            ($grade->knowledge_score * 0.40) +
            ($grade->skills_score * 0.50) +
            ($grade->attitude_score * 0.10)
        );

        $grade->save();
    }

    /**
     * Import students from Excel file
     */
    public function importStudents(Request $request)
    {
        $teacherId = Auth::id();

        // Verify class belongs to teacher
        $class = ClassModel::where('teacher_id', $teacherId)
            ->findOrFail($request->class_id);

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('excel_file');
            $path = $file->getRealPath();
            $imported = 0;
            $errors = [];

            if (($handle = fopen($path, 'r')) !== false) {
                $header = fgetcsv($handle);
                $lineNo = 1;
                $enrollmentYear = date('Y');

                while (($row = fgetcsv($handle)) !== false) {
                    $lineNo++;
                    try {
                        $data = array_combine($header, $row);

                        if (empty($data['Email'])) {
                            continue;
                        }

                        // Validate required fields
                        $name = $data['Name'] ?? null;
                        $email = $data['Email'] ?? null;
                        $year = $data['Year'] ?? null;
                        $section = $data['Section'] ?? null;

                        if (! $name || ! $email || ! $year || ! $section) {
                            $errors[] = "Row {$lineNo}: Missing required fields (Name, Email, Year, Section)";

                            continue;
                        }

                        // Validate year
                        if (! in_array($year, [1, 2, 3, 4])) {
                            $errors[] = "Row {$lineNo}: Invalid year '{$year}'. Must be 1, 2, 3, or 4.";

                            continue;
                        }

                        // Validate section
                        if (! in_array($section, ['A', 'B', 'C', 'D', 'E'])) {
                            $errors[] = "Row {$lineNo}: Invalid section '{$section}'. Must be A, B, C, D, or E.";

                            continue;
                        }

                        // Check if user already exists
                        $existingUser = \App\Models\User::where('email', $email)->first();
                        if ($existingUser) {
                            $errors[] = "Row {$lineNo}: User with email '{$email}' already exists.";

                            continue;
                        }

                        // Create user
                        $user = \App\Models\User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => bcrypt('password'),
                            'role' => 'student',
                        ]);

                        // Generate student ID
                        $nextStudentNumber = Student::count() + 1;
                        $sequentialNumber = str_pad($nextStudentNumber, 4, '0', STR_PAD_LEFT);
                        $studentId = sprintf('%d-%s-%s', $enrollmentYear, $sequentialNumber, $section);

                        // Create student
                        Student::create([
                            'user_id' => $user->id,
                            'class_id' => $class->id,
                            'student_id' => $studentId,
                            'year' => $year,
                            'section' => $section,
                            'status' => 'Active',
                        ]);

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Row {$lineNo}: ".$e->getMessage();
                    }
                }

                fclose($handle);
            }

            if ($imported > 0) {
                $message = "✓ {$imported} students imported successfully!";
                if (! empty($errors)) {
                    $message .= ' ('.count($errors).' rows had errors)';
                }

                return redirect()->back()->with('success', $message);
            } else {
                $errorMsg = ! empty($errors) ? implode(' | ', array_slice($errors, 0, 3)) : 'No valid records found';

                return redirect()->back()->with('error', "Import failed: {$errorMsg}");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Excel import error: '.$e->getMessage());
        }
    }

    /**
     * Show assessment range configuration page for a class
     */
    public function configureAssessmentRanges($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('course')
            ->firstOrFail();

        $course = $class->course;

        // Get or create assessment range
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (! $range) {
            $range = AssessmentRange::create([
                'class_id' => $classId,
                'subject_id' => $course ? $course->id : null,
                'teacher_id' => $teacherId,
                // Default quiz values (5 quizzes at 25 points each)
                'quiz_1_max' => 25,
                'quiz_2_max' => 25,
                'quiz_3_max' => 25,
                'quiz_4_max' => 25,
                'quiz_5_max' => 25,
                // Default exam values (70 for midterm, 80 for final as requested)
                'midterm_exam_max' => 70,
                'final_exam_max' => 80,
                'prelim_exam_max' => 70,
                // Default skills values (midterm)
                'class_participation_midterm' => 15,
                'activities_midterm' => 15,
                'assignments_midterm' => 15,
                'output_midterm' => 30,
                // Default skills values (final)
                'class_participation_final' => 15,
                'activities_final' => 15,
                'assignments_final' => 15,
                'output_final' => 30,
                // Default attitude values (midterm)
                'behavior_midterm' => 5,
                'awareness_midterm' => 5,
                // Default attitude values (final)
                'behavior_final' => 5,
                'awareness_final' => 5,
                // Other defaults
                'attendance_required' => true,
                'attendance_min_percentage' => 80,
            ]);
        }

        return view('teacher.assessment.configure_enhanced', compact('class', 'range'));
    }

    /**
     * Store assessment range configuration
     */
    public function storeAssessmentRanges(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            // Knowledge - Quizzes (5 separate quizzes, each max 50 points)
            'quiz_1_max' => 'required|integer|min:5|max:100',
            'quiz_2_max' => 'required|integer|min:5|max:100',
            'quiz_3_max' => 'required|integer|min:5|max:100',
            'quiz_4_max' => 'required|integer|min:5|max:100',
            'quiz_5_max' => 'required|integer|min:5|max:100',

            // Knowledge - Exams (Midterm & Final only)
            'midterm_exam_max' => 'required|integer|min:20|max:200',
            'final_exam_max' => 'required|integer|min:20|max:200',

            // Skills - Class Participation
            'class_participation_midterm' => 'required|integer|min:0|max:100',
            'class_participation_final' => 'required|integer|min:0|max:100',

            // Skills - Activities
            'activities_midterm' => 'required|integer|min:0|max:100',
            'activities_final' => 'required|integer|min:0|max:100',

            // Skills - Assignments
            'assignments_midterm' => 'required|integer|min:0|max:100',
            'assignments_final' => 'required|integer|min:0|max:100',

            // Skills - Output/Project
            'output_midterm' => 'required|integer|min:0|max:100',
            'output_final' => 'required|integer|min:0|max:100',

            // Attitude - Behavior
            'behavior_midterm' => 'required|integer|min:0|max:100',
            'behavior_final' => 'required|integer|min:0|max:100',

            // Attitude - Awareness
            'awareness_midterm' => 'required|integer|min:0|max:100',
            'awareness_final' => 'required|integer|min:0|max:100',

            // Attendance
            'attendance_required' => 'boolean',
            'attendance_min_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['teacher_id'] = $teacherId;
        $validated['class_id'] = $classId;
        $validated['subject_id'] = $class->course_id ?? null;

        AssessmentRange::updateOrCreate(
            [
                'class_id' => $classId,
                'teacher_id' => $teacherId,
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Assessment ranges configured successfully!');
    }

    /**
     * Show enhanced CHED grade entry form with configurable ranges
     */
    public function showGradeEntryEnhanced($classId, $term = 'midterm')
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId, 'term' => $term])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store enhanced CHED grades with attendance and assignments
     */
    public function storeGradesEnhanced(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return redirect()->back()->with('error', 'Invalid term selected');
        }

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.q1' => 'nullable|numeric|min:0',
            'grades.*.q2' => 'nullable|numeric|min:0',
            'grades.*.q3' => 'nullable|numeric|min:0',
            'grades.*.q4' => 'nullable|numeric|min:0',
            'grades.*.q5' => 'nullable|numeric|min:0',
            'grades.*.midterm_exam' => 'nullable|numeric|min:0',
            'grades.*.final_exam' => 'nullable|numeric|min:0',
            'grades.*.output_score' => 'nullable|numeric|min:0',
            'grades.*.class_participation_score' => 'nullable|numeric|min:0',
            'grades.*.activities_score' => 'nullable|numeric|min:0',
            'grades.*.assignments_score' => 'nullable|numeric|min:0',
            'grades.*.behavior_score' => 'nullable|numeric|min:0',
            'grades.*.awareness_score' => 'nullable|numeric|min:0',
            'grades.*.attendance_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.remarks' => 'nullable|string|max:255',
        ]);

        $gradesCreated = 0;
        $gradesUpdated = 0;
        $attendanceUpdated = 0;
        $errors = [];
        foreach ($validated['grades'] as $gradeData) {
            try {
                $studentId = $gradeData['student_id'];

                // Prepare quiz data - support 1-10 quizzes dynamically
                $quizzes = [];
                for ($q = 1; $q <= 10; $q++) {
                    $quizValue = floatval($gradeData["q$q"] ?? 0);
                    if ($quizValue > 0) {
                        $quizzes[] = $quizValue;
                    }
                }

                // If no quizzes provided, use empty array (will be handled by calculation)
                if (empty($quizzes)) {
                    $quizzes = array_fill(0, max(1, $range->num_quizzes ?? 5), 0);
                }

                // Prepare exam data
                $exams = [
                    'midterm' => floatval($gradeData['midterm_exam'] ?? 0),
                    'final' => floatval($gradeData['final_exam'] ?? 0),
                ];

                // Calculate scores using configurable ranges
                $knowledgeScore = Grade::calculateKnowledge($quizzes, $exams, $range, $term);

                $skillsScore = Grade::calculateSkills(
                    floatval($gradeData['output_score'] ?? 0),
                    floatval($gradeData['class_participation_score'] ?? 0),
                    floatval($gradeData['activities_score'] ?? 0),
                    floatval($gradeData['assignments_score'] ?? 0),
                    $range
                );

                $attitudeScore = Grade::calculateAttitude(
                    floatval($gradeData['behavior_score'] ?? 0),
                    floatval($gradeData['awareness_score'] ?? 0),
                    $range
                );

                $finalGrade = Grade::calculateFinalGrade($knowledgeScore, $skillsScore, $attitudeScore);
                $gradePoint = Grade::getGradePoint($finalGrade);

                // Store or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'subject_id' => $class->course_id,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    [
                        'q1' => $quizzes[0],
                        'q2' => $quizzes[1],
                        'q3' => $quizzes[2],
                        'q4' => $quizzes[3],
                        'q5' => $quizzes[4],
                        'midterm_exam' => $exams['midterm'],
                        'final_exam' => $exams['final'],
                        'knowledge_score' => $knowledgeScore,
                        'output_score' => floatval($gradeData['output_score'] ?? 0),
                        'class_participation_score' => floatval($gradeData['class_participation_score'] ?? 0),
                        'activities_score' => floatval($gradeData['activities_score'] ?? 0),
                        'assignments_score' => floatval($gradeData['assignments_score'] ?? 0),
                        'skills_score' => $skillsScore,
                        'behavior_score' => floatval($gradeData['behavior_score'] ?? 0),
                        'awareness_score' => floatval($gradeData['awareness_score'] ?? 0),
                        'attitude_score' => $attitudeScore,
                        'final_grade' => $finalGrade,
                        'grade_point' => $gradePoint,
                        'remarks' => $gradeData['remarks'] ?? null,
                        'grading_period' => $term,
                    ]
                );

                if ($grade->wasRecentlyCreated) {
                    $gradesCreated++;
                } else {
                    $gradesUpdated++;
                }

                // Update attendance if provided
                if (isset($gradeData['attendance_score']) && $gradeData['attendance_score'] !== null) {
                    StudentAttendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'class_id' => $classId,
                            'subject_id' => $class->course_id,
                            'term' => $term,
                        ],
                        [
                            'teacher_id' => $teacherId,
                            'attendance_score' => floatval($gradeData['attendance_score']),
                        ]
                    );
                    $attendanceUpdated++;
                }
            } catch (\Exception $e) {
                $errors[] = "Student {$studentId}: ".$e->getMessage();
            }
        }

        $message = "✓ Grades saved! Created: {$gradesCreated}, Updated: {$gradesUpdated}, Attendance: {$attendanceUpdated}";

        if (! empty($errors)) {
            $message .= ' ('.count($errors).' errors)';
        }

        return redirect()->route('teacher.grades')->with('success', $message);
    }

    /**
     * Show inline grade entry form (NEW ENHANCED GRADING)
     */
    public function showGradeEntryInline($classId)
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store grades via inline entry (NEW ENHANCED GRADING)
     */
    public function storeGradesInline(Request $request, $classId)
    {
        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.component' => 'required|string',
            'grades.*.score' => 'required|numeric|min:0',
        ]);

        foreach ($validated['grades'] as $gradeData) {
            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'class_id' => $classId,
                    'component' => $gradeData['component'],
                ],
                [
                    'teacher_id' => $teacherId,
                    'score' => $gradeData['score'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Grades saved successfully',
        ]);
    }

    /**
     * Show grade analytics dashboard (NEW ENHANCED GRADING)
     */
    public function showGradeAnalytics($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user', 'course')
            ->firstOrFail();

        $grades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('student')
            ->whereNotNull('final_grade')
            ->get();

        $totalStudents = $class->students()->count();
        $gradesWithFinal = $grades->where('final_grade', '!=', null);

        // Calculate comprehensive analytics
        $analytics = [];

        if ($gradesWithFinal->count() > 0) {
            $finalGrades = $gradesWithFinal->pluck('final_grade');
            $analytics = [
                'total_students' => $totalStudents,
                'graded_count' => $gradesWithFinal->count(),
                'ungraded_count' => $totalStudents - $gradesWithFinal->count(),
                'avg_grade' => round($finalGrades->avg(), 2),
                'highest_grade' => round($finalGrades->max(), 2),
                'lowest_grade' => round($finalGrades->min(), 2),
                'median_grade' => round($finalGrades->median(), 2),
                'std_dev' => $this->calculateStandardDeviation($finalGrades->toArray()),
                'passed_count' => $gradesWithFinal->where('final_grade', '>=', 60)->count(),
                'failed_count' => $gradesWithFinal->where('final_grade', '<', 60)->count(),
                'pass_percentage' => round(($gradesWithFinal->where('final_grade', '>=', 60)->count() / max(1, $gradesWithFinal->count())) * 100, 1),
                'fail_percentage' => round(($gradesWithFinal->where('final_grade', '<', 60)->count() / max(1, $gradesWithFinal->count())) * 100, 1),
                'grade_a_count' => $gradesWithFinal->where('final_grade', '>=', 90)->count(),
                'grade_b_count' => $gradesWithFinal->whereBetween('final_grade', [80, 89.99])->count(),
                'grade_c_count' => $gradesWithFinal->whereBetween('final_grade', [70, 79.99])->count(),
                'grade_d_count' => $gradesWithFinal->whereBetween('final_grade', [60, 69.99])->count(),
                'grade_f_count' => $gradesWithFinal->where('final_grade', '<', 60)->count(),
                'knowledge_avg' => round($gradesWithFinal->avg('knowledge_score'), 2),
                'skills_avg' => round($gradesWithFinal->avg('skills_score'), 2),
                'attitude_avg' => round($gradesWithFinal->avg('attitude_score'), 2),
                'quiz_avg' => round($gradesWithFinal->avg(function ($g) {
                    $quizzes = array_filter([$g->q1, $g->q2, $g->q3, $g->q4, $g->q5]);

                    return ! empty($quizzes) ? array_sum($quizzes) / count($quizzes) : 0;
                }), 2),
                'exam_avg' => round($gradesWithFinal->avg(function ($g) {
                    $exams = array_filter([$g->midterm_exam, $g->final_exam]);

                    return ! empty($exams) ? array_sum($exams) / count($exams) : 0;
                }), 2),
            ];
        } else {
            $analytics = [
                'total_students' => $totalStudents,
                'graded_count' => 0,
                'ungraded_count' => $totalStudents,
                'avg_grade' => 0,
                'highest_grade' => 0,
                'lowest_grade' => 0,
                'median_grade' => 0,
                'std_dev' => 0,
                'passed_count' => 0,
                'failed_count' => 0,
                'pass_percentage' => 0,
                'fail_percentage' => 0,
                'grade_a_count' => 0,
                'grade_b_count' => 0,
                'grade_c_count' => 0,
                'grade_d_count' => 0,
                'grade_f_count' => 0,
                'knowledge_avg' => 0,
                'skills_avg' => 0,
                'attitude_avg' => 0,
                'quiz_avg' => 0,
                'exam_avg' => 0,
            ];
        }

        return view('teacher.grades.analytics_dashboard', compact('class', 'grades', 'analytics', 'totalStudents'));
    }

    /**
     * Calculate standard deviation for an array of numbers
     */
    private function calculateStandardDeviation($values)
    {
        if (empty($values)) {
            return 0;
        }

        $mean = array_sum($values) / count($values);
        $deviationSquared = array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values);
        $variance = array_sum($deviationSquared) / count($values);

        return round(sqrt($variance), 2);
    }

    /**
     * Show create class form
     */
    public function createClass()
    {
        $teacherId = Auth::id();

        // Get subjects assigned to this teacher
        $assignedSubjects = Subject::with('course')
            ->whereHas('teachers', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->orWhereDoesntHave('teachers') // Subjects not assigned to any teacher
            ->orderBy('subject_name')
            ->get();

        // Also get all courses for fallback
        $courses = Course::orderBy('course_name')->get();

        // Get all departments for student filtering
        $departments = Course::distinct()->pluck('department')->filter()->toArray();

        return view('teacher.classes.create', compact('assignedSubjects', 'courses', 'departments'));
    }

    /**
     * Store a new class created by teacher
     */
    public function storeClass(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'section' => 'required|string|in:A,B,C,D,E',
            'year' => 'required|integer|in:1,2,3,4',
            'academic_year' => 'required|string',
            'capacity' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string|max:500',
            'semester' => 'required|string|in:First,Second,Summer',
            'auto_assign' => 'boolean',
            'create_assignment' => 'boolean',
            'additional_teachers' => 'nullable|array',
            'additional_teachers.*' => 'exists:users,id',
        ]);

        // Get the subject to extract units
        $subject = \App\Models\Subject::find($validated['subject_id']);
        $units = $subject ? $subject->credit_hours : 3;

        // Create the class
        $class = ClassModel::create([
            'class_name' => $validated['class_name'],
            'course_id' => $validated['course_id'],
            'teacher_id' => Auth::id(),
            'section' => $validated['section'],
            'year' => $validated['year'] ?? null,
            'academic_year' => $validated['academic_year'] ?? null,
            'capacity' => $validated['capacity'],
            'class_level' => (int) $validated['year'],
            'description' => $validated['description'] ?? null,
            'units' => $units,
        ]);

        // Create teacher assignments if requested
        $teacherId = Auth::id();
        $course = Course::find($validated['course_id']);

        $assignmentIds = [];

        // Auto-assign the creating teacher to the class
        if ($request->boolean('auto_assign')) {
            $assignment = TeacherAssignment::create([
                'teacher_id' => $teacherId,
                'class_id' => $class->id,
                'subject_id' => $validated['subject_id'],
                'course_id' => $validated['course_id'],
                'department' => $course->department ?? null,
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
                'status' => 'active',
                'notes' => 'Auto-assigned when creating class',
            ]);

            $assignmentIds[] = $assignment->id;
        }

        // Create additional assignments if requested
        if ($request->boolean('create_assignment') && $request->filled('additional_teachers')) {
            foreach ($request->additional_teachers as $additionalTeacherId) {
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $additionalTeacherId,
                    'class_id' => $class->id,
                    'subject_id' => $validated['subject_id'],
                    'course_id' => $validated['course_id'],
                    'department' => $course->department ?? null,
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                    'status' => 'active',
                    'notes' => 'Assigned by '.Auth::user()->name,
                ]);

                $assignmentIds[] = $assignment->id;
            }
        }

        // Assign students to class if provided
        if ($request->has('assigned_students')) {
            $submittedStudentIds = array_filter(array_map('trim', explode(',', $request->input('assigned_students', ''))));
            $validStudentIds = Student::whereIn('id', $submittedStudentIds)->pluck('id')->toArray();

            // Ensure there's at least one assignment for student assignment tracking
            if (empty($assignmentIds)) {
                $assignment = TeacherAssignment::firstOrCreate([
                    'teacher_id' => $teacherId,
                    'class_id' => $class->id,
                ], [
                    'subject_id' => $validated['subject_id'],
                    'course_id' => $validated['course_id'],
                    'department' => $course->department ?? null,
                    'academic_year' => $validated['academic_year'],
                    'semester' => $validated['semester'],
                    'status' => 'active',
                    'notes' => 'Auto-assigned when creating class',
                ]);
                $assignmentIds[] = $assignment->id;
            }

            foreach ($assignmentIds as $assignmentId) {
                $assignment = TeacherAssignment::find($assignmentId);
                if ($assignment) {
                    $assignment->assignStudents($validStudentIds);
                }
            }

            // Update students' class assignment and related school/department information
            if (!empty($validStudentIds)) {
                $studentUpdate = ['class_id' => $class->id];
                if (!empty($course->college)) {
                    $studentUpdate['school'] = $course->college;
                }
                if (!empty($course->department)) {
                    $studentUpdate['department'] = $course->department;
                }

                Student::whereIn('id', $validStudentIds)->update($studentUpdate);
            }
        }

        $message = "Class '{$class->class_name}' created successfully!";
        if ($request->boolean('auto_assign') || $request->boolean('create_assignment')) {
            $message .= ' Teacher assignments have been created.';
        }
        if ($request->filled('assigned_students')) {
            $studentCount = count($validStudentIds ?? []);
            $message .= " {$studentCount} students have been assigned to the class.";
        }

        return redirect()->route('teacher.classes.show', $class->id)
            ->with('success', $message);
    }

    /**
     * Show form to edit a class
     */
    public function editClass($classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->with('course')
            ->firstOrFail();

        $courses = Course::orderBy('course_name')->get();

        return view('teacher.classes.edit', compact('class', 'courses'));
    }

    /**
     * Update class information
     */
    public function updateClass(Request $request, $classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'section' => 'required|string|in:A,B,C,D,E',
            'year' => 'required|integer|in:1,2,3,4',
            'academic_year' => 'required|string',
            'capacity' => 'required|integer|min:1|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // Check if new capacity is less than current student count
        if ($validated['capacity'] < $class->students->count()) {
            return back()->withErrors([
                'capacity' => "Capacity cannot be less than the current number of students ({$class->students->count()})",
            ]);
        }

        $class->update([
            'class_name' => $validated['class_name'],
            'course_id' => $validated['course_id'],
            'section' => $validated['section'],
            'year' => $validated['year'],
            'academic_year' => $validated['academic_year'],
            'capacity' => $validated['capacity'],
            'description' => $validated['description'] ?? null,
        ]);

        // Sync class student assignments if provided (supports adding/removing students)
        if ($request->has('assigned_students')) {
            $submittedStudentIds = array_filter(array_map('trim', explode(',', $request->input('assigned_students', ''))));
            $validStudentIds = Student::whereIn('id', $submittedStudentIds)->pluck('id')->toArray();

            $currentStudentIds = $class->students()->pluck('id')->toArray();
            $toAdd = array_diff($validStudentIds, $currentStudentIds);
            $toRemove = array_diff($currentStudentIds, $validStudentIds);

            // Ensure there is a teacher assignment record for this class
            $assignment = TeacherAssignment::firstOrCreate(
                [
                    'teacher_id' => Auth::id(),
                    'class_id' => $class->id,
                ],
                [
                    'subject_id' => 1, // Default subject
                    'status' => 'active',
                    'notes' => 'Auto-created for student assignment',
                ]
            );

            // Sync the pivot table for assignment_students
            $assignment->syncStudents($validStudentIds);

            // Update students' class assignment and school/department when added
            if (! empty($toAdd)) {
                $studentUpdate = ['class_id' => $class->id];
                if (!empty($class->course->college)) {
                    $studentUpdate['school'] = $class->course->college;
                }
                if (!empty($class->course->department)) {
                    $studentUpdate['department'] = $class->course->department;
                }
                Student::whereIn('id', $toAdd)->update($studentUpdate);
            }
            if (! empty($toRemove)) {
                Student::whereIn('id', $toRemove)->update(['class_id' => null]);
            }
        }

        $message = "Class '{$class->class_name}' updated successfully!";
        if ($request->filled('assigned_students')) {
            $studentCount = count($validStudentIds ?? []);
            $message .= " {$studentCount} students have been assigned to the class.";
        }

        return redirect()->route('teacher.classes.show', $class->id)
            ->with('success', $message);
    }

    /**
     * Ajax helper: Get filtered students for assignment modals
     */
    public function getStudents(Request $request)
    {
        $query = Student::with(['user', 'class.course']);

        if ($request->year) {
            $query->where('year', $request->year);
        }

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

    /**
     * Delete/destroy a class
     */
    public function destroyClass($classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $className = $class->class_name;

        // Delete all related grades first
        Grade::where('class_id', $classId)->delete();

        // Delete all related attendance records
        StudentAttendance::whereIn('student_id', $class->students->pluck('id'))->delete();

        // Delete the class
        $class->delete();

        return redirect()->route('teacher.classes')
            ->with('success', "Class '{$className}' has been deleted successfully!");
    }

    /**
     * Remove a student from a class (unassign without deleting)
     */
    public function removeStudentFromClass($classId, $studentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $student = Student::where('id', $studentId)
            ->where('class_id', $classId)
            ->firstOrFail();

        // Unassign student from class
        $student->class_id = null;
        $student->save();

        // Remove student from any teacher assignment for this class
        $assignments = TeacherAssignment::where('class_id', $classId)->get();
        foreach ($assignments as $assignment) {
            $assignment->students()->detach($studentId);
        }

        return response()->json(['success' => true]);
    }

    /**
     * List all students in a class
     */
    public function indexStudents($classId)
    {
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $students = $class->students()->with('user')->get()->sortBy(function ($student) {
            return $student->user?->name ?? '';
        })->values();

        return view('teacher.students.index', compact('class', 'students'));
    }

    /**
     * Show form to edit a student
     */
    public function editStudent($studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // Verify teacher owns the class this student belongs to
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teacher.students.edit', compact('student', 'class'));
    }

    /**
     * Update student information
     */
    public function updateStudent(Request $request, $studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // Verify teacher owns the class
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$student->user_id,
            'year' => 'required|integer|in:1,2,3,4',
            'section' => 'required|string|in:A,B,C,D,E',
        ]);

        // Update user information
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student academic info
        $student->update([
            'year' => $validated['year'],
            'section' => $validated['section'],
        ]);

        return redirect()->route('teacher.students.index', $class->id)
            ->with('success', "Student '{$student->user->name}' updated successfully!");
    }

    /**
     * Delete/destroy a student
     */
    public function destroyStudent($studentId)
    {
        $student = Student::with('user')->findOrFail($studentId);

        // Verify teacher owns the class
        $class = ClassModel::where('id', $student->class_id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $studentName = $student->user?->name ?? 'Unknown';
        $classId = $class->id;

        // Delete all related grades
        Grade::where('student_id', $studentId)->delete();

        // Delete all related attendance records
        StudentAttendance::where('student_id', $studentId)->delete();

        // Delete the student
        $student->delete();

        return redirect()->route('teacher.students.index', $classId)
            ->with('success', "Student '$studentName' has been removed successfully!");
    }

    /**
     * AJAX: Save individual grade field
     */
    public function saveGradeField(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'class_id' => 'required|integer',
            'term' => 'required|in:midterm,final',
            'field' => 'required|string',
            'value' => 'nullable|numeric',
        ]);

        $teacherId = Auth::id();

        // Verify teacher owns this class
        $class = ClassModel::where('id', $request->class_id)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get or create grade record
        $grade = Grade::firstOrCreate(
            [
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'term' => $request->term,
                'teacher_id' => $teacherId,
            ],
            [
                'subject_id' => $class->course_id,
            ]
        );

        // Update the field
        $grade->update([
            $request->field => $request->value,
        ]);

        // Calculate scores if this is a component input
        $this->calculateGradeScores($grade);

        return response()->json([
            'success' => true,
            'message' => 'Grade saved successfully',
            'final_grade' => $grade->final_grade,
        ]);
    }

    /**
     * AJAX: Save a single GradeEntry cell (auto-save on blur)
     */
    public function saveGradeEntryCell(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term'       => 'required|in:midterm,final',
            'field'      => 'required|string',
            'value'      => 'nullable|numeric|min:0|max:100',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $allowedFields = [
            'exam_pr','exam_md','exam_fn',
            'quiz_1','quiz_2','quiz_3','quiz_4','quiz_5',
            'output_1','output_2','output_3',
            'classpart_1','classpart_2','classpart_3',
            'activity_1','activity_2','activity_3',
            'assignment_1','assignment_2','assignment_3',
            'behavior_1','behavior_2','behavior_3',
            'attendance_1','attendance_2','attendance_3',
            'awareness_1','awareness_2','awareness_3',
        ];

        if (!in_array($request->field, $allowedFields)) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 422);
        }

        $entry = GradeEntry::updateOrCreate(
            ['student_id' => $request->student_id, 'class_id' => $classId, 'teacher_id' => $teacherId, 'term' => $request->term],
            [$request->field => $request->value ?? 0]
        );

        $computed = $entry->computeAverages(['knowledge' => 40, 'skills' => 50, 'attitude' => 10]);
        $entry->update($computed);

        return response()->json(['success' => true, 'computed' => $computed]);
    }

    /**
     * AJAX: Clear a single GradeEntry cell (set to 0)
     */
    public function clearGradeEntryCell(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term'       => 'required|in:midterm,final',
            'field'      => 'required|string',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $entry = GradeEntry::where('student_id', $request->student_id)
            ->where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $request->term)
            ->first();

        if ($entry) {
            $entry->update([$request->field => 0]);
            $computed = $entry->computeAverages(['knowledge' => 40, 'skills' => 50, 'attitude' => 10]);
            $entry->update($computed);
        }

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Clear all grade entry data for a student in a term
     */
    public function clearStudentGradeEntry(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'term'       => 'required|in:midterm,final',
        ]);

        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        GradeEntry::where('student_id', $request->student_id)
            ->where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->where('term', $request->term)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Student grades cleared']);
    }

    /**
     * Calculate all component scores and final grade from individual entries
     */
    private function calculateGradeScores($grade)
    {
        // Knowledge Score (40%)
        $quizzes = array_filter([
            $grade->q1, $grade->q2, $grade->q3, $grade->q4, $grade->q5,
            $grade->q6, $grade->q7, $grade->q8, $grade->q9, $grade->q10,
        ]);

        $quizAvg = count($quizzes) > 0 ? array_sum($quizzes) / count($quizzes) : 0;
        $examAvg = (($grade->midterm_exam ?? 0) + ($grade->final_exam ?? 0)) / 2;

        $grade->knowledge_score = ($quizAvg * 0.40) + ($examAvg * 0.60);

        // Skills Score (50%) - Calculate from individual entries
        $outputEntries = array_filter([$grade->output_1, $grade->output_2, $grade->output_3]);
        $outputAvg = count($outputEntries) > 0 ? array_sum($outputEntries) / count($outputEntries) : 0;

        $cpEntries = array_filter([$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3]);
        $cpAvg = count($cpEntries) > 0 ? array_sum($cpEntries) / count($cpEntries) : 0;

        $actEntries = array_filter([$grade->activities_1, $grade->activities_2, $grade->activities_3]);
        $actAvg = count($actEntries) > 0 ? array_sum($actEntries) / count($actEntries) : 0;

        $asgEntries = array_filter([$grade->assignments_1, $grade->assignments_2, $grade->assignments_3]);
        $asgAvg = count($asgEntries) > 0 ? array_sum($asgEntries) / count($asgEntries) : 0;

        $grade->skills_score = ($outputAvg * 0.40) + ($cpAvg * 0.30) + ($actAvg * 0.15) + ($asgAvg * 0.15);

        // Attitude Score (10%) - Calculate from individual entries
        $behaviorEntries = array_filter([$grade->behavior_1, $grade->behavior_2, $grade->behavior_3]);
        $behaviorAvg = count($behaviorEntries) > 0 ? array_sum($behaviorEntries) / count($behaviorEntries) : 0;

        $awarenessEntries = array_filter([$grade->awareness_1, $grade->awareness_2, $grade->awareness_3]);
        $awarenessAvg = count($awarenessEntries) > 0 ? array_sum($awarenessEntries) / count($awarenessEntries) : 0;

        $grade->attitude_score = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);

        // Final Grade (K=40%, S=50%, A=10%)
        $grade->final_grade = (
            ($grade->knowledge_score * 0.40) +
            ($grade->skills_score * 0.50) +
            ($grade->attitude_score * 0.10)
        );

        $grade->save();
    }

    /**
     * AJAX: Get all scores for a student as JSON (supports live score display)
     */
    public function getStudentScores($studentId, $classId, $term)
    {
        $teacherId = Auth::id();

        // Verify teacher owns this class
        ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get the grade record
        $grade = Grade::where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('term', $term)
            ->first();

        if (! $grade) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No grades found for this student',
            ]);
        }

        // Extract all entered scores
        $scores = [
            'knowledge' => [
                'q1' => $grade->q1,
                'q2' => $grade->q2,
                'q3' => $grade->q3,
                'q4' => $grade->q4,
                'q5' => $grade->q5,
                'midterm_exam' => $grade->midterm_exam,
                'final_exam' => $grade->final_exam,
                'knowledge_score' => $grade->knowledge_score,
            ],
            'skills' => [
                'output_1' => $grade->output_1,
                'output_2' => $grade->output_2,
                'output_3' => $grade->output_3,
                'class_participation_1' => $grade->class_participation_1,
                'class_participation_2' => $grade->class_participation_2,
                'class_participation_3' => $grade->class_participation_3,
                'activities_1' => $grade->activities_1,
                'activities_2' => $grade->activities_2,
                'activities_3' => $grade->activities_3,
                'assignments_1' => $grade->assignments_1,
                'assignments_2' => $grade->assignments_2,
                'assignments_3' => $grade->assignments_3,
                'skills_score' => $grade->skills_score,
            ],
            'attitude' => [
                'behavior_1' => $grade->behavior_1,
                'behavior_2' => $grade->behavior_2,
                'behavior_3' => $grade->behavior_3,
                'awareness_1' => $grade->awareness_1,
                'awareness_2' => $grade->awareness_2,
                'awareness_3' => $grade->awareness_3,
                'attitude_score' => $grade->attitude_score,
            ],
            'final' => [
                'final_grade' => $grade->final_grade,
                'grade_point' => Grade::getGradePoint($grade->final_grade),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $scores,
            'message' => 'Scores retrieved successfully',
        ]);
    }

    /**
     * Show NEW KSA Grade Entry Form
     */
    public function showGradeEntryNew($classId)
    {
        // This method has been deprecated - redirecting to the main grade entry
        return redirect()->route('teacher.grades.entry', ['classId' => $classId])
            ->with('info', 'Using the standard grade entry form.');
    }

    /**
     * Store NEW KSA Grades using Midterm/Final structure
     */
    public function storeGradesNew(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        $grades = $request->input('grades', []);

        $successCount = 0;
        $errors = [];

        foreach ($grades as $studentId => $gradeData) {
            try {
                $student = Student::findOrFail($studentId);

                // Prepare update data
                $updateData = [
                    'subject_id' => $class->course_id,
                    'teacher_id' => $teacherId,
                ];

                // Knowledge Components - Exams
                $updateData['exam_prelim'] = floatval($gradeData['exam_prelim'] ?? 0);
                $updateData['exam_midterm'] = floatval($gradeData['exam_midterm'] ?? 0);
                $updateData['exam_final'] = floatval($gradeData['exam_final'] ?? 0);

                // Knowledge Components - Quizzes
                $updateData['quiz_1'] = floatval($gradeData['quiz_1'] ?? 0);
                $updateData['quiz_2'] = floatval($gradeData['quiz_2'] ?? 0);
                $updateData['quiz_3'] = floatval($gradeData['quiz_3'] ?? 0);
                $updateData['quiz_4'] = floatval($gradeData['quiz_4'] ?? 0);
                $updateData['quiz_5'] = floatval($gradeData['quiz_5'] ?? 0);

                // Skills Components - Output
                $updateData['output_1'] = floatval($gradeData['output_1'] ?? 0);
                $updateData['output_2'] = floatval($gradeData['output_2'] ?? 0);
                $updateData['output_3'] = floatval($gradeData['output_3'] ?? 0);

                // Skills Components - Class Participation
                $updateData['class_participation_1'] = floatval($gradeData['class_participation_1'] ?? 0);
                $updateData['class_participation_2'] = floatval($gradeData['class_participation_2'] ?? 0);
                $updateData['class_participation_3'] = floatval($gradeData['class_participation_3'] ?? 0);

                // Skills Components - Activities
                $updateData['activities_1'] = floatval($gradeData['activities_1'] ?? 0);
                $updateData['activities_2'] = floatval($gradeData['activities_2'] ?? 0);
                $updateData['activities_3'] = floatval($gradeData['activities_3'] ?? 0);

                // Skills Components - Assignments
                $updateData['assignments_1'] = floatval($gradeData['assignments_1'] ?? 0);
                $updateData['assignments_2'] = floatval($gradeData['assignments_2'] ?? 0);
                $updateData['assignments_3'] = floatval($gradeData['assignments_3'] ?? 0);

                // Attitude Components - Behavior
                $updateData['behavior_1'] = floatval($gradeData['behavior_1'] ?? 0);
                $updateData['behavior_2'] = floatval($gradeData['behavior_2'] ?? 0);
                $updateData['behavior_3'] = floatval($gradeData['behavior_3'] ?? 0);

                // Attitude Components - Awareness
                $updateData['awareness_1'] = floatval($gradeData['awareness_1'] ?? 0);
                $updateData['awareness_2'] = floatval($gradeData['awareness_2'] ?? 0);
                $updateData['awareness_3'] = floatval($gradeData['awareness_3'] ?? 0);

                // Create or update grade
                $grade = Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                    ],
                    $updateData
                );

                // Calculate component averages
                $this->recalculateNewGradeScores($grade);

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Student {$studentId}: ".$e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "✓ {$successCount} grades saved successfully!";
            if (! empty($errors)) {
                $message .= ' ('.count($errors).' errors encountered)';
            }

            return redirect()->route('teacher.grades', $classId)->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No grades were saved. Please check for errors.');
        }
    }

    /**
     * Recalculate all grade scores using NEW system (Midterm/Final)
     */
    private function recalculateNewGradeScores($grade)
    {
        // Prepare arrays for calculation methods
        $quizzes = [
            'quiz_1' => $grade->quiz_1,
            'quiz_2' => $grade->quiz_2,
            'quiz_3' => $grade->quiz_3,
            'quiz_4' => $grade->quiz_4,
            'quiz_5' => $grade->quiz_5,
        ];

        $exams = [
            'exam_prelim' => $grade->exam_prelim,
            'exam_midterm' => $grade->exam_midterm,
            'exam_final' => $grade->exam_final,
        ];

        // Calculate Knowledge Average
        $grade->knowledge_average = Grade::calculateKnowledgeAverage($quizzes, $exams);

        // Calculate Skills Average (now returns array with totals)
        $output = [$grade->output_1, $grade->output_2, $grade->output_3];
        $cp = [$grade->class_participation_1, $grade->class_participation_2, $grade->class_participation_3];
        $activities = [$grade->activities_1, $grade->activities_2, $grade->activities_3];
        $assignments = [$grade->assignments_1, $grade->assignments_2, $grade->assignments_3];

        $skillsResult = Grade::calculateSkillsAverage($output, $cp, $activities, $assignments);
        $grade->skills_average = $skillsResult['average'];
        $grade->output_total = $skillsResult['totals']['output_total'];
        $grade->class_participation_total = $skillsResult['totals']['class_participation_total'];
        $grade->activities_total = $skillsResult['totals']['activities_total'];
        $grade->assignments_total = $skillsResult['totals']['assignments_total'];

        // Calculate Attitude Average (now returns array with totals)
        $behavior = [$grade->behavior_1, $grade->behavior_2, $grade->behavior_3];
        $awareness = [$grade->awareness_1, $grade->awareness_2, $grade->awareness_3];

        $attitudeResult = Grade::calculateAttitudeAverage($behavior, $awareness);
        $grade->attitude_average = $attitudeResult['average'];
        $grade->behavior_total = $attitudeResult['totals']['behavior_total'];
        $grade->awareness_total = $attitudeResult['totals']['awareness_total'];

        // Calculate Midterm Grade (Knowledge 40% + Skills 50% + Attitude 10%)
        $grade->midterm_grade =
            ($grade->knowledge_average * 0.40) +
            ($grade->skills_average * 0.50) +
            ($grade->attitude_average * 0.10);

        // For now, set final_grade_value equal to midterm_grade
        // In a full implementation, this would be based on final exam scores
        $grade->final_grade_value = $grade->midterm_grade;

        // Calculate Overall Grade (Midterm 40% + Final 60%)
        $grade->overall_grade = Grade::calculateOverallGrade($grade->midterm_grade, $grade->final_grade_value);

        // Set decimal grade (same as overall grade)
        $grade->decimal_grade = $grade->overall_grade;

        // Get grade point and letter grade
        $grade->grade_point = Grade::getGradePoint($grade->overall_grade);
        $decimalScale = \App\Helpers\GradeHelper::convertToDecimalScale($grade->overall_grade);
        $gradeLabel = \App\Helpers\GradeHelper::getGradeLabel($decimalScale);
        $grade->letter_grade = \App\Helpers\GradeHelper::extractLetterGrade($gradeLabel);

        // Save all changes
        $grade->save();
    }

    /**
     * Upload/Lock grades from grade_entries table to grades table
     * This moves grades from temporary storage (grade_entries) to permanent storage (grades)
     */
    public function uploadGradeEntry(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $term = $request->input('term', 'midterm');

        // Validate term
        if (! in_array($term, ['midterm', 'final'])) {
            return back()->with('error', 'Invalid term specified.');
        }

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        // Get all grade entries for this class and term
        $entries = GradeEntry::where('class_id', $classId)
            ->where('term', $term)
            ->get();

        if ($entries->isEmpty()) {
            return back()->with('error', 'No grade entries found for '.ucfirst($term).' term. Please enter grades first.');
        }

        $successCount = 0;
        $errors = [];

        try {
            foreach ($entries as $entry) {
                // Find or create grade record
                $grade = Grade::firstOrCreate(
                    [
                        'student_id' => $entry->student_id,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                    ],
                    [
                        'subject_id' => $class->course_id,
                        'academic_year' => date('Y'),
                    ]
                );

                // Prefix for the term (mid_ or final_)
                $prefix = ($term === 'midterm') ? 'mid_' : 'final_';

                // Map grade_entries columns to grades table columns
                $updateData = [
                    // Knowledge Component
                    $prefix.'exam_pr' => $entry->exam_pr,
                    $prefix.'exam_md' => $entry->exam_md,
                    $prefix.'quiz_1' => $entry->quiz_1,
                    $prefix.'quiz_2' => $entry->quiz_2,
                    $prefix.'quiz_3' => $entry->quiz_3,
                    $prefix.'quiz_4' => $entry->quiz_4,
                    $prefix.'quiz_5' => $entry->quiz_5,
                    // Skills Component
                    $prefix.'output_1' => $entry->output_1,
                    $prefix.'output_2' => $entry->output_2,
                    $prefix.'output_3' => $entry->output_3,
                    $prefix.'classpart_1' => $entry->classpart_1,
                    $prefix.'classpart_2' => $entry->classpart_2,
                    $prefix.'classpart_3' => $entry->classpart_3,
                    $prefix.'activity_1' => $entry->activity_1,
                    $prefix.'activity_2' => $entry->activity_2,
                    $prefix.'activity_3' => $entry->activity_3,
                    $prefix.'assignment_1' => $entry->assignment_1,
                    $prefix.'assignment_2' => $entry->assignment_2,
                    $prefix.'assignment_3' => $entry->assignment_3,
                    // Attitude Component
                    $prefix.'behavior_1' => $entry->behavior_1,
                    $prefix.'behavior_2' => $entry->behavior_2,
                    $prefix.'behavior_3' => $entry->behavior_3,
                    $prefix.'awareness_1' => $entry->awareness_1,
                    $prefix.'awareness_2' => $entry->awareness_2,
                    $prefix.'awareness_3' => $entry->awareness_3,
                    // Computed Averages
                    $prefix.'knowledge_average' => $entry->knowledge_average,
                    $prefix.'skills_average' => $entry->skills_average,
                    $prefix.'attitude_average' => $entry->attitude_average,
                    $prefix.'final_grade' => $entry->term_grade,
                ];

                // Update grade record
                $grade->update($updateData);
                $successCount++;
            }

            // If all uploads successful, show success message
            return redirect()->route('teacher.grades', $classId)
                ->with('success', "🎉 Successfully uploaded {$successCount} grades for ".ucfirst($term).' term to permanent storage!');

        } catch (\Exception $e) {
            Log::error('Grade upload error: '.$e->getMessage());

            return back()->with('error', 'Error uploading grades: '.$e->getMessage());
        }
    }

    /**
     * Show grade results and summary by class with calculated decimal grades
     * Organized by class groups with pass/fail status and verification from backend
     */
    public function gradeResults()
    {
        $teacherId = Auth::id();
        $selectedClassId = request()->query('class_id');

        // Get teacher's classes with students and grades
        $classesQuery = ClassModel::where('teacher_id', $teacherId)
            ->with(['students.user', 'course']);

        if ($selectedClassId) {
            $classesQuery->where('id', $selectedClassId);
        }

        $classes = $classesQuery->get();
        $selectedClass = $selectedClassId ? $classes->first() : null;

        $classGradeResults = [];

        foreach ($classes as $class) {
            // Get all grades for this class
            $grades = Grade::where('class_id', $class->id)
                ->where('teacher_id', $teacherId)
                ->with('student.user')
                ->get();

            // Calculate results for each student
            $studentResults = [];
            $classStats = [
                'total_students' => $class->students()->count(),
                'graded_students' => 0,
                'passed_count' => 0,
                'failed_count' => 0,
                'average_grade' => 0,
                'pass_percentage' => 0,
            ];

            $totalGrade = 0;
            $graderCount = 0;

            foreach ($grades as $grade) {
                // Use the GradeHelper to calculate complete summary
                $summary = \App\Helpers\GradeHelper::getCompleteGradeSummary(
                    $grade->mid_knowledge_average ?? 0,
                    $grade->mid_skills_average ?? 0,
                    $grade->mid_attitude_average ?? 0,
                    $grade->final_knowledge_average ?? 0,
                    $grade->final_skills_average ?? 0,
                    $grade->final_attitude_average ?? 0
                );

                // Only include if has grades
                if ($summary['overall']['term_grade'] > 0) {
                    // Update the grade record with calculated values
                    $grade->update([
                        'mid_final_grade' => $summary['midterm']['term_grade'],
                        'final_final_grade' => $summary['final']['term_grade'],
                        'overall_grade' => $summary['overall']['term_grade'],
                        'grade_5pt_scale' => $summary['overall']['decimal_grade'],
                        'letter_grade' => \App\Helpers\GradeHelper::extractLetterGrade($summary['overall']['grade_label']),
                        'remarks' => $summary['overall']['remarks'],
                    ]);

                    $studentResults[] = [
                        'student' => $grade->student,
                        'student_id' => $grade->student->student_id,
                        'student_name' => $grade->student->user->name ?? $grade->student->name,
                        'midterm_grade' => $summary['midterm']['term_grade'],
                        'midterm_decimal' => $summary['midterm']['decimal_grade'],
                        'midterm_status' => $summary['midterm']['status'],
                        'final_grade' => $summary['final']['term_grade'],
                        'final_decimal' => $summary['final']['decimal_grade'],
                        'final_status' => $summary['final']['status'],
                        'overall_grade' => $summary['overall']['term_grade'],
                        'decimal_grade' => $summary['overall']['decimal_grade'],
                        'letter_grade' => \App\Helpers\GradeHelper::extractLetterGrade($summary['overall']['grade_label']),
                        'status' => $summary['overall']['status'],
                        'remarks' => $summary['overall']['remarks'],
                    ];

                    $totalGrade += $summary['overall']['term_grade'];
                    $graderCount++;

                    if ($summary['overall']['status'] === 'Passed') {
                        $classStats['passed_count']++;
                    } else {
                        $classStats['failed_count']++;
                    }
                }
            }

            // Calculate class statistics
            $classStats['graded_students'] = $graderCount;
            $classStats['average_grade'] = $graderCount > 0 ? round($totalGrade / $graderCount, 2) : 0;
            $classStats['pass_percentage'] = $classStats['total_students'] > 0
                ? round(($classStats['passed_count'] / $classStats['total_students']) * 100, 1)
                : 0;

            // Always include the class so the teacher can see the summary even if grades are not yet entered
            $classGradeResults[] = [
                'class' => $class,
                'course' => $class->course,
                'students' => $studentResults,
                'stats' => $classStats,
            ];
        }

        return view('teacher.grades.grade_results', compact('classGradeResults', 'selectedClass'));
    }
}
