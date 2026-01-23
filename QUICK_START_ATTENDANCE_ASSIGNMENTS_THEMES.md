# Quick Start Guide - New Features (Attendance, Assignments, Themes)

## Prerequisites

1. Run migrations: `php artisan migrate`
2. System already running with Laravel 11

## Feature Overview

### 1. Attendance Management

**Path**: Teacher Dashboard → Attendance

**How to Use**:

1. Click "Attendance" in sidebar or menu
2. Select a class from the list
3. Select a date (defaults to today)
4. For each student, select their status:
    - ✓ Present
    - ✗ Absent
    - ⏱ Late
    - ⛔ Leave
5. Click "Save Attendance"

**Key Points**:

- Attendance is tracked daily per class
- Cannot edit future dates
- Each student-class-date combination is unique

---

### 2. Assignment Management

**Path**: Teacher Dashboard → Assignments

**Creating an Assignment**:

1. Click "Assignments" in sidebar or menu
2. Select a class from the list
3. Click "Create Assignment" button
4. Fill in:
    - **Title**: Assignment name (e.g., "Chapter 5 Math Homework")
    - **Description**: Detailed assignment information
    - **Instructions**: How to submit (optional)
    - **Due Date**: When assignment is due
    - **Max Score**: Total points (e.g., 100)
    - **Term**: Midterm or Final
5. Click "Create Assignment"

**Grading Submissions**:

1. Go to assignment list for a class
2. Click "Grade" on any assignment
3. See all student submissions
4. For each student, click "Grade"
5. Enter:
    - **Score**: Points earned (0 to max score)
    - **Feedback**: Comments for student (optional)
6. Click "Save Score"

**Editing/Deleting**:

- Click "Edit" to modify assignment details
- Click "Delete" to remove assignment (careful!)

**Key Points**:

- Assignments are tied to specific classes
- Supports midterm and final terms
- Late submissions are auto-detected
- Scores can be updated anytime

---

### 3. Theme System

**Path**: User Menu (top-right) → Settings

**Changing Your Theme**:

1. Click your user name/avatar in top-right corner
2. Select "Settings"
3. Scroll to "Appearance - Theme Selection"
4. Click on any of the 5 theme cards:
    - 🤍 **Light** - Clean, bright, professional
    - 🖤 **Dark** - Easy on eyes, dark background
    - 🔵 **Ocean Blue** - Professional blue theme
    - 🟢 **Forest Green** - Natural green theme
    - 🟠 **Sunset Orange** - Warm orange theme
5. Click "Save Settings" at bottom
6. Theme changes immediately across all pages

**Theme Persistence**:

- Your theme choice is saved to your account
- It loads automatically when you log in
- Works across all devices

---

## Database Migrations

Run these migrations to create necessary tables:

```bash
php artisan migrate
```

This will:

1. Add `theme` column to users table
2. Create `assignments` table
3. Create `assignment_submissions` table

---

## Routes Added

### Attendance

```
GET    /attendance                 → attendance()
GET    /attendance/manage/{id}     → manageAttendance()
POST   /attendance/record/{id}     → recordAttendance()
```

### Assignments

```
GET    /assignments               → assignments()
GET    /assignments/list/{id}     → listAssignments()
GET    /assignments/create/{id}   → createAssignment()
POST   /assignments/store/{id}    → storeAssignment()
GET    /assignments/edit/{id}/{id}→ editAssignment()
POST   /assignments/update/{id}/{id}→ updateAssignment()
DELETE /assignments/delete/{id}/{id}→ deleteAssignment()
GET    /assignments/grade/{id}/{id}→ gradeAssignments()
POST   /assignments/score/{id}/{id}/{id}→ submitAssignmentScore()
```

### Settings

```
GET    /settings                  → settings.index
POST   /settings/update           → settings.update
POST   /settings/theme            → settings.changeTheme
```

---

## Key Files Created

### Controllers

- `app/Http/Controllers/SettingsController.php` - Theme and settings
- `app/Http/Controllers/TeacherController.php` - Updated with 11 new methods

### Models

- `app/Models/Assignment.php` - Assignment model
- `app/Models/AssignmentSubmission.php` - Submission tracking
- `app/Models/Attendance.php` - Already existed, used for attendance

### Views

- `resources/views/settings/index.blade.php` - Settings page
- `resources/views/teacher/attendance/manage.blade.php` - Attendance form
- `resources/views/teacher/assignments/list.blade.php` - Assignment list
- `resources/views/teacher/assignments/create.blade.php` - Create form
- `resources/views/teacher/assignments/edit.blade.php` - Edit form
- `resources/views/teacher/assignments/grade.blade.php` - Grading interface

### Themes (CSS)

- `public/css/themes/light.css`
- `public/css/themes/dark.css`
- `public/css/themes/ocean.css`
- `public/css/themes/forest.css`
- `public/css/themes/sunset.css`

---

## Troubleshooting

### Q: Theme not applying

**A**: Clear browser cache (Ctrl+Shift+Delete) and refresh page

### Q: Attendance not saving

**A**:

- Verify all students are selected
- Check that date is valid (not future)
- Ensure you're a teacher for this class

### Q: Can't see assignments

**A**:

- Make sure you have a class assigned
- Check that you're logged in as teacher
- Run migrations: `php artisan migrate`

### Q: "Class not found" error

**A**: You don't own this class. Only see classes assigned to you.

---

## Feature Highlights

✅ **Attendance**

- Daily tracking per student
- 4 status options
- Date-based records
- Easy bulk entry

✅ **Assignments**

- Full CRUD (Create, Read, Update, Delete)
- Score tracking
- Feedback comments
- Late submission detection
- Supports midterm/final terms

✅ **Themes**

- 5 beautiful themes
- Persists across sessions
- Easy switching in settings
- Professional color palettes

---

## System Requirements

- Laravel 11.x
- PHP 8.1+
- Bootstrap 5
- Font Awesome 6.4+

---

## Support

For issues or questions:

1. Check the full documentation: `ATTENDANCE_ASSIGNMENTS_THEMES_COMPLETE.md`
2. Review error messages carefully
3. Verify migrations have run: `php artisan migrate:status`

---

## Next Steps

1. **Run Migrations**: `php artisan migrate`
2. **Clear Cache**: `php artisan cache:clear`
3. **Test Attendance**: Create attendance for a class
4. **Test Assignments**: Create and grade an assignment
5. **Test Themes**: Change to different themes

---

Created: January 25, 2026
System: EduTrack v1.0
Status: ✅ Production Ready
