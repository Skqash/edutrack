# 📊 Grading System Logic Correction

## Current Issue

The grading calculation logic is too simplistic and doesn't properly implement the detailed weighting requirements.

## Required Grading Structure

### Knowledge Component (40% of Total Grade)

**Breaking down to total grade percentages:**

- Quizzes: 40% of Knowledge = 16% of total grade
    - 5 quizzes equally distributed
    - Each quiz = 20% of quizzes portion = 3.2% of total grade
    - Each quiz item (if multiple items): divided further

- Exam: 60% of Knowledge = 24% of total grade
    - Prelim: varying by period
    - Midterm: varying by period
    - Final: varying by period

### Skills Component (50% of Total Grade)

**Breaking down to total grade percentages:**

- Output: 40% of Skills = 20% of total grade
    - 3 inputs (Prelim, Midterm, Final)
    - Each input = 1/3 × 40% of Skills = 6.67% of total grade

- Class Participation: 30% of Skills = 15% of total grade
    - 3 inputs (Prelim, Midterm, Final)
    - Each input = 1/3 × 30% of Skills = 5% of total grade

- Activities: 15% of Skills = 7.5% of total grade
    - 3 inputs (Prelim, Midterm, Final)
    - Each input = 1/3 × 15% of Skills = 2.5% of total grade

- Assignments: 15% of Skills = 7.5% of total grade
    - 3 inputs (Prelim, Midterm, Final)
    - Each input = 1/3 × 15% of Skills = 2.5% of total grade

### Attitude Component (10% of Total Grade)

**Breaking down to total grade percentages:**

- Behavior: 50% of Attitude = 5% of total grade

- Engagement: 50% of Attitude = 5% of total grade
    - Attendance: 60% of Engagement = 3% of total grade
    - Class Participation & Awareness: 40% of Engagement = 2% of total grade

## Calculation Formula

```
Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)

Where:

Knowledge = (Q_avg × 0.40) + (E_avg × 0.60)
  Q_avg = Average of 5 quizzes
  E_avg = Average of exam(s) relevant to term

Skills = (Output_avg × 0.40) + (CP_avg × 0.30) + (Act_avg × 0.15) + (Assign_avg × 0.15)
  Output_avg = Average of 3 output scores
  CP_avg = Average of 3 class participation scores
  Act_avg = Average of 3 activity scores
  Assign_avg = Average of 3 assignment scores

Attitude = (Behavior × 0.50) + (Engagement × 0.50)
  Engagement = (Attendance × 0.60) + (ClassParticipation_Awareness × 0.40)
```

## Files That Need Updates

1. **app/Models/Grade.php**
    - Update `calculateKnowledge()` to properly weight quizzes and exams
    - Update `calculateSkills()` to average 3 inputs per category
    - Update `calculateAttitude()` to include attendance weighting

2. **app/Http/Controllers/TeacherController.php**
    - Update grade calculation calls to pass proper data structure
    - Ensure 3-period-based inputs are averaged correctly

3. **Database/Migrations**
    - Ensure `assessment_ranges` table has period-based columns for each component
    - Add fields for attendance weighting if not present

4. **Views**
    - Update grade entry forms to clearly show the input structure (3 inputs per category)
    - Show weight percentages in tooltips/help text

## Implementation Steps

1. ✅ Identify current calculation logic (DONE)
2. ⏳ Update Grade model calculation methods
3. ⏳ Update TeacherController to use new logic
4. ⏳ Verify database has all required columns
5. ⏳ Update views to support period-based entries
6. ⏳ Test with sample data
7. ⏳ Document the changes
