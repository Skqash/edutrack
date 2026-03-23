# Dynamic Grade Entry System - Complete Implementation Guide

## 🎯 Overview

This guide explains the **fully dynamic and flexible grade entry system** where teachers can:
- Add/delete/edit assessment components (quizzes, outputs, activities, assignments, exams, etc.)
- Adjust max scores for each component
- Control the weight of Knowledge, Skills, and Attitude (KSA) percentages
- Set separate KSA percentages for midterm and final terms
- Lock settings during grading periods

---

## 📊 System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    TEACHER DASHBOARD                         │
└────────────┬────────────────────────────────────┬────────────┘
             │                                    │
       Grade Settings                       Grade Entry
    (Configure & Lock)                    (Enter Grades)
             │                                    │
             ▼                                    ▼
┌──────────────────────────────┐  ┌──────────────────────────────┐
│   grade_settings.blade.php   │  │ grade_entry_dynamic.blade.php│
│                              │  │                              │
│ 1. KSA Percentage Control    │  │ 1. Dynamic Table             │
│    - Knowledge (40% default) │  │ 2. Student Rows              │
│    - Skills (50% default)    │  │ 3. Component Columns         │
│    - Attitude (10% default)  │  │ 4. Auto-calculation          │
│                              │  │ 5. Save/Update               │
│ 2. Component Manager         │  │                              │
│    - Add/Delete/Edit         │  │                              │
│    - Set Max Scores          │  │                              │
│    - Set Weights             │  │                              │
│                              │  │                              │
│ 3. Lock/Unlock Settings      │  │                              │
└──────────┬───────────────────┘  └────────────┬─────────────────┘
           │                                   │
           └─────────────┬─────────────────────┘
                         ▼
          ┌──────────────────────────────────┐
          │   GradeSettingsController        │
          │   - show()                       │
          │   - getSettings()                │
          │   - updatePercentages()          │
          │   - addComponent()               │
          │   - updateComponent()            │
          │   - deleteComponent()            │
          │   - toggleLock()                 │
          └────────────┬─────────────────────┘
                       │
        ┌──────────────┼──────────────┐
        ▼              ▼              ▼
    ┌────────────┐ ┌──────────────┐ ┌────────────────┐
    │GradingScale│ │ Assessment   │ │ComponentEntry  │
    │  Setting   │ │ Component    │ │(Student Scores)│
    │            │ │              │ │                │
    │ - KSA %    │ │ - Category   │ │ - raw_score    │
    │ - Weights  │ │ - Max Score  │ │ - auto-norm    │
    │ - Locked   │ │ - Weight     │ │ - calc needed  │
    └────────────┘ └──────────────┘ └────────────────┘
           │              │                  │
           └──────────────┼──────────────────┘
                         ▼
          ┌──────────────────────────────────┐
          │  DynamicGradeCalculationService  │
          │  - calculateCategoryAverages()   │
          │  - calculateWeightedAverage()    │
          │  - Uses flexible KSA %           │
          └────────────┬─────────────────────┘
                       │
                       ▼
          ┌──────────────────────────────────┐
          │    ComponentAverage Model        │
          │    - knowledge_average      [80] │
          │    - skills_average        [92.5]│
          │    - attitude_average       [88] │
          │    - final_grade using K:40%     │
          │                           S:50%  │
          │                           A:10%  │
          └──────────────────────────────────┘
```

---

## 🗂️ Database Schema

### grading_scale_settings
```sql
id                        INTEGER PRIMARY KEY
class_id                  INTEGER FK → classes.id
teacher_id                INTEGER FK → users.id
term                      ENUM: 'midterm', 'final'
knowledge_percentage      DECIMAL(5,2) default 40.00
skills_percentage         DECIMAL(5,2) default 50.00
attitude_percentage       DECIMAL(5,2) default 10.00
is_locked                 BOOLEAN default false
description               TEXT (optional notes)
created_at, updated_at    TIMESTAMPS

