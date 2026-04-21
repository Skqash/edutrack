# Grade Summary View - Visual Guide

## Overview

This guide shows what the new Grade Summary view looks like and how to interpret the data.

## Page Layout

```
┌─────────────────────────────────────────────────────────────────┐
│  📊 Comprehensive Grade Summary                                 │
│  Detailed KSA component breakdown with midterm and final        │
│                                                                  │
│  [← Back to Grades]  [🖨️ Print Summary]                        │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  📐 Grade Calculation Formula                                   │
│                                                                  │
│  Midterm Grade (40%): Knowledge (40%) + Skills (50%) +         │
│                       Attitude (10%)                            │
│  Final Grade (60%):   Knowledge (40%) + Skills (50%) +         │
│                       Attitude (10%)                            │
│  Overall Grade:       (Midterm × 40%) + (Final × 60%)          │
│                                                                  │
│  Knowledge: Exam (60%) + Quizzes (40%)                         │
│  Skills: Output (40%) + Class Part (30%) + Activities (15%) +  │
│          Assignments (15%)                                      │
│  Attitude: Behavior (50%) + Awareness (50%)                    │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  Legend:                                                         │
│  [K] Knowledge  [S] Skills  [A] Attitude                        │
│  [Mid] Midterm (40%)  [Final] Final (60%)                       │
└─────────────────────────────────────────────────────────────────┘
```

## Class Summary Card

