# Teacher Module Manual Testing Guide
**Quick Reference for UI & Function Testing**

---

## Prerequisites
1. Login as a teacher user
2. Ensure you have at least one class assigned
3. Have some students enrolled in your class

---

## 1. Dashboard Testing

### Access
- Navigate to: `/teacher/dashboard`
- Route: `teacher.dashboard`

### What to Check
- [ ] Dashboard loads without errors
- [ ] Statistics display correctly (Total Classes, Total Students, Grades Posted)
- [ ] Recent activities show up
- [ ] My Classes section displays your classes
- [ ] My Courses section shows assigned courses
- [ ] Pending tasks notification works
- [ ] Quick action buttons functional

### Expected Behavior
- All counts should be accurate
- Links should navigate to correct pages
- No console errors in browser

---

## 2. My Classes Testing

### Access
- Navigate to: `/teacher/classes`
- Route: `teacher.classes`

### What to Check
- [ ] All your classes are listed
- [ ] Class cards show: Name, Course, Student Count
- [ ] "View Details" button works
- [ ] "Create New Class" button accessible
- [ ] Campus isolation working (only see your campus classes)
- [ ] Pagination works if you have many classes

### Test Actions
1. Click "View Details" on a class
2. Verify class details page loads
3. Check student list displays
4. Test "Edit Class" button
5. Test "Delete Class" button (with confirmation)

---

## 3. My Subjects Testing

### Access
- Navigate to: `/teacher/subjects`
- Route: `teacher.subjects`

### What to Check
- [ ] Assigned subjects display
- [ ] Course grouping works
- [ ] Student counts per course accurate
- [ ] "Request New Subject" button works
- [ ] "Create Independent Subject" button works
- [ ] Subject removal works

### Test Actions
1. Click "Request New Subject"
2. Fill out form and submit
3. Verify request appears in pending
4. Try creating an independent subject
5. Test removing a subject from your list

---

## 4. Grades Management Testing

### Access
- Navigate to: `/teacher/grades`
- Route: `teacher.grades`

### What to Check
- [ ] Class list displays
- [ ] "Enter Grades" button for each class
- [ ] "Grade Settings" button accessible
- [ ] Term selector works (Midterm/Final)

### Test Grade Entry
1. Click "Enter Grades" for a class
2. Verify grade entry form loads
3. Check all students are listed
4. Test entering scores in components
5. Verify auto-calculation works
6. Test "Save Grades" button
7. Check success message appears

---

## 5. Grade Settings Testing

### Access
- Navigate to: `/teacher/grades/settings/{classId}`
- Route: `teacher.grades.settings.index`

### What to Check - KSA Percentages
- [ ] Current KSA percentages display
- [ ] Knowledge weight slider/input works
- [ ] Skills weight slider/input works
- [ ] Attitude weight slider/input works
- [ ] Sum validation (must equal 100%)
- [ ] "Update KSA Percentages" button works
- [ ] Success message after update

### Test KSA Update
1. Change Knowledge to 50%
2. Change Skills to 30%
3. Change Attitude to 20%
4. Click "Update KSA Percentages"
5. Verify success message
6. Refresh page and verify changes persist

### What to Check - Components
- [ ] Components grouped by category (Knowledge, Skills, Attitude)
- [ ] Each component shows: Name, Max Score, Weight
- [ ] "Add Component" button works
- [ ] "Edit Component" button works
- [ ] "Delete Component" button works
- [ ] Component reordering works (drag & drop)

### Test Component CRUD

#### CREATE
1. Click "Add Component"
2. Select category (e.g., Knowledge)
3. Select subcategory (e.g., Quiz)
4. Enter name (e.g., "Quiz 1")
5. Enter max score (e.g., 50)
6. Enter weight (e.g., 10%)
7. Click "Save"
8. Verify component appears in list

#### UPDATE
1. Click "Edit" on a component
2. Change name to "Updated Quiz 1"
3. Change weight to 15%
4. Click "Update"
5. Verify changes appear

#### DELETE
1. Click "Delete" on a component
2. Confirm deletion
3. Verify component removed from list

### Test Component Weights
1. Create multiple components in same category
2. Verify weights can be adjusted
3. Check that weight changes affect calculations
4. Test that weights within category can sum to any value (not restricted to 100%)

---

## 6. Attendance Configuration Testing

### Access
- Navigate to: `/teacher/attendance/settings` or via Grade Settings
- Route: `teacher.attendance.settings`

### What to Check
- [ ] Total meetings input works
- [ ] Attendance weight percentage input works
- [ ] Attendance category selector works (Knowledge/Skills/Attitude)
- [ ] "Update Attendance Settings" button works
- [ ] Settings persist after update

### Test Attendance Settings
1. Set total meetings to 20
2. Set attendance weight to 10%
3. Select category (e.g., Attitude)
4. Click "Update"
5. Verify success message
6. Check that attendance now affects selected category

### Test Attendance Calculation
1. Go to attendance management
2. Mark some students present/absent
3. Go back to grades
4. Verify attendance score calculated correctly
5. Formula: `(attendance_count / total_meetings) × 50 + 50`
6. Example: 10/20 meetings = 50% → (0.5 × 50) + 50 = 75 score

---

## 7. Advanced Grade Entry Testing

### Access
- Navigate to: `/teacher/grades/advanced/{classId}`
- Route: `teacher.grades.advanced`

### What to Check
- [ ] All components display in columns
- [ ] All students display in rows
- [ ] Input fields for each student-component combination
- [ ] Auto-save functionality works
- [ ] Real-time calculation updates
- [ ] Category averages display
- [ ] Final grade displays
- [ ] Term selector works

### Test Grade Entry
1. Enter a score for a student in a component
2. Verify normalized score calculates automatically
3. Check category average updates
4. Verify final grade updates
5. Test entering scores for multiple students
6. Refresh page and verify scores persist

