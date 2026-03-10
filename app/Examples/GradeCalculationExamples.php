<?php

/**
 * GRADE CALCULATION EXAMPLES & IMPLEMENTATION GUIDE
 * 
 * This documentation file demonstrates practical usage of the grade calculation system
 * in the EduTrack application. These examples show best practices for:
 * 
 * ✓ Calculating grades in controller actions
 * ✓ Using GradeHelper functions directly
 * ✓ Working with Grade models
 * ✓ Implementing observers for auto-calculation
 * ✓ Creating reports and statistics
 * ✓ Bulk importing grades
 * 
 * GRADING SYSTEM OVERVIEW:
 * ─────────────────────────
 * Standard: CHED Philippines (Commission on Higher Education)
 * Decimal Scale: 1.0 (Excellent) to 5.0 (Failed)
 * Pass Threshold: ≤ 3.0 = Passed, > 3.0 = Failed
 * 
 * Grade Components:
 * - Knowledge: 40%
 * - Skills: 50%
 * - Attitude: 10%
 * 
 * Formula:
 * Term Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
 * Overall = (Midterm × 0.40) + (Final × 0.60)
 */

// ============================================================
// EXAMPLE 1: Calculate Grade in a Controller Action
// ============================================================
// Location: app/Http/Controllers/GradeCalculationExamples.php
// Purpose: Shows how to handle grade calculation after form submission

/*
namespace App\Http\Controllers;

use App\Helpers\GradeHelper;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeCalculationController extends Controller
{
    /**
     * Store calculated grades for a single student
     * 
     * POST /grades/store/{studentId}/{classId}
     * 
     * @param Request $request Form input data
     * @param int $studentId Student ID to calculate for
     * @param int $classId Class ID context
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeStudentGrade(Request $request, int $studentId, int $classId)
    {
        // Validate all component scores
        $validated = $request->validate([
            'mid_knowledge_average' => 'required|numeric|min:0|max:100',
            'mid_skills_average' => 'required|numeric|min:0|max:100',
            'mid_attitude_average' => 'required|numeric|min:0|max:100',
            'final_knowledge_average' => 'required|numeric|min:0|max:100',
            'final_skills_average' => 'required|numeric|min:0|max:100',
            'final_attitude_average' => 'required|numeric|min:0|max:100',
        ]);

        try {
            // Get or create grade record
            $grade = Grade::firstOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'teacher_id' => auth()->id(),
                ]
            );

            // Update with component averages
            $grade->update($validated);

            // Calculate all grades and store in database
            $grade->calculateAndUpdateGrades();
            $grade->save();

            // Get formatted summary for response
            $summary = $grade->getGradeSummary();

            return response()->json([
                'success' => true,
                'message' => 'Grade calculated and saved successfully',
                'student' => $summary,
                'status' => $grade->getPassFailStatus(),
                'decimal_grade' => $grade->grade_5pt_scale,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate grade: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate and store grades for entire class
     * 
     * POST /grades/class/{classId}
     * 
     * @param int $classId Class ID to process
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateClassGrades(int $classId)
    {
        $grades = Grade::where('class_id', $classId)
            ->where('teacher_id', auth()->id())
            ->get();

        $results = [];
        $passCount = 0;
        $failCount = 0;
        $totalGrade = 0;

        foreach ($grades as $grade) {
            // Skip if no component scores entered
            if (!$grade->mid_knowledge_average || !$grade->final_knowledge_average) {
                continue;
            }

            // Calculate for each student
            $grade->calculateAndUpdateGrades();
            $grade->save();

            $summary = $grade->getGradeSummary();
            $results[] = $summary;

            // Count pass/fail
            if ($grade->hasPassed()) {
                $passCount++;
            } else {
                $failCount++;
            }

            $totalGrade += $grade->overall_grade ?? 0;
        }

        $totalStudents = $passCount + $failCount;
        $passRate = $totalStudents > 0 
            ? round(($passCount / $totalStudents) * 100, 2)
            : 0;

        return response()->json([
            'success' => true,
            'message' => 'Class grades calculated successfully',
            'summary' => [
                'total_students' => $totalStudents,
                'passed' => $passCount,
                'failed' => $failCount,
                'pass_rate' => $passRate . '%',
                'average_grade' => $totalStudents > 0 
                    ? round($totalGrade / $totalStudents, 2)
                    : 0,
            ],
            'grades' => $results,
        ], 200);
    }
}
*/

