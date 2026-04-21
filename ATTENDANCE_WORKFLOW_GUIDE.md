# 📋 Attendance System - Interactive Workflow Guide

**Updated: April 14, 2026**

---

## 🎯 Complete Workflow Overview

### **Stage 1: Recording Interactive Attendance** ✅
**Location:** `Attendance > Manage Class > Take Attendance`

#### Steps:
1. **Select Class** from the attendance management page
2. **Choose Date & Term** (Midterm/Final)
3. **For Each Student:**
   - **Mark Status** - Click ✓ (Present), ✗ (Absent), or 🕐 (Late)
   - **Capture E-Signature** - Click the pen button (🖊️) to open signature modal
   - **Draw Signature** - Use mouse/touchpad to sign
   - **Save Signature** - Click "Save Signature" button (signature indicator changes to blue ✓)

4. **Quick Mark Options:**
   - **"All Present"** - Mark entire class present at once
   - **"All Absent"** - Mark entire class absent  
   - **"Clear"** - Reset all selections

5. **Save to Database** - Click **"Save"** button
   - Saves attendance status (Present/Absent/Late)
   - Saves e-signatures for each student
   - Records date, term, and teacher ID

---

### **Stage 2: View & Print Attendance Sheet** 📄
**Location:** After saving, click **"View Sheet"** button

#### Features:
- **Interactive Date/Term Selection** - Click ⚙️ to change date or term
- **E-Signature Display** - Shows student signatures instead of blank lines
- **Official Format** - CPSU-formatted document with:
  - University header and logo
  - Class information (Subject, Course, Schedule)
  - Date and Term
  - 2-column layout for up to 50 students
  - Student names in formal format (Last, First, Middle Initial)
  - Signature spaces with captured e-signatures
  - Remarks section for notes
  - Document control footer

#### Print Options:
- **On-Screen Preview** - Review sheet before printing
- **Print to PDF** - Use browser print (Ctrl+P) → Save as PDF
- **Print to Paper** - Send directly to printer
- **Export for Records** - Keep digital copies for verification

---

## 📊 Database Architecture

### **Attendance Table** (Stores attendance records)
```sql
- student_id       (FK to students)
- class_id         (FK to classes)
- date             (attendance date)
- term             (Midterm/Final)
- status           (Present/Absent/Late/Leave)
- e_signature      (base64 PNG image of signature)
- signature_type   (e-signature/manual)
- signature_timestamp
- teacher_id       (who recorded it)
- campus
- school_id
```

### **Student Table** (Stores latest signature)
```sql
- e_signature      (latest signature captured)
- signature_date   (when it was captured)
```

---

## 🔄 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────┐
│   TEACHER INTERACTIVE ATTENDANCE                         │
│   (manage.blade.php)                                     │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  1. Select Date, Term, & Students                       │
│  2. Mark Status (Present/Absent/Late)                   │
│  3. Capture E-Signature (via SignaturePad.js)           │
│  4. Store signature in hidden form field                │
│     → attribute: attendance[studentId][e_signature]     │
│                                                          │
│  5. SUBMIT FORM                                          │
│     POST /teacher/attendance/record/{classId}           │
│                                                          │
└────────────┬────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────┐
│   TeacherController::recordAttendance()                  │
│   (app/Http/Controllers/TeacherController.php)           │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  - Validate date, term, class ownership                 │
│  - Loop through attendance data                         │
│  - For each student:                                    │
│    └─ Extract: status, e_signature                      │
│    └─ Save to Attendance table                          │
│    └─ Update Student record (latest signature)          │
│                                                          │
│  - Redirect to manage view with success message         │
│                                                          │
└────────────┬────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────┐
│   DATABASE - Attendance Records                          │
│   (One record per student per date per term)            │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  student_id │ date    │ term    │ status   │ e_signature
│  ────────────────────────────────────────────────      │
│  5          │ 4/14/26 │ Midterm │ Present  │ [PNG BASE64]
│  6          │ 4/14/26 │ Midterm │ Absent   │ NULL
│  7          │ 4/14/26 │ Midterm │ Late     │ [PNG BASE64]
│                                                          │
└────────────┬────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────┐
│   VIEW ATTENDANCE SHEET                                  │
│   (sheet.blade.php)                                      │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  GET /teacher/attendance/sheet/{classId}                │
│       ?date={date}&term={term}                          │
│                                                          │
│  - Call TeacherController::attendanceSheet()            │
│  - Load students for class/course/campus               │
│  - Load attendance records for date/term                │
│  - Match attendance.e_signature to each student         │
│  - Render official attendance sheet                     │
│                                                          │
│  Features:                                              │
│  ✓ Date/Term selection dropdown (⚙️)                    │
│  ✓ Display student signatures from attendance records   │
│  ✓ Print to PDF or paper                                │
│  ✓ CPSU official format                                 │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🎨 User Interface Layout

