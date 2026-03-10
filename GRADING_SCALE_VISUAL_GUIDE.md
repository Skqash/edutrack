# Grade Calculation System - Visual Reference Guide

## 📊 Grading Scale Visualization

```
NUMERIC SCORE (0-100)         DECIMAL GRADE (1.0-5.0)         LETTER GRADE         PERFORMANCE LEVEL
═══════════════════════════════════════════════════════════════════════════════════════════════════════

98 - 100 ══════════════════════► 1.00 ════════════════════► A+ ════════════► ⭐⭐⭐ EXCELLENT

95 - 97  ══════════════════════► 1.25 ════════════════════► A  ════════════► ⭐⭐  EXCELLENT

92 - 94  ══════════════════════► 1.50 ════════════════════► A- ════════════► ⭐   EXCELLENT

89 - 91  ══════════════════════► 1.75 ════════════════════► B+ ════════════► ✓✓✓  VERY GOOD

86 - 88  ══════════════════════► 2.00 ════════════════════► B  ════════════► ✓✓   VERY GOOD

83 - 85  ══════════════════════► 2.25 ════════════════════► B- ════════════► ✓    VERY GOOD

80 - 82  ══════════════════════► 2.50 ════════════════════► C+ ════════════► ⚠    GOOD

77 - 79  ══════════════════════► 2.75 ════════════════════► C  ════════════► ⚠⚠   GOOD

74 - 76  ══════════════════════► 3.00 ════════════════════► C- ════════════► ⚠⚠⚠  SATISFACTORY
                                                                              ↑
                                                                     ✅ PASS THRESHOLD
                                                                          (≤ 3.0)

71 - 73  ══════════════════════► 3.25 ════════════════════► D+ ════════════► →    FAIR
                                                                              ↓
                                                                      ❌ FAIL THRESHOLD
                                                                           (> 3.0)

70 ══════════════════════════════► 3.50 ════════════════════► D  ════════════► → →  FAIR

0 - 69   ══════════════════════► 5.00 ════════════════════► F  ════════════► ✗    FAILED
```

---

## 🎯 Pass/Fail Logic

```
                    STUDENT GRADE EVALUATION
                    ════════════════════════

                    IF decimal_grade ≤ 3.0
                         THEN
                         ✅ PASSED
                         
                    IF decimal_grade > 3.0
                         THEN
                         ❌ FAILED
```

---

## 📈 Sample Student Scenarios

### Scenario 1: Excellent Student ⭐⭐⭐
```
Midterm Assessment:
├─ Knowledge: 98  → 60% of term
├─ Skills:    96  → 50% of term
├─ Attitude:  97  → 10% of term
└─ Midterm Grade: 96.80 → 1.25 (A) → ✅ PASSED

Final Assessment:
├─ Knowledge: 99  → 60% of term
├─ Skills:    97  → 50% of term
├─ Attitude:  98  → 10% of term
└─ Final Grade: 97.60 → 1.25 (A) → ✅ PASSED

Semester Overall:
├─ Midterm:   96.80 × 0.40 = 38.72
├─ Final:     97.60 × 0.60 = 58.56
├─ Overall:   38.72 + 58.56 = 97.28
├─ Decimal:   97.28 → 1.25 (A)
└─ Status:    ✅ PASSED (Excellent)
```

### Scenario 2: Average Student ✓✓
```
Midterm Assessment:
├─ Knowledge: 82  → 60% of term
├─ Skills:    85  → 50% of term
├─ Attitude:  80  → 10% of term
└─ Midterm Grade: 82.60 → 2.50 (C+) → ✅ PASSED

Final Assessment:
├─ Knowledge: 88  → 60% of term
├─ Skills:    89  → 50% of term
├─ Attitude:  87  → 10% of term
└─ Final Grade: 88.30 → 2.00 (B) → ✅ PASSED

Semester Overall:
├─ Midterm:   82.60 × 0.40 = 33.04
├─ Final:     88.30 × 0.60 = 52.98
├─ Overall:   33.04 + 52.98 = 86.02
├─ Decimal:   86.02 → 2.00 (B)
└─ Status:    ✅ PASSED (Very Good)
```

