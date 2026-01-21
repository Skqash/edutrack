# System Architecture & Data Flow

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                       TEACHER INTERFACE                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────┐      ┌──────────────────────┐          │
│  │  Configuration Form  │      │  Grade Entry Form    │          │
│  │  • Quiz ranges       │      │  • Student list      │          │
│  │  • Exam ranges       │      │  • Quiz inputs       │          │
│  │  • Skills ranges     │      │  • Exam inputs       │          │
│  │  • Attitude ranges   │      │  • Skills inputs     │          │
│  │  • Attendance max    │      │  • Attitude inputs   │          │
│  └──────────────────────┘      │  • Attendance %      │          │
│                                 │  • Remarks           │          │
│                                 └──────────────────────┘          │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │        Attendance Management                              │   │
│  │  • Record present/absent classes                          │   │
│  │  • Auto-calculate attendance percentage                   │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────────┐
│                   CONTROLLER LAYER                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  • configureAssessmentRanges()    [GET]                         │
│  • storeAssessmentRanges()        [POST] → Validate → Save     │
│  • showGradeEntryEnhanced()       [GET]                         │
│  • storeGradesEnhanced()          [POST] → Calculate → Save    │
│  • manageAttendance()             [GET]                         │
│  • recordAttendance()             [POST] → Calculate → Save    │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────────┐
│                    BUSINESS LOGIC                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Score Normalization                                     │   │
│  │  Normalized = (Raw Score / Max Value) × 100              │   │
│  └─────────────────────────────────────────────────────────┘   │
│                           ↓                                       │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Component Calculation (Grade Model)                    │   │
│  │  • Knowledge: (Quiz_avg×40%) + (Exam_avg×60%)          │   │
│  │  • Skills: (Out×40%) + (CP×30%) + (Act×15%) + (Asg×15%)│   │
│  │  • Attitude: (Behavior×50%) + (Awareness×50%)           │   │
│  └─────────────────────────────────────────────────────────┘   │
│                           ↓                                       │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Final Grade Calculation                                │   │
│  │  Final = (Knowledge×40%) + (Skills×50%) + (Attitude×10%)│   │
│  │  Letter Grade: A (90+), B (80+), C (70+), D (60+), F    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────┐      ┌──────────────────────┐          │
│  │ assessment_ranges    │      │  student_attendance  │          │
│  │ • class_id          │      │  • student_id       │          │
│  │ • subject_id        │      │  • class_id         │          │
│  │ • teacher_id        │      │  • subject_id       │          │
│  │ • quiz_*_max        │      │  • term             │          │
│  │ • exam_*_max        │      │  • attendance_score │          │
│  │ • component_max     │      │  • present_classes  │          │
│  │ • attendance_max    │      │  • total_classes    │          │
│  └──────────────────────┘      └──────────────────────┘          │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              grades (CHED System)                         │   │
│  │  • q1-q5 scores            • behavior_score              │   │
│  │  • prelim/midterm/final    • awareness_score             │   │
│  │  • output_score            • attitude_score              │   │
│  │  • class_participation     • final_grade                 │   │
│  │  • activities_score        • grade_letter                │   │
│  │  • assignments_score       • term, remarks               │   │
│  │  • knowledge_score                                       │   │
│  │  • skills_score                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## 📊 Data Flow: Grade Entry Process

```
┌─────────────────┐
│   Teacher       │
│   Enters Data   │
└────────┬────────┘
         │
         ↓
    ┌────────────────────────────────┐
    │  Raw Scores Submitted          │
    │  • Q1: 18/20                  │
    │  • Q2: 14/15                  │
    │  • Q3: 22/25                  │
    │  • Exam: 55/60                │
    │  • Output: 85/100             │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  Fetch AssessmentRange         │
    │  (configuration for this class)│
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  NORMALIZE SCORES              │
    │  • Q1: (18/20)×100 = 90        │
    │  • Q2: (14/15)×100 = 93.33     │
    │  • Q3: (22/25)×100 = 88        │
    │  • Exam: (55/60)×100 = 91.67   │
    │  • Output: (85/100)×100 = 85   │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  CALCULATE KNOWLEDGE (40%)     │
    │  Quiz Avg: (90+93.33+88)/3=90.4│
    │  Exam Avg: 91.67               │
    │  Knowledge: (90.4×40%) +       │
    │             (91.67×60%)        │
    │           = 36.16 + 55 = 91.16 │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  CALCULATE SKILLS (50%)        │
    │  (Output, CP, Activities, etc) │
    │  Skills = 85 (assumed)         │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  CALCULATE ATTITUDE (10%)      │
    │  (Behavior, Awareness)         │
    │  Attitude = 90 (assumed)       │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  CALCULATE FINAL GRADE         │
    │  (91.16×40%) +                 │
    │  (85×50%) +                    │
    │  (90×10%)                      │
    │  = 36.46 + 42.5 + 9            │
    │  = 87.96 → Grade: B            │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  STORE IN DATABASE             │
    │  • All raw scores              │
    │  • All calculated scores       │
    │  • Final grade & letter        │
    │  • Term & timestamp            │
    └────────┬───────────────────────┘
             │
             ↓
    ┌────────────────────────────────┐
    │  DISPLAY TO TEACHER            │
    │  ✓ Grade saved successfully    │
    │  ✓ Final Grade: 87.96 (B)     │
    └────────────────────────────────┘
```

## 🔄 Data Persistence Flow

