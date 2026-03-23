# Attendance & Grade Integration Flow

## Visual Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    ATTENDANCE RECORDING                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Teacher Records │
                    │   Attendance     │
                    │  (Daily Basis)   │
                    └──────────────────┘
                              │
                              ▼
                ┌──────────────────────────────┐
                │   Attendance Table           │
                │  ─────────────────────       │
                │  • student_id                │
                │  • class_id                  │
                │  • date                      │
                │  • status (Present/Late/     │
                │    Absent/Leave)             │
                │  • term (Midterm/Final)      │
                └──────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                  ATTENDANCE CALCULATION                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
              ┌────────────────────────────────┐
              │ AttendanceCalculationService   │
              │ ────────────────────────────   │
              │ Formula:                       │
              │ Score = (Count/Total)×50 + 50  │
              │                                │
              │ Count = Present + Late         │
              └────────────────────────────────┘
                              │
                              ▼
                ┌──────────────────────────────┐
                │  Attendance Score Calculated │
                │  ──────────────────────────  │
                │  Range: 50-100               │
                │  • 100% attendance = 100     │
                │  • 50% attendance = 75       │
                │  • 0% attendance = 50        │
                └──────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    GRADE CALCULATION                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
        ┌─────────────────────────────────────────┐
        │      Component Grades Entry             │
        │  ─────────────────────────────────────  │
        │  Knowledge (40%):                       │
        │    • Exam (60%)                         │
        │    • Quizzes (40%)                      │
        │                                         │
        │  Skills (50%):                          │
        │    • Output (40%)                       │
        │    • Participation (30%)                │
        │    • Activities (15%)                   │
        │    • Assignments (15%)                  │
        │                                         │
        │  Attitude (10%):                        │
        │    • Behavior (50%)                     │
        │    • Awareness (50%)                    │
        └─────────────────────────────────────────┘
                              │
                              ▼
              ┌────────────────────────────────┐
              │   Calculate Category Averages  │
              │  ────────────────────────────  │
              │  • Knowledge Ave = 85          │
              │  • Skills Ave = 90             │
              │  • Attitude Ave = 88           │
              └────────────────────────────────┘
                              │
                              ▼
              ┌────────────────────────────────┐
              │    Calculate Base Grade        │
              │  ────────────────────────────  │
              │  Base = (K×40%) + (S×50%)      │
              │         + (A×10%)              │
              │  Base = 88.3                   │
              └────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              ATTENDANCE INTEGRATION (3 OPTIONS)                  │
└─────────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────────┐   ┌──────────────────┐
│  OPTION 1    │    │    OPTION 2      │   │    OPTION 3      │
│  Part of     │    │   Separate       │   │   Multiplier     │
│  Attitude    │    │   Component      │   │  (Recommended)   │
└──────────────┘    └──────────────────┘   └──────────────────┘
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────────┐   ┌──────────────────┐
│ Attitude =   │    │ Final =          │   │ Final =          │
│ (Behavior×   │    │ (K×35%) +        │   │ Base × (Att/100) │
│  50%) +      │    │ (S×45%) +        │   │                  │
│ (Awareness×  │    │ (A×10%) +        │   │ 88.3 × (85/100)  │
│  25%) +      │    │ (Att×10%)        │   │ = 75.06          │
│ (Att×25%)    │    │                  │   │                  │
└──────────────┘    └──────────────────┘   └──────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      FINAL GRADE OUTPUT                          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                ┌──────────────────────────────┐
                │   Final Grade Display        │
                │  ──────────────────────────  │
                │  • Base Grade: 88.3          │
                │  • Attendance: 85% (17/20)   │
                │  • Final Grade: 75.06        │
                │  • Status: Passed            │
                └──────────────────────────────┘
