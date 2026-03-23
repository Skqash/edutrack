# 🔥 COMPREHENSIVE DATABASE RECONSTRUCTION GUIDE

## Current State (March 19, 2026)

### ✅ What's Working:
- ✅ Colleges table (3 records: Computer Studies, Education, Hospitality)
- ✅ Departments table (3 records: BSIT, BEED, BSHM - linked to colleges)
- ✅ Subjects table (153 subjects properly normalized)
- ✅ Users table (1 admin, 8 teachers, 388 students)
- ✅ Students table (323 students linked to classes)
- ✅ Classes table created (62 classes)
- ✅ class_subjects junction table created (but empty)

### ❌ Critical Issues to Fix:

#### 1. COURSES TABLE PROBLEMS:
- Has 4 courses (BSIT, BEED, BSHM, BSAFE)
- **program_name shows "General" for all** (should show full program names)
- Too many redundant columns:
  - `college` (text) - should use FK
  - `department` (text) - should use FK  
  - `departm` - seems truncated/corrupted
  - `course_code` vs `program_code` - redundant
  - `course_name` vs `program_name` - redundant
  - `department_code` - redundant
  - `duration`, `credit_hours`, `total_students` - belong elsewhere
- **Missing update**: Only 3 programs needed (BSIT, BEED, BSHM)

#### 2. SUBJECTS TABLE ISSUES:
- 153 subjects but ALL in year_level 1!  
- Should have subjects for Years 1, 2, 3, 4
- All subjects show `program_id` but year_level is wrong distribution

#### 3. CLASSES TABLE ISSUES:
- 62 classes created
- **program_id is empty/NULL** - Not linked to courses
- Still has redundant `course_id` column alongside `program_id`
- Only 14 classes have students (48 classes are empty)
- class_name still old format "Class 10-A" instead of program-based names

#### 4. CLASS_SUBJECTS TABLE ISSUES:
- Table created but has **0 records**!
- Should have 62 × (subjects per year) relationships
- Not auto-populated when classes were created

#### 5. STUDENT DISTRIBUTION:
- 323 students across only 14 classes
- 48 classes have no students at all
- Students should be distributed: ~50-70 per class for balance

#### 6. LEGACY TABLES (Should be cleaned):
- `admins` - use users table instead
- `teachers` - use users table instead
- `super_admins` - use users table instead
- `class_students` - conflicts with students table
- `teacher_subject` - conflicts with class assignments
- `course_instructors` - should use class relationships
- Multiple grading/attendance tables - need consolidation

---

## Reconstruction Plan (Step by Step)

### PHASE 1: Clean Up Courses Table

**Expected Final Structure:**
```sql
courses (3 records total)
├─ id, course_code (BSIT, BEED, BSHM)
├─ course_name (full program name)
├─ program_code, program_name (same as course info)
├─ department_id (FK to departments)
├─ program_head_id (FK to users - nullable)
├─ total_years (4)
├─ description
├─ status (Active/Inactive)
└─ timestamps
```

**Data to Have:**
```
ID | Code | Name                                | Dept | Years | Status
1  | BSIT | Bachelor of Science in IT           | 1    | 4     | Active
2  | BEED | Bachelor of Elementary Education    | 2    | 4     | Active
3  | BSHM | Bachelor of Science in Hospitality  | 3    | 4     | Active
```

### PHASE 2: Ensure Subjects Populated for All Years

**Current:** 153 subjects all in year_level 1
**Target:** ~600-700 subjects (153+ for each year level)

**Distribution by Program & Year:**
```
BSIT Year 1: ~20 subjects
BSIT Year 2: ~20 subjects  
BSIT Year 3: ~20 subjects
BSIT Year 4: ~20 subjects
(Same for BEED and BSHM)
```

### PHASE 3: Update Classes with Program Links

**Current:** 62 classes with NULL program_id
**Target:** Each class linked to correct program

**Mapping Logic:**
```
Classes with "Introduction to IT" → BSIT
Classes with "Elementary Education" → BEED
Classes with "Hospitality" → BSHM
```

### PHASE 4: Populate class_subjects Junction Table

**Logic:**
```
For each class:
  1. Get class.program_id and year_level
  2. Get all subjects where:
     - program_id = class.program_id
     - year_level = class.year_level
     - semester = class.semester
  3. Create class_subject records for all those subjects
```

### PHASE 5: Clean Legacy Tables

Delete/Archive:
- `admins` table (data → users table)
- `teachers` table (data → users table)
- `super_admins` table (data → users table)
- `class_students` table (conflicting with students table)
- Refactor teacher-subject links through classes

---

## Expected Final Database Structure

```
Users (397 total)
├─ 1 admin
├─ 8 teachers
└─ 388 students

Colleges (3)
├─ College of Computer Studies
├─ College of Education
└─ College of Hospitality

Departments (3)
├─ BSIT (→ Computer Studies)
├─ BEED (→ Education)
└─ BSHM (→ Hospitality)

Courses/Programs (3)
├─ BSIT
├─ BEED
└─ BSHM

Subjects (~600+)
├─ BSIT: 153 subjects × 4 years
├─ BEED: 153 subjects × 4 years
└─ BSHM: 153 subjects × 4 years

Classes (62)
├─ Each linked to program_id
├─ Each linked to year_level (1-4)
└─ Each linked to semester (1-2)

Class_Subjects (62 × avg_subjects_per_year)
├─ Links each class to its curriculum
└─ Auto-populated based on program/year/semester

Class_Students (323)
├─ Links 323 students to their classes
└─ Distributed across 62 classes

Grades
├─ Records for each student per subject per class
└─ Supports dynamic component grading
```

---

## Next Actions

1. ✅ DONE: Create colleges & departments tables with proper relationships
2. ✅ DONE: Normalize subjects table
3. ✅ DONE: Create class_subjects junction table  
4. ❌ TODO: Fix courses table (remove redundant columns, update data)
5. ❌ TODO: Populate subjects for Years 2, 3, 4
6. ❌ TODO: Link classes to programs (program_id)
7. ❌ TODO: Auto-populate class_subjects
8. ❌ TODO: Redistribute/verify students across classes
9. ❌ TODO: Remove legacy tables
10. ❌ TODO: Final system test

---

## Defense-Level Explanation (SAY THIS):

**"The database was refactored to establish proper normalization and relationships. Courses represent programs (BSIT, BEED, BSHM) linked to departments. Subjects define the curriculum organized by program, year level, and semester. Classes are instances of programs in a given year/semester, with a junction table (class_subjects) establishing which subjects are taught in each class. This ensures data consistency, eliminates redundancy, and allows dynamic curriculum assignment based on institutional structure."**