// ============================================================
// EXAMPLE 2: Direct GradeHelper Function Usage
// ============================================================
// Purpose: Shows how to use helper functions directly without model

/*
// Calculate single term grade with decimal scale
$termGrade = GradeHelper::calculateTermGradeWithDecimal(
    knowledge: 85.5,  // Student scored 85.5 in knowledge
    skills: 87.3,     // Student scored 87.3 in skills
    attitude: 84.0    // Student scored 84.0 in attitude
);

// Output:
echo "Term Grade: " . $termGrade['term_grade'];          // 85.88
echo "Decimal Grade: " . $termGrade['decimal_grade'];    // 2.00
echo "Status: " . $termGrade['status'];                  // Passed
echo "Grade Label: " . $termGrade['grade_label'];        // B (Very Good)
echo "Performance Remarks: " . $termGrade['remarks'];    // Very Good - Strong performance

// Output Sample:
// Term Grade: 85.88 (out of 100)
// Decimal Grade: 2.00 (on 1.0-5.0 scale, where 1.0=Excellent, 5.0=Failed)
// Status: Passed ✅
// Grade Label: B (Very Good)
// Performance Remarks: Very Good - Strong performance
*/

// ============================================================
// EXAMPLE 3: Complete Grade Summary Calculation
// ============================================================
// Purpose: Calculate both midterm and final grades, then overall

/*
$summary = GradeHelper::getCompleteGradeSummary(
    midtermKnowledge: 82.0,
    midtermSkills: 81.0,
    midtermAttitude: 80.0,
    finalKnowledge: 88.0,
    finalSkills: 89.0,
    finalAttitude: 87.0
);

// Access different components of the summary
echo "=== MIDTERM ===";
echo "Grade: " . $summary['midterm']['term_grade'];          // 81.40
echo "Decimal: " . $summary['midterm']['decimal_grade'];     // 2.50 (C+)
echo "Status: " . $summary['midterm']['status'];             // Passed

echo "\n=== FINAL ===";
echo "Grade: " . $summary['final']['term_grade'];            // 88.40
echo "Decimal: " . $summary['final']['decimal_grade'];       // 1.75 (B+)
echo "Status: " . $summary['final']['status'];               // Passed

echo "\n=== OVERALL SEMESTER ===";
echo "Grade: " . $summary['overall']['term_grade'];          // 85.96
echo "Decimal: " . $summary['overall']['decimal_grade'];     // 2.00 (B)
echo "Status: " . $summary['overall']['status'];             // Passed

echo "\n=== SUMMARY ===";
echo "Student Status: " . $summary['summary']['student_status'];  // Passed
echo "Final Label: " . $summary['summary']['grade_label'];        // B (Very Good)
echo "Final Remarks: " . $summary['summary']['remarks'];          // Very Good - Strong performance
*/

// ============================================================
// EXAMPLE 4: Model Observer for Auto-Calculation
// ============================================================
// Location: app/Observers/GradeObserver.php
// Purpose: Automatically recalculate grades when component scores change

/*
namespace App\Observers;

use App\Helpers\GradeHelper;
use App\Models\Grade;

class GradeObserver
{
    /**
     * Handle the model "saving" event
     * Auto-calculate grades when component scores are modified
     * 
     * @param Grade $grade
     * @return void
     */
    public function saving(Grade $grade): void
    {
        // Check if any component scores have been modified
        $componentFields = [
            'mid_knowledge_average', 'mid_skills_average', 'mid_attitude_average',
            'final_knowledge_average', 'final_skills_average', 'final_attitude_average'
        ];

        if ($grade->isDirty($componentFields)) {
            // Auto-calculate all derived grades
            $grade->calculateAndUpdateGrades();
        }
    }

    /**
     * Handle the model "saved" event
     * You could log grade changes here
     * 
     * @param Grade $grade
     * @return void
     */
    public function saved(Grade $grade): void
    {
        // Example: Log grade updates
        // Log::info('Grade updated', [
        //     'student_id' => $grade->student_id,
        //     'overall_grade' => $grade->overall_grade,
        //     'status' => $grade->getPassFailStatus(),
        // ]);
    }
}

// Register in app/Providers/AppServiceProvider.php boot() method:
// Grade::observe(GradeObserver::class);
*/

