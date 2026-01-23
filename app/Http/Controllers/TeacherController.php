<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AssessmentRange;
use App\Models\StudentAttendance;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->with('subject', 'students')
            ->get();
        
        // Get total students in teacher's classes
        $totalStudents = Student::whereIn('class_id', 
            ClassModel::where('teacher_id', $teacherId)->pluck('id')
        )->count();
        
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
        
        // Get recent grades for dashboard
        $recentGrades = Grade::where('teacher_id', $teacherId)
            ->with('student', 'class')
            ->whereNotNull('final_grade')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('teacher.dashboard', compact(
            'myClasses',
            'totalStudents',
            'gradesPosted',
            'pendingTasks',
            'recentGrades'
        ));
    }

    /**
     * Show teacher's classes
     */
    public function classes()
    {
        $teacherId = Auth::id();
        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students')
            ->paginate(10);

        return view('teacher.classes.index', compact('classes'));
    }

    /**
     * Show class details with students
     */
    public function classDetail($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'subject')
            ->firstOrFail();

        return view('teacher.classes.show', compact('class'));
    }

    /**
     * Show grades page
     */
    public function grades()
    {
        $teacherId = Auth::id();
        $classes = ClassModel::where('teacher_id', $teacherId)
            ->with('students')
            ->get();

        $recentGrades = Grade::where('teacher_id', $teacherId)
            ->with('student', 'subject')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('teacher.grades.index', compact('classes', 'recentGrades'));
    }

    /**
     * Show grade entry form for a class
     */
    public function gradeEntry($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students', 'subject')
            ->firstOrFail();

        // Get existing grades for this class
        $existingGrades = Grade::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades.entry', compact('class', 'existingGrades'));
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
                    'grading_period' => date('Y-m') . '-' . (intdiv((int)date('m') - 1, 3) + 1),
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
        $classes = ClassModel::where('teacher_id', $teacherId)->get();

        return view('teacher.attendance.index', compact('classes'));
    }

    /**
     * Manage attendance for a specific class
     */
    public function manageAttendance($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        
        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $students = $class->students()->get();
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
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:Present,Absent,Late,Leave',
            'date' => 'required|date',
        ]);

        $date = $validated['date'];
        
        foreach ($validated['attendance'] as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully');
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
     * Show assignments page
     */
    public function assignments()
    {
        $teacherId = Auth::id();
        $classes = ClassModel::where('teacher_id', $teacherId)->get();

        return view('teacher.assignments.index', compact('classes'));
    }

    /**
     * List assignments for a class
     */
    public function listAssignments($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        
        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $assignments = Assignment::where('class_id', $classId)
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('teacher.assignments.list', compact('class', 'assignments'));
    }

    /**
     * Create new assignment form
     */
    public function createAssignment($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        
        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        return view('teacher.assignments.create', compact('class'));
    }

    /**
     * Store new assignment
     */
    public function storeAssignment(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        
        // Verify teacher owns this class
        if ($class->teacher_id !== $teacherId) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'max_score' => 'required|numeric|min:1|max:1000',
            'term' => 'required|in:midterm,final',
            'instructions' => 'nullable|string',
        ]);

        Assignment::create([
            'class_id' => $classId,
            'teacher_id' => $teacherId,
            ...$validated,
        ]);

        return redirect()->route('teacher.assignments.list', $classId)
            ->with('success', 'Assignment created successfully');
    }

    /**
     * Edit assignment form
     */
    public function editAssignment($classId, $assignmentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        $assignment = Assignment::findOrFail($assignmentId);
        
        // Verify ownership
        if ($class->teacher_id !== $teacherId || $assignment->teacher_id !== $teacherId) {
            abort(403);
        }

        return view('teacher.assignments.edit', compact('class', 'assignment'));
    }

    /**
     * Update assignment
     */
    public function updateAssignment(Request $request, $classId, $assignmentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        $assignment = Assignment::findOrFail($assignmentId);
        
        // Verify ownership
        if ($class->teacher_id !== $teacherId || $assignment->teacher_id !== $teacherId) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'max_score' => 'required|numeric|min:1|max:1000',
            'term' => 'required|in:midterm,final',
            'instructions' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return redirect()->route('teacher.assignments.list', $classId)
            ->with('success', 'Assignment updated successfully');
    }

    /**
     * Delete assignment
     */
    public function deleteAssignment($classId, $assignmentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        $assignment = Assignment::findOrFail($assignmentId);
        
        // Verify ownership
        if ($class->teacher_id !== $teacherId || $assignment->teacher_id !== $teacherId) {
            abort(403);
        }

        $assignment->delete();

        return redirect()->route('teacher.assignments.list', $classId)
            ->with('success', 'Assignment deleted successfully');
    }

    /**
     * Grade assignment submissions
     */
    public function gradeAssignments($classId, $assignmentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        $assignment = Assignment::findOrFail($assignmentId);
        
        // Verify ownership
        if ($class->teacher_id !== $teacherId || $assignment->teacher_id !== $teacherId) {
            abort(403);
        }

        $submissions = AssignmentSubmission::where('assignment_id', $assignmentId)
            ->with('student')
            ->get();

        return view('teacher.assignments.grade', compact('class', 'assignment', 'submissions'));
    }

    /**
     * Submit assignment score
     */
    public function submitAssignmentScore(Request $request, $classId, $assignmentId, $submissionId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::findOrFail($classId);
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::findOrFail($submissionId);
        
        // Verify ownership
        if ($class->teacher_id !== $teacherId || $assignment->teacher_id !== $teacherId) {
            abort(403);
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
        ]);

        return redirect()->back()->with('success', 'Score submitted successfully');
    }

    /**
     * Get KSA grading information
     */
    public function ksaInfo()
    {
        return response()->json([
            'knowledge' => [
                'weight' => 40,
                'description' => 'Quizzes 40% + Exams 60% = 40% of term'
            ],
            'skills' => [
                'weight' => 50,
                'description' => 'Output 40% + Class Part 30% + Activities 15% + Assignments 15% = 50% of term'
            ],
            'attitude' => [
                'weight' => 10,
                'description' => 'Behavior 50% + Awareness 50% = 10% of term'
            ],
            'formula' => 'Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)'
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'admission_number' => 'nullable|string|max:255',
            'roll_number' => 'nullable|string|max:255',
        ]);

        // Create user
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Create student
        $student = Student::create([
            'user_id' => $user->id,
            'class_id' => $validated['class_id'],
            'admission_number' => $validated['admission_number'] ?? null,
            'roll_number' => $validated['roll_number'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Student added successfully!');
    }

    /**
     * Show CHED grade entry form
     */
    public function showGradeEntryChed($classId, $term = 'midterm')
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)
            ->with('students.user')
            ->findOrFail($classId);

        $students = $class->students()->with('user')->get();

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        return view('teacher.grades.entry_ched', compact('class', 'students', 'term', 'range'));
    }

    /**
     * Store CHED grades with improved validation and error handling
     */
    public function storeGradesChed(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $grades = $request->input('grades', []);
        $term = $request->input('term', 'midterm');
        
        // Validate term
        if (!in_array($term, ['midterm', 'final'])) {
            return redirect()->back()->with('error', 'Invalid term selected');
        }

        $successCount = 0;
        $errors = [];

        foreach ($grades as $studentId => $gradeData) {
            try {
                $student = Student::findOrFail($studentId);

                // Clean and validate scores
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

                // Calculate component scores
                $knowledge = Grade::calculateKnowledge($quizzes, $exams, $range, $term);
                
                // Aggregate 3 entries per skill component for the current term
                $termSuffix = '_' . $term;
                $outputEntries = [
                    floatval($gradeData['output_1' . $termSuffix] ?? 0),
                    floatval($gradeData['output_2' . $termSuffix] ?? 0),
                    floatval($gradeData['output_3' . $termSuffix] ?? 0),
                ];
                $outputScore = array_sum($outputEntries) / count(array_filter($outputEntries, fn($v) => $v > 0 || count(array_filter($outputEntries)) == 0));
                if (empty(array_filter($outputEntries))) $outputScore = 0;
                
                $classParticipationEntries = [
                    floatval($gradeData['class_participation_1' . $termSuffix] ?? 0),
                    floatval($gradeData['class_participation_2' . $termSuffix] ?? 0),
                    floatval($gradeData['class_participation_3' . $termSuffix] ?? 0),
                ];
                $classParticipationScore = array_sum($classParticipationEntries) / count(array_filter($classParticipationEntries, fn($v) => $v > 0 || count(array_filter($classParticipationEntries)) == 0));
                if (empty(array_filter($classParticipationEntries))) $classParticipationScore = 0;

                $activitiesEntries = [
                    floatval($gradeData['activities_1' . $termSuffix] ?? 0),
                    floatval($gradeData['activities_2' . $termSuffix] ?? 0),
                    floatval($gradeData['activities_3' . $termSuffix] ?? 0),
                ];
                $activitiesScore = array_sum($activitiesEntries) / count(array_filter($activitiesEntries, fn($v) => $v > 0 || count(array_filter($activitiesEntries)) == 0));
                if (empty(array_filter($activitiesEntries))) $activitiesScore = 0;

                $assignmentsEntries = [
                    floatval($gradeData['assignments_1' . $termSuffix] ?? 0),
                    floatval($gradeData['assignments_2' . $termSuffix] ?? 0),
                    floatval($gradeData['assignments_3' . $termSuffix] ?? 0),
                ];
                $assignmentsScore = array_sum($assignmentsEntries) / count(array_filter($assignmentsEntries, fn($v) => $v > 0 || count(array_filter($assignmentsEntries)) == 0));
                if (empty(array_filter($assignmentsEntries))) $assignmentsScore = 0;

                $skills = Grade::calculateSkills(
                    $outputScore,
                    $classParticipationScore,
                    $activitiesScore,
                    $assignmentsScore,
                    $range
                );

                // Aggregate 3 entries per attitude component for the current term
                $behaviorEntries = [
                    floatval($gradeData['behavior_1' . $termSuffix] ?? 0),
                    floatval($gradeData['behavior_2' . $termSuffix] ?? 0),
                    floatval($gradeData['behavior_3' . $termSuffix] ?? 0),
                ];
                $behaviorScore = array_sum($behaviorEntries) / count(array_filter($behaviorEntries, fn($v) => $v > 0 || count(array_filter($behaviorEntries)) == 0));
                if (empty(array_filter($behaviorEntries))) $behaviorScore = 0;

                $awarenessEntries = [
                    floatval($gradeData['awareness_1' . $termSuffix] ?? 0),
                    floatval($gradeData['awareness_2' . $termSuffix] ?? 0),
                    floatval($gradeData['awareness_3' . $termSuffix] ?? 0),
                ];
                $awarenessScore = array_sum($awarenessEntries) / count(array_filter($awarenessEntries, fn($v) => $v > 0 || count(array_filter($awarenessEntries)) == 0));
                if (empty(array_filter($awarenessEntries))) $awarenessScore = 0;

                $attitude = Grade::calculateAttitude(
                    $behaviorScore,
                    $awarenessScore,
                    $range
                );

                $finalGrade = Grade::calculateFinalGrade($knowledge, $skills, $attitude);
                $gradePoint = Grade::getGradePoint($finalGrade);

                // Create or update grade
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'teacher_id' => $teacherId,
                        'term' => $term,
                    ],
                    [
                        'subject_id' => $class->subject_id,
                        'q1' => $quizzes[0],
                        'q2' => $quizzes[1],
                        'q3' => $quizzes[2],
                        'q4' => $quizzes[3],
                        'q5' => $quizzes[4],
                        'midterm_exam' => $exams['midterm'],
                        'final_exam' => $exams['final'],
                        'knowledge_score' => $knowledge,
                        'output_score' => $outputScore,
                        'class_participation_score' => $classParticipationScore,
                        'activities_score' => $activitiesScore,
                        'assignments_score' => $assignmentsScore,
                        'skills_score' => $skills,
                        'behavior_score' => $behaviorScore,
                        'awareness_score' => $awarenessScore,
                        'attitude_score' => $attitude,
                        'final_grade' => $finalGrade,
                        'grade_point' => $gradePoint,
                        'remarks' => $gradeData['remarks'] ?? null,
                        'grading_period' => $term,
                    ]
                );
                
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Student {$studentId}: " . $e->getMessage();
            }
        }

        if ($successCount > 0) {
            $message = "✓ {$successCount} grades saved successfully for {$term} term!";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " errors encountered)";
            }
            return redirect()->route('teacher.grades')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No grades were saved. Please check for errors.');
        }
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

        // For now, return a message that Excel import requires Laravel Excel package
        return redirect()->back()->with('info', 'Excel import functionality coming soon. Please add students manually for now.');
    }

    /**
     * Show assessment range configuration page for a class
     */
    public function configureAssessmentRanges($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('subject')
            ->firstOrFail();

        $subject = $class->subject;

        // Get or create assessment range
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        if (!$range) {
            $range = AssessmentRange::create([
                'class_id' => $classId,
                'subject_id' => $subject ? $subject->id : null,
                'teacher_id' => $teacherId,
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
            'class_participation_prelim' => 'required|integer|min:0|max:100',
            'class_participation_midterm' => 'required|integer|min:0|max:100',
            'class_participation_final' => 'required|integer|min:0|max:100',
            
            // Skills - Activities
            'activities_prelim' => 'required|integer|min:0|max:100',
            'activities_midterm' => 'required|integer|min:0|max:100',
            'activities_final' => 'required|integer|min:0|max:100',
            
            // Skills - Assignments
            'assignments_prelim' => 'required|integer|min:0|max:100',
            'assignments_midterm' => 'required|integer|min:0|max:100',
            'assignments_final' => 'required|integer|min:0|max:100',
            
            // Skills - Output/Project
            'output_prelim' => 'required|integer|min:0|max:100',
            'output_midterm' => 'required|integer|min:0|max:100',
            'output_final' => 'required|integer|min:0|max:100',
            
            // Attitude - Behavior
            'behavior_prelim' => 'required|integer|min:0|max:100',
            'behavior_midterm' => 'required|integer|min:0|max:100',
            'behavior_final' => 'required|integer|min:0|max:100',
            
            // Attitude - Awareness
            'awareness_prelim' => 'required|integer|min:0|max:100',
            'awareness_midterm' => 'required|integer|min:0|max:100',
            'awareness_final' => 'required|integer|min:0|max:100',
            
            // Attendance
            'attendance_required' => 'boolean',
            'attendance_min_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['teacher_id'] = $teacherId;
        $validated['class_id'] = $classId;
        $validated['subject_id'] = $class->subject_id ?? null;

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
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user', 'subject')
            ->firstOrFail();

        // Get assessment ranges
        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        // Get existing grades
        $grades = Grade::where('class_id', $classId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        // Get attendance records
        $attendance = StudentAttendance::where('class_id', $classId)
            ->where('term', $term)
            ->get()
            ->keyBy('student_id');

        $students = $class->students()->with('user')->get();

        return view('teacher.grades.entry_updated', compact(
            'class',
            'students',
            'term',
            'range',
            'grades',
            'attendance'
        ));
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
        if (!in_array($term, ['midterm', 'final'])) {
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
                        'subject_id' => $class->subject_id,
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
                            'subject_id' => $class->subject_id,
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
                $errors[] = "Student {$studentId}: " . $e->getMessage();
            }
        }

        $message = "✓ Grades saved! Created: {$gradesCreated}, Updated: {$gradesUpdated}, Attendance: {$attendanceUpdated}";
        
        if (!empty($errors)) {
            $message .= " (" . count($errors) . " errors)";
        }

        return redirect()->route('teacher.grades')->with('success', $message);
    }

    /**
     * Show inline grade entry form (NEW ENHANCED GRADING)
     */
    public function showGradeEntryInline($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user', 'subject')
            ->firstOrFail();

        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $grades = Grade::where('class_id', $classId)->get();

        return view('teacher.grades.entry_inline', compact('class', 'range', 'grades'));
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
            ->with('students.user', 'subject')
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
                'quiz_avg' => round($gradesWithFinal->avg(function($g) {
                    $quizzes = array_filter([$g->q1, $g->q2, $g->q3, $g->q4, $g->q5]);
                    return !empty($quizzes) ? array_sum($quizzes) / count($quizzes) : 0;
                }), 2),
                'exam_avg' => round($gradesWithFinal->avg(function($g) {
                    $exams = array_filter([$g->midterm_exam, $g->final_exam]);
                    return !empty($exams) ? array_sum($exams) / count($exams) : 0;
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
        if (empty($values)) return 0;
        
        $mean = array_sum($values) / count($values);
        $deviationSquared = array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values);
        $variance = array_sum($deviationSquared) / count($values);
        return round(sqrt($variance), 2);
    }
}