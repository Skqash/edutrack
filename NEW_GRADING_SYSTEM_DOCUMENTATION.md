# NEW KSA GRADING SYSTEM - COMPLETE IMPLEMENTATION

## Overview
This document describes the complete new KSA (Knowledge, Skills, Attitude) grading system with Midterm (40%) and Final (60%) weighting structure.

## What's New

### 1. **Grading Structure**
- **Midterm Weight: 40%** - Based on Preliminary, Midterm Exams, Quizzes, and Skills/Attitude components
- **Final Weight: 60%** - Based on Final Exam and updated Skills/Attitude components
- **Overall Grade = (Midterm × 0.40) + (Final × 0.60)**

### 2. **Component Breakdown**

#### Knowledge (40% weight)
- **Exams (60% of Knowledge = 24% of overall)**
  - Preliminary Exam
  - Midterm Exam
  - Final Exam
- **Quizzes (40% of Knowledge = 16% of overall)**
  - 5 quizzes (Q1-Q5)

#### Skills (50% weight)
- **Output (40% of Skills = 20% of overall)**
  - 3 entries for tracking portfolio/outputs
- **Class Participation (30% of Skills = 15% of overall)**
  - 3 entries for class interaction
- **Activities (15% of Skills = 7.5% of overall)**
  - 3 entries for class activities
- **Assignments (15% of Skills = 7.5% of overall)**
  - 3 entries for homework/assignments

#### Attitude (10% weight)
- **Behavior (50% of Attitude = 5% of overall)**
  - 3 entries for behavior tracking
- **Awareness (50% of Attitude = 5% of overall)**
  - 3 entries for class awareness/consciousness

## Installation Steps

### Step 1: Run Migrations
Execute the new migration to add all required fields to the grades table:

```bash
php artisan migrate
```

This migration adds:
- `assessment_period` (midterm/final distinction)
- Exam fields: `exam_prelim`, `exam_midterm`, `exam_final`
- Quiz fields: `quiz_1` through `quiz_5`
- Skills component fields (3 entries each)
- Attitude component fields (3 entries each)
- Component average fields: `knowledge_average`, `skills_average`, `attitude_average`
- Grade fields: `midterm_grade`, `final_grade_value`, `overall_grade`, `grade_point`, `letter_grade`

### Step 2: Access the New Grade Entry Form
Teachers can now access the new grading system at:
```
/teacher/grades/entry-new/{classId}
```

### Step 3: Key Features

#### Real-time Calculations
- Component averages calculate automatically as teachers enter scores
- Midterm and final grades calculate based on weighted components
- Letter grades and grade points assigned automatically

#### Validation
- All scores must be between 0-100
- Data validation prevents errors before saving
- Error messages provide feedback on invalid entries

#### Component Tracking
The system tracks 3 entries for each skill and attitude component, allowing:
- Multiple measurement points per grading period
- Flexibility in how schools implement components
- Clearer picture of student performance trends

## Database Schema

### New Columns
```
- assessment_period: ENUM('midterm', 'final')
- exam_prelim, exam_midterm, exam_final: DECIMAL(5,2)
- quiz_1 through quiz_5: DECIMAL(5,2)
- output_1, output_2, output_3: DECIMAL(5,2)
- class_participation_1, class_participation_2, class_participation_3: DECIMAL(5,2)
- activities_1, activities_2, activities_3: DECIMAL(5,2)
- assignments_1, assignments_2, assignments_3: DECIMAL(5,2)
- behavior_1, behavior_2, behavior_3: DECIMAL(5,2)
- awareness_1, awareness_2, awareness_3: DECIMAL(5,2)
- knowledge_average, skills_average, attitude_average: DECIMAL(5,2)
- midterm_grade, final_grade_value, overall_grade: DECIMAL(5,2)
- grade_point: DECIMAL(5,2)
- letter_grade: ENUM('A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'F', 'INC')
```

## Grade Conversion Scale

| Score Range | Letter Grade | Grade Point |
|------------|--------------|-------------|
| 98.0 - 100.0 | A+ | 4.0 |
| 95.0 - 97.9 | A | 4.0 |
| 92.0 - 94.9 | A- | 4.0 |
| 89.0 - 91.9 | B+ | 3.75 |
| 86.0 - 88.9 | B | 3.5 |
| 83.0 - 85.9 | B- | 3.25 |
| 80.0 - 82.9 | C+ | 3.0 |
| 77.0 - 79.9 | C | 2.75 |
| 74.0 - 76.9 | C- | 2.5 |
| 71.0 - 73.9 | D+ | 2.25 |
| 70.0 - 70.9 | D | 2.0 |
| 60.0 - 69.9 | D- | 1.0 |
| Below 60.0 | F | 0.0 |

