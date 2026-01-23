# Flexible Quiz Configuration System - Implementation Guide

## Overview

The EduTrack grading system now supports **flexible quiz configuration**, allowing teachers to set the number of quiz items from 1 to 10 (previously fixed at 5). This document explains how the system works and how to use it.

## System Architecture

### Components

1. **Assessment Configuration** (`AssessmentRange` Model)
    - Stores quiz configuration per class
    - Fields: `num_quizzes` (1-10), `total_quiz_items` (1-500), `equal_quiz_distribution` (bool)

2. **Grade Entry Form** (New: `entry_updated.blade.php`)
    - Title: **"Edutrack Grade Entry Form"**
    - Dynamically generates quiz columns based on configuration
    - Supports Output, Class Participation, Activities, Assignments, Behavior, and Awareness components
    - Excel-format compliant layout with color-coded sections

3. **Grade Model** (`Grade.php`)
    - Supports Q1-Q10 columns for flexible quiz storage
    - Calculation methods handle variable quiz counts automatically

4. **Grade Calculation Engine**
    - **Knowledge (40%):** Quiz Average (40%) + Exam Average (60%)
    - **Skills (50%):** Output (40%) + Class Participation (30%) + Activities (15%) + Assignments (15%)
    - **Attitude (10%):** Behavior (50%) + Awareness (50%)
    - **Final Grade:** Knowledge × 0.40 + Skills × 0.50 + Attitude × 0.10

## Key Features

### 1. Dynamic Quiz Count

- Teachers can configure 1-10 quizzes per class
- Each quiz automatically weighted at **20% of total quizzes** to reach **40% knowledge**
- Example:
    - 5 quizzes = 8% each × 5 = 40% knowledge
    - 8 quizzes = 5% each × 8 = 40% knowledge

### 2. Excel-Format Compliant Layout

The form displays:

```
[KNOWLEDGE (40%)] | [SKILLS (50%)] | [ATTITUDE (10%)] | [FINAL SCORE]
Q1-Qn | Pre | Mid | K% | Output | C.Part | Activ | Assign | S% | Behav | Aware | A% | Final | Letter
```

Color coding:

- **Blue**: Knowledge components
- **Orange**: Skills components
- **Teal**: Attitude components
- **Gray**: Final scores

### 3. New Skill/Attitude Components

Previously stored as combined scores. Now individually tracked:

#### Skills Components:

- **Output** (40% of skills)
- **Class Participation** (30% of skills)
- **Activities** (15% of skills)
- **Assignments** (15% of skills)

#### Attitude Components:

- **Behavior** (50% of attitude)
- **Awareness** (50% of attitude)

### 4. Automatic Grade Calculation

All calculated fields (K%, S%, A%, Final, Letter) update based on inputs:

- Teachers enter individual component scores
- System automatically calculates weighted percentages
- Letter grades assigned based on final score

## Usage Guide

### For Teachers

#### Step 1: Configure Quiz Settings

1. Navigate to a class in the teacher dashboard
2. Click **"Configure"** button on the grading form
3. Set these parameters:
    - **Quiz Items**: Total number of quiz items (1-500)
    - **Number of Quizzes**: 1-10 quizzes
    - **Equal Distribution**: Toggle for equal weighting or custom

#### Step 2: Enter Grades

1. Go to **Grades → Entry Form** for your class
2. The form title shows: **"Edutrack Grade Entry Form"**
3. Enter scores for:
    - **Knowledge**: Each quiz (Q1-Qn) and exams (Prelim, Midterm, Final)
    - **Skills**: Output, Class Participation, Activities, Assignments
    - **Attitude**: Behavior and Awareness
4. All calculated fields auto-populate
5. Click **"Save All Grades"** to submit

#### Step 3: Review Results

1. Navigate to **Analytics** to see:
    - Distribution of grades by category
    - Class performance statistics
    - Individual student progress
    - Comparison against configured ranges

### For System Administrators

#### Monitoring Configuration

Check current quiz configuration per class:

```php
// In TeacherController or custom admin panel
$range = AssessmentRange::where('class_id', $classId)->first();

echo "Quizzes: " . $range->num_quizzes;          // e.g., 8
echo "Total Items: " . $range->total_quiz_items; // e.g., 100
echo "Equal Distribution: " . $range->equal_quiz_distribution ? 'Yes' : 'No';
```

#### Database Fields

**AssessmentRange Table:**

```sql
num_quizzes          INT (1-10)
total_quiz_items     INT (1-500)
equal_quiz_distribution BOOLEAN
quiz_distribution    JSON (optional custom distribution)
```

**Grades Table:**

```sql
q1, q2, q3, q4, q5, q6, q7, q8, q9, q10  DECIMAL(5,2)  -- Quiz scores
prelim_exam, midterm_exam, final_exam    DECIMAL(5,2)  -- Exam scores
output_score                             DECIMAL(5,2)  -- Skills component
class_participation_score                DECIMAL(5,2)  -- Skills component
activities_score                         DECIMAL(5,2)  -- Skills component
assignments_score                        DECIMAL(5,2)  -- Skills component
behavior_score                           DECIMAL(5,2)  -- Attitude component
awareness_score                          DECIMAL(5,2)  -- Attitude component
knowledge_score, skills_score, attitude_score DECIMAL(5,2) -- Calculated
final_grade, grade_letter                DECIMAL(5,2) / CHAR(1) -- Final
```

## Technical Details

### Grade Calculation Algorithm

#### Knowledge Score Calculation