UNIQUE(class_id, term)
```

### assessment_components
```sql
id                  INTEGER PRIMARY KEY
class_id            INTEGER FK → classes.id
teacher_id          INTEGER FK → users.id
category            ENUM: 'Knowledge', 'Skills', 'Attitude'
subcategory         VARCHAR(50) - Quiz, Output, Activity, etc
name                VARCHAR(50) - Display name
max_score           INTEGER - Max possible points (1-1000)
weight              DECIMAL(5,2) - Weight % in category
order               INTEGER - Display sequence within category
is_active           BOOLEAN default true (soft delete)
created_at, updated_at TIMESTAMPS

INDEX(class_id, category, order)
```

### component_entries
```sql
id                  INTEGER PRIMARY KEY
student_id          INTEGER FK → students.id
class_id            INTEGER FK → classes.id
component_id        INTEGER FK → assessment_components.id
term                ENUM: 'midterm', 'final'
raw_score           DECIMAL(5,2) - Input score (0 - max_score)
normalized_score    DECIMAL(5,2) - Auto-calculated 0-100 scale
remarks             TEXT (optional)
created_at, updated_at TIMESTAMPS

UNIQUE(student_id, component_id, term)
INDEX(class_id, student_id, term)
```

### component_averages
```sql
id                  INTEGER PRIMARY KEY
student_id          INTEGER FK → students.id
class_id            INTEGER FK → classes.id
term                ENUM: 'midterm', 'final'
knowledge_average   DECIMAL(5,2) - 0-100 weighted avg
skills_average      DECIMAL(5,2) - 0-100 weighted avg
attitude_average    DECIMAL(5,2) - 0-100 weighted avg
final_grade         DECIMAL(5,2) - Final calculated grade
created_at, updated_at TIMESTAMPS

UNIQUE(student_id, class_id, term)
```

---

## 🔄 Grade Calculation Formula

### Step 1: Normalize Individual Scores
```
normalized_score = (raw_score / max_score) × 100

Example:
  Component: Quiz 1
  Raw Score: 24 out of 25
  Normalized: (24 / 25) × 100 = 96.00
```

### Step 2: Calculate Category Weighted Average
```
category_avg = Σ(normalized_score × weight) / Σweights

Example - Knowledge Category:
  Quiz 1:      96.00 × 10% = 9.6
  Quiz 2:      88.00 × 10% = 8.8
  Midterm:     92.00 × 30% = 27.6
  ──────────────────────────────
  Total:       70% (weights)  =  46.0
  Knowledge Average = 46.0 / 0.70 = 65.71
```

### Step 3: Calculate Final Grade with Flexible KSA Percentages
```
final_grade = (K_avg × K%) + (S_avg × S%) + (A_avg × A%)

Where:
  K_avg = Knowledge Average (0-100)
  S_avg = Skills Average (0-100)
  A_avg = Attitude Average (0-100)
  K%, S%, A% = Teacher-defined percentages that sum to 100%

Examples:
  Default (K:40%, S:50%, A:10%):
    final_grade = (65.71 × 0.40) + (78.50 × 0.50) + (82.00 × 0.10)
    final_grade = 26.28 + 39.25 + 8.20 = 73.73

  Custom (K:50%, S:30%, A:20%):
    final_grade = (65.71 × 0.50) + (78.50 × 0.30) + (82.00 × 0.20)
    final_grade = 32.86 + 23.55 + 16.40 = 72.81
