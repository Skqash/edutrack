# Grade Entry Form - Quick Start Guide

## ✅ Completed Components

### 1. Excel-Style Form Redesign
- **File**: `resources/views/teacher/grades/grade_entry.blade.php`
- ✅ Hierarchical structure with MIDTERM (40%) and FINAL (60%)
- ✅ Three main components (Knowledge, Skills, Attitude)
- ✅ Real-time calculations
- ✅ Sticky student column
- ✅ Color-coded sections

### 2. Database Schema
- ✅ Migration file created: `2026_02_15_add_period_grades_to_grades_table.php`
- ✅ 68 new columns added to `grades` table
- ✅ All columns properly commented

### 3. Model Updates
- ✅ Grade model fillable array updated
- ✅ Casts configured for all new columns

### 4. Controller Logic
- ✅ `storeGradeEntryAdvanced()` method updated
- ✅ New computation methods (calculs per period)
- ✅ Grade 5-point scale conversion
- ✅ Remarks generation

### 5. JavaScript
- ✅ Dynamic real-time calculations
- ✅ Weight adjustment support
- ✅ Period-based computation
- ✅ Automatic averages

---

## How to Use

### Loading the Form
1. Teacher navigates to: **Grades → Grade Entry**
2. Select a class and grading scheme
3. Form loads with all students listed

### Entering Grades
1. **Left Side**: Student list with row numbers
2. **Midterm Section**: Enter preliminary (PR) and midterm (MD) exam scores
3. **Quizzes**: Enter Q1-Q5 scores (max 25 each)
4. **Skills Components**:
   - Output (3 entries)
   - Class Participation (3 entries)
   - Activities (3 entries)
   - Assignments (3 entries)
5. **Attitude Components**:
   - Behavior (3 entries)
   - Awareness (3 entries)
6. **Repeat for Final Period** with identical structure

### Real-Time Features
- ✅ Averages compute automatically
- ✅ No page reload needed
- ✅ Adjust weights and see recalculation
- ✅ All computed cells have gray background

### Computed Values
The following are **automatically calculated**:
- Exam averages
- Quiz averages
- Knowledge average
- Output/Class Part/Activities/Assignments averages
- Skills average
- Behavior/Awareness averages
- Attitude average
- Period grade
- Overall grade (Midterm 40% + Final 60%)
- 5-point scale conversion

---

## Form Layout

```
┌─────────────────────────────────────────────────────┐
│  Grade Entry Form                                   │
├──────────────────────────────────────────────────────┤
│ Grading Scheme: [Dropdown]                          │
│ Weights: Mid 40 / Final 60                          │
│ Component Weights: K 40 / S 50 / A 10              │
├──────────────────────────────────────────────────────┤
│                                                      │
│  No. │ Student │ MIDTERM (40%) │ FINAL (60%) │ OVR │
│  ──────────────────────────────────────────────────  │
│  1   │ Student │ [Grades...]   │ [Grades...] │ xx  │
│  2   │ Student │ [Grades...]   │ [Grades...] │ xx  │
│   ...                                          │
├──────────────────────────────────────────────────────┤
│                              [Save All Grades]      │
└──────────────────────────────────────────────────────┘
```

---

## Database Columns Created

### Midterm Period (36 columns)
```
mid_exam_pr, mid_exam_md          (Exam: Preliminary, Midterm)
mid_quiz_1 to mid_quiz_5          (5 quizzes)
mid_output_1, mid_output_2, mid_output_3
mid_classpart_1, mid_classpart_2, mid_classpart_3
mid_activity_1, mid_activity_2, mid_activity_3
mid_assignment_1, mid_assignment_2, mid_assignment_3
mid_behavior_1, mid_behavior_2, mid_behavior_3
mid_awareness_1, mid_awareness_2, mid_awareness_3
mid_knowledge_average, mid_skills_average, mid_attitude_average, mid_final_grade
```

### Final Period (36 columns)
```
Same structure with final_ prefix
```

### Overall (3 columns)
```
overall_grade, grade_5pt_scale, grade_remarks
```

---

## Calculation Formulas

### Knowledge (K)
```
Exam Ave = (PR + MD) / 2
Quiz Ave = (Q1 + Q2 + Q3 + Q4 + Q5) / 5
K_AVE = (Exam Ave × 60%) + (Quiz Ave × 40%)
```

### Skills (S)
```
Output_Ave = (Output1 + Output2 + Output3) / 3
CP_Ave = (CP1 + CP2 + CP3) / 3
Activity_Ave = (Act1 + Act2 + Act3) / 3
Assignment_Ave = (Asg1 + Asg2 + Asg3) / 3

S_AVE = (Output × 40%) + (CP × 30%) + (Activity × 15%) + (Assignment × 15%)
```

### Attitude (A)
```
Behavior_Ave = (Behavior1 + Behavior2 + Behavior3) / 3
Awareness_Ave = (Awareness1 + Awareness2 + Awareness3) / 3
A_AVE = (Behavior × 50%) + (Awareness × 50%)
```

### Period Grade
```
PERIOD_GRADE = (K_AVE × K%) + (S_AVE × S%) + (A_AVE × A%)
Where K%, S%, A% are the configured weights (default: 40%, 50%, 10%)
```

### Overall Grade
```
OVERALL = (MIDTERM × 40%) + (FINAL × 60%)
5-PT SCALE = 5.0 (90+), 4.0 (80-89), 3.0 (70-79), 2.0 (60-69), 1.0 (50-59), 0.0 (<50)
```

---

## Color Coding

- 🟢 **Green Section**: Midterm Period (40% weight)
- 🟢 **Green Section**: Final Period (60% weight)
- 🟩 **Light Blue**: Knowledge Component (40%)
- 🟧 **Light Orange**: Skills Component (50%)
- 🟦 **Light Blue**: Attitude Component (10%)
- **Gray Cells**: Computed/display only (not editable)
- **Sticky Column**: Student names stay visible when scrolling

---

## Testing the Form

```bash
# 1. Check database columns were added
php artisan tinker
DB::table('grades')->getColumns();

# 2. Run form locally
# Navigate to: http://localhost:8000/teacher/grades
```

---

## Future Enhancements

As noted, consider implementing a dynamic grading system with:
- `grading_components` table
- `grading_subcomponents` table
- Flexible component/subcomponent counts
- No need for hardcoded columns

This would allow schools to adjust:
- 5 quizzes → 6, 7, or custom number
- 3 outputs → 2, 4, or custom number
- Component weights dynamically

---

## Support

If you encounter issues:
1. Check browser console (F12) for JavaScript errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify migration ran: `php artisan migrate:status`
4. Clear cache: `php artisan cache:clear`

