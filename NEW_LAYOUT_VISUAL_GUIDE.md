# New Separate KSA Tables - Visual Guide

## Page Layout Overview

```
┌─────────────────────────────────────────────────────────────────┐
│  📊 Comprehensive Grade Summary                                 │
│  [← Back to Grades]  [🖨️ Print Summary]                        │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  📐 Grade Calculation Formula                                   │
│  [Formula details...]                                           │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  Legend: [K] [S] [A] [Midterm 40%] [Final 60%]                 │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  BSIT 2A - Programming Fundamentals                             │
│  Statistics: [Avg Midterm] [Avg Final] [Overall] [Pass Rate]   │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  📊 MIDTERM GRADES (40% of Final Grade)                         │
│  [Midterm KSA Table]                                            │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│  📊 FINAL GRADES (60% of Final Grade)                           │
│  [Final KSA Table]                                              │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│  🎯 OVERALL FINAL GRADE (Midterm 40% + Final 60%)              │
│  [Overall Summary Table]                                        │
└─────────────────────────────────────────────────────────────────┘
```

## Table 1: Midterm Grades (40%)

### Header
```
┌─────────────────────────────────────────────────────────────────┐
│  📊 MIDTERM GRADES (40% of Final Grade)                         │
│  Background: Yellow (#fef3c7)                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Table Structure
```
┌──────────────────┬──────────────┬──────────────┬──────────────┬──────────────┬──────────────┐
│                  │              │              │              │              │              │
│  Student Name    │  Knowledge   │    Skills    │   Attitude   │   Midterm    │   Decimal    │
│                  │    (40%)     │    (50%)     │    (10%)     │    Grade     │    Grade     │
│                  │              │              │              │              │              │
├──────────────────┼──────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│                  │              │              │              │              │              │
│ ABENIR,          │    92.00     │    87.00     │    83.00     │    88.00     │    1.75      │
│ CHRISTEL C       │              │              │              │              │              │
│ ID: 2021-0001    │ Exam+Quizzes │ Output+Act   │ Behavior+    │              │              │
│                  │              │              │   Awareness  │              │              │
│                  │              │              │              │              │              │
├──────────────────┼──────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│                  │              │              │              │              │              │
│ ALGARME,         │    94.00     │    87.00     │    85.00     │    90.00     │    1.75      │
│ JOHN REY H       │              │              │              │              │              │
│ ID: 2021-0002    │ Exam+Quizzes │ Output+Act   │ Behavior+    │              │              │
│                  │              │              │   Awareness  │              │              │
│                  │              │              │              │              │              │
└──────────────────┴──────────────┴──────────────┴──────────────┴──────────────┴──────────────┘
```

### Column Details

**Column 1: Student Name**
- Student full name
- Student ID below name
- Sticky (stays visible on scroll)

**Column 2: Knowledge (40%)**
- Blue header (#dbeafe)
- Shows knowledge score
- Label: "Exam+Quizzes"
- Color-coded by performance

**Column 3: Skills (50%)**
- Green header (#d1fae5)
- Shows skills score
- Label: "Output+Act"
- Color-coded by performance

**Column 4: Attitude (10%)**
- Purple header (#e9d5ff)
- Shows attitude score
- Label: "Behavior+Awareness"
- Color-coded by performance

**Column 5: Midterm Grade**
- Yellow background (#fef9e7)
- Weighted average of K, S, A
- Bold text
- Formula: (K×40%) + (S×50%) + (A×10%)

**Column 6: Decimal Grade**
- Shows 1.0-5.0 scale
- Converted from midterm grade
- Bold text

## Table 2: Final Grades (60%)

### Header
```
┌─────────────────────────────────────────────────────────────────┐
│  📊 FINAL GRADES (60% of Final Grade)                           │
│  Background: Orange (#fed7aa)                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Table Structure
```
┌──────────────────┬──────────────┬──────────────┬──────────────┬──────────────┬──────────────┐
│                  │              │              │              │              │              │
│  Student Name    │  Knowledge   │    Skills    │   Attitude   │    Final     │   Decimal    │
│                  │    (40%)     │    (50%)     │    (10%)     │    Grade     │    Grade     │
│                  │              │              │              │              │              │
├──────────────────┼──────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│                  │              │              │              │              │              │
│ ABENIR,          │    94.00     │    87.00     │    83.00     │    89.00     │    1.75      │
│ CHRISTEL C       │              │              │              │              │              │
│ ID: 2021-0001    │ Exam+Quizzes │ Output+Act   │ Behavior+    │              │              │
│                  │              │              │   Awareness  │              │              │
│                  │              │              │              │              │              │
├──────────────────┼──────────────┼──────────────┼──────────────┼──────────────┼──────────────┤
│                  │              │              │              │              │              │
│ ALGARME,         │   100.00     │    88.00     │    85.00     │    93.00     │    1.50      │
│ JOHN REY H       │              │              │              │              │              │
│ ID: 2021-0002    │ Exam+Quizzes │ Output+Act   │ Behavior+    │              │              │
│                  │              │              │   Awareness  │              │              │
│                  │              │              │              │              │              │
└──────────────────┴──────────────┴──────────────┴──────────────┴──────────────┴──────────────┘
```

### Column Details

Same structure as Midterm table, but with:
- Orange background for Final Grade column (#fff5e6)
- Final term data instead of midterm

## Table 3: Overall Final Grade

### Header
```
┌─────────────────────────────────────────────────────────────────┐
│  🎯 OVERALL FINAL GRADE (Midterm 40% + Final 60%)              │
│  Background: Purple Gradient                                    │
└─────────────────────────────────────────────────────────────────┘
```

### Table Structure
```
┌──────────────┬─────────┬──────────┬─────────┬──────────┬─────────┬─────────┬──────────┐
│              │         │ Midterm  │         │  Final   │         │         │          │
│ Student Name │ Midterm │Contribu- │  Final  │Contribu- │ Overall │ Decimal │  Status  │
│              │  Grade  │tion(40%) │  Grade  │tion(60%) │  Grade  │  Grade  │          │
│              │         │          │         │          │         │         │          │
├──────────────┼─────────┼──────────┼─────────┼──────────┼─────────┼─────────┼──────────┤
│              │         │          │         │          │         │         │          │
│ ABENIR,      │  88.00  │  35.20   │  89.00  │  53.40   │  88.60  │  1.75   │ ✅ Passed│
│ CHRISTEL C   │         │          │         │          │         │         │          │
│ ID:2021-0001 │         │          │         │          │         │         │          │
│              │         │          │         │          │         │         │          │
├──────────────┼─────────┼──────────┼─────────┼──────────┼─────────┼─────────┼──────────┤
│              │         │          │         │          │         │         │          │
│ ALGARME,     │  90.00  │  36.00   │  93.00  │  55.80   │  91.80  │  1.50   │ ✅ Passed│
│ JOHN REY H   │         │          │         │          │         │         │          │
│ ID:2021-0002 │         │          │         │          │         │         │          │
│              │         │          │         │          │         │         │          │
└──────────────┴─────────┴──────────┴─────────┴──────────┴─────────┴─────────┴──────────┘
```

### Column Details

**Column 1: Student Name**
- Same as other tables

**Column 2: Midterm Grade**
- Yellow background
- From Midterm table

**Column 3: Midterm Contribution (40%)**
- Shows: Midterm × 0.40
- Brown text (#92400e)
- Bold

**Column 4: Final Grade**
- Orange background
- From Final table

**Column 5: Final Contribution (60%)**
- Shows: Final × 0.60
- Dark orange text (#9a3412)
- Bold

**Column 6: Overall Grade**
- Purple background (#e9d5ff)
- Sum of contributions
- Large font (1.1rem)
- Bold

**Column 7: Decimal Grade**
- Shows 1.0-5.0 scale
- Bold
- Large font (1rem)

**Column 8: Status**
- Badge style
- Green for Passed (✅)
- Red for Failed (❌)

## Color Legend

### Header Colors
```
┌─────────────────────────────────────────────────────────────────┐
│  Knowledge (K)     │  🔵 Blue (#dbeafe)                         │
│  Skills (S)        │  🟢 Green (#d1fae5)                        │
│  Attitude (A)      │  🟣 Purple (#e9d5ff)                       │
│  Midterm Section   │  🟡 Yellow (#fef3c7)                       │
│  Final Section     │  🟠 Orange (#fed7aa)                       │
│  Overall Section   │  🟣 Purple Gradient                        │
└─────────────────────────────────────────────────────────────────┘
```

### Performance Colors
```
┌─────────────────────────────────────────────────────────────────┐
│  Excellent (90-100)  │  🟢 Green (#059669)                      │
│  Good (80-89)        │  🔵 Blue (#0891b2)                       │
│  Average (75-79)     │  🟠 Orange (#d97706)                     │
│  Poor (<75)          │  🔴 Red (#dc2626)                        │
└─────────────────────────────────────────────────────────────────┘
```

## Example: Complete Student View

### Student: ABENIR, CHRISTEL C (ID: 2021-0001)

#### Midterm Table Entry
```
┌──────────────────┬──────────┬──────────┬──────────┬──────────┬──────────┐
│ ABENIR,          │  92.00   │  87.00   │  83.00   │  88.00   │   1.75   │
│ CHRISTEL C       │   🟢     │   🔵     │   🔵     │   🔵     │          │
│ ID: 2021-0001    │Exam+Quiz │Output+Act│Behavior  │          │          │
└──────────────────┴──────────┴──────────┴──────────┴──────────┴──────────┘
```

#### Final Table Entry
```
┌──────────────────┬──────────┬──────────┬──────────┬──────────┬──────────┐
│ ABENIR,          │  94.00   │  87.00   │  83.00   │  89.00   │   1.75   │
│ CHRISTEL C       │   🟢     │   🔵     │   🔵     │   🔵     │          │
│ ID: 2021-0001    │Exam+Quiz │Output+Act│Behavior  │          │          │
└──────────────────┴──────────┴──────────┴──────────┴──────────┴──────────┘
```

#### Overall Table Entry
```
┌──────────────┬─────────┬──────────┬─────────┬──────────┬─────────┬─────────┬──────────┐
│ ABENIR,      │  88.00  │  35.20   │  89.00  │  53.40   │  88.60  │  1.75   │ ✅ Passed│
│ CHRISTEL C   │         │          │         │          │         │         │          │
│ ID:2021-0001 │         │          │         │          │         │         │          │
└──────────────┴─────────┴──────────┴─────────┴──────────┴─────────┴─────────┴──────────┘
```

## Calculation Flow

```
MIDTERM:
Knowledge: 92.00 × 40% = 36.80
Skills:    87.00 × 50% = 43.50
Attitude:  83.00 × 10% =  8.30
                         ------
Midterm Grade:           88.60
                            ↓
                    Decimal: 1.75

FINAL:
Knowledge: 94.00 × 40% = 37.60
Skills:    87.00 × 50% = 43.50
Attitude:  83.00 × 10% =  8.30
                         ------
Final Grade:             89.40
                            ↓
                    Decimal: 1.75

OVERALL:
Midterm:   88.60 × 40% = 35.44
Final:     89.40 × 60% = 53.64
                         ------
Overall Grade:           89.08
                            ↓
                    Decimal: 1.75
                            ↓
                    Status: PASSED ✅
```

## Mobile View

On mobile devices, each table scrolls independently:

```
┌─────────────────────────────┐
│  📊 MIDTERM GRADES          │
├─────────────────────────────┤
│ Student Name │ [Scroll →]   │
├──────────────┼──────────────┤
│ ABENIR, C    │ K│S│A│Mid│Dec│
└──────────────┴──────────────┘
        ↓
┌─────────────────────────────┐
│  📊 FINAL GRADES            │
├─────────────────────────────┤
│ Student Name │ [Scroll →]   │
├──────────────┼──────────────┤
│ ABENIR, C    │ K│S│A│Fin│Dec│
└──────────────┴──────────────┘
        ↓
┌─────────────────────────────┐
│  🎯 OVERALL GRADE           │
├─────────────────────────────┤
│ Student Name │ [Scroll →]   │
├──────────────┼──────────────┤
│ ABENIR, C    │Mid│Fin│Overall│
└──────────────┴──────────────┘
```

## Print View

When printed, all three tables appear on separate pages or sections:

```
Page 1:
┌─────────────────────────────────────────┐
│  CENTRAL PHILIPPINES STATE UNIVERSITY   │
│  MIDTERM GRADES                         │
│  [Midterm Table]                        │
└─────────────────────────────────────────┘

Page 2:
┌─────────────────────────────────────────┐
│  CENTRAL PHILIPPINES STATE UNIVERSITY   │
│  FINAL GRADES                           │
│  [Final Table]                          │
└─────────────────────────────────────────┘

Page 3:
┌─────────────────────────────────────────┐
│  CENTRAL PHILIPPINES STATE UNIVERSITY   │
│  OVERALL FINAL GRADES                   │
│  [Overall Table]                        │
└─────────────────────────────────────────┘
```

---

**Last Updated:** April 15, 2026
**Version:** 2.0
**Layout:** 3 Separate Tables