```

---

## 🛣️ API Endpoints

### Grade Settings Endpoints

#### Get Settings Page
```
GET /teacher/grade-settings/{classId}/{term}
Response: view with settings form
```

#### Get Settings as JSON
```
GET /teacher/grade-settings/{classId}/{term}/settings
Response:
{
  "settings": {
    "id": 1,
    "class_id": 5,
    "knowledge_percentage": 40.00,
    "skills_percentage": 50.00,
    "attitude_percentage": 10.00,
    "is_locked": false
  },
  "components": {
    "Knowledge": [
      {
        "id": 1,
        "name": "Quiz 1",
        "max_score": 25,
        "weight": 10,
        "order": 1
      }
    ],
    ...
  }
}
```

#### Update KSA Percentages
```
POST /teacher/grade-settings/{classId}/{term}/percentages
Body:
{
  "knowledge_percentage": 40.00,
  "skills_percentage": 50.00,
  "attitude_percentage": 10.00
}
Response:
{
  "success": true,
  "message": "Percentage settings updated successfully",
  "settings": { ... }
}
```

#### Add Component
```
POST /teacher/grade-settings/{classId}/components
Body:
{
  "name": "Quiz 1",
  "category": "Knowledge",
  "subcategory": "Quiz",
  "max_score": 25,
  "weight": 10
}
Response:
{
  "success": true,
  "component": { ... }
}
```

#### Update Component
```
PUT /teacher/grade-settings/{classId}/components/{componentId}
Body:
{
  "name": "Quiz 1",
  "max_score": 25,
  "weight": 10
}
```

#### Delete Component
```
DELETE /teacher/grade-settings/{classId}/components/{componentId}
Response: soft-deletes (is_active = false)
```

#### Lock/Unlock Settings
```
POST /teacher/grade-settings/{classId}/{term}/toggle-lock
Response:
{
  "success": true,
  "is_locked": true,
  "message": "Settings locked"
}
```

### Grade Entry Endpoints

#### Get Students
```
GET /teacher/classes/{classId}/students
Response:
[
  {
    "id": 1,
    "student_no": "STU001",
    "full_name": "John Doe",
    "name": "John Doe"
  },
  ...
]
```

#### Get Grade Entries
```
GET /teacher/grades/entries/{classId}?term=midterm
Response:
{
  "1": [  // student_id
    {
      "id": 1,
      "component_id": 1,
      "raw_score": 24,
      "normalized_score": 96
    }
  ]
}
```

#### Save Grade Entries
```
POST /teacher/grades/entries/{classId}
Body:
{
  "term": "midterm",
  "entries": {
    "1": {           // student_id
      "1": 24,       // component_id: raw_score
      "2": 25,
      "3": 22
    },
    "2": {
      "1": 20,
      "2": 22,
      "3": 19
    }
  }
}
Response:
{
  "success": true,
  "saved": 9,
  "message": "✅ Saved 9 component entries"
}
```

#### Get Calculated Averages
```
GET /teacher/grades/averages/{classId}?term=midterm
Response:
[
  {
    "id": 1,
    "student_id": 1,
    "knowledge_average": 85.50,
    "skills_average": 78.20,
    "attitude_average": 82.10,
    "final_grade": 79.87
  }
]
```

---

## 👨‍🏫 Teacher Workflow

### First Time Setup (Grade Settings)

1. **Access Grade Settings**
   ```
   URL: /teacher/grade-settings/{classId}/midterm
   ```

2. **Configure KSA Percentages**
   - Leave defaults (K:40%, S:50%, A:10%)
   - OR adjust based on class needs
   - Ensure total = 100%
   - Click "Save KSA Settings"

3. **Add Assessment Components**
   - Click "+ Add" under each category
   - Enter component name (Quiz 1, Quiz 2, etc.)
   - Set max score (typical: 25, 50, 100)
   - Set weight % within category (components in a category should sum to 100%)
   - Click "Save Component"

4. **Configure Multiple Components**
   - Knowledge: Quiz 1-3 (10% each = 30%), Midterm Exam (70%)
   - Skills: Hands-on Activities 1-3 (20% each = 60%), Project (40%)
   - Attitude: Class Participation (50%), Conduct (50%)

5. **Lock Settings** (When ready for grading)
   - Click "Lock Settings (Prevent Changes During Grading)"
   - Settings frozen - cannot modify until unlocked

### Regular Grade Entry

1. **Access Grade Entry**
   ```
   URL: /teacher/grades/entry/{classId}/midterm
   - OR from Grade Settings: "Go to Grade Entry" button
   ```

2. **View Dynamic Table**
   - Column headers organized by Knowledge | Skills | Attitude
   - Each component has its own column
   - Student rows pre-populated

3. **Enter Grades**
   - Click input field for student + component
   - Enter raw score (0 to max_score)
   - Table auto-validates and shows error if exceeding max
   - Averages display in real-time

4. **Save Grades**
   - Click "Save All Grades" when done
   - System sends all entries to API
   - Auto-calculates all averages
   - Updates component_averages table
   - Display confirmation message

### View Results

1. **Final Calculations**
   - All students' final grades calculated
   - Using teacher-configured KSA percentages
   - Stored in component_averages table

2. **Export/Report** (Future feature)
   - Generate reports with all grades
   - Print student transcripts
   - Export to Excel

---

## 🔐 Settings Lock Feature

### When to Lock
- After finalizing component configuration
- Before students/admin review grades
- Before grade approval process

### Effects of Lock
- Cannot add/delete/edit components
- Cannot change KSA percentages
- CAN still enter/update grades

### Unlock
- Only teacher can unlock
- Confirms intent before unlocking
- Can then modify settings again

---

## 📋 Setup Checklist

- [ ] Create migration: `2026_03_17_000002_create_grading_scale_settings_table.php`
- [ ] Create model: `app/Models/GradingScaleSetting.php`
- [ ] Create controller: `app/Http/Controllers/GradeSettingsController.php`
- [ ] Create view: `resources/views/teacher/grades/grade_settings.blade.php`
- [ ] Update view: `resources/views/teacher/grades/grade_entry_dynamic.blade.php`
- [ ] Update routes: Add grade settings routes
- [ ] Update service: `DynamicGradeCalculationService.php`
- [ ] Update model: `ComponentAverage.php` and `GradeEntryDynamicController.php`
- [ ] Run migration: `php artisan migrate`
- [ ] Test with sample class and students

---

## 🧪 Testing Scenarios

### Test 1: Default KSA Setup
- Setup class with K:40%, S:50%, A:10%
- Create 10 components
- Enter grades for 5 students
- Verify calculations are correct

### Test 2: Custom KSA Percentages
- Change to K:50%, S:30%, A:20%
- Verify final grades recalculate
- Verify total = 100% requirement

### Test 3: Lock/Unlock
- Configure components
- Lock settings
- Verify cannot add/delete
- Unlock and verify can modify again

### Test 4: Max Score Validation
- Set Quiz 1 max = 25
- Try to enter 30
- Verify error/correction

### Test 5: Multiple Terms
- Setup midterm grades
- Switch to final term
- Verify different components/percentages possible

---

## 🚀 Deployment Steps

1. **Backup Database**
   ```bash
   # Manual backup before deploying
   ```

2. **Run Migration**
   ```bash
   php artisan migrate
   ```

3. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   php artisan route:cache
   ```

