# Grade Calculation - Quick Start Implementation

## 5-Minute Setup Guide

### What Was Added

✅ **GradeHelper functions** - 8 new methods for grade calculation  
✅ **Grade model methods** - 5 new methods for auto-calculation  
✅ **Decimal scale conversion** - 1.0-5.0 scale with pass/fail logic  
✅ **Complete documentation** - Comprehensive guides and examples  

---

## Quick Implementation

### Step 1: In Your Grade Storage Controller

```php
<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Grade;
use App\Helpers\GradeHelper;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function store(Request $request, $classId)
    {
        $gradeData = $request->validated();
        
        foreach ($gradeData as $studentId => $scores) {
            $grade = Grade::updateOrCreate(
                ['student_id' => $studentId, 'class_id' => $classId],
                $scores
            );

            // AUTO-CALCULATE GRADES
            $grade->calculateAndUpdateGrades();
            $grade->save();
        }

        return response()->json(['message' => 'Grades saved and calculated']);
    }
}
```

### Step 2: Display in Blade Template

```blade
{{-- Show midterm grade with decimal and status --}}
<div class="grade-card">
    <h5>Midterm Grade</h5>
    <p class="grade-value">{{ $grade->mid_final_grade ?? '-' }}</p>
    <p class="decimal-grade">{{ $grade->mid_decimal_grade ?? '-' }} on 1.0-5.0 scale</p>
    <span class="badge @if($grade->mid_status === 'Passed') bg-success @else bg-danger @endif">
        {{ $grade->mid_status ?? 'Pending' }}
    </span>
</div>

{{-- Show final grade with decimal and status --}}
<div class="grade-card">
    <h5>Final Grade</h5>
    <p class="grade-value">{{ $grade->final_final_grade ?? '-' }}</p>
    <p class="decimal-grade">{{ $grade->final_decimal_grade ?? '-' }} on 1.0-5.0 scale</p>
    <span class="badge @if($grade->final_status === 'Passed') bg-success @else bg-danger @endif">
        {{ $grade->final_status ?? 'Pending' }}
    </span>
</div>

{{-- Show overall semester grade --}}
<div class="grade-card highlight">
    <h5>Semester Grade</h5>
    <p class="grade-value">{{ $grade->overall_grade ?? '-' }}</p>
    <p class="decimal-grade">{{ $grade->grade_5pt_scale ?? '-' }} ({{ $grade->letter_grade ?? '-' }})</p>
    <span class="badge @if($grade->hasPassed()) bg-success @else bg-danger @endif">
        @if($grade->hasPassed())
            ✅ Passed
        @else
            ❌ Failed
        @endif
    </span>
    <p class="remarks">{{ $grade->remarks ?? 'Grade pending calculation' }}</p>
</div>
```

### Step 3: In an Observer (Optional - for auto-calculation)

```php
<?php

namespace App\Observers;

use App\Models\Grade;

class GradeObserver
{
    public function saving(Grade $grade)
    {
        // Auto-calculate if component averages changed
        if ($grade->isDirty([
            'mid_knowledge_average', 'mid_skills_average', 'mid_attitude_average',
            'final_knowledge_average', 'final_skills_average', 'final_attitude_average'
        ])) {
            $grade->calculateAndUpdateGrades();
        }
    }
}

// In AppServiceProvider boot():
// Grade::observe(GradeObserver::class);
```

---

## Key Formulas Used

### Term Grade Calculation (0-100)
```
Term Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
```

### Overall Grade Calculation
```
Overall = (Midterm × 0.40) + (Final × 0.60)
```

### Scale Conversion (to 1.0-5.0)
```
98-100 → 1.00 (A+)
95-97  → 1.25 (A)
92-94  → 1.50 (A-)
89-91  → 1.75 (B+)
86-88  → 2.00 (B)
83-85  → 2.25 (B-)
80-82  → 2.50 (C+)
77-79  → 2.75 (C)
74-76  → 3.00 (C-)
71-73  → 3.25 (D+)
70     → 3.50 (D)
<70    → 5.00 (F - Failed)
```

### Pass/Fail Logic
```
IF decimal_grade ≤ 3.0 THEN "Passed" ✅
IF decimal_grade > 3.0 THEN "Failed" ❌
```

---

## Database Fields Used

Update your `grades` table to ensure these columns exist:

```php
// In migration:
Schema::table('grades', function (Blueprint $table) {
    // Midterm grades
    $table->decimal('mid_final_grade', 5, 2)->nullable();
    $table->decimal('mid_decimal_grade', 5, 2)->nullable();
    $table->string('mid_status')->nullable();
    $table->text('mid_remarks')->nullable();

    // Final grades
    $table->decimal('final_final_grade', 5, 2)->nullable();
    $table->decimal('final_decimal_grade', 5, 2)->nullable();
    $table->string('final_status')->nullable();
    $table->text('final_remarks')->nullable();

    // Overall grades
    $table->decimal('overall_grade', 5, 2)->nullable();
    $table->decimal('grade_5pt_scale', 5, 2)->nullable(); // Decimal scale
    $table->string('letter_grade')->nullable();
    $table->string('final_status')->nullable();
    $table->text('remarks')->nullable();
});
```

Or if columns already exist, just ensure they're there.

---

## Usage Examples

### Example 1: Calculate Single Student Grade
```php
$grade = Grade::find($gradeId);
$grade->calculateAndUpdateGrades();
$grade->save();

echo "Overall: " . $grade->overall_grade;      // 85.30
echo "Decimal: " . $grade->grade_5pt_scale;    // 2.00
echo "Status: " . $grade->getPassFailStatus(); // Passed
```

### Example 2: Calculate Class Grades
```php
$grades = Grade::where('class_id', $classId)->get();

foreach ($grades as $grade) {
    $grade->calculateAndUpdateGrades();
    $grade->save();
}

// Check pass/fail count
$passCount = Grade::where('class_id', $classId)
    ->where('grade_5pt_scale', '<=', 3.0)
    ->count();
```

### Example 3: Get Summary for Display
```php
$grade = Grade::with('student')->find($gradeId);
$summary = $grade->getGradeSummary();

// $summary contains:
// - student_name
// - midterm_grade, midterm_decimal
// - final_grade, final_decimal
// - overall_grade, overall_decimal
// - status (Passed/Failed)
// - remarks
// - grade_label
```

---

## Testing

### Quick Test
```php
// In tinker or test:
use App\Helpers\GradeHelper;

// Test calculation
$result = GradeHelper::calculateTermGradeWithDecimal(
    knowledge: 85,
    skills: 87,
    attitude: 84
);

dd($result);
// Should return:
// [
//     'term_grade' => 85.9,
//     'decimal_grade' => 2.0,
//     'status' => 'Passed',
//     'remarks' => 'Very Good - Strong performance',
//     'grade_label' => 'B (Very Good)'
// ]
```

---

## Important Notes

1. **Pass Threshold**: Grade ≤ 3.0 on 1.0-5.0 scale = Passed
2. **Fail Threshold**: Grade > 3.0 = Failed
3. **Highest Grade**: 1.0 (Excellent)
4. **Lowest Grade**: 5.0 (Failed)
5. **All grades rounded to 2 decimal places**

---

## Troubleshooting

### Grades not calculating?
- Check that component averages are set (not null/0)
- Call `$grade->calculateAndUpdateGrades()` explicitly
- Check database columns exist

### Wrong decimal grade?
- Verify numeric grade is 0-100 range
- Check conversion table above
- Use `GradeHelper::convertToDecimalScale($score)` directly

### Pass/Fail showing wrong?
- Check if `grade_5pt_scale` column has value
- Verify using `$grade->hasPassed()` or `$grade->hasFailed()`
- Manual check: if `grade_5pt_scale <= 3.0` then Passed

---

## Files Created

1. **GradeHelper.php** - 8 new calculation functions
2. **Grade.php** - 5 new model methods
3. **GRADING_CALCULATION_GUIDE.md** - Complete documentation
4. **GradeCalculationExamples.php** - Code examples
5. **GRADES_QUICKSTART.md** - This file

---

## Next Steps

1. ✅ Functions are created and ready to use
2. ✅ Database fields ready (update if needed)
3. Keep reading documentation for advanced usage
4. See Examples file for integration patterns
5. Test with actual student data

---

## Support Reference

For more details, refer to:
- `GRADING_CALCULATION_GUIDE.md` - Complete API documentation
- `app/Examples/GradeCalculationExamples.php` - Code examples
- `app/Helpers/GradeHelper.php` - Source code
- `app/Models/Grade.php` - Model methods