## Calculation Formulas

### Knowledge Average
```
quizAverage = (Q1 + Q2 + Q3 + Q4 + Q5) / 5
examAverage = (Prelim + Midterm + Final) / 3
Knowledge = (quizAverage × 0.40) + (examAverage × 0.60)
```

### Skills Average
```
outputAvg = (Output1 + Output2 + Output3) / 3
cpAvg = (CP1 + CP2 + CP3) / 3
actAvg = (Act1 + Act2 + Act3) / 3
assAvg = (Ass1 + Ass2 + Ass3) / 3
Skills = (outputAvg × 0.40) + (cpAvg × 0.30) + (actAvg × 0.15) + (assAvg × 0.15)
```

### Attitude Average
```
behaviorAvg = (Behavior1 + Behavior2 + Behavior3) / 3
awarenessAvg = (Awareness1 + Awareness2 + Awareness3) / 3
Attitude = (behaviorAvg × 0.50) + (awarenessAvg × 0.50)
```

### Midterm Grade
```
Midterm = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
```

### Overall Grade
```
Overall = (Midterm × 0.40) + (Final × 0.60)
```

## Files Modified/Created

### New Files
1. **Migration**: `database/migrations/2026_02_10_000001_restructure_grades_for_midterm_final.php`
   - Adds all new grading system fields

2. **View**: `resources/views/teacher/grades/entry_new.blade.php`
   - Comprehensive grade entry interface
   - Real-time calculation support
   - Professional table layout matching provided images

3. **Helper**: Methods added to `app/Helpers/GradeHelper.php`
   - `formatNewGradingBreakdown()` - Format grade breakdown
   - `getGradeStatusBadgeNew()` - Get grade status badges
   - `validateNewGradingData()` - Validate grade data
   - `generateNewGradingReport()` - Generate class reports

### Modified Files
1. **Model**: `app/Models/Grade.php`
   - Updated `$fillable` array with new fields
   - Updated `$casts` array with decimal casting for new fields
   - Added calculation methods:
     - `calculateKnowledgeAverage()`
     - `calculateSkillsAverage()`
     - `calculateAttitudeAverage()`
     - `calculateOverallGrade()`
     - `getLetterGrade()`
     - `getGradePoint()`

2. **Controller**: `app/Http/Controllers/TeacherController.php`
   - Added `showGradeEntryNew()` - Display new grade entry form
   - Added `storeGradesNew()` - Save new grades
   - Added `recalculateNewGradeScores()` - Calculate grades

3. **Routes**: `routes/web.php`
   - Added route: `teacher.grades.entry.new` → `/teacher/grades/entry-new/{classId}`
   - Added route: `teacher.grades.store.new` → `/teacher/grades/store-new/{classId}`

## Usage Instructions

### For Teachers

1. **Navigate to Grade Entry**
   - Go to Classes → Select Class → "Enter Grades (New System)"
   - Or directly access: `/teacher/grades/entry-new/{classId}`

2. **Enter Component Scores**
   - Enter examination scores (Preliminary, Midterm, Final)
   - Enter quiz scores (Q1-Q5)
   - Enter skills component scores (3 entries each)
   - Enter attitude component scores (3 entries each)

3. **Review Calculations**
   - Component averages auto-calculate
   - Midterm and Final grades calculate automatically
   - Overall grade based on 40%/60% weighting

4. **Save Grades**
   - Click "Save All Grades" to submit
   - System validates data before saving
   - Confirmation message shows successful save

### For Administrators

1. **Monitor Grade Entry Progress**
   - Check completion status in dashboard
   - View analytics for each class
   - Generate reports for stakeholders

2. **Generate Reports**
   - Use `GradeHelper::generateNewGradingReport()` to generate class statistics
   - Export grades for external reporting

## Backward Compatibility

The new system is additive and doesn't remove previous grading functionality:
- Old grade entry forms still work
- Existing grades remain intact
- Teachers can choose which system to use
- Smooth transition period available

## Troubleshooting

### Database Migration Fails
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback

# Run specific migration
php artisan migrate --path=database/migrations/2026_02_10_000001_restructure_grades_for_midterm_final.php
```

### Grades Not Calculating
- Ensure all Grade model methods are loaded
- Check Grade model fillable array includes new fields
- Verify cast definitions for decimal fields

### Route Not Found
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

## Support

For issues or questions:
1. Check error logs: `storage/logs/laravel.log`
2. Verify migration ran successfully: `php artisan migrate:status`
3. Test Grade model methods in tinker: `php artisan tinker`

---

**System Version**: 2.0 (Midterm/Final Structure)
**Last Updated**: February 10, 2026