// ============================================================
// EXAMPLE 5: Using in Blade Templates
// ============================================================
// Purpose: Display grades in views with proper formatting and styling

/*
<!-- Grade Results Table in Blade Template -->
<div class="grades-table">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Midterm Grade</th>
                <th>Final Grade</th>
                <th>Overall Grade</th>
                <th>Letter Grade</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grades as $grade)
                <tr class="@if($grade->hasFailed()) table-danger @else table-success @endif">
                    <td>{{ $grade->student->user->name ?? $grade->student->name }}</td>
                    <td>{{ $grade->mid_final_grade ?? '-' }} ({{ $grade->mid_decimal ?? '-' }})</td>
                    <td>{{ $grade->final_final_grade ?? '-' }} ({{ $grade->final_decimal ?? '-' }})</td>
                    <td class="fw-bold">{{ $grade->overall_grade ?? '-' }}</td>
                    <td>
                        <span class="badge @if($grade->hasPassed()) bg-success @else bg-danger @endif">
                            {{ $grade->letter_grade ?? 'Pending' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge @if($grade->hasPassed()) bg-success @else bg-danger @endif">
                            {{ $grade->getPassFailStatus() }}
                        </span>
                    </td>
                    <td><small>{{ $grade->remarks ?? '-' }}</small></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No grades available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
*/

// ============================================================
// EXAMPLE 6: Grade Summary for Student Portal
// ============================================================
// Purpose: Display comprehensive grade breakdown to students

/*
STUDENT GRADE CARD - SEMESTER VIEW
════════════════════════════════════════════════════════════

📚 Course: BSCS101 - Introduction to Programming
👨‍🏫 Instructor: Dr. Maria Santos
📅 Semester: 1st Semester, 2025-2026

MIDTERM EVALUATION:
─────────────────────────────────────────────────────────────
Knowledge Score:           82.00 (40% weight)
Skills Score:              81.00 (50% weight)
Attitude Score:            80.00 (10% weight)
                          ────────────────
Midterm Grade:            81.40 / 100.00
Decimal Scale (1-5):       2.50 (C+ - Good)
Status:                    ✅ PASSED
Performance Level:         Good - Solid performance

FINAL EVALUATION:
─────────────────────────────────────────────────────────────
Knowledge Score:           88.00 (40% weight)
Skills Score:              89.00 (50% weight)
Attitude Score:            87.00 (10% weight)
                          ────────────────
Final Grade:              88.40 / 100.00
Decimal Scale (1-5):       1.75 (B+ - Very Good)
Status:                    ✅ PASSED
Performance Level:         Very Good - Impressive performance

SEMESTER OVERALL:
─────────────────────────────────────────────────────────────
Calculation:
  Overall = (Midterm 81.40 × 0.40) + (Final 88.40 × 0.60)
  Overall = 32.56 + 53.04 = 85.60

Final Grade:               85.60 / 100.00
Decimal Scale (1-5):       2.00 (B - Very Good)
Letter Grade:              B (Very Good)
Status:                    ✅ PASSED

Pass Criteria:             Grade ≤ 3.0 ✅ Passed
Fail Criteria:             Grade > 3.0 ❌ Failed
Your Grade:                2.00 → PASSED ✅

Final Remarks:             Very Good - Strong performance
*/

// ============================================================
// EXAMPLE 7: Creating Reports and Statistics
// ============================================================
// Purpose: Query and aggregate grade data for reports

