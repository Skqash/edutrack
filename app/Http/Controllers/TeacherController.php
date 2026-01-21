<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AssessmentRange;
use App\Models\StudentAttendance;
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
        
        // Get teacher's classes count
        $myClasses = ClassModel::where('teacher_id', $teacherId)->count();
        
        // Get total students in teacher's classes
        $totalStudents = Student::whereIn('class_id', 
            ClassModel::where('teacher_id', $teacherId)->pluck('id')
        )->count();
        
        // Get grades posted by this teacher
        $gradesPosted = Grade::where('teacher_id', $teacherId)->count();
        
        // Get pending grade submissions
        $pendingTasks = ClassModel::where('teacher_id', $teacherId)
            ->sum('capacity'); // This would be customized based on actual pending logic

        return view('teacher.dashboard', compact(
            'myClasses',
            'totalStudents',
            'gradesPosted',
            'pendingTasks'
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
     * Show assignments page
     */
    public function assignments()
    {
        $teacherId = Auth::id();
        $classes = ClassModel::where('teacher_id', $teacherId)->get();

        return view('teacher.assignments.index', compact('classes'));
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

        return view('teacher.grades.entry_ched', compact('class', 'students', 'term'));
    }

    /**
     * Store CHED grades
     */
    public function storeGradesChed(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('teacher_id', $teacherId)->findOrFail($classId);

        $grades = $request->input('grades', []);
        $term = $request->input('term', 'midterm');

        foreach ($grades as $studentId => $gradeData) {
            $student = Student::findOrFail($studentId);

            // Calculate component scores
            $quizzes = [
                $gradeData['q1'] ?? 0,
                $gradeData['q2'] ?? 0,
                $gradeData['q3'] ?? 0,
                $gradeData['q4'] ?? 0,
                $gradeData['q5'] ?? 0,
            ];

            $exams = [
                'prelim' => $gradeData['prelim_exam'] ?? 0,
                'midterm' => $gradeData['midterm_exam'] ?? 0,
                'final' => $gradeData['final_exam'] ?? 0,
            ];

            $knowledge = Grade::calculateKnowledge($quizzes, $exams, $term);
            
            $skills = Grade::calculateSkills(
                $gradeData['output_score'] ?? 0,
                $gradeData['class_participation_score'] ?? 0,
                $gradeData['activities_score'] ?? 0,
                $gradeData['assignments_score'] ?? 0
            );

            $attitude = Grade::calculateAttitude(
                $gradeData['behavior_score'] ?? 0,
                $gradeData['awareness_score'] ?? 0
            );

            $finalGrade = Grade::calculateFinalGrade($knowledge, $skills, $attitude);
            $letterGrade = Grade::getLetterGrade($finalGrade);

            // Create or update grade
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => $term,
                ],
                [
                    'q1' => $gradeData['q1'] ?? null,
                    'q2' => $gradeData['q2'] ?? null,
                    'q3' => $gradeData['q3'] ?? null,
                    'q4' => $gradeData['q4'] ?? null,
                    'q5' => $gradeData['q5'] ?? null,
                    'prelim_exam' => $gradeData['prelim_exam'] ?? null,
                    'midterm_exam' => $gradeData['midterm_exam'] ?? null,
                    'final_exam' => $gradeData['final_exam'] ?? null,
                    'knowledge_score' => $knowledge,
                    'output_score' => $gradeData['output_score'] ?? null,
                    'class_participation_score' => $gradeData['class_participation_score'] ?? null,
                    'activities_score' => $gradeData['activities_score'] ?? null,
                    'assignments_score' => $gradeData['assignments_score'] ?? null,
                    'skills_score' => $skills,
                    'behavior_score' => $gradeData['behavior_score'] ?? null,
                    'awareness_score' => $gradeData['awareness_score'] ?? null,
                    'attitude_score' => $attitude,
                    'final_grade' => $finalGrade,
                    'grade_letter' => $letterGrade,
                    'grading_period' => $term,
                ]
            );
        }

        return redirect()->route('teacher.grades')->with('success', 'Grades saved successfully!');
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
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacherId)
            ->first();

        if (!$range) {
            $range = AssessmentRange::create([
                'class_id' => $classId,
                'subject_id' => $subject->id,
                'teacher_id' => $teacherId,
            ]);
        }

        return view('teacher.assessment.configure', compact('class', 'range'));
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
            'quiz_1_max' => 'required|integer|min:5|max:100',
            'quiz_2_max' => 'required|integer|min:5|max:100',
            'quiz_3_max' => 'required|integer|min:5|max:100',
            'quiz_4_max' => 'required|integer|min:5|max:100',
            'quiz_5_max' => 'required|integer|min:5|max:100',
            'prelim_exam_max' => 'required|integer|min:20|max:200',
            'midterm_exam_max' => 'required|integer|min:20|max:200',
            'final_exam_max' => 'required|integer|min:20|max:200',
            'output_max' => 'required|integer|min:10|max:200',
            'class_participation_max' => 'required|integer|min:10|max:200',
            'activities_max' => 'required|integer|min:10|max:200',
            'assignments_max' => 'required|integer|min:10|max:200',
            'behavior_max' => 'required|integer|min:10|max:200',
            'awareness_max' => 'required|integer|min:10|max:200',
            'attendance_max' => 'required|integer|min:1|max:500',
            'attendance_required' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['teacher_id'] = $teacherId;
        $validated['class_id'] = $classId;
        $validated['subject_id'] = $class->subject_id;

        AssessmentRange::updateOrCreate(
            [
                'class_id' => $classId,
                'subject_id' => $class->subject_id,
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

        return view('teacher.grades.entry_enhanced', compact(
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
            'grades.*.prelim_exam' => 'nullable|numeric|min:0',
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

        foreach ($validated['grades'] as $gradeData) {
            $studentId = $gradeData['student_id'];

            // Prepare quiz data
            $quizzes = [
                $gradeData['q1'] ?? 0,
                $gradeData['q2'] ?? 0,
                $gradeData['q3'] ?? 0,
                $gradeData['q4'] ?? 0,
                $gradeData['q5'] ?? 0,
            ];

            // Prepare exam data
            $exams = [
                'prelim' => $gradeData['prelim_exam'] ?? 0,
                'midterm' => $gradeData['midterm_exam'] ?? 0,
                'final' => $gradeData['final_exam'] ?? 0,
            ];

            // Calculate scores using configurable ranges
            $knowledgeScore = Grade::calculateKnowledge($quizzes, $exams, $range, $term);

            $skillsScore = Grade::calculateSkills(
                $gradeData['output_score'] ?? 0,
                $gradeData['class_participation_score'] ?? 0,
                $gradeData['activities_score'] ?? 0,
                $gradeData['assignments_score'] ?? 0,
                $range
            );

            $attitudeScore = Grade::calculateAttitude(
                $gradeData['behavior_score'] ?? 0,
                $gradeData['awareness_score'] ?? 0,
                $range
            );

            $finalGrade = Grade::calculateFinalGrade($knowledgeScore, $skillsScore, $attitudeScore);
            $letterGrade = Grade::getLetterGrade($finalGrade);

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
                    'q1' => $gradeData['q1'] ?? null,
                    'q2' => $gradeData['q2'] ?? null,
                    'q3' => $gradeData['q3'] ?? null,
                    'q4' => $gradeData['q4'] ?? null,
                    'q5' => $gradeData['q5'] ?? null,
                    'prelim_exam' => $gradeData['prelim_exam'] ?? null,
                    'midterm_exam' => $gradeData['midterm_exam'] ?? null,
                    'final_exam' => $gradeData['final_exam'] ?? null,
                    'knowledge_score' => $knowledgeScore,
                    'output_score' => $gradeData['output_score'] ?? null,
                    'class_participation_score' => $gradeData['class_participation_score'] ?? null,
                    'activities_score' => $gradeData['activities_score'] ?? null,
                    'assignments_score' => $gradeData['assignments_score'] ?? null,
                    'skills_score' => $skillsScore,
                    'behavior_score' => $gradeData['behavior_score'] ?? null,
                    'awareness_score' => $gradeData['awareness_score'] ?? null,
                    'attitude_score' => $attitudeScore,
                    'final_grade' => $finalGrade,
                    'grade_letter' => $letterGrade,
                    'remarks' => $gradeData['remarks'] ?? null,
                    'grading_period' => $term,
                ]
            );

            // Update attendance if provided
            if (isset($gradeData['attendance_score'])) {
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'subject_id' => $class->subject_id,
                        'term' => $term,
                    ],
                    [
                        'attendance_score' => floatval($gradeData['attendance_score']),
                    ]
                );
                $attendanceUpdated++;
            }
        }

        return redirect()->route('teacher.grades')->with('success', "Grades and attendance saved successfully!");
    }

    /**
     * Show attendance management page
     */
    public function manageAttendance($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->with('students.user')
            ->firstOrFail();

        $range = AssessmentRange::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $attendance = StudentAttendance::where('class_id', $classId)
            ->get()
            ->groupBy('term');

        return view('teacher.attendance.manage', compact('class', 'range', 'attendance'));
    }

    /**
     * Record individual attendance
     */
    public function recordAttendance(Request $request, $classId)
    {
        $teacherId = Auth::id();
        ClassModel::where('id', $classId)->where('teacher_id', $teacherId)->firstOrFail();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|in:midterm,final',
            'present_classes' => 'required|integer|min:0',
            'total_classes' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        $record = StudentAttendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'class_id' => $classId,
                'term' => $validated['term'],
            ],
            [
                'subject_id' => ClassModel::find($classId)->subject_id,
                'present_classes' => $validated['present_classes'],
                'total_classes' => $validated['total_classes'],
                'absent_classes' => $validated['total_classes'] - $validated['present_classes'],
                'attendance_score' => ($validated['present_classes'] / $validated['total_classes']) * 100,
                'remarks' => $validated['remarks'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully',
            'attendance_score' => $record->attendance_score,
        ]);
    }
}
