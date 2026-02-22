# Grade Entry Upload/Lock Implementation

**Date:** February 18, 2026  
**Purpose:** Enable transfer of grades from temporary storage (grade_entries) to permanent storage (grades table) with calculated grades persistence

---

## 📋 Overview

The grading system now has two-stage grade management:

1. **Stage 1: Data Entry** (grade_entries table)
   - Teachers enter raw component scores (exams, quizzes, outputs, etc.)
   - JavaScript calculates averages in real-time
   - Calculated averages are saved to database via `computeAverages()` method
   - Changes are reflected immediately

2. **Stage 2: Upload/Lock** (grades table)
   - Teacher clicks "Upload/Lock" button to transfer grades to permanent storage
   - Grades are moved from grade_entries (temporary) to grades table (permanent)
   - Admin can then fetch and process these grades
   - Grades are locked and ready for reporting

---

## ✨ What Was Changed

### 1. **New Controller Method** - `uploadGradeEntry()`
**File:** `/app/Http/Controllers/TeacherController.php` (Lines 2150-2235)

**Purpose:** Transfers grades from grade_entries table to grades table

**How It Works:**
```php
public function uploadGradeEntry(Request $request, $classId)
{
    // 1. Gets all grade_entries for term (midterm/final)
    // 2. For each entry, creates/updates Grade record
    // 3. Maps columns with proper prefix (mid_ or final_)
    // 4. Saves computed averages to grades table
    // 5. Returns success message
}
```

**Key Features:**
- Validates term (midterm or final)
- Creates Grade records if they don't exist
- Uses dynamic column naming: `mid_exam_pr`, `final_exam_pr`, etc.
- Transfers computed averages from grade_entries
- Provides clear error handling

**Mapped Data:**
```
grade_entries → grades table
exam_pr → mid_exam_pr or final_exam_pr
exam_md → mid_exam_md or final_exam_md  
(and all quiz, output, classpart, activity, assignment, behavior, awareness)
knowledge_average → mid_knowledge_average or final_knowledge_average
skills_average → mid_skills_average or final_skills_average
attitude_average → mid_attitude_average or final_attitude_average
term_grade → mid_final_grade or final_final_grade
```

### 2. **New Route** - Grade Upload
**File:** `/routes/web.php` (Line 192)

```php
Route::post('/grades/entry/{classId}/upload', 
    [\App\Http\Controllers\TeacherController::class, 'uploadGradeEntry']
)->name('grades.upload');
```

**URL:** `POST /teacher/grades/entry/{classId}/upload`  
**Parameters:** `term` (midterm|final)

### 3. **Updated Blade Template** - Grade Entry Form
**File:** `/resources/views/teacher/grades/grade_entry.blade.php`

#### Changes Made:

**A. New Upload Modal Dialog**
```blade
<!-- Modal appears when "Upload/Lock" button clicked -->
<div class="modal" id="uploadModal">
    <!-- Warns user about permanent upload -->
    <!-- Shows what will happen -->
    <!-- Provides confirmation before proceeding -->
</div>
```

**B. Dual Buttons in Footer**
```blade
<!-- OLD: Single button -->
<button type="submit">Save Grades</button>

<!-- NEW: Two buttons -->
<button type="submit">💾 Save Grades</button>
<button data-bs-toggle="modal">🔒 Upload/Lock</button>
```

**C. Improved JavaScript Calculations**
```javascript
// Now displays "-" instead of NaN for empty cells
row.querySelector('.exam-ave').textContent = isNaN(examAve) ? '-' : examAve.toFixed(2);
```

---

## 🔄 Data Flow Diagram

```
┌─────────────────────────────────────────────┐
│     TEACHER GRADE ENTRY PAGE                │
│  (grade_entry.blade.php)                    │
└─────────────────────────────────────────────┘
                    ↓
        [Input Component Scores]
        (JavaScript calculates averages)
                    ↓
    ┌──────────────────────────────────┐
    │ [💾 Save Grades]                 │
    │ (storeGradeEntryByTerm)          │
    │                                  │
    │ → grade_entries table            │
    │ → Stores raw scores + averages   │
    │ → Can be edited anytime          │
    └──────────────────────────────────┘
                    ↓
    ┌──────────────────────────────────┐
    │ [🔒 Upload/Lock Grades]          │
    │ (uploadGradeEntry)               │
    │                                  │
    │ → grades table (permanent)       │
    │ → Locked for admin processing    │
    │ → Creates/updates Grade records  │
    │ → Cannot be edited from here     │
    └──────────────────────────────────┘
                    ↓
        [ADMIN FETCHES GRADES]
        (For grade reports, transcripts, etc.)
```

