# Dynamic Grade Entry System - Implementation Guide

## 🎯 Overview

Your grade entry system has been transformed into a **fully dynamic, teacher-controlled platform** where teachers can:

✅ **Add/Delete Components** - Unlimited quizzes, outputs, activities, assignments, etc.
✅ **Adjust KSA Percentages** - Flexible Knowledge, Skills, Attitude weights per class/term
✅ **Set Custom Max Scores** - Each component can have different maximum scores
✅ **Lock Settings** - Freeze configuration during grading periods
✅ **Real-time Calculations** - Grades adapt to whatever components are configured

---

## 📦 What Was Created

### 1. Database Tables (Migration: `2026_03_17_100000_create_grade_components_table.php`)

#### `grade_components`
Stores dynamic assessment components (quizzes, outputs, etc.)
- `class_id` - Which class this component belongs to
- `term` - 'midterm' or 'final'
- `category` - 'knowledge', 'skills', or 'attitude'
- `component_type` - Type: 'quiz', 'exam', 'output', 'activity', etc.
- `name` - Display name: "Quiz 1", "Output 1", etc.
- `max_score` - Maximum possible score (default: 100)
- `weight_percentage` - Weight within category (e.g., Quiz 1 = 8% of Knowledge)
- `order` - Display order in grade entry table
- `is_active` - Enable/disable without deleting

#### `component_entries`
Stores individual student scores for each component
- `component_id` - Links to grade_components
- `student_id` - Links to students
- `raw_score` - Actual score student received
- `normalized_score` - Score converted to 100-point scale (auto-calculated)

#### `ksa_settings`
Stores flexible KSA percentages per class/term
- `class_id` - Which class
- `term` - 'midterm' or 'final'
- `knowledge_percentage` - % weight of Knowledge (default: 40%)
- `skills_percentage` - % weight of Skills (default: 50%)
- `attitude_percentage` - % weight of Attitude (default: 10%)
- `is_locked` - Lock settings during grading period

### 2. Models

#### `GradeComponent.php`
- Manages assessment components
- Auto-normalizes scores to 100-point scale
- Relationships: belongs to ClassModel, has many ComponentEntries

#### `ComponentEntry.php`
- Stores student scores
- Auto-calculates normalized_score when raw_score is saved
- Relationships: belongs to GradeComponent and Student

#### `KsaSetting.php`
- Manages KSA percentage distribution
- Validates percentages sum to 100%
- Default: K:40%, S:50%, A:10%

### 3. Controller

#### `GradeSettingsController.php`
Handles all grade settings operations:
- `index()` - Show settings page
- `updateKsaPercentages()` - Update K/S/A weights
- `addComponent()` - Add new quiz/output/etc.
- `updateComponent()` - Edit existing component
- `deleteComponent()` - Remove component
- `reorderComponents()` - Change display order
- `toggleLock()` - Lock/unlock settings
- `initializeDefaults()` - Create standard KSA components

### 4. View

#### `resources/views/teacher/grades/settings.blade.php`
Beautiful UI with:
- **KSA Sliders** - Adjust percentages with real-time visual feedback
- **Component Management** - Add/edit/delete components by category
- **Lock Feature** - Freeze settings during grading
- **Quick Actions** - Initialize defaults, toggle lock
- **Term Switcher** - Separate settings for midterm/final

### 5. Routes

```php
// Grade Settings Routes
Route::prefix('grades/settings')->name('grades.settings.')->group(function () {
    Route::get('/{classId}', [GradeSettingsController::class, 'index'])->name('index');
    Route::post('/{classId}/ksa', [GradeSettingsController::class, 'updateKsaPercentages'])->name('update-ksa');
    Route::post('/{classId}/component', [GradeSettingsController::class, 'addComponent'])->name('add-component');
    Route::put('/{classId}/component/{componentId}', [GradeSettingsController::class, 'updateComponent'])->name('update-component');
    Route::delete('/{classId}/component/{componentId}', [GradeSettingsController::class, 'deleteComponent'])->name('delete-component');
    Route::post('/{classId}/reorder', [GradeSettingsController::class, 'reorderComponents'])->name('reorder');
    Route::post('/{classId}/toggle-lock', [GradeSettingsController::class, 'toggleLock'])->name('toggle-lock');
    Route::post('/{classId}/initialize', [GradeSettingsController::class, 'initializeDefaults'])->name('initialize');
});
```

