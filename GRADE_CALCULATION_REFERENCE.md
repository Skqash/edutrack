# Grade Calculation Reference Guide

## Quick Reference: How Grades Are Calculated

### 📐 Main Formula

```
Final Grade = (Midterm Grade × 40%) + (Final Grade × 60%)
```

### 🎯 Term Grade Calculation (Midterm & Final)

```
Term Grade = (Knowledge × 40%) + (Skills × 50%) + (Attitude × 10%)
```

---

## Component Breakdown

### 1️⃣ Knowledge (40% of term grade)

**Formula:**
```
Knowledge = (Exam × 60%) + (Quizzes × 40%)
```

**Components:**
- **Exam**: 60% weight
  - Midterm: exam_md
  - Final: exam_fn
- **Quizzes**: 40% weight
  - Average of quiz_1, quiz_2, quiz_3, quiz_4, quiz_5

**Example:**
```
Exam = 90
Quizzes = (85 + 88 + 90 + 87 + 85) / 5 = 87
Knowledge = (90 × 0.60) + (87 × 0.40) = 54 + 34.8 = 88.8
```

---

### 2️⃣ Skills (50% of term grade)

**Formula:**
```
Skills = (Output × 40%) + (Class Participation × 30%) + (Activities × 15%) + (Assignments × 15%)
```

**Components:**
- **Output**: 40% weight
  - Average of output_1, output_2, output_3
- **Class Participation**: 30% weight
  - Average of classpart_1, classpart_2, classpart_3
- **Activities**: 15% weight
  - Average of activity_1, activity_2, activity_3
- **Assignments**: 15% weight
  - Average of assignment_1, assignment_2, assignment_3

**Example:**
```
Output = (90 + 88 + 92) / 3 = 90
Class Participation = (85 + 87 + 86) / 3 = 86
Activities = (88 + 90 + 89) / 3 = 89
Assignments = (87 + 88 + 90) / 3 = 88.33

Skills = (90 × 0.40) + (86 × 0.30) + (89 × 0.15) + (88.33 × 0.15)
Skills = 36 + 25.8 + 13.35 + 13.25 = 88.4
```

---

### 3️⃣ Attitude (10% of term grade)

**Formula:**
```
Attitude = (Behavior × 50%) + (Awareness × 50%)
```

**Components:**
- **Behavior**: 50% weight
  - Average of behavior_1, behavior_2, behavior_3
- **Awareness/Class Participation**: 50% weight
  - Average of awareness_1, awareness_2, awareness_3

**Example:**
```
Behavior = (90 + 92 + 91) / 3 = 91
Awareness = (88 + 90 + 89) / 3 = 89

Attitude = (91 × 0.50) + (89 × 0.50) = 45.5 + 44.5 = 90
```

---

## Complete Example Calculation

### Student: Maria Santos

#### Midterm Term

**Knowledge:**
- Exam: 85
- Quizzes: (80, 82, 85, 83, 85) = 83
- Knowledge = (85 × 0.60) + (83 × 0.40) = 51 + 33.2 = **84.2**

**Skills:**
- Output: (88, 90, 87) = 88.33
- Class Part: (85, 86, 87) = 86
- Activities: (90, 88, 89) = 89
- Assignments: (87, 88, 86) = 87
- Skills = (88.33 × 0.40) + (86 × 0.30) + (89 × 0.15) + (87 × 0.15) = **87.53**

**Attitude:**
- Behavior: (90, 91, 92) = 91
- Awareness: (88, 89, 90) = 89
- Attitude = (91 × 0.50) + (89 × 0.50) = **90**

**Midterm Grade:**
```
Midterm = (84.2 × 0.40) + (87.53 × 0.50) + (90 × 0.10)
Midterm = 33.68 + 43.77 + 9 = 86.45
```

#### Final Term

**Knowledge:**
- Exam: 90
- Quizzes: (88, 90, 87, 89, 91) = 89
- Knowledge = (90 × 0.60) + (89 × 0.40) = 54 + 35.6 = **89.6**

**Skills:**
- Output: (92, 90, 93) = 91.67
- Class Part: (88, 90, 89) = 89
- Activities: (91, 92, 90) = 91
- Assignments: (90, 89, 91) = 90
- Skills = (91.67 × 0.40) + (89 × 0.30) + (91 × 0.15) + (90 × 0.15) = **90.37**