```
SAVE → DATABASE → REFRESH → LOAD → DISPLAY

1. Teacher enters grade and clicks "Save"
   ↓
2. POST request sent to server
   ↓
3. Validation occurs
   ↓
4. Calculations performed
   ↓
5. Data stored in database
   ↓
6. Response sent to browser (success)
   ↓
7. Page refreshes (or teacher navigates away)
   ↓
8. New GET request fetches data
   ↓
9. Database query retrieves saved grades
   ↓
10. View renders with persisted data
   ↓
11. Teacher sees same grades they entered
```

## 🎯 Component Relationship Diagram

```
┌────────────────────────────────────────────────┐
│  FINAL GRADE (0-100)                           │
│  = K×40% + S×50% + A×10%                       │
│                                                │
├────────┬─────────────────┬──────────────────────┤
│        │                 │                      │
↓        ↓                 ↓                      ↓
Knowledge  Skills          Attitude               Attendance
(40%)      (50%)          (10%)               (Tracked)
  │          │              │                    │
  ├─ Q_avg   ├─ Output   ├─ Behavior          └─ For reference
  │ (40%)    │  (40%)     │  (50%)              (Optional)
  │          │            │
  ├─ E_avg   ├─ CP       └─ Awareness
  │ (60%)    │  (30%)       (50%)
  │          │
  │          ├─ Activities
  │          │  (15%)
  │          │
  │          └─ Assignments
  │             (15%)
  │
  ├─ Q1 (normalized)
  ├─ Q2 (normalized)
  ├─ Q3 (normalized)
  ├─ Q4 (normalized)
  ├─ Q5 (normalized)
  │
  ├─ Prelim Exam (normalized)
  ├─ Midterm Exam (normalized)
  └─ Final Exam (normalized)
```

## 📈 Score Normalization Examples

```
Scenario 1: Quiz Range 20 items
  Raw Score: 18/20
  Normalized: (18/20) × 100 = 90 points

Scenario 2: Quiz Range 10 items
  Raw Score: 9/10
  Normalized: (9/10) × 100 = 90 points
  (SAME normalized score, different max)

Scenario 3: Exam Range 60 items
  Raw Score: 55/60
  Normalized: (55/60) × 100 = 91.67 points

Scenario 4: Exam Range 40 items
  Raw Score: 37/40
  Normalized: (37/40) × 100 = 92.5 points
  (SAME raw skill, different exam size)

Key Point:
  All scores normalize to 0-100 scale
  → Allows comparison across different ranges
  → Maintains percentage weighting consistency
```

## 🗄️ Database Relationships

```
┌─────────────┐
│   CLASSES   │
│  (1 to many)│
└─────┬───────┘
      │
      ├─────────────────────────┐
      │                         │
      ↓                         ↓
┌──────────────────┐    ┌──────────────────┐
│ ASSESSMENT_RANGES│    │  GRADES          │
│ • class_id       │    │  • class_id      │
│ • quiz_*_max     │    │  • q1-q5         │
│ • exam_*_max     │    │  • exams         │
│ • component_max  │    │  • knowledge     │
└──────────────────┘    │  • skills        │
                        │  • attitude      │
                        │  • final_grade   │
                        └──────────────────┘

┌──────────────────┐
│   STUDENTS       │
│  (1 to many)     │
└─────┬────────────┘
      │
      ├─────────────────────────┐
      │                         │
      ↓                         ↓
┌──────────────────┐    ┌──────────────────┐
│      GRADES      │    │ STUDENT_ATTENDANCE│
│  • student_id    │    │  • student_id    │
│  • all scores    │    │  • attendance%   │
│  • term-based    │    │  • term-based    │
└──────────────────┘    └──────────────────┘
```

## ⚙️ Configuration Example

```
Teacher sets up class configuration:

KNOWLEDGE (40% of term):
  Q1: 30 items max (not 5)
  Q2: 25 items max
  Q3: 35 items max
  Exam: 80 items max

SKILLS (50% of term):
  Output: 150 points max
  Class Participation: 75 points max
  Activities: 100 points max
  Assignments: 50 points max

ATTITUDE (10% of term):
  Behavior: 100 points max
  Awareness: 100 points max

ATTENDANCE:
  Max classes per term: 45
  Attendance required: YES

---

Teacher enters grades:
  Q1: 25/30 items     → (25/30)×100 = 83.33
  Q2: 22/25 items     → (22/25)×100 = 88.00
  Q3: 32/35 items     → (32/35)×100 = 91.43
  Exam: 75/80 items   → (75/80)×100 = 93.75

  Output: 140/150     → (140/150)×100 = 93.33
  Class Part: 70/75   → (70/75)×100 = 93.33
  Activities: 95/100  → (95/100)×100 = 95.00
  Assignments: 48/50  → (48/50)×100 = 96.00

  Behavior: 90/100    → 90.00
  Awareness: 88/100   → 88.00

---

System calculates:

KNOWLEDGE:
  Q_avg = (83.33 + 88.00 + 91.43) / 3 = 87.59
  K = (87.59 × 0.40) + (93.75 × 0.60)
  K = 35.04 + 56.25 = 91.29

SKILLS:
  S = (93.33 × 0.40) + (93.33 × 0.30) + (95.00 × 0.15) + (96.00 × 0.15)
  S = 37.33 + 28.00 + 14.25 + 14.40 = 93.98

ATTITUDE:
  A = (90.00 × 0.50) + (88.00 × 0.50)
  A = 45.00 + 44.00 = 89.00

FINAL:
  Grade = (91.29 × 0.40) + (93.98 × 0.50) + (89.00 × 0.10)
  Grade = 36.52 + 46.99 + 8.90 = 92.41

  Letter Grade: A (90+)

✓ ALL DATA STORED IN DATABASE WITH TERM DESIGNATION
```

---

**Diagram Created**: January 21, 2026 | **Status**: Complete
