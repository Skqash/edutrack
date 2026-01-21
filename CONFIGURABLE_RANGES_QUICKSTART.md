# Configurable Assessment Ranges - Quick Start Guide

## 🚀 What You Can Do Now

### 1. **Configure Quiz/Exam Item Ranges**

Teachers can now customize the maximum items/points for:

- 5 separate quizzes (Q1-Q5) with different max items
    - Example: Q1=20 items, Q2=15 items, Q3=25 items, etc.
- 3 exam types (Prelim, Midterm, Final)
    - Example: Exams can be 40 items or 60 items (you choose!)

### 2. **Configure Skills & Attitude Ranges**

Set custom maximum scores for:

- **Skills**: Output, Class Participation, Activities, Assignments
- **Attitude**: Behavior, Awareness
- **Attendance**: Total classes per term

### 3. **Smart Automatic Calculations**

Scores are automatically normalized to 0-100 scale:

```
Student enters Quiz 1 score: 18/20 items
System calculates: (18/20) × 100 = 90 points
```

### 4. **Fixed Percentage Weighting**

Percentages stay the same regardless of ranges:

- Knowledge: 40% (composed of quizzes 40% + exams 60%)
- Skills: 50% (Output 40% + CP 30% + Activities 15% + Assignments 15%)
- Attitude: 10% (Behavior 50% + Awareness 50%)

### 5. **Data Persists After Refresh**

- Enter grades → Save → Refresh page → Grades still there
- All data stored in database
- No data loss

## 📋 How to Use

### Step 1: Configure Assessment Ranges (First Time Only)

1. Go to your class grades page
2. Click **"Configure Ranges"** button
3. Set maximum items/scores:
    - Quiz 1: 20 items
    - Quiz 2: 15 items
    - Quiz 3: 25 items
    - Exam: 60 items
    - Skills components: 100 points each
    - Attitude components: 100 points each
4. Click **"Save Configuration"**

### Step 2: Enter Grades Using Enhanced Form

1. Go to **Grades** → **Entry - Enhanced**
2. Select your class and term (Midterm/Final)
3. For each student, enter:
    - Quiz scores (in configured max items)
    - Exam scores (in configured max items)
    - Skills scores (Output, CP, Activities, Assignments)
    - Attitude scores (Behavior, Awareness)
    - Attendance percentage
    - Optional remarks

4. The system automatically:
    - Normalizes all scores to 0-100 scale
    - Calculates component scores (Knowledge, Skills, Attitude)
    - Calculates final grade
    - Displays letter grade (A, B, C, D, F)

5. Click **"Save All Grades"**
6. Grades persist in database - refresh page to verify!

### Step 3: Track Attendance (Optional)

1. Go to **Attendance** → **Manage**
2. For each student, record:
    - Present classes
    - Total classes
    - Optional remarks
3. Attendance percentage auto-calculates

## 📊 Example Calculation

### Teacher Configuration:

```
Quiz 1: 20 items (not 5!)
Quiz 2: 15 items
Quiz 3: 25 items
Exam (Midterm): 60 items
Output: 100 points
```

### Student Scores:

```
Q1: 18/20
Q2: 12/15
Q3: 22/25
Exam: 55/60
Output: 85/100
```

### System Calculations:

**Step 1: Normalize to 0-100**

```
Q1: (18/20) × 100 = 90
Q2: (12/15) × 100 = 80
Q3: (22/25) × 100 = 88
Exam: (55/60) × 100 = 91.67
Output: (85/100) × 100 = 85
```

**Step 2: Calculate Knowledge (40% of term)**

```
Quiz Average: (90 + 80 + 88) / 3 = 86
Knowledge = (Quiz Avg × 40%) + (Exam × 60%)
Knowledge = (86 × 0.40) + (91.67 × 0.60)
Knowledge = 34.4 + 55 = 89.4
```

**Step 3: Final Grade**

```
Assuming Skills: 85, Attitude: 90

Final = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
Final = (89.4 × 0.40) + (85 × 0.50) + (90 × 0.10)
Final = 35.76 + 42.5 + 9
Final = 87.26 ≈ 87 (Grade: B)
```

## 🔄 Data Persistence

### How It Works:

1. Teacher enters scores and clicks "Save"
2. System stores in database:
    - All raw scores (Q1-Q5, exams, skills, attitude)
    - Calculated component scores (Knowledge, Skills, Attitude)
    - Final grade and letter grade
    - Timestamp
3. Data remains in database permanently
4. Next time teacher views form:
    - All previously entered scores appear
    - Can edit and re-save
    - Changes update in database

### What Persists:

- ✅ Quiz scores (Q1-Q5)
- ✅ Exam scores (Prelim, Midterm, Final)
- ✅ Skills scores (Output, CP, Activities, Assignments)
- ✅ Attitude scores (Behavior, Awareness)
- ✅ Final calculated grades
- ✅ Attendance records
- ✅ Remarks/notes
- ✅ Term designation (Midterm/Final)

## 🎯 Key Features

| Feature                    | Details                                     |
| -------------------------- | ------------------------------------------- |
| **Configurable Ranges**    | Different max items for each quiz/exam      |
| **Auto Normalization**     | Scores automatically convert to 0-100 scale |
| **Persistent Data**        | Data survives page refresh                  |
| **Component Tracking**     | Track all 9 K, S, A components separately   |
| **Attendance Integration** | Optional attendance percentage tracking     |
| **Responsive Design**      | Works on desktop, tablet, mobile            |
| **Error Validation**       | Validates all inputs before saving          |
| **Backward Compatible**    | Old grading routes still work               |

## 🚨 Important Notes

1. **Configuration per Class/Subject**: Each class-subject combination has its own assessment range configuration
2. **Teacher Verification**: Only the class's teacher can configure ranges and enter grades
3. **Term Separation**: Midterm and Final grades stored separately
4. **Percentage Fixed**: No matter what ranges you set, percentages stay at 40-50-10
5. **Normalization Automatic**: All calculations use 0-100 scale internally

## 📍 Where to Find Everything

| Feature              | URL Pattern                                       | Route Name                      |
| -------------------- | ------------------------------------------------- | ------------------------------- |
| Configure Ranges     | `/teacher/assessment/configure/{classId}`         | `teacher.assessment.configure`  |
| Enhanced Grade Entry | `/teacher/grades/entry-enhanced/{classId}/{term}` | `teacher.grades.entry.enhanced` |
| Manage Attendance    | `/teacher/attendance/manage/{classId}`            | `teacher.attendance.manage`     |
| All Grades Dashboard | `/teacher/grades`                                 | `teacher.grades`                |

## 💾 Database Storage

### Tables Used:

- `assessment_ranges` - Stores configuration (quiz/exam/skills/attitude max values)
- `student_attendance` - Stores attendance records
- `grades` - Stores all grade components and calculations

### Unique Keys:

- Grades: `(student_id, class_id, subject_id, teacher_id, term)`
- Attendance: `(student_id, class_id, subject_id, term)`
- Ranges: `(class_id, subject_id, teacher_id)`

## ✅ Testing Checklist

- [ ] Configure assessment ranges for a class
- [ ] Enter grades using enhanced form
- [ ] Verify grades save (click Save button)
- [ ] Refresh page - grades still visible
- [ ] Edit a grade value
- [ ] Save again - updated value persists
- [ ] Check final grade calculates correctly
- [ ] Record attendance
- [ ] View different term (Midterm vs Final)
- [ ] Try different quiz/exam configurations

## 🆘 Troubleshooting

| Issue                      | Solution                                                     |
| -------------------------- | ------------------------------------------------------------ |
| Grades not showing         | Make sure class is assigned to your teacher account          |
| Can't configure ranges     | Must be the assigned teacher for the class                   |
| Scores don't normalize     | Check configured max values in range configuration           |
| Data lost after refresh    | Make sure you clicked "Save" button, not just entered data   |
| Attendance not calculating | Verify total_classes > 0 and present_classes ≤ total_classes |

## 📞 Support

All features are designed to be intuitive. If issues occur:

1. Check the CONFIGURABLE_ASSESSMENT_RANGES_GUIDE.md for detailed documentation
2. Review your assessment range configuration
3. Verify all required fields are filled
4. Check browser console for any JavaScript errors

---

**Version**: 1.0 | **Status**: ✅ Ready for Production | **Last Updated**: January 21, 2026