/*
namespace App\Http\Controllers;

use App\Models\Grade;

class ReportController extends Controller
{
    /**
     * Get detailed class statistics
     */
    public function classReport(int $classId)
    {
        // Get all grades with student details
        $studentGrades = Grade::where('class_id', $classId)
            ->with(['student.user', 'class.course'])
            ->get()
            ->map(function ($grade) {
                return [
                    'student_id' => $grade->student->student_id,
                    'student_name' => $grade->student->user->name ?? $grade->student->name,
                    'email' => $grade->student->user->email ?? 'N/A',
                    'overall_grade' => $grade->overall_grade,
                    'decimal_grade' => $grade->grade_5pt_scale,
                    'letter_grade' => $grade->letter_grade,
                    'status' => $grade->getPassFailStatus(),
                    'remarks' => $grade->remarks,
                ];
            })
            ->sortByDesc('overall_grade');

        // Calculate statistics
        $passCount = Grade::where('class_id', $classId)
            ->whereNotNull('grade_5pt_scale')
            ->where('grade_5pt_scale', '<=', 3.0)
            ->count();

        $failCount = Grade::where('class_id', $classId)
            ->whereNotNull('grade_5pt_scale')
            ->where('grade_5pt_scale', '>', 3.0)
            ->count();

        $totalGraded = $passCount + $failCount;
        $passRate = $totalGraded > 0 
            ? round(($passCount / $totalGraded) * 100, 2)
            : 0;

        $averageGrade = Grade::where('class_id', $classId)
            ->whereNotNull('overall_grade')
            ->avg('overall_grade');

        $highestGrade = Grade::where('class_id', $classId)
            ->whereNotNull('overall_grade')
            ->max('overall_grade');

        $lowestGrade = Grade::where('class_id', $classId)
            ->whereNotNull('overall_grade')
            ->min('overall_grade');

        return [
            'class_name' => $class->name,
            'total_students' => $class->students->count(),
            'graded_students' => $totalGraded,
            'passed' => $passCount,
            'failed' => $failCount,
            'pass_rate' => $passRate . '%',
            'statistics' => [
                'average_grade' => round($averageGrade ?? 0, 2),
                'highest_grade' => $highestGrade,
                'lowest_grade' => $lowestGrade,
            ],
            'grades' => $studentGrades,
        ];
    }
}
*/

// ============================================================
// EXAMPLE 8: Converting Scores to Decimal Scale
// ============================================================
// Purpose: Direct conversion utility for individual scores

/*
// Convert a single numeric score (0-100) to decimal scale (1.0-5.0)
$score = 92.5;
$decimalGrade = GradeHelper::convertToDecimalScale($score);
echo "Score: " . $score . " → Decimal: " . $decimalGrade;
// Output: Score: 92.5 → Decimal: 1.50

// Get the label for that grade
$label = GradeHelper::getGradeLabel($decimalGrade);
echo "Label: " . $label;
// Output: Label: A- (Excellent)

// Get performance remarks
$remarks = GradeHelper::getPerformanceRemarks($score);
echo "Remarks: " . $remarks;
// Output: Remarks: Excellent - Strong performance

// Use case: Display decimal grade information
echo "Student scored {$score}/100, which is {$decimalGrade} on the 1.0-5.0 scale ({$label})";
// Output: Student scored 92.5/100, which is 1.50 on the 1.0-5.0 scale (A- (Excellent))
*/

// ============================================================
// EXAMPLE 9: Bulk Import with Auto-Calculation
// ============================================================
// Purpose: Import grades from CSV/Excel with automatic calculation