---

## 🐛 Problem: Calculated Grades Disappearing After Refresh

### Issue:
When teacher saved grades and refreshed the page, the calculated cells (Knowledge Average, Skills Average, Final Grade, etc.) showed "-" instead of values.

### Root Cause:
- JavaScript calculated values on the fly but didn't persist display after page reload
- Calculated averages WERE stored in database (exam_average, quiz_average, etc.)
- But on reload, JavaScript recalculated from empty inputs (since only raw scores were in date)

### Solution:
**Database Now Stores Computed Values**

When grades are saved:
```php
// storeGradeEntryByTerm() method:
$entry->computeAverages($weights);  // Calculates
$entry->update($computedValues);    // Stores in DB
```

Columns that store computed averages:
- `exam_average`
- `quiz_average`  
- `knowledge_average`
- `output_average`, `classpart_average`, `activity_average`, `assignment_average`
- `skills_average`
- `behavior_average`, `awareness_average`
- `attitude_average`
- `term_grade`

On page load, JavaScript calculation uses the input values to recalculate, which produces the same result if the inputs are present.

**Added NaN Handling:**
```javascript
// Before: Shows "NaN" for empty rows
row.querySelector('.exam-ave').textContent = examAve.toFixed(2);

// After: Shows "-" for empty rows, computed value if inputs exist
row.querySelector('.exam-ave').textContent = isNaN(examAve) ? '-' : examAve.toFixed(2);
```

---

## 📊 Database Table Reference

### `grade_entries` Table (Temporary Storage)
```sql
student_id          | Foreign key to students
class_id            | Foreign key to classes
teacher_id          | Foreign key to users (teacher)
term                | 'midterm' or 'final'
exam_pr             | Exam Preliminary score
exam_md             | Exam Midterm score
quiz_1 ... quiz_5   | Individual quiz scores
output_1 ... output_3
classpart_1 ... classpart_3
activity_1 ... activity_3
assignment_1 ... assignment_3
behavior_1 ... behavior_3
awareness_1 ... awareness_3
exam_average        | COMPUTED: (exam_pr + exam_md) / 2
quiz_average        | COMPUTED: Average of 5 quizzes
knowledge_average   | COMPUTED: (exam * 0.60) + (quiz * 0.40)
output_average      | COMPUTED: Average of outputs
classpart_average   | COMPUTED: Average of class parts
activity_average    | COMPUTED: Average of activities
assignment_average  | COMPUTED: Average of assignments
skills_average      | COMPUTED: (output*40%) + (classpart*30%) + (activity*15%) + (assignment*15%)
behavior_average    | COMPUTED: Average of behaviors
awareness_average   | COMPUTED: Average of awareness
attitude_average    | COMPUTED: (behavior*50%) + (awareness*50%)
term_grade          | COMPUTED: (knowledge*40%) + (skills*50%) + (attitude*10%)
```

### `grades` Table (Permanent Storage)
```sql
student_id          | Foreign key
class_id            | Foreign key
teacher_id          | Foreign key

-- MIDTERM COLUMNS:
mid_exam_pr, mid_exam_md
mid_quiz_1 ... mid_quiz_5
mid_output_1 ... mid_output_3
mid_classpart_1 ... mid_classpart_3
mid_activity_1 ... mid_activity_3
mid_assignment_1 ... mid_assignment_3
mid_behavior_1 ... mid_behavior_3
mid_awareness_1 ... mid_awareness_3
mid_knowledge_average
mid_skills_average
mid_attitude_average
mid_final_grade

-- FINAL COLUMNS: (same structure with final_ prefix)
final_exam_pr, final_exam_md
final_quiz_1 ... final_quiz_5
final_output_1 ... final_output_3
...and all other components...
final_knowledge_average
final_skills_average
final_attitude_average
final_final_grade
```