```
┌─────────────────────────────────────────────────────────────────┐
│  BSIT 2A - Programming Fundamentals                             │
│  📚 Bachelor of Science in Information Technology               │
│  👥 32 Students  ✅ 30 Graded                                   │
├─────────────────────────────────────────────────────────────────┤
│  Statistics:                                                     │
│  ┌──────────────┬──────────────┬──────────────┬──────────────┐ │
│  │ Avg Midterm  │  Avg Final   │ Avg Overall  │  Pass Rate   │ │
│  │    86.45     │    89.23     │    88.12     │    93.3%     │ │
│  └──────────────┴──────────────┴──────────────┴──────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

## Grade Table Structure

```
┌──────────────────┬─────────────────────────────────────────────┬─────────────────────────────────────────────┬──────────────┐
│                  │         MIDTERM (40%)                       │          FINAL (60%)                        │              │
│  Student Name    ├──────┬──────┬──────┬──────────────────────┼──────┬──────┬──────┬──────────────────────┤ Final Grade  │
│                  │  K   │  S   │  A   │    Mid Grade         │  K   │  S   │  A   │   Final Grade        │              │
│                  │(40%) │(50%) │(10%) │    (Weighted)        │(40%) │(50%) │(10%) │   (Weighted)         │   Overall    │
├──────────────────┼──────┼──────┼──────┼──────────────────────┼──────┼──────┼──────┼──────────────────────┼──────────────┤
│ ABENIR,          │      │      │      │                      │      │      │      │                      │              │
│ CHRISTEL C       │ 92.00│ 87.00│ 83.00│      88.00           │ 94.00│ 87.00│ 83.00│      89.00           │    88.60     │
│ ID: 2021-0001    │      │      │      │                      │      │      │      │                      │ 1.75 / Pass  │
├──────────────────┼──────┼──────┼──────┼──────────────────────┼──────┼──────┼──────┼──────────────────────┼──────────────┤
│ ALGARME,         │      │      │      │                      │      │      │      │                      │              │
│ JOHN REY H       │ 94.00│ 87.00│ 85.00│      90.00           │100.00│ 88.00│ 85.00│      93.00           │    91.80     │
│ ID: 2021-0002    │      │      │      │                      │      │      │      │                      │ 1.50 / Pass  │
└──────────────────┴──────┴──────┴──────┴──────────────────────┴──────┴──────┴──────┴──────────────────────┴──────────────┘
```

## Color Coding

### Header Colors

```
┌──────────────────────────────────────────────────────────────┐
│  Knowledge (K)     │  Light Blue (#dbeafe)                   │
│  Skills (S)        │  Light Green (#d1fae5)                  │
│  Attitude (A)      │  Light Purple (#e9d5ff)                 │
│  Midterm           │  Light Yellow (#fef3c7)                 │
│  Final             │  Light Orange (#fed7aa)                 │
└──────────────────────────────────────────────────────────────┘
```

### Performance Colors

```
┌──────────────────────────────────────────────────────────────┐
│  Excellent (90-100)  │  🟢 Green (#059669)                   │
│  Good (80-89)        │  🔵 Blue (#0891b2)                    │
│  Average (75-79)     │  🟠 Orange (#d97706)                  │
│  Poor (<75)          │  🔴 Red (#dc2626)                     │
└──────────────────────────────────────────────────────────────┘
```

## Example: Reading a Student's Grade

### Student: ABENIR, CHRISTEL C (ID: 2021-0001)

```
┌─────────────────────────────────────────────────────────────────┐
│  MIDTERM (40% of final grade)                                   │
├─────────────────────────────────────────────────────────────────┤
│  Knowledge (40%):  92.00  🟢 Excellent                          │
│  Skills (50%):     87.00  🔵 Good                               │
│  Attitude (10%):   83.00  🔵 Good                               │
│  ─────────────────────────────────────────────────────────────  │
│  Midterm Grade:    88.00                                        │
│                                                                  │
│  Calculation:                                                    │
│  (92.00 × 0.40) + (87.00 × 0.50) + (83.00 × 0.10) = 88.00      │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  FINAL (60% of final grade)                                     │
├─────────────────────────────────────────────────────────────────┤
│  Knowledge (40%):  94.00  🟢 Excellent                          │
│  Skills (50%):     87.00  🔵 Good                               │
│  Attitude (10%):   83.00  🔵 Good                               │
│  ─────────────────────────────────────────────────────────────  │
│  Final Grade:      89.00                                        │
│                                                                  │
│  Calculation:                                                    │
│  (94.00 × 0.40) + (87.00 × 0.50) + (83.00 × 0.10) = 89.00      │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  OVERALL FINAL GRADE                                            │
├─────────────────────────────────────────────────────────────────┤
│  Midterm Contribution (40%):  88.00 × 0.40 = 35.20             │
│  Final Contribution (60%):    89.00 × 0.60 = 53.40             │
│  ─────────────────────────────────────────────────────────────  │
│  Overall Grade:               88.60                             │
│  Decimal Grade:               1.75                              │
│  Status:                      ✅ Passed                         │
└─────────────────────────────────────────────────────────────────┘
```

## Component Breakdown Details

### Knowledge Component

```
┌─────────────────────────────────────────────────────────────────┐
│  Knowledge = (Exam × 60%) + (Quizzes × 40%)                     │
├─────────────────────────────────────────────────────────────────┤
│  Example:                                                        │
│  Exam Score:    90                                              │
│  Quiz Average:  (85 + 88 + 90 + 87 + 85) / 5 = 87              │
│                                                                  │
│  Knowledge = (90 × 0.60) + (87 × 0.40)                         │
│            = 54 + 34.8                                          │
│            = 88.8                                               │
└─────────────────────────────────────────────────────────────────┘
```

### Skills Component

```
┌─────────────────────────────────────────────────────────────────┐
│  Skills = (Output × 40%) + (Class Part × 30%) +                │
│           (Activities × 15%) + (Assignments × 15%)              │
├─────────────────────────────────────────────────────────────────┤
│  Example:                                                        │
│  Output Average:        (90 + 88 + 92) / 3 = 90                │
│  Class Part Average:    (85 + 87 + 86) / 3 = 86                │
│  Activities Average:    (88 + 90 + 89) / 3 = 89                │
│  Assignments Average:   (87 + 88 + 90) / 3 = 88.33             │
│                                                                  │
│  Skills = (90 × 0.40) + (86 × 0.30) + (89 × 0.15) +           │
│           (88.33 × 0.15)                                        │
│         = 36 + 25.8 + 13.35 + 13.25                            │
│         = 88.4                                                  │
└─────────────────────────────────────────────────────────────────┘
```

### Attitude Component

```
┌─────────────────────────────────────────────────────────────────┐
│  Attitude = (Behavior × 50%) + (Awareness × 50%)                │
├─────────────────────────────────────────────────────────────────┤
│  Example:                                                        │
│  Behavior Average:   (90 + 92 + 91) / 3 = 91                   │
│  Awareness Average:  (88 + 90 + 89) / 3 = 89                   │
│                                                                  │
│  Attitude = (91 × 0.50) + (89 × 0.50)                          │
│           = 45.5 + 44.5                                         │
│           = 90                                                  │
└─────────────────────────────────────────────────────────────────┘
```

## Decimal Grade Scale Reference

```
┌──────────────┬──────────────┬────────┬────────┬──────────────────┐
│ Numeric      │ Decimal      │ Letter │ Status │ Description      │
├──────────────┼──────────────┼────────┼────────┼──────────────────┤
│ 98-100       │ 1.00         │ A+     │ Pass   │ Excellent        │
│ 95-97        │ 1.25         │ A      │ Pass   │ Excellent        │
│ 92-94        │ 1.50         │ A-     │ Pass   │ Excellent        │
│ 89-91        │ 1.75         │ B+     │ Pass   │ Very Good        │
│ 86-88        │ 2.00         │ B      │ Pass   │ Very Good        │
│ 83-85        │ 2.25         │ B-     │ Pass   │ Very Good        │
│ 80-82        │ 2.50         │ C+     │ Pass   │ Good             │
│ 77-79        │ 2.75         │ C      │ Pass   │ Good             │
│ 74-76        │ 3.00         │ C-     │ Pass   │ Satisfactory     │
│ 71-73        │ 3.25         │ D+     │ Fail   │ Fair             │
│ 70           │ 3.50         │ D      │ Fail   │ Fair             │
│ Below 70     │ 5.00         │ F      │ Fail   │ Failed           │
└──────────────┴──────────────┴────────┴────────┴──────────────────┘
```

## Print View

When you click "Print Summary", the view is optimized for paper:

```
┌─────────────────────────────────────────────────────────────────┐
│  CENTRAL PHILIPPINES STATE UNIVERSITY                           │
│  VICTORIAS CAMPUS                                               │
│  Barangay XIV, Victorias City, Negros Occidental                │
│                                                                  │
│  GRADING SHEET                                                  │
│                                                                  │
│  Subject: CCIT 04                                               │
│  Course/Yr/Section: BSIT 2A                                     │
│  Academic Year: 2023-2024                                       │
│  Semester: 1st Semester                                         │
│                                                                  │
│  [Full Grade Table - Same as screen view]                       │
│                                                                  │
│  ___________________________    ___________________________     │
│  Instructor                     Program Head                    │
│                                                                  │
│  ___________________________                                     │
│  Campus Registrar                                               │
└─────────────────────────────────────────────────────────────────┘
```

## Mobile View

On mobile devices, the table scrolls horizontally while keeping student names visible:

```
┌─────────────────────────────────┐
│  Student Name    │ [Scroll →]   │
├──────────────────┼──────────────┤
│  ABENIR,         │              │
│  CHRISTEL C      │  [K][S][A]   │
│  ID: 2021-0001   │  [Mid][Fin]  │
├──────────────────┼──────────────┤
│  ALGARME,        │              │
│  JOHN REY H      │  [K][S][A]   │
│  ID: 2021-0002   │  [Mid][Fin]  │
└──────────────────┴──────────────┘
```

## Quick Tips

### For Teachers:

1. **Understanding Colors**: 
   - Green = Excellent performance
   - Blue = Good performance
   - Orange = Average performance
   - Red = Needs improvement

2. **Reading the Table**:
   - Left side = Midterm (40% weight)
   - Right side = Final (60% weight)
   - Far right = Overall final grade

3. **Checking Calculations**:
   - Each term grade shows weighted average
   - Final grade combines both terms
   - Decimal grade shows 1.0-5.0 scale

4. **Using Statistics**:
   - Check class average to gauge overall performance
   - Monitor pass rate to identify struggling students
   - Compare midterm vs final to see improvement

### For Students:

1. **Find Your Grade**:
   - Locate your name in the first column
   - Read across to see all components

2. **Understand Your Score**:
   - K = Knowledge (exams and quizzes)
   - S = Skills (outputs, activities, etc.)
   - A = Attitude (behavior and participation)

3. **Check Your Status**:
   - Look at the final column
   - Green "Passed" = You passed!
   - Red "Failed" = Need to improve

4. **Identify Weak Areas**:
   - Compare your K, S, A scores
   - Focus on improving lower scores

---

**Last Updated**: April 15, 2026
**Version**: 1.0