---

## 🚀 How to Use

### Step 1: Access Grade Settings

Navigate to: `/teacher/grades/settings/{classId}?term=midterm`

Or click the **"Configure Assessment"** button from:
- Teacher Dashboard (My Classes table)
- Grades page
- Grade Entry page

### Step 2: Initialize Default Components (First Time)

Click **"Initialize Default Components"** to create:

**Knowledge (40%):**
- Midterm/Final Exam (60% weight)
- Quiz 1-5 (8% each = 40% total)

**Skills (50%):**
- Output 1-3 (40% total)
- Class Participation 1-3 (30% total)
- Activities 1-3 (15% total)
- Assignments 1-3 (15% total)

**Attitude (10%):**
- Behavior 1-3 (50% total)
- Attendance 1-3 (30% total)
- Awareness 1-3 (20% total)

### Step 3: Customize Components

**Add More Components:**
1. Click **"Add Component"** button in any category
2. Enter component type, name, max score, and weight
3. Click **"Add Component"**

**Edit Components:**
1. Click edit icon on any component
2. Modify name, max score, or weight
3. Save changes

**Delete Components:**
1. Click trash icon on component
2. Confirm deletion
3. All student scores for that component will be deleted

### Step 4: Adjust KSA Percentages

1. Use the sliders to adjust Knowledge, Skills, Attitude percentages
2. Watch the visual progress bar update in real-time
3. Ensure total = 100% (badge turns green)
4. Click **"Save KSA Percentages"**

**Examples:**
- Traditional: K:40%, S:50%, A:10%
- Theory-heavy: K:60%, S:30%, A:10%
- Skills-focused: K:20%, S:70%, A:10%
- Balanced: K:33%, S:34%, A:33%

### Step 5: Lock Settings (Optional)

Once you start grading, click **"Lock Settings"** to prevent accidental changes.

Locked settings prevent:
- Adding/editing/deleting components
- Changing KSA percentages
- Reordering components

Unlock anytime to make changes.

### Step 6: Enter Grades

1. Click **"Grade Entry"** button
2. The grade entry table will dynamically show all configured components
3. Enter scores for each student
4. Grades calculate automatically using your custom KSA percentages

---

## 📊 How Calculations Work

### Formula

```
Final Grade = (K_avg × K%) + (S_avg × S%) + (A_avg × A%)
```

### Example with Custom Settings

**Configuration:**
- Knowledge: 50%
- Skills: 40%
- Attitude: 10%

**Student Scores:**
- Knowledge Average: 85
- Skills Average: 90
- Attitude Average: 95

**Calculation:**
```
Final Grade = (85 × 0.50) + (90 × 0.40) + (95 × 0.10)
            = 42.5 + 36 + 9.5
            = 88.0
```

### Component Weights

Each component's weight is a percentage of its category:

**Example - Knowledge Category:**
- Exam: 60% of Knowledge
- Quiz 1: 8% of Knowledge
- Quiz 2: 8% of Knowledge
- ...

**If Knowledge = 40% of final grade:**
- Exam contributes: 60% × 40% = 24% to final grade
- Each quiz contributes: 8% × 40% = 3.2% to final grade

---

## 🔄 Migration from Old System

### If you have existing grades in the old system:

1. **Keep both systems running** - Old grades remain in `grades` table
2. **Gradually migrate** - Start using new system for new terms
3. **Data migration script** (optional) - Contact developer to create migration script

### Old vs New Comparison