### **Attendance Management Page**
```
┌────────────────────────────────────────────────┐
│  Take Attendance                         [Back] │
├────────────────────────────────────────────────┤
│                                                │
│  [Class Name] | Term: [Midterm▼] | Date: [__] │
│  ────────────────────────────────────────────  │
│                                                │
│  [All Present] [All Absent] [Clear]            │
│                                                │
│  ┌──────────────────────────────────────────┐  │
│  │  1 Student Name   [🖊️] [✓] [✗] [🕐]      │  │
│  │  2 Another Name   [🖊️] [✓] [✗] [🕐]      │  │
│  │  3 Third Student  [🖊️] [✓] [✗] [🕐]      │  │
│  │  4 Fourth Name    [🖊️] [✓] [✗] [🕐]      │  │
│  └──────────────────────────────────────────┘  │
│                                                │
│  [Cancel] [View Sheet] [Save]                  │
│                                                │
└────────────────────────────────────────────────┘
```

### **Signature Modal**
```
┌────────────────────────────────────────────────┐
│  E-Signature Capture - Student Name      [✕]   │
├────────────────────────────────────────────────┤
│                                                │
│  ┌──────────────────────────────────────────┐  │
│  │                                          │  │
│  │     [Canvas for signature drawing]       │  │
│  │     (Mouse, Touchpad, or Digital Pen)   │  │
│  │                                          │  │
│  └──────────────────────────────────────────┘  │
│                                                │
│  [Clear] [Undo] [Cancel] [Save Signature]      │
│                                                │
└────────────────────────────────────────────────┘
```

### **Attendance Sheet (Printable)**
```
┌────────────────────────────────────────────────┐
│  [⚙️ Select Date & Term] [🖨️ Print Sheet]      │
├────────────────────────────────────────────────┤
│                                                │
│   CENTRAL PHILIPPINES STATE UNIVERSITY         │
│   Kabankalan, Negros Occidental                │
│   CLASS ATTENDANCE SHEET                       │
│                                                │
│   Subject: [____________________]              │
│   Date: Apr 14, 2026 | Term: Midterm          │
│   Faculty: Prof. Roberto Garcia                │
│                                                │
│   No. │ Name (Last, First, Middle) │ E-Sign   │
│   ────┼───────────────────────────┼──────    │
│   1   │ Fernandez, Rodrigo A.     │ [🖊️]     │
│   2   │ Lopez, Maricel G.         │ [🖊️]     │
│   3   │ Ramos, Danilo C.          │          │
│   ────┬───────────────────────────┬──────    │
│                                                │
│   Remarks: _____________________________       │
│                                                │
│   Doc Control: CPSU-F-VPAA-13                 │
│                                                │
└────────────────────────────────────────────────┘
```

---

## 🔐 Security Features

✅ **Teacher Authorization** - Only class teachers can record attendance
✅ **Date Validation** - Cannot record attendance for future dates
✅ **E-Signature Validation** - Signature required (or can mark absent without)
✅ **Data Integrity** - Attendance records immutable after save
✅ **Audit Trail** - Timestamp and teacher ID recorded
✅ **Base64 Encoding** - Signatures stored as base64 PNG images

---

## 📝 Example Usage

### **Scenario: Midterm Attendance - April 14, 2026**

**11:30 AM - Attendance Period**
```
Teacher: "Let me take attendance for BSIT 1-A"
→ Navigate to Attendance > Manage Class
→ Select BSIT 1-A - Intro to Computing
→ Date: 04/14/2026 | Term: Midterm (auto-selected)

For each of 5 students present:
→ Click ✓ (Present)
→ Click pen icon 🖊️ → Student signs → Click Save

For 2 students absent:
→ Click ✗ (Absent) - no signature needed

Click the blue [Save] button
→ All 7 records saved to database with e-signatures
→ Success message: "Attendance saved for 7 student(s)"

[View Sheet] button opens the attendance sheet showing:
- All 7 student names
- Signatures for the 5 present students
- Empty signature fields for 2 absent students
- Print to PDF for record keeping
```

---

## 🛠️ Technical Details

### **Files Modified**
- ✅ `app/Http/Controllers/TeacherController.php` - attendanceSheet method
- ✅ `resources/views/teacher/attendance/sheet.blade.php` - Signature display
- ✅ `resources/views/teacher/attendance/manage.blade.php` - Sheet link

### **Routes Used**
```php
GET  /teacher/attendance/manage/{classId}      # Take attendance
POST /teacher/attendance/record/{classId}      # Save attendance
GET  /teacher/attendance/sheet/{classId}       # View sheet
```

### **Dependencies**
- **SignaturePad.js v4.0.0** - E-signature capture library
- **Bootstrap 5.3** - UI components
- **Laravel 11** - Backend framework
- **MySQL** - Attendance database

---

## ✨ Key Features

| Feature | Details |
|---------|---------|
| **Interactive** | Real-time status marking and signature capture |
| **E-Signature** | Digital signatures via SignaturePad.js |
| **Official Format** | CPSU-formatted document with logo and footer |
| **Date Selection** | View different dates and terms |
| **Print Ready** | CSS optimized for printing to PDF/paper |
| **Data Persistence** | Signatures stored in database and displayed |
| **Audit Trail** | Date, term, teacher, and timestamp recorded |
| **Authorization** | Teachers can only manage their own classes |

---

## 🎓 Ready to Use!

The attendance system is now fully configured for:
- ✅ Taking interactive attendance with e-signatures
- ✅ Viewing official attendance sheets with student signatures
- ✅ Printing sheets for official records
- ✅ Tracking attendance for grade integration

**System Status:** 🟢 FULLY OPERATIONAL