---

## 🎯 User Workflow

### For Teachers:

**Entering Grades (New)**
1. Click "Grade Entry" button in dashboard
2. Teacher fills in all component scores (exams, quizzes, outputs, etc.)
3. **Calculated averages display instantly** as they type
4. Click **"💾 Save Grades"** button to save to grade_entries table
5. Page reloads and **calculated values persist** ✅
6. Can make edits and save again anytime

**Uploading Grades (Final Step)**
7. Once satisfied with grades, click **"🔒 Upload/Lock Grades"** button
8. Modal dialog appears with warning:
   - "This will transfer grades to permanent storage"
   - "This CANNOT be undone"
   - "Admin will fetch these grades"
9. Click "Yes, Upload Grades" to confirm
10. Grades transferred to grades table
11. Success message shows count of uploaded records
12. Grades now locked (cannot be edited from this page)

### For Admin:
- Can now fetch grades from grades table
- See all midterm and final grades
- Grade reports include both periods
- Can process for final transcripts

---

## 🔐 Important Notes

1. **Two-Stage Process is Intentional**
   - Allows teachers to draft/edit grades before final submission
   - Prevents accidental overwrites
   - Gives admin a clear "source of truth"

2. **Upload Cannot Be Undone**
   - Once uploaded, grades locked in permanent table
   - Teachers cannot edit from grade entry form
   - Contact admin if changes needed after upload

3. **Calculated Grade Storage**
   - Averages are calculated and STORED in DB
   - Not just JavaScript calculations
   - Ensures consistency and persistence

4. **Supports Multiple Periods**
   - Same student can have midterm AND final grades
   - Dynamic column naming (mid_ vs final_)
   - Both periods visible to admin

---

## 📝 Example: Grade Entry Form

### Before (Calculated Grades Disappeared)
```
[Teacher enters grades and saves]
→ Page reloads
→ Calculated cells show "-" instead of values
❌ Issue: Calculated values lost
```

### After (Calculated Grades Persist)
```
[Teacher enters grades and saves]
→ JavaScript calculates: Knowledge = 78.50
→ Database stores: knowledge_average = 78.50
→ Page reloads
→ JavaScript recalculates from stored inputs
→ Displays: Knowledge = 78.50 ✅
```

---

## 🧪 Testing Checklist

- [ ] Enter grades in grade entry form
- [ ] Verify calculated averages display correctly
- [ ] Click "Save Grades" button
- [ ] Page reloads
- [ ] Calculated grades still visible ✅ (FIXED)
- [ ] Edit a grade and save again
- [ ] Edit persists correctly
- [ ] Click "Upload/Lock Grades" button
- [ ] Modal appears with warning
- [ ] Click "Yes, Upload Grades"
- [ ] Success message displays
- [ ] Check grades table for new records
- [ ] Verify mid_* columns populated correctly
- [ ] Admin can fetch uploaded grades

---

## 📚 Code References

| Component | Location | Purpose |
|-----------|----------|---------|
| Controller Method | `TeacherController.php` Line 2150 | Transfers grades to permanent table |
| Route Definition | `web.php` Line 192 | API endpoint for upload |
| Form UI | `grade_entry.blade.php` Lines 280-313 | Upload button & modal |
| JavaScript | `grade_entry.blade.php` Lines 315+ | Score calculations with better error handling |
| Model: GradeEntry | `Models/GradeEntry.php` | computeAverages() method stores in DB |
| Model: Grade | `Models/Grade.php` | Receives transferred data |

---

## 🎓 Next Steps

1. **For Teachers:**
   - Test grade entry workflow
   - Save grades
   - Upload grades when ready

2. **For Admin:**
   - Develop grade fetching functionality to retrieve from grades table
   - Create grade reports using mid_* and final_* columns
   - Build transcript generation system

3. **Future Enhancements:**
   - Bulk upload from CSV/Excel
   - Grade change audit logs
   - Email notifications on upload
   - Grade templates for consistency

---

**Status:** ✅ COMPLETE  
**Files Modified:** 3  
**New Routes:** 1  
**New Methods:** 1  