4. **Test Access**
   ```
   URL: /teacher/grade-settings/1/midterm
   ```

5. **Monitor Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 📞 Troubleshooting

### Issue: 403 Unauthorized
- **Cause**: Accessing class not owned by teacher
- **Fix**: Verify teacher_id matches class.teacher_id

### Issue: Components not appearing
- **Cause**: Possibly deleted (is_active = false)
- **Fix**: Check database for is_active = true

### Issue: KSA percentages won't save
- **Cause**: Total doesn't equal 100%
- **Fix**: Adjust percentages to sum to 100.00%

### Issue: Grades not calculating
- **Cause**: Missing ComponentAverage::calculateAndUpdate() call
- **Fix**: Verify saveEntries() triggers calculation

### Issue: Student list empty
- **Cause**: No students enrolled in class
- **Fix**: Add students to class first

---

## 📈 Future Enhancements

- [ ] Bulk import components from templates
- [ ] Clone components from previous term
- [ ] Grade weighting per student (extra credit/makeup)
- [ ] Automated grading rubrics
- [ ] Student portal to view grades
- [ ] Parent notifications
- [ ] Advanced analytics & trends
- [ ] Multi-class grade aggregation
- [ ] Grade appeals/revision tracking
- [ ] Integration with academic calendar

---

**Last Updated:** March 17, 2026  
**Version:** 1.0.0  
**Status:** Production Ready