```

## Data Flow Example

### Step-by-Step Example

**Student: John Doe**
**Class: Computer Science 101**
**Term: Midterm**

#### 1. Attendance Records (20 meetings)
```
Present: 15 days
Late: 2 days
Absent: 3 days
```

#### 2. Attendance Calculation
```
Attendance Count = 15 + 2 = 17
Total Meetings = 20
Attendance Score = (17/20) × 50 + 50 = 92.5
Attendance Percentage = (17/20) × 100 = 85%
```

#### 3. Component Grades
```
Knowledge:
  - Midterm Exam: 85/100
  - Quiz 1: 90/100
  - Quiz 2: 88/100
  - Quiz 3: 92/100

Skills:
  - Output 1: 88/100
  - Output 2: 90/100
  - Output 3: 92/100
  - Participation 1: 85/100
  - Participation 2: 88/100
  - Participation 3: 90/100
  - Activity 1: 95/100
  - Activity 2: 93/100
  - Activity 3: 94/100
  - Assignment 1: 90/100
  - Assignment 2: 92/100
  - Assignment 3: 91/100

Attitude:
  - Behavior 1: 90/100
  - Behavior 2: 88/100
  - Behavior 3: 92/100
  - Awareness 1: 85/100
  - Awareness 2: 88/100
  - Awareness 3: 90/100
```

#### 4. Transmutation (x50+50)
```
Each score is transmuted:
Exam: (85/100 × 50) + 50 = 92.5
Quiz 1: (90/100 × 50) + 50 = 95.0
... (all scores transmuted)
```

#### 5. Subcategory Averages
```
Knowledge:
  - Exam Ave: 92.5
  - Quiz Ave: (95.0 + 94.0 + 96.0) / 3 = 95.0
  - Knowledge Ave: (92.5 × 60%) + (95.0 × 40%) = 93.5

Skills:
  - Output Ave: (94.0 + 95.0 + 96.0) / 3 = 95.0
  - Participation Ave: (92.5 + 94.0 + 95.0) / 3 = 93.8
  - Activity Ave: (97.5 + 96.5 + 97.0) / 3 = 97.0
  - Assignment Ave: (95.0 + 96.0 + 95.5) / 3 = 95.5
  - Skills Ave: (95.0×40%) + (93.8×30%) + (97.0×15%) + (95.5×15%) = 94.9

Attitude:
  - Behavior Ave: (95.0 + 94.0 + 96.0) / 3 = 95.0
  - Awareness Ave: (92.5 + 94.0 + 95.0) / 3 = 93.8
  - Attitude Ave: (95.0 × 50%) + (93.8 × 50%) = 94.4
```

#### 6. Base Grade
```
Base Grade = (93.5 × 40%) + (94.9 × 50%) + (94.4 × 10%)
Base Grade = 37.4 + 47.45 + 9.44 = 94.29
```

#### 7. Final Grade (with Attendance Multiplier)
```
Final Grade = Base Grade × (Attendance Score / 100)
Final Grade = 94.29 × (92.5 / 100)
Final Grade = 87.22
```

## Summary Table

| Component | Weight | Score | Contribution |
|-----------|--------|-------|--------------|
| Knowledge | 40% | 93.5 | 37.40 |
| Skills | 50% | 94.9 | 47.45 |
| Attitude | 10% | 94.4 | 9.44 |
| **Base Grade** | **100%** | **94.29** | **94.29** |
| Attendance | Multiplier | 92.5% | ×0.925 |
| **Final Grade** | - | **87.22** | **87.22** |

## Implementation Status

### ✅ Completed
- Attendance recording system
- Attendance calculation service
- Component-based grade entry
- Subcategory averages
- Grade calculation with KSA weights

### 🔄 In Progress
- Attendance integration with final grade
- UI display of attendance impact
- Attendance score in grade entry table

### 📋 To Do
- Add attendance column to grade entry
- Implement attendance multiplier option
- Add attendance configuration in class settings
- Create attendance impact visualization
- Add attendance warnings for low attendance
