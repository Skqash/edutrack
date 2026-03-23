# Remaining Tasks to Complete

## Issue 1: 404 Error on Save
**Problem**: Route `/teacher/grades/save/{classId}` doesn't exist

**Solution**: Need to add route and controller method

```php
// In routes/web.php
Route::post('/grades/save/{classId}', [\App\Http\Controllers\TeacherController::class, 'saveComponentGrades'])->name('grades.save');

// In TeacherController.php
public function saveComponentGrades(Request $request, $classId) {
    // Save grades logic
}
```

## Issue 2: Add Subcategory Averages
**Current**: Shows "Total" and "Average" for each category
**Needed**: Show subcategory averages instead

### Knowledge Section Should Show:
- Midterm Exam column
- **Exam Ave** column (just the exam score transmuted)
- Quiz 1, Quiz 2, Quiz 3 columns  
- **Quiz Ave** column (average of all quizzes transmuted)
- **K Ave** column (Exam Ave × 60% + Quiz Ave × 40%)

### Skills Section Should Show:
- Output 1, 2, 3 columns
- **Output Ave** column
- Class Participation 1, 2, 3 columns
- **CP Ave** column
- Activity 1, 2, 3 columns
- **Activity Ave** column
- Assignment 1, 2, 3 columns
- **Assignment Ave** column
- **S Ave** column (weighted sum of all subcategory averages)

### Attitude Section Should Show:
- Behavior 1, 2, 3 columns
- **Behavior Ave** column
- Awareness 1, 2, 3 columns
- **Awareness Ave** column
- **A Ave** column (Behavior Ave × 50% + Awareness Ave × 50%)

## Calculation Formula Per Component:
```
Component Transmuted = (Raw Score / Max Score × 50) + 50
```

## Calculation Formula Per Subcategory:
```
Subcategory Ave = Average of all transmuted scores in that subcategory
```

## Calculation Formula Per Category:
```
Category Ave = Σ(Subcategory Ave × Subcategory Weight%)
```

## Final Grade:
```
Final = (K Ave × 40%) + (S Ave × 50%) + (A Ave × 10%)
```

## Implementation Steps:

1. ✅ Fix calculation formula (DONE - applies x50+50 per component)
2. ❌ Add save route and controller method
3. ❌ Restructure table headers to show subcategory groups
4. ❌ Add subcategory average columns
5. ❌ Update JavaScript to calculate subcategory averages
6. ❌ Remove "Total" columns, keep only "Ave" columns

This is a significant restructuring that requires careful implementation to maintain data integrity and calculation accuracy.