```php
// Dynamic quiz weighting
$numQuizzes = $range->num_quizzes ?? 5;
$quizWeight = 40 / $numQuizzes;  // Each quiz = 20% of knowledge

$quizzes = [q1, q2, q3, ..., qN];  // Variable length array
$quizAverage = array_sum($quizzes) / count($quizzes);
$quizPart = ($quizAverage / 100) * 40;  // 40% weight

// Exams always worth 60%
$examAverage = (prelim + midterm + final) / 3;
$examPart = ($examAverage / 100) * 60;

$knowledgeScore = $quizPart + $examPart;  // 0-100
```

#### Skills Score Calculation

```php
$skillsScore = (
    ($output / 100) * 40 +
    ($classParticipation / 100) * 30 +
    ($activities / 100) * 15 +
    ($assignments / 100) * 15
);  // 0-100
```

#### Attitude Score Calculation

```php
$attitudeScore = (
    ($behavior / 100) * 50 +
    ($awareness / 100) * 50
);  // 0-100
```

#### Final Grade Calculation

```php
$finalGrade = (
    ($knowledgeScore / 100) * 40 +
    ($skillsScore / 100) * 50 +
    ($attitudeScore / 100) * 10
);  // 0-100

// Letter grade mapping:
// A: 90-100, B: 80-89, C: 70-79, D: 60-69, F: 0-59
```

### Data Validation

The form validates:

- All scores are 0-100 numeric values
- Required fields match configuration
- Attendance between 0-100
- Student IDs exist in system

## File Structure

### Created/Modified Files

1. **Views:**
    - `resources/views/teacher/grades/entry_updated.blade.php` (NEW) - Main entry form

2. **Models:**
    - `app/Models/Grade.php` (MODIFIED) - Added Q6-Q10 support
    - `app/Models/AssessmentRange.php` (EXISTING) - Supports configuration

3. **Controllers:**
    - `app/Http/Controllers/TeacherController.php` (MODIFIED) - Updated validation and quiz processing

4. **Migrations:**
    - `database/migrations/2025_01_25_000001_add_flexible_quiz_columns_to_grades.php` (NEW) - Q6-Q10 columns
    - `database/migrations/2026_01_21_000005_add_total_quiz_configuration.php` (EXISTING) - Quiz configuration

5. **Documentation:**
    - This file: `FLEXIBLE_QUIZ_SYSTEM_IMPLEMENTATION.md`

## Running the System

### 1. Apply Migrations

```bash
php artisan migrate
```

This creates:

- Q6-Q10 columns in grades table (if not already present)
- Assessment range configuration fields (if not already present)

### 2. Seed Initial Configuration (Optional)

```bash
php artisan db:seed --class=AssessmentRangeSeeder
```

### 3. Access the System

```
Teacher Dashboard → Select Class → Click "Entry Form"
```

## Testing

### Test Scenarios

1. **Basic Entry (5 Quizzes)**
    - Configure: 5 quizzes × 20 items = 100 total
    - Enter all scores
    - Verify calculations match formula

2. **Flexible Entry (8 Quizzes)**
    - Configure: 8 quizzes × 12-13 items = 100 total
    - Verify only 8 quiz columns display
    - Verify each quiz = 5% × 8 = 40% knowledge

3. **Minimal Entry (1 Quiz)**
    - Configure: 1 quiz × 100 items = 100 total
    - Verify only 1 quiz column displays
    - Verify quiz = 40% knowledge

4. **All Components**
    - Enter scores for all skill/attitude components
    - Verify Skills section calculates correctly
    - Verify Attitude section calculates correctly

### Expected Results

- Form displays correct number of quiz columns
- Calculated fields update without page reload
- Grade letter appears with correct color coding
- Final grade = (K×0.4 + S×0.5 + A×0.1)
- Database stores all individual component scores

## Troubleshooting

### Issue: Quiz columns not displaying correctly

**Solution:** Verify AssessmentRange exists for the class:

```php
$range = AssessmentRange::where('class_id', $classId)->first();
if (!$range) {
    // Create default range
    AssessmentRange::create([
        'class_id' => $classId,
        'teacher_id' => $teacherId,
        'num_quizzes' => 5,
        'total_quiz_items' => 100,
        'equal_quiz_distribution' => true,
    ]);
}
```

### Issue: Grades not saving

**Solution:** Check validation errors:

```php
// In controller after store
if ($errors->any()) {
    return redirect()->back()->withErrors($errors)->withInput();
}
```

### Issue: Calculations appear incorrect

**Solution:** Verify configurable ranges are set:

```php
// Check if range normalization is working
$range = AssessmentRange::find($classId);
$maxScores = $range->getQuizMaxScores();
dd($maxScores);  // Should show distribution
```

## Performance Considerations

- Form optimized for <50 students per class
- Uses lazy loading for relationships
- Calculations done server-side for consistency
- Database indices on class_id and teacher_id for fast queries

## Future Enhancements

1. **Batch Import**: Upload grades from Excel file
2. **Export Function**: Export grades to Excel format
3. **Grade Curves**: Adjust grading scale per term
4. **Backup/History**: Track grade changes over time
5. **Mobile Optimization**: Better mobile form layout
6. **Offline Mode**: Sync grades when online

## Support & Contact

For issues or questions:

1. Check this documentation first
2. Review the configuration in **Settings → Assessment Ranges**
3. Check application logs: `storage/logs/laravel.log`
4. Contact system administrator

---

**Version:** 1.0  
**Last Updated:** January 2025  
**System:** EduTrack CHED-Compliant Grading System