| Feature | Old System | New System |
|---------|-----------|------------|
| Quizzes | Fixed 5 | Unlimited ✨ |
| Max Scores | Fixed | Customizable ✨ |
| KSA % | Hard-coded 40:50:10 | Flexible ✨ |
| Components | Can't add/delete | Easy CRUD ✨ |
| Flexibility | None | Complete ✨ |
| Scalability | ~20 columns | Unlimited ✨ |

---

## 🛠️ Technical Details

### Auto-Normalization

All scores are automatically normalized to 100-point scale:

```php
normalized_score = (raw_score / max_score) × 100
```

**Example:**
- Quiz max score: 25 points
- Student score: 20 points
- Normalized: (20 / 25) × 100 = 80

This allows components with different max scores to be fairly compared.

### Database Indexes

Optimized for performance:
- `grade_components`: indexed on (class_id, term, category)
- `component_entries`: indexed on (student_id, component_id)
- `ksa_settings`: unique index on (class_id, term)

### Validation Rules

**KSA Percentages:**
- Each must be 0-100
- Sum must equal 100
- Validated on save

**Components:**
- Name: required, max 100 chars
- Max score: required, min 1
- Weight: required, 0-100
- Category: must be 'knowledge', 'skills', or 'attitude'

---

## 📱 Next Steps

### 1. Update Grade Entry View (TODO)

The current `grade_entry.blade.php` needs to be updated to:
- Fetch components from `grade_components` table
- Dynamically generate table columns
- Use `component_entries` for storing scores
- Calculate using flexible KSA percentages from `ksa_settings`

### 2. Update Grade Calculation Service (TODO)

Create/update service to:
- Calculate category averages from component entries
- Apply flexible KSA percentages
- Generate final grades
- Handle missing scores gracefully

### 3. Update Reports (TODO)

Modify grade reports to:
- Show dynamic components
- Display custom KSA percentages
- Adapt to whatever configuration is active

---

## 🎓 Teacher Training Guide

### Quick Start for Teachers

1. **First Time Setup** (5 minutes)
   - Go to Grade Settings
   - Click "Initialize Default Components"
   - Adjust KSA percentages if needed
   - Lock settings

2. **Adding Extra Quizzes** (30 seconds)
   - Go to Grade Settings
   - Unlock if locked
   - Click "Add Component" in Knowledge section
   - Enter: Type="quiz", Name="Quiz 6", Max=25, Weight=8
   - Lock settings again

3. **Changing KSA Weights** (1 minute)
   - Go to Grade Settings
   - Unlock if locked
   - Adjust sliders until total = 100%
   - Save changes
   - Lock settings

### Common Scenarios

**Scenario 1: "I want 10 quizzes instead of 5"**
1. Add Quiz 6, 7, 8, 9, 10 (each with weight ~4%)
2. Adjust existing quiz weights to balance to 40% total

**Scenario 2: "I don't use assignments"**
1. Delete Assignment 1, 2, 3 components
2. Redistribute weight to other Skills components

**Scenario 3: "My class is theory-heavy"**
1. Change KSA to K:60%, S:30%, A:10%
2. Save changes

---

## ✅ Status

- ✅ Database migration created
- ✅ Models created (GradeComponent, ComponentEntry, KsaSetting)
- ✅ Controller created (GradeSettingsController)
- ✅ View created (settings.blade.php)
- ✅ Routes added
- ⏳ Grade entry view needs update (next step)
- ⏳ Calculation service needs update (next step)
- ⏳ Reports need update (next step)

---

## 🚨 Important Notes

1. **Backup First** - Always backup database before major changes
2. **Test on Staging** - Test with sample class before production
3. **Lock During Grading** - Lock settings once students start taking assessments
4. **Communicate Changes** - Inform students of grading criteria
5. **Keep Records** - Document any KSA percentage changes

---

## 📞 Support

For questions or issues:
1. Check this documentation
2. Review code comments in controllers/models
3. Contact system administrator

---

**Version:** 1.0.0  
**Last Updated:** March 17, 2026  
**System:** EduTrack Dynamic Grade Entry
