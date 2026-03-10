# Grade Calculation Functions - Complete Guide

## Overview
The system now includes comprehensive grading functions that calculate midterm, final, and overall grades with decimal scale conversion (1.0-5.0) and automatic pass/fail determination.

---

## Grading Scale (1.0 to 5.0)

| Numeric Score | Decimal Grade | Label | Status |
|---|---|---|---|
| 98-100 | 1.00 | A+ (Excellent) | ✅ Passed |
| 95-97 | 1.25 | A (Excellent) | ✅ Passed |
| 92-94 | 1.50 | A- (Excellent) | ✅ Passed |
| 89-91 | 1.75 | B+ (Very Good) | ✅ Passed |
| 86-88 | 2.00 | B (Very Good) | ✅ Passed |
| 83-85 | 2.25 | B- (Very Good) | ✅ Passed |
| 80-82 | 2.50 | C+ (Good) | ✅ Passed |
| 77-79 | 2.75 | C (Good) | ✅ Passed |
| 74-76 | 3.00 | C- (Satisfactory) | ✅ Passed |
| 71-73 | 3.25 | D+ (Fair) | ❌ Failed |
| 70 | 3.50 | D (Fair) | ❌ Failed |
| Below 70 | 5.00 | F (Failed) | ❌ Failed |

**Pass/Fail Rule:**
- ✅ **Passed**: Decimal Grade ≤ 3.0
- ❌ **Failed**: Decimal Grade > 3.0

---

## Available Functions

### 1. Calculate Term Grade (0-100 scale)
```php
use App\Helpers\GradeHelper;

$result = GradeHelper::calculateTermGradeWithDecimal(
    $knowledge,  // float 0-100
    $skills,     // float 0-100
    $attitude    // float 0-100
);

// Returns:
[
    'term_grade' => 85.50,           // 0-100
    'decimal_grade' => 2.00,          // 1.0-5.0 (B)
    'status' => 'Passed',             // or 'Failed'
    'remarks' => 'Very Good - Strong performance',
    'grade_label' => 'B (Very Good)'
]
```

**Formula:**
```
Term Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
```

---

### 2. Calculate Midterm Grade
```php
$midtermData = GradeHelper::calculateMidtermGrade(
    $midtermKnowledge,   // float 0-100
    $midtermSkills,      // float 0-100
    $midtermAttitude     // float 0-100
);

// Returns same as above + 'period' => 'Midterm', 'weight' => 0.40
```

---

### 3. Calculate Final Grade
```php
$finalData = GradeHelper::calculateFinalGrade(
    $finalKnowledge,     // float 0-100
    $finalSkills,        // float 0-100
    $finalAttitude       // float 0-100
);

// Returns same as above + 'period' => 'Final', 'weight' => 0.60
```

---

### 4. Calculate Overall Grade (from Midterm & Final)
```php
$overallData = GradeHelper::calculateOverallTermGrade(
    $midtermData,        // from calculateMidtermGrade()
    $finalData           // from calculateFinalGrade()
);

// Returns:
[
    'period' => 'Overall',
    'term_grade' => 85.30,           // weighted average
    'decimal_grade' => 2.00,         // 1.0-5.0
    'status' => 'Passed',
    'remarks' => 'Very Good - Strong performance',
    'grade_label' => 'B (Very Good)',
    'midterm_contribution' => 34.20, // (Midterm × 0.40)
    'final_contribution' => 51.10    // (Final × 0.60)
]

// Formula:
// Overall = (Midterm × 0.40) + (Final × 0.60)
```

---

### 5. Get Complete Grade Summary
```php
$summary = GradeHelper::getCompleteGradeSummary(
    $midtermKnowledge,
    $midtermSkills,
    $midtermAttitude,
    $finalKnowledge,
    $finalSkills,
    $finalAttitude
);

// Returns:
[
    'midterm' => [
        'term_grade' => 85.50,
        'decimal_grade' => 2.00,
        'status' => 'Passed',
        'remarks' => '...',
        'grade_label' => 'B (Very Good)',
        'period' => 'Midterm',
        'weight' => 0.40
    ],
    'final' => [
        'term_grade' => 87.20,
        'decimal_grade' => 2.00,
        'status' => 'Passed',
        'remarks' => '...',
        'grade_label' => 'B (Very Good)',
        'period' => 'Final',
        'weight' => 0.60
    ],
    'overall' => [
        'term_grade' => 86.68,           // (85.50 × 0.40) + (87.20 × 0.60)
        'decimal_grade' => 2.00,
        'status' => 'Passed',
        'remarks' => '...',
        'grade_label' => 'B (Very Good)',
        'midterm_contribution' => 34.20,
        'final_contribution' => 52.32
    ],
    'summary' => [
        'student_status' => 'Passed',
        'final_grade_decimal' => 2.00,
        'final_grade_numeric' => 86.68,
        'grade_label' => 'B (Very Good)',
        'remarks' => 'Very Good - Strong performance'
    ]
]
```

---

### 6. Convert Numeric Score to Decimal Scale (1.0-5.0)
```php
$decimalGrade = GradeHelper::convertToDecimalScale(85.50);
// Returns: 2.00

$decimalGrade = GradeHelper::convertToDecimalScale(65);
// Returns: 5.00 (Failed)
```

---

### 7. Get Performance Remarks
```php
$remarks = GradeHelper::getPerformanceRemarks(85.50);
// Returns: "Very Good - Strong performance"

$remarks = GradeHelper::getPerformanceRemarks(98);
// Returns: "⭐⭐⭐ Excellent - Exceptional performance"
```

---