### Scenario 3: Borderline Student →
```
Midterm Assessment:
├─ Knowledge: 72  → 60% of term
├─ Skills:    74  → 50% of term
├─ Attitude:  75  → 10% of term
└─ Midterm Grade: 73.60 → 3.25 (D+) → ❌ FAILED

Final Assessment:
├─ Knowledge: 78  → 60% of term
├─ Skills:    77  → 50% of term
├─ Attitude:  76  → 10% of term
└─ Final Grade: 77.30 → 2.75 (C) → ✅ PASSED

Semester Overall:
├─ Midterm:   73.60 × 0.40 = 29.44
├─ Final:     77.30 × 0.60 = 46.38
├─ Overall:   29.44 + 46.38 = 75.82
├─ Decimal:   75.82 → 3.00 (C-)
└─ Status:    ✅ PASSED (AT THRESHOLD - Barely)
```

### Scenario 4: Failing Student ✗
```
Midterm Assessment:
├─ Knowledge: 65  → 60% of term
├─ Skills:    62  → 50% of term
├─ Attitude:  60  → 10% of term
└─ Midterm Grade: 62.80 → 5.00 (F) → ❌ FAILED

Final Assessment:
├─ Knowledge: 68  → 60% of term
├─ Skills:    64  → 50% of term
├─ Attitude:  63  → 10% of term
└─ Final Grade: 65.40 → 5.00 (F) → ❌ FAILED

Semester Overall:
├─ Midterm:   62.80 × 0.40 = 25.12
├─ Final:     65.40 × 0.60 = 39.24
├─ Overall:   25.12 + 39.24 = 64.36
├─ Decimal:   64.36 → 5.00 (F)
└─ Status:    ❌ FAILED (Below Standards)
```

---

## 🔄 Grade Calculation Flow

```
INPUT SCORES (0-100)
    │
    ├─ Midterm Knowledge Average
    ├─ Midterm Skills Average
    └─ Midterm Attitude Average
    │
    ▼
CALCULATE MIDTERM GRADE
    │
    └─ Formula: (K×0.40) + (S×0.50) + (A×0.10)
    │
    ├─ Result: Numeric (0-100)
    ├─ Convert: to Decimal (1.0-5.0)
    └─ Status: Passed/Failed
    │
    ▼
INPUT SCORES (0-100)
    │
    ├─ Final Knowledge Average
    ├─ Final Skills Average
    └─ Final Attitude Average
    │
    ▼
CALCULATE FINAL GRADE
    │
    └─ Formula: (K×0.40) + (S×0.50) + (A×0.10)
    │
    ├─ Result: Numeric (0-100)
    ├─ Convert: to Decimal (1.0-5.0)
    └─ Status: Passed/Failed
    │
    ▼
CALCULATE OVERALL SEMESTER GRADE
    │
    └─ Formula: (Midterm×0.40) + (Final×0.60)
    │
    ├─ Result: Numeric (0-100)
    ├─ Convert: to Decimal (1.0-5.0)
    ├─ Status: Passed/Failed
    ├─ Label: Grade letter
    └─ Remarks: Performance description
    │
    ▼
OUTPUT
    ├─ Overall Grade (0-100)
    ├─ Decimal Grade (1.0-5.0)
    ├─ Pass/Fail Status
    ├─ Letter Grade (A+, A, B, etc.)
    └─ Performance Remarks
```

---

## 🎓 Grade Component Weights

```
MIDTERM GRADE (40% of Semester)
│
├─ Knowledge (40% of term grade)
│  ├─ Quizzes: 40% of Knowledge
│  │  └─ 5 quizzes averaged
│  └─ Exams: 60% of Knowledge
│     └─ Prelim + Midterm averaged
│
├─ Skills (50% of term grade)
│  ├─ Output: 40% of Skills
│  ├─ Class Participation: 30% of Skills
│  ├─ Activities: 15% of Skills
│  └─ Assignments: 15% of Skills
│
└─ Attitude (10% of term grade)
   ├─ Behavior: 50% of Attitude
   └─ Awareness: 50% of Attitude

═════════════════════════════════════════════════════════════

FINAL GRADE (60% of Semester)
│
├─ Knowledge (40% of term grade)
│  ├─ Quizzes: 40% of Knowledge
│  │  └─ 5 quizzes averaged
│  └─ Exams: 60% of Knowledge
│     └─ Midterm + Final averaged
│
├─ Skills (50% of term grade)
│  ├─ Output: 40% of Skills
│  ├─ Class Participation: 30% of Skills
│  ├─ Activities: 15% of Skills
│  └─ Assignments: 15% of Skills
│
└─ Attitude (10% of term grade)
   ├─ Behavior: 50% of Attitude
   └─ Awareness: 50% of Attitude

═════════════════════════════════════════════════════════════

SEMESTER OVERALL
│
├─ Midterm Grade: 40% weight
└─ Final Grade: 60% weight
```

