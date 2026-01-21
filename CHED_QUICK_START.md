# CHED Grading System - Teacher Quick Start Guide

## Accessing the System

1. **Login as Teacher**
    - Go to: `http://localhost:8000`
    - Email: `teacher1@example.com` (or any teacher account)
    - Password: `password123`

2. **Dashboard**
    - You'll see 4 stat cards with green/blue colors showing:
        - Classes assigned to you
        - Total students
        - Grades posted
        - Pending tasks

---

## Managing Students

### Add Student Manually

1. Go to **Classes** → **Add Students** (top right button)
2. **Select Class** from dropdown
3. Enter student details:
    - Full Name (required)
    - Email (required, unique)
    - Admission Number (optional)
    - Roll Number (optional)
4. Click **Add Student**

### Bulk Import via Excel (Coming Soon)

- Feature prepared, requires Laravel Excel package
- Excel format: Name, Email, Admission#, Roll#

---

## Entering Grades - CHED System

### Step 1: Select Term

Go to **Grades** section and you'll see your classes with two buttons:

- **Midterm** - For midterm term grades
- **Final** - For final term grades

### Step 2: CHED Grade Entry Form

The form has organized columns by component:

#### KNOWLEDGE (40% of Term) - 7 columns

| Q1  | Q2  | Q3  | Q4  | Q5  | PR Exam | MD Exam |
| --- | --- | --- | --- | --- | ------- | ------- |
| 0-5 | 0-5 | 0-5 | 0-5 | 0-5 | 0-100   | 0-100   |

**Calculation:**

- Quiz average from Q1-Q5 (out of 25 total) × 0.40
- Exam average (PR + MD) / 2 × 0.60
- Knowledge = Quiz part + Exam part

#### SKILLS (50% of Term) - 4 columns

| Output | Class Part | Activities | Assignments |
| ------ | ---------- | ---------- | ----------- |
| 0-100  | 0-100      | 0-100      | 0-100       |

**Calculation:**

- (Output × 0.40) + (Class Part × 0.30) + (Activities × 0.15) + (Assignments × 0.15)

#### ATTITUDE (10% of Term) - 2 columns

| Behavior | Awareness |
| -------- | --------- |
| 0-100    | 0-100     |

**Calculation:**

- (Behavior × 0.50) + (Awareness × 0.50)

#### FINAL GRADE (Auto-calculated)

The final grade auto-populates based on CHED formula:

- **(K × 0.40) + (S × 0.50) + (A × 0.10)**

---

## Entering Data - Best Practices

### Quizzes (Q1-Q5)

- Enter scores out of 5 points each
- Maximum 5 quizzes
- Converted automatically to 0-100 scale

### Exams

- **Prelim Exam (PR):** 0-100 points
- **Midterm Exam (MD):** 0-100 points
- **Final Exam (FE):** 0-100 points (used only in final term)

### Skills Components

- All components scored out of 100
- Output: Project/practical work
- Class Participation: In-class engagement
- Activities: Group activities, projects
- Assignments: Written assignments, tasks

### Attitude Components

- Behavior: Conduct, discipline
- Awareness: Class consciousness, participation

---

## Term Differences

### Midterm Term

- **Exams used:** Prelim Exam + Midterm Exam
- **Formula:** (PR + MD) / 2

### Final Term

- **Exams used:** Midterm Exam (carried) + Final Exam
- **Formula:** (MD + FE) / 2

---

## Letter Grades

| Score    | Grade |
| -------- | ----- |
| 90-100   | A     |
| 80-89    | B     |
| 70-79    | C     |
| 60-69    | D     |
| Below 60 | F     |

---

## Saving Grades

1. Fill in all student scores
2. Final grades auto-calculate real-time
3. Add optional remarks in the notes section
4. Click **Save Grades** button
5. System confirms save with success message

---

## Color Scheme

### Sidebar (Left Navigation)

- **Background:** Clean white for professionalism
- **Text:** Blue/purple gradient colors
- **Hover:** Light blue background

### Dashboard Cards

- **Classes:** Primary gradient (blue-purple)
- **Students:** Green-blue gradient (fresh, professional)
- **Grades Posted:** Blue-green gradient (success)
- **Pending Tasks:** Blue-green accent (alerts)

### Buttons

- **Primary Actions:** Blue-purple gradient
- **Midterm:** Blue button
- **Final:** Yellow/orange button

---

## Features

✅ **CHED Philippines Grading System** - Official grading scales
✅ **Two Terms** - Midterm and Final grading periods
✅ **Component Scoring** - Detailed K/S/A breakdown
✅ **Real-time Calculation** - Grades calculate as you type
✅ **Professional Design** - Clean white sidebar, color accents
✅ **Student Management** - Add students manually or via Excel
✅ **Responsive Design** - Works on desktop, tablet, mobile
✅ **4-Year Course Support** - Track students 1st-4th year

---

## Troubleshooting

### Grades Not Saving

- Ensure all required fields have values (0-100)
- Check browser console for errors
- Verify you're logged in as teacher

### Students Not Showing

- Ensure students are added to the class
- Check class selection in add student form
- Refresh page if needed

### Final Grade Not Calculating

- Ensure all component scores are entered
- Check that values are within valid ranges
- Refresh page if calculation lags

---

## Contact Support

For issues or enhancements, contact development team.

**System Version:** 1.0 - CHED Edition
**Last Updated:** January 21, 2026