### 8. Get Grade Label
```php
$label = GradeHelper::getGradeLabel(2.00);
// Returns: "B (Very Good)"

$label = GradeHelper::getGradeLabel(1.00);
// Returns: "A+ (Excellent)"

$label = GradeHelper::getGradeLabel(5.00);
// Returns: "F (Failed)"
```

---

## Usage Example in Controller/Model

```php
<?php

namespace App\Http\Controllers;

use App\Helpers\GradeHelper;
use App\Models\Grade;

class GradeController extends Controller
{
    public function calculateStudentGrade($studentId, $classId)
    {
        // Get the grade record
        $grade = Grade::where('student_id', $studentId)
                      ->where('class_id', $classId)
                      ->first();

        if (!$grade) {
            return response()->json(['error' => 'Grade not found'], 404);
        }

        // Get complete grade summary
        $gradeSummary = GradeHelper::getCompleteGradeSummary(
            $grade->mid_knowledge_average ?? 0,
            $grade->mid_skills_average ?? 0,
            $grade->mid_attitude_average ?? 0,
            $grade->final_knowledge_average ?? 0,
            $grade->final_skills_average ?? 0,
            $grade->final_attitude_average ?? 0
        );

        // Update the grade record with calculated values
        $grade->update([
            'mid_final_grade' => $gradeSummary['midterm']['term_grade'],
            'final_final_grade' => $gradeSummary['final']['term_grade'],
            'overall_grade' => $gradeSummary['overall']['term_grade'],
            'grade_5pt_scale' => $gradeSummary['overall']['decimal_grade'],
            'grade_remarks' => $gradeSummary['summary']['grade_label'],
        ]);

        return response()->json($gradeSummary);
    }
}
```

---

## Database Fields to Update

When saving grades to the database, update these fields:

| Field | Type | Source |
|---|---|---|
| `mid_final_grade` | decimal(5,2) | `$summary['midterm']['term_grade']` |
| `final_final_grade` | decimal(5,2) | `$summary['final']['term_grade']` |
| `overall_grade` | decimal(5,2) | `$summary['overall']['term_grade']` |
| `grade_5pt_scale` | decimal(5,2) | `$summary['overall']['decimal_grade']` |
| `grade_remarks` | string | `$summary['summary']['grade_label']` |
| `letter_grade` | string | Determine from decimal grade |

---

## Grade Weighting in System

### Within Term Grade (0-100)
- **Knowledge**: 40%
  - Quizzes: 40% of Knowledge (16% of term)
  - Exams: 60% of Knowledge (24% of term)
- **Skills**: 50%
  - Output: 40% (20% of term)
  - Class Participation: 30% (15% of term)
  - Activities: 15% (7.5% of term)
  - Assignments: 15% (7.5% of term)
- **Attitude**: 10%
  - Behavior: 50% (5% of term)
  - Awareness: 50% (5% of term)

### Semester Grade (Overall)
- **Midterm**: 40%
- **Final**: 60%

---

## Examples

### Example 1: Student with Excellent Performance
```php
// Midterm Scores
$midtermKnowledge = 95;  // Excellent exams and quizzes
$midtermSkills = 92;     // Excellent outputs, participation, activities
$midtermAttitude = 96;   // Excellent behavior and awareness

// Final Scores
$finalKnowledge = 97;
$finalSkills = 94;
$finalAttitude = 95;

$summary = GradeHelper::getCompleteGradeSummary(
    $midtermKnowledge, $midtermSkills, $midtermAttitude,
    $finalKnowledge, $finalSkills, $finalAttitude
);

// Result:
// Midterm: 94.90 → 1.25 (A) - Passed
// Final: 95.40 → 1.25 (A) - Passed
// Overall: 95.26 → 1.25 (A) - Passed ✅
```

### Example 2: Student on Borderline
```php
// Midterm Scores
$midtermKnowledge = 74;  // Fair
$midtermSkills = 73;     // Fair
$midtermAttitude = 75;   // Fair

// Final Scores
$finalKnowledge = 80;    // Good
$finalSkills = 78;       // Fair
$finalAttitude = 79;     // Fair

$summary = GradeHelper::getCompleteGradeSummary(
    $midtermKnowledge, $midtermSkills, $midtermAttitude,
    $finalKnowledge, $finalSkills, $finalAttitude
);

// Result:
// Midterm: 73.70 → 3.25 (D+) - Failed
// Final: 78.40 → 2.75 (C) - Passed
// Overall: 76.94 → 3.00 (C-) - Passed (borderline) ✅
```

### Example 3: Student Failing
```php
// Midterm Scores
$midtermKnowledge = 65;
$midtermSkills = 62;
$midtermAttitude = 60;

// Final Scores
$finalKnowledge = 68;
$finalSkills = 64;
$finalAttitude = 63;

$summary = GradeHelper::getCompleteGradeSummary(
    $midtermKnowledge, $midtermSkills, $midtermAttitude,
    $finalKnowledge, $finalSkills, $finalAttitude
);

// Result:
// Midterm: 63.60 → 5.00 (F) - Failed
// Final: 65.40 → 5.00 (F) - Failed
// Overall: 64.76 → 5.00 (F) - Failed ❌
```

---

## Integration Points

These functions can be integrated into:

1. **Grade Entry Form** - Real-time calculation as teachers enter scores
2. **Grade Report Cards** - Display decimal grades and pass/fail status
3. **Analytics Dashboard** - Calculate class statistics
4. **Bulk Grade Import** - Calculate all grades when importing from CSV
5. **Student Portal** - Show students their grades with status

---

## Notes

- All grades are rounded to 2 decimal places for consistency
- Pass threshold is fixed at 3.0 on decimal scale
- Decimal grade 1.0 is the highest (excellent)
- Decimal grade 5.0 indicates failure
- System uses CHED (Commission on Higher Education) Philippines standards