---

## 💾 How Functions Map to Data

```
GradeHelper::calculateTermGradeWithDecimal($K, $S, $A)
                                │
                                └─► Returns:
                                    ├─ term_grade (0-100)
                                    ├─ decimal_grade (1.0-5.0)
                                    ├─ status (Passed/Failed)
                                    ├─ remarks (Performance text)
                                    └─ grade_label (Letter grade)

GradeHelper::getCompleteGradeSummary($mK, $mS, $mA, $fK, $fS, $fA)
                                        │
                                        └─► Returns:
                                            ├─ midterm[all fields above]
                                            ├─ final[all fields above]
                                            ├─ overall[all fields above]
                                            └─ summary[consolidated data]

Grade::calculateAndUpdateGrades()
                    │
                    └─► Updates fields:
                        ├─ mid_final_grade
                        ├─ mid_decimal_grade
                        ├─ final_final_grade
                        ├─ final_decimal_grade
                        ├─ overall_grade
                        ├─ grade_5pt_scale
                        ├─ letter_grade
                        └─ remarks
```

---

## 🎯 Common Decimal Grades

| Grade | Decimal | Status | Example Text |
|---|---|---|---|
| A+ | 1.00 | ✅ Passed | Exceptional student |
| A | 1.25 | ✅ Passed | Outstanding student |
| B+ | 1.75 | ✅ Passed | Very good student |
| B | 2.00 | ✅ Passed | Strong performance |
| C+ | 2.50 | ✅ Passed | Acceptable performance |
| C- | 3.00 | ✅ Passed | Minimum passing |
| D+ | 3.25 | ❌ Failed | Below passing |
| D | 3.50 | ❌ Failed | Significantly below |
| F | 5.00 | ❌ Failed | Failed course |

---

## 🔍 Quick Lookup

**What decimal grade means student passed?**  
→ Any grade ≤ 3.0

**What's the highest possible grade?**  
→ 1.00 (A+, for score 98-100)

**What's the lowest passing grade?**  
→ 3.00 (C-, for score 74-76)

**What's the lowest possible grade?**  
→ 5.00 (F, for score below 70)

**How is overall calculated?**  
→ (Midterm × 0.40) + (Final × 0.60)

**How is term grade calculated?**  
→ (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)

---

## 📱 Integration Quick Code

```php
// Calculate and display
$grade->calculateAndUpdateGrades();
echo $grade->overall_grade;        // 85.30
echo $grade->grade_5pt_scale;      // 2.00
echo $grade->letter_grade;         // B
echo $grade->remarks;              // Very Good - Strong performance
echo $grade->getPassFailStatus();  // Passed
```

---

## ✨ Color Coding Suggestion

For front-end display:
```
Green (✅ Passed):   Decimal Grade ≤ 3.0
Red (❌ Failed):     Decimal Grade > 3.0
Blue (ℹ️ Pending):   Decimal Grade not yet calculated
```

---

## 📊 Class Statistics Example

```
CLASS: Grade 11 Biology
PERIOD: 1st Semester

OVERALL STATISTICS:
│
├─ Total Students: 45
├─ Average Grade: 84.52
├─ Highest: 98.50 (1.00)
├─ Lowest: 58.30 (5.00)
│
├─ PASS/FAIL BREAKDOWN:
│  ├─ Passed: 42 students (93.3%)
│  ├─ Failed: 3 students (6.7%)
│  └─ Pass Rate: 93.3%
│
└─ GRADE DISTRIBUTION:
   ├─ A (1.0-1.75): 12 students
   ├─ B (2.0-2.75): 18 students
   ├─ C (2.75-3.0): 12 students
   └─ F (3.25-5.0): 3 students
```

---

## 🎓 CHED Philippines Standards Note

This grading system follows **CHED (Commission on Higher Education) Philippines** standards:
- ✅ Decimal scale (1.0-5.0)
- ✅ Knowledge: 40%
- ✅ Skills: 50%
- ✅ Attitude: 10%
- ✅ Midterm: 40%
- ✅ Final: 60%

---

## 📝 Reference Summary

**Start here**: GRADES_QUICKSTART.md  
**Detailed docs**: GRADING_CALCULATION_GUIDE.md  
**Code examples**: app/Examples/GradeCalculationExamples.php  
**Implementation**: GRADE_SYSTEM_IMPLEMENTATION_SUMMARY.md  

---

**Grade Calculation System v1.0 - Ready to Deploy**