**Attitude:**
- Behavior: (93, 94, 92) = 93
- Awareness: (90, 91, 92) = 91
- Attitude = (93 × 0.50) + (91 × 0.50) = **92**

**Final Grade:**
```
Final = (89.6 × 0.40) + (90.37 × 0.50) + (92 × 0.10)
Final = 35.84 + 45.19 + 9.2 = 90.23
```

#### Overall Final Grade

```
Overall = (Midterm × 0.40) + (Final × 0.60)
Overall = (86.45 × 0.40) + (90.23 × 0.60)
Overall = 34.58 + 54.14 = 88.72
```

**Decimal Grade:** 1.75 (Very Good)
**Status:** ✅ Passed

---

## Decimal Grade Conversion Table

| Numeric Grade | Decimal Grade | Letter | Status | Description |
|---------------|---------------|--------|--------|-------------|
| 98-100 | 1.00 | A+ | Passed | Excellent |
| 95-97 | 1.25 | A | Passed | Excellent |
| 92-94 | 1.50 | A- | Passed | Excellent |
| 89-91 | 1.75 | B+ | Passed | Very Good |
| 86-88 | 2.00 | B | Passed | Very Good |
| 83-85 | 2.25 | B- | Passed | Very Good |
| 80-82 | 2.50 | C+ | Passed | Good |
| 77-79 | 2.75 | C | Passed | Good |
| 74-76 | 3.00 | C- | Passed | Satisfactory |
| 71-73 | 3.25 | D+ | Failed | Fair |
| 70 | 3.50 | D | Failed | Fair |
| Below 70 | 5.00 | F | Failed | Failed |

---

## Weight Summary

### Term Grade Weights
- Knowledge: **40%**
- Skills: **50%**
- Attitude: **10%**

### Knowledge Component Weights
- Exam: **60%**
- Quizzes: **40%**

### Skills Component Weights
- Output: **40%**
- Class Participation: **30%**
- Activities: **15%**
- Assignments: **15%**

### Attitude Component Weights
- Behavior: **50%**
- Awareness: **50%**

### Final Grade Weights
- Midterm: **40%**
- Final: **60%**

---

## Quick Calculation Checklist

✅ **Step 1:** Calculate Knowledge average
- [ ] Get exam score
- [ ] Calculate quiz average
- [ ] Apply weights: Exam (60%) + Quiz (40%)

✅ **Step 2:** Calculate Skills average
- [ ] Calculate output average
- [ ] Calculate class participation average
- [ ] Calculate activities average
- [ ] Calculate assignments average
- [ ] Apply weights: Output (40%) + ClassPart (30%) + Activities (15%) + Assignments (15%)

✅ **Step 3:** Calculate Attitude average
- [ ] Calculate behavior average
- [ ] Calculate awareness average
- [ ] Apply weights: Behavior (50%) + Awareness (50%)

✅ **Step 4:** Calculate Term Grade
- [ ] Apply KSA weights: K (40%) + S (50%) + A (10%)

✅ **Step 5:** Calculate Final Grade
- [ ] Apply term weights: Midterm (40%) + Final (60%)

✅ **Step 6:** Convert to Decimal Grade
- [ ] Use conversion table (1.0-5.0 scale)

✅ **Step 7:** Determine Status
- [ ] Passed if decimal ≤ 3.0
- [ ] Failed if decimal > 3.0

---

## Common Questions

**Q: Why is the final term weighted more than midterm?**
A: The final term (60%) is weighted more because it represents cumulative learning and demonstrates mastery of the entire course content.

**Q: What happens if a student misses a quiz?**
A: Missing quizzes are typically recorded as 0, which will lower the quiz average. Check with your institution's policy on make-up assessments.

**Q: Can weights be customized?**
A: The current system uses standard CHED weights. Customization may be available in future updates.

**Q: How is attendance factored in?**
A: Attendance can be included in the Attitude component, specifically in the Awareness/Class Participation score.

---

**Last Updated:** April 15, 2026
**Version:** 1.0
**Reference:** Based on CHED Philippines grading standards