### Test Calculations
**Example Test Case:**
- Student: John Doe
- Knowledge Components:
  - Exam: 85/100 → Normalized: 92.5 (weight 60%)
  - Quiz 1: 20/25 → Normalized: 90 (weight 40%)
- Knowledge Average: (92.5 × 0.6) + (90 × 0.4) = 55.5 + 36 = 91.5

**Verify:**
- [ ] Normalized scores correct
- [ ] Weighted averages correct
- [ ] Final grade correct based on KSA percentages

---

## 8. Settings & Profile Testing

### Profile Testing
- Navigate to: `/teacher/profile`
- Route: `teacher.profile.show`

#### What to Check
- [ ] Profile information displays
- [ ] "Edit Profile" button works
- [ ] Name, email, phone editable
- [ ] Campus and school_id display (not editable)
- [ ] "Change Password" link works
- [ ] Profile update saves correctly

### Settings Testing
- Navigate to: `/teacher/settings`
- Route: `teacher.settings.index`

#### What to Check
- [ ] Theme selector works (light/dark/auto)
- [ ] Language selector works
- [ ] Timezone selector works
- [ ] Notification preferences toggles work
- [ ] Settings save correctly
- [ ] Changes persist after logout/login

---

## 9. Bug Testing Checklist

### Data Integrity
- [ ] No duplicate grade entries
- [ ] No orphaned component entries
- [ ] All component weights within 0-100%
- [ ] KSA percentages always sum to 100%
- [ ] Attendance scores capped at 100

### UI/UX
- [ ] No console errors in browser
- [ ] All buttons have proper labels
- [ ] Loading states display during saves
- [ ] Success/error messages appear
- [ ] Forms validate input properly
- [ ] Responsive design works on mobile

### Performance
- [ ] Pages load within 2 seconds
- [ ] Grade entry saves quickly
- [ ] No lag when entering multiple scores
- [ ] Pagination works smoothly
- [ ] Search/filter functions responsive

### Security
- [ ] Can only access own classes
- [ ] Cannot access other teachers' data
- [ ] Campus isolation enforced
- [ ] CSRF tokens present in forms
- [ ] Session timeout works

---

## 10. Common Issues & Solutions

### Issue: Grades not saving
**Check:**
- Browser console for errors
- Network tab for failed requests
- Verify component exists
- Check student is enrolled in class

### Issue: Calculations incorrect
**Check:**
- Component weights sum correctly
- KSA percentages sum to 100%
- Normalized score formula: `(raw/max) × 50 + 50`
- Attendance formula: `(count/total) × 50 + 50`

### Issue: Components not displaying
**Check:**
- Components marked as active (`is_active = true`)
- Components belong to correct class
- Term filter applied correctly
- Browser cache cleared

### Issue: Attendance not affecting grades
**Check:**
- Attendance settings configured
- Total meetings set
- Attendance weight set
- Attendance category selected
- Attendance records exist

---

## 11. Test Data Scenarios

### Scenario 1: New Class Setup
1. Create new class
2. Add students
3. Initialize default components
4. Set KSA percentages
5. Configure attendance
6. Enter first grades
7. Verify calculations

### Scenario 2: Midterm to Final Transition
1. Complete midterm grades
2. Lock midterm settings
3. Switch to final term
4. Verify components available
5. Enter final grades
6. Compare midterm vs final

### Scenario 3: Component Customization
1. Delete default components
2. Create custom components
3. Set custom weights
4. Enter grades
5. Verify calculations with custom setup

### Scenario 4: Attendance Impact
1. Set attendance weight to 20%
2. Mark half students present
3. Mark half students absent
4. Verify attendance scores differ
5. Check impact on final grades

---

## 12. Validation Checklist

### Before Marking Complete
- [ ] All routes accessible
- [ ] All buttons functional
- [ ] All forms validate properly
- [ ] All calculations correct
- [ ] All data persists
- [ ] No console errors
- [ ] No database errors
- [ ] Mobile responsive
- [ ] Cross-browser compatible
- [ ] Security enforced

### Sign-off
- [ ] Dashboard: ✓ Working
- [ ] Classes: ✓ Working
- [ ] Subjects: ✓ Working
- [ ] Grades: ✓ Working
- [ ] Settings: ✓ Working
- [ ] Attendance: ✓ Working
- [ ] Profile: ✓ Working
- [ ] Components CRUD: ✓ Working
- [ ] KSA Settings: ✓ Working
- [ ] Calculations: ✓ Working

---

## Quick Test Commands

### Run Automated Tests
```bash
php test_teacher_module_comprehensive.php
```

### Check Database
```bash
php artisan tinker
>>> User::where('role', 'teacher')->count()
>>> ClassModel::count()
>>> DB::table('assessment_components')->count()
>>> DB::table('component_entries')->count()
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Check Routes
```bash
php artisan route:list --name=teacher
```

---

## Expected Results Summary

✓ **Dashboard:** Displays statistics, classes, and activities  
✓ **Classes:** CRUD operations work, campus isolation enforced  
✓ **Subjects:** Assignment and request system functional  
✓ **Grades:** Entry, calculation, and display working  
✓ **Components:** Full CRUD with weight manipulation  
✓ **KSA Settings:** Percentage distribution configurable  
✓ **Attendance:** Configuration and calculation correct  
✓ **Settings:** Profile and preferences manageable  
✓ **Security:** Authorization and isolation enforced  
✓ **Performance:** Fast loading and responsive UI  

---

**Testing Complete When:**
- All checkboxes marked ✓
- No critical errors found
- All calculations verified
- Data persists correctly
- UI/UX smooth and intuitive

**Status:** Ready for Production ✓