/*
namespace App\Imports;

use App\Models\Grade;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GradesImport implements ToModel, WithHeadingRow
{
    /**
     * Map CSV row to Grade model with auto-calculation
     * 
     * CSV Format:
     * student_id | class_id | mid_knowledge | mid_skills | mid_attitude | final_knowledge | final_skills | final_attitude
     * 
     * @param array $row
     * @return Grade|null
     */
    public function model(array $row)
    {
        try {
            // Find or create grade record
            $grade = Grade::firstOrCreate(
                [
                    'student_id' => $row['student_id'],
                    'class_id' => $row['class_id'],
                ],
                [
                    'teacher_id' => auth()->id(),
                    'mid_knowledge_average' => (float) ($row['mid_knowledge'] ?? 0),
                    'mid_skills_average' => (float) ($row['mid_skills'] ?? 0),
                    'mid_attitude_average' => (float) ($row['mid_attitude'] ?? 0),
                    'final_knowledge_average' => (float) ($row['final_knowledge'] ?? 0),
                    'final_skills_average' => (float) ($row['final_skills'] ?? 0),
                    'final_attitude_average' => (float) ($row['final_attitude'] ?? 0),
                ]
            );

            // Auto-calculate all derived grades
            $grade->calculateAndUpdateGrades();
            $grade->save();

            return $grade;

        } catch (\Exception $e) {
            // Log import errors
            \Log::error('Grade import failed for row: ' . json_encode($row), [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}

// Usage in controller:
// $file = $request->file('grades');
// Excel::import(new GradesImport(), $file);
*/

// ============================================================
// GRADE CALCULATION METHOD REFERENCE
// ============================================================

/*
┌──────────────────────────────────────────────────────────┐
│ APP\HELPERS\GRADEHELPER - PUBLIC STATIC METHODS          │
└──────────────────────────────────────────────────────────┘

1. calculateTermGradeWithDecimal($knowledge, $skills, $attitude)
   └─ Calculates single term grade with decimal scale conversion
   └─ Returns: array with term_grade, decimal_grade, status, grade_label, remarks

2. calculateMidtermGrade($knowledge, $skills, $attitude)
   └─ Calculates midterm grade with all metadata
   └─ Returns: array with complete midterm assessment data

3. calculateFinalGrade($knowledge, $skills, $attitude)
   └─ Calculates final term grade with all metadata
   └─ Returns: array with complete final assessment data

4. calculateOverallTermGrade($midtermGrade, $finalGrade)
   └─ Calculates overall semester grade from midterm + final
   └─ Formula: (Midterm × 0.40) + (Final × 0.60)
   └─ Returns: array with overall assessment data

5. getCompleteGradeSummary($midKnow, $midSkill, $midAtt, $finKnow, $finSkill, $finAtt)
   └─ Comprehensive calculation with all components
   └─ Returns: array['midterm', 'final', 'overall', 'summary']

6. convertToDecimalScale($score)
   └─ Converts 0-100 numeric score to 1.0-5.0 decimal scale
   └─ Returns: float decimal grade

7. getPerformanceRemarks($score)
   └─ Returns descriptive performance text
   └─ Examples: "Excellent", "Very Good", "Failed - Below passing standard"

8. getGradeLabel($decimalGrade)
   └─ Returns letter grade with description
   └─ Examples: "A+ (Excellent)", "B (Very Good)", "F (Failed)"

9. extractLetterGrade($gradeLabel)
   └─ Extracts just the letter from full label
   └─ Example: "F (Failed)" → "F"

┌──────────────────────────────────────────────────────────┐
│ APP\MODELS\GRADE - PUBLIC METHODS                        │
└──────────────────────────────────────────────────────────┘

1. calculateAndUpdateGrades() : self
   └─ Calculates all grades and stores in model
   └─ Returns: $this for method chaining
   └─ Persists: overall_grade, grade_5pt_scale, letter_grade, remarks

2. getPassFailStatus() : string
   └─ Returns: "Passed" or "Failed"
   └─ Uses: grade_5pt_scale <= 3.0 ? "Passed" : "Failed"

3. hasPassed() : bool
   └─ Returns: true if grade_5pt_scale <= 3.0
   └─ Use for: conditional logic, filtering

4. hasFailed() : bool
   └─ Returns: true if grade_5pt_scale > 3.0
   └─ Use for: conditional logic, filtering

5. getGradeSummary() : array
   └─ Returns: formatted summary for display
   └─ Includes: all grade components with status
*/

// ============================================================
// TROUBLESHOOTING GUIDE
// ============================================================

/*
ISSUE: "Call to a member function isEmpty() on array"
SOLUTION: Use empty() for arrays, ->isEmpty() only for Collections
EXAMPLE: 
    ✗ $array->isEmpty()
    ✓ empty($array)

ISSUE: "Undefined type 'Request'"
SOLUTION: Add use statement at top of file
EXAMPLE:
    use Illuminate\Http\Request;

ISSUE: "Database column too small to store data"
SOLUTION: Check column type definitions in migrations
EXAMPLE:
    letter_grade should be ENUM or STRING(50)
    remarks should be TEXT, not VARCHAR(255)

ISSUE: Grades stored as 0 or NULL
SOLUTION: Ensure component scores are entered before calculation
EXAMPLE:
    if (!$grade->mid_knowledge_average) {
        return "Please enter component scores first";
    }

ISSUE: "Logic Error in GradeHelper or Grade Model"
SOLUTION: Check calculate*Grade methods return proper array structure
EXAMPLE:
    ✓ return ['term_grade' => 81.4, 'decimal_grade' => 2.5, ...];
    ✗ return 81.4; // Missing data structure

ISSUE: Decimal grades not converting correctly
SOLUTION: Verify convertToDecimalScale() conversion ranges
EXAMPLE:
    98-100 → 1.0 (A+)
    95-97 → 1.25 (A)
    70 → 3.0/3.5 boundary
    <70 → higher decimal (failing)
*/
