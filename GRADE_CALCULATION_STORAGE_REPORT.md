# EDUTRACK GRADE CALCULATION & STORAGE REPORT
**Date**: February 12, 2026  
**System**: Central Philippines State University - Victorias Campus

---

## 1. GRADE STORAGE ARCHITECTURE

### 1.1 Database Schema (Grade Model)

The `grades` table stores individual assessment scores with the following structure:

```
KNOWLEDGE COMPONENT (40% of Overall Grade):
├── exam_prelim          (Preliminary Exam)
├── exam_midterm         (Midterm Exam)
├── exam_final           (Final Exam)
└── quiz_1 to quiz_5     (5 Quiz entries)

SKILLS COMPONENT (50% of Overall Grade):
├── OUTPUT (40% of Skills):
│   ├── output_1, output_2, output_3
│   └── output_total (auto-calculated sum)
├── CLASS PARTICIPATION (30% of Skills):
│   ├── class_participation_1, 2, 3
│   └── class_participation_total (auto-calculated sum)
├── ACTIVITIES (15% of Skills):
│   ├── activities_1, 2, 3
│   └── activities_total (auto-calculated sum)
└── ASSIGNMENTS (15% of Skills):
    ├── assignments_1, 2, 3
    └── assignments_total (auto-calculated sum)

ATTITUDE COMPONENT (10% of Overall Grade):
├── BEHAVIOR (50% of Attitude):
│   ├── behavior_1, 2, 3
│   └── behavior_total (auto-calculated sum)
└── AWARENESS (50% of Attitude):
    ├── awareness_1, 2, 3
    ├── awareness_quiz_1
    └── awareness_total (auto-calculated sum)

CALCULATED AVERAGES (stored as decimal(5,2)):
├── knowledge_average    (weighted: exam 60% + quiz 40%)
├── skills_average       (weighted components)
├── attitude_average     (weighted: behavior 50% + awareness 50%)
├── midterm_grade        (40% weight in overall)
├── final_grade_value    (60% weight in overall)
├── overall_grade        (final calculated grade)
└── decimal_grade        (grade point equivalent 1.0-5.0)
```

### 1.2 Storage Location
**Table**: `grades`  
**Primary Key**: `id` (auto-increment)  
**Foreign Keys**: 
- `student_id` (references `students` table)
- `class_id` (references `classes` table)
- `teacher_id` (references `users` table)  
**Subject relation**: Via class → subject relationship

---

## 2. CALCULATION METHODOLOGY

### 2.1 Component-Level Calculations

#### Knowledge Average Calculation
```
Knowledge Average = (Exam Average × 0.60) + (Quiz Average × 0.40)

Where:
  Exam Average = (exam_prelim + exam_midterm + exam_final) / 3
  Quiz Average = (quiz_1 + quiz_2 + quiz_3 + quiz_4 + quiz_5) / 5

Example:
  Exams: PR=80, MID=85, FIN=90 → Average = (80+85+90)/3 = 85
  Quizzes: Q1=80, Q2=85, Q3=88, Q4=90, Q5=92 → Average = (80+85+88+90+92)/5 = 87
  Knowledge = (85 × 0.60) + (87 × 0.40) = 51 + 34.8 = 85.8
```

#### Skills Component Calculations
```
Each skill component is auto-calculated by database SUM on entry:
- output_total = output_1 + output_2 + output_3
- class_participation_total = class_participation_1 + 2 + 3
- activities_total = activities_1 + 2 + 3
- assignments_total = assignments_1 + 2 + 3 + quiz_5

Then component AVERAGES are calculated:
- Output Average = output_total / 3
- CP Average = class_participation_total / 3
- Activities Average = activities_total / 3
- Assignments Average = assignments_total / 4

Skills Average = (Output Avg × 0.40) + (CP Avg × 0.30) 
                 + (Activities Avg × 0.15) + (Assignments Avg × 0.15)

Example (All components with perfect 100):
  Output Avg = 300/3 = 100
  CP Avg = 300/3 = 100
  Activities Avg = 300/3 = 100
  Assignments Avg = 400/4 = 100
  Skills = (100×0.40) + (100×0.30) + (100×0.15) + (100×0.15) = 100
```

#### Attitude Component Calculations
```
Behavior Average = (behavior_1 + behavior_2 + behavior_3) / 3
Awareness Average = (awareness_1 + awareness_2 + awareness_3 + awareness_quiz_1) / 4

Attitude Average = (Behavior Avg × 0.50) + (Awareness Avg × 0.50)

Totals (sums for display):
- behavior_total = behavior_1 + behavior_2 + behavior_3
- awareness_total = awareness_1 + awareness_2 + awareness_3 + awareness_quiz_1

Example:
  Behavior: 85, 88, 90 → Average = (85+88+90)/3 = 87.67
  Awareness: 80, 82, 84, 86 → Average = (80+82+84+86)/4 = 83
  Attitude = (87.67 × 0.50) + (83 × 0.50) = 43.835 + 41.5 = 85.335
```

### 2.2 Overall Grade Calculation

#### KSA Integration
```
MIDTERM GRADE (calculated from current assessments):
  Midterm_Grade = (Knowledge_Avg × 0.40) + (Skills_Avg × 0.50) + (Attitude_Avg × 0.10)

FINAL GRADE (calculated when final exams are completed):
  Final_Grade = (Knowledge_Avg × 0.40) + (Skills_Avg × 0.50) + (Attitude_Avg × 0.10)
  
  Note: Final grade includes updated exam_final scores but maintains same weighted structure

OVERALL GRADE (final assessment):
  Overall_Grade = (Midterm_Grade × 0.40) + (Final_Grade × 0.60)

DECIMAL GRADE (Grade Point Equivalent):
  5.0 = 98-100
  4.5 = 94-97
  4.0 = 90-93
  3.5 = 86-89
  3.0 = 82-85
  2.5 = 78-81
  2.0 = 74-77
  1.5 = 70-73
  1.0 = Less than 70
```

#### Complete Example
```
Student: Juan dela Cruz
Subject: Advanced Mathematics

KNOWLEDGE (40%):
  Exams (60%): PR=75, MID=80, FIN=85 → Avg = 80
  Quizzes (40%): Q1=85, Q2=88, Q3=90, Q4=87, Q5=89 → Avg = 87.8
  Knowledge Average = (80 × 0.60) + (87.8 × 0.40) = 48 + 35.12 = 83.12

SKILLS (50%):
  Output (40%): 85, 87, 89 → Avg = 87 → Contribution = 87 × 0.40 = 34.8
  CP (30%): 80, 82, 84 → Avg = 82 → Contribution = 82 × 0.30 = 24.6
  Activities (15%): 88, 90, 92 → Avg = 90 → Contribution = 90 × 0.15 = 13.5
  Assignments (15%): 86, 88, 90, 85 → Avg = 87.25 → Contribution = 87.25 × 0.15 = 13.0875
  Skills Average = 34.8 + 24.6 + 13.5 + 13.0875 = 85.9875

ATTITUDE (10%):
  Behavior (50%): 80, 82, 84 → Avg = 82 → Contribution = 82 × 0.50 = 41
  Awareness (50%): 85, 87, 89, 83 → Avg = 86 → Contribution = 86 × 0.50 = 43
  Attitude Average = 41 + 43 = 84

MIDTERM CALCULATION:
  Midterm = (83.12 × 0.40) + (85.9875 × 0.50) + (84 × 0.10)
          = 33.248 + 42.99375 + 8.4
          = 84.64175 ≈ 84.64

FINAL CALCULATION (after final exams, same structure):
  Final = (85.5 × 0.40) + (86.5 × 0.50) + (84.5 × 0.10)
        = 34.2 + 43.25 + 8.45
        = 85.90

OVERALL GRADE:
  Overall = (84.64 × 0.40) + (85.90 × 0.60)
          = 33.856 + 51.54
          = 85.396 ≈ 85.40

DECIMAL GRADE: 3.5 (85.40 falls in 86-89 range)
```

---

## 3. ATTENDANCE & BEHAVIORAL CONTRIBUTION

### 3.1 Attendance Integration in Attitude Component

**Attendance Impact Path**:
```
Attendance Records → behavior_1, behavior_2, behavior_3 entries
                  → behavior_total (sum)
                  → Behavior Average (average)
                  → Attitude Component (50% weight)
                  → Overall Grade (10% weight of KSA)
```

### 3.2 Quantifiable Contribution Calculation

```
ATTENDANCE CONTRIBUTION FORMULA:

1. Attendance tracking increases behavior scores
   - Perfect attendance (95-100%): behavior entries = 95-100
   - Good attendance (85-94%): behavior entries = 85-94
   - Fair attendance (75-84%): behavior entries = 75-84
   - Poor attendance (<75%): behavior entries = <75

2. In Midterm & Final calculating:
   - Behavior Average = (behavior_1 + behavior_2 + behavior_3) / 3
   - Attitude = Behavior × 0.50 + Awareness × 0.50
   
3. Overall Impact:
   - Attitude contributes 10% to KSA overall
   - Behavior is 50% of Attitude
   - Therefore: Attendance impacts 5% of overall grade
   
   Attendance Impact = Behavior_Score × 0.50 × 0.10 × 100%
                     = Behavior_Score × 0.05
```

### 3.3 Attendance Contribution Examples

```
SCENARIO 1: Perfect Attendance
  Behavior scores: 100, 100, 100 → Behavior Avg = 100
  Awareness scores: 85, 87, 89, 83 → Awareness Avg = 86
  Attitude = (100 × 0.50) + (86 × 0.50) = 50 + 43 = 93
  Contribution to Final Grade = 93 × 0.10 = 9.3 points

SCENARIO 2: Good Attendance  
  Behavior scores: 85, 85, 88 → Behavior Avg = 86
  Awareness scores: 85, 87, 89, 83 → Awareness Avg = 86
  Attitude = (86 × 0.50) + (86 × 0.50) = 43 + 43 = 86
  Contribution to Final Grade = 86 × 0.10 = 8.6 points

SCENARIO 3: Poor Attendance
  Behavior scores: 65, 68, 70 → Behavior Avg = 67.67
  Awareness scores: 85, 87, 89, 83 → Awareness Avg = 86
  Attitude = (67.67 × 0.50) + (86 × 0.50) = 33.835 + 43 = 76.835
  Contribution to Final Grade = 76.835 × 0.10 = 7.68 points

DIFFERENCE IN SCORE: 9.3 - 7.68 = 1.62 points
(Perfect attendance can yield 1.62 points more than poor attendance)
```

---

## 4. MIDTERM VS. FINAL TERM HANDLING

### 4.1 Data Storage Strategy

```
Single Grade Record Per Student Per Class:
- One row in grades table tracks both MIDTERM and FINAL assessments
- Shared fields: knowledge_average, skills_average, attitude_average
- Separate term columns: 
  * midterm_grade (stored after midterm period)
  * final_grade_value (stored after final period)
  * overall_grade (calculated after final is complete)

Term Selection in UI:
- User selects MIDTERM or FINAL from clickable badges
- Hidden field 'current_term' indicates which term being edited
- Selection affects form submission handling but not data structure
```

### 4.2 Calculation Timeline

```
MIDTERM PHASE (First half of semester):
├─ Week 1-8: Knowledge component inputs
│  ├─ exam_prelim (assessment)
│  ├─ quiz_1 through quiz_3 (periodic quizzes)
│  └─ Calculated: Knowledge Average
├─ Week 1-8: Skills components inputs
│  ├─ output_1, 2, 3 (project outputs)
│  ├─ class_participation_1, 2, 3 (classroom engagement)
│  ├─ activities_1, 2, 3 (extra-curricular activities)
│  ├─ assignments_1, 2, 3 (homework submissions)
│  └─ Calculated: Skills Average
├─ Week 1-8: Attitude component inputs
│  ├─ behavior_1, 2, 3 (observed classroom behavior + attendance)
│  ├─ awareness_1, 2, 3 (environmental awareness)
│  └─ Calculated: Attitude Average
└─ MIDTERM GRADE = KSA weighted average

FINAL PHASE (Second half + finals):
├─ Week 9-16: Updated Knowledge inputs
│  ├─ exam_final (final exam)
│  ├─ quiz_4, quiz_5 (additional quizzes)
│  ├─ Knowledge Average RECALCULATED with all scores
│  └─ May differ from midterm if scores changed
├─ Week 9-16: Updated Skills components
│  ├─ output_total, class_participation_total, activities_total, assignments_total
│  ├─ All accumulated scores
│  └─ Skills Average RECALCULATED with new data
├─ Week 9-16: Updated Attitude component
│  ├─ behavior additional entries + attendance data
│  ├─ awareness_quiz_1 (awareness assessment)
│  └─ Attitude Average RECALCULATED
└─ FINAL GRADE = KSA weighted average (may differ from midterm)

OVERALL GRADE CALCULATION:
  Overall = (Midterm × 0.40) + (Final × 0.60)
```

### 4.3 Midterm/Final Weight Distribution

```
PERCENTAGE CONTRIBUTION TO FINAL GRADE:

Midterm Grade:
├─ Knowledge component:     40% × 40% × 40% = 6.4%
├─ Skills component:         50% × 40% × 50% = 10.0%
└─ Attitude component:       10% × 40% × 10% = 0.4%
└─ SUBTOTAL MIDTERM: 16.8% of final grade

Final Grade:
├─ Knowledge component:      40% × 60% × 40% = 9.6%
├─ Skills component:         50% × 60% × 50% = 15.0%
└─ Attitude component:       10% × 60% × 10% = 0.6%
└─ SUBTOTAL FINAL: 25.2% of final grade

Additional Notes:
- Final semester assessments (exam_final, final projects) weight more heavily
- Early semester performance (midterm) carries forward at 40% weight
- Emphasis on final performance as student learning indicator
```

---

## 5. BACKEND STORAGE & RETRIEVAL

### 5.1 Form Submission Handler

```php
// Route: POST /grades/entry/{classId}
// Handler: TeacherController@storeGradesNew()

Process:
1. Receive POST data with:
   - class_id: identifier for the class
   - current_term: 'midterm' or 'final' (from hidden field)
   - grades[student_id][field_name]: assessment score
   
2. Iterate through grade data:
   foreach ($grades as $studentId => $gradeData) {
     $grade = Grade::where('student_id', $studentId)
                   ->where('class_id', $classId)
                   ->firstOrCreate();
     
     // Update individual scores
     $grade->update($gradeData);
     
     // Trigger auto-calculation
     $grade->calculateComponents();
     $grade->calculateAverages();
     
     if ($currentTerm == 'midterm') {
       $grade->calculateMidtermGrade();
     } elseif ($currentTerm == 'final') {
       $grade->calculateFinalGrade();
       $grade->calculateOverallGrade();
     }
     
     $grade->save();
   }
```

### 5.2 Calculation Service (Backend)

```php
// Grade Model method: calculateComponents()
public function calculateComponents()
{
    // Totals (simple sums)
    $this->output_total = ($this->output_1 ?? 0) + 
                         ($this->output_2 ?? 0) + 
                         ($this->output_3 ?? 0);
    
    $this->class_participation_total = ($this->class_participation_1 ?? 0) + 
                                       ($this->class_participation_2 ?? 0) + 
                                       ($this->class_participation_3 ?? 0);
    
    $this->activities_total = ($this->activities_1 ?? 0) + 
                             ($this->activities_2 ?? 0) + 
                             ($this->activities_3 ?? 0);
    
    $this->assignments_total = ($this->assignments_1 ?? 0) + 
                              ($this->assignments_2 ?? 0) + 
                              ($this->assignments_3 ?? 0) + 
                              ($this->quiz_5 ?? 0);
    
    $this->behavior_total = ($this->behavior_1 ?? 0) + 
                           ($this->behavior_2 ?? 0) + 
                           ($this->behavior_3 ?? 0);
    
    $this->awareness_total = ($this->awareness_1 ?? 0) + 
                            ($this->awareness_2 ?? 0) + 
                            ($this->awareness_3 ?? 0) + 
                            ($this->awareness_quiz_1 ?? 0);
}

public function calculateAverages()
{
    // Knowledge Average
    $examAvg = (($this->exam_prelim ?? 0) + 
               ($this->exam_midterm ?? 0) + 
               ($this->exam_final ?? 0)) / 3;
    
    $quizAvg = (($this->quiz_1 ?? 0) + ($this->quiz_2 ?? 0) + 
               ($this->quiz_3 ?? 0) + ($this->quiz_4 ?? 0) + 
               ($this->quiz_5 ?? 0)) / 5;
    
    $this->knowledge_average = ($examAvg * 0.60) + ($quizAvg * 0.40);
    
    // Skills Average
    $outputAvg = $this->output_total / 3;
    $cpAvg = $this->class_participation_total / 3;
    $actAvg = $this->activities_total / 3;
    $assAvg = $this->assignments_total / 4;
    
    $this->skills_average = ($outputAvg * 0.40) + ($cpAvg * 0.30) + 
                           ($actAvg * 0.15) + ($assAvg * 0.15);
    
    // Attitude Average
    $behaviorAvg = $this->behavior_total / 3;
    $awarenessAvg = $this->awareness_total / 4;
    
    $this->attitude_average = ($behaviorAvg * 0.50) + ($awarenessAvg * 0.50);
}

public function calculateMidtermGrade()
{
    $this->midterm_grade = ($this->knowledge_average * 0.40) + 
                          ($this->skills_average * 0.50) + 
                          ($this->attitude_average * 0.10);
}

public function calculateFinalGrade()
{
    // Same structure as midterm
    $this->final_grade_value = ($this->knowledge_average * 0.40) + 
                              ($this->skills_average * 0.50) + 
                              ($this->attitude_average * 0.10);
}

public function calculateOverallGrade()
{
    $this->overall_grade = ($this->midterm_grade * 0.40) + 
                          ($this->final_grade_value * 0.60);
    
    // Convert to decimal grade
    $this->decimal_grade = $this->calculateDecimalGrade($this->overall_grade);
}

private function calculateDecimalGrade($score)
{
    if ($score >= 98) return 5.0;
    if ($score >= 94) return 4.5;
    if ($score >= 90) return 4.0;
    if ($score >= 86) return 3.5;
    if ($score >= 82) return 3.0;
    if ($score >= 78) return 2.5;
    if ($score >= 74) return 2.0;
    if ($score >= 70) return 1.5;
    return 1.0;
}
```

---

## 6. SUMMARY TABLE

| Aspect | Details |
|--------|---------|
| **Storage Method** | Single Grade record per student per class |
| **Midterm/Final** | Both stored in same record (`midterm_grade`, `final_grade_value`) |
| **Auto-Calculation** | Triggered on form submission via controller |
| **Attendance Impact** | 5% of overall grade (via Behavior in Attitude) |
| **KSA Distribution** | Knowledge 40%, Skills 50%, Attitude 10% |
| **Term Weighting** | Midterm 40%, Final 60% of Overall Grade |
| **Decimal Grade** | Mapped from Overall Grade (1.0-5.0 scale) |
| **Refresh Frequency** | On form save - all calculations immediate |
| **Visibility** | Real-time in form, stored in database |

---

## 7. SYSTEM STATUS

✅ **Midterm/Final Toggle**: Implemented with clickable badges (lines 327-333 of entry_new.blade.php)  
✅ **AVE Columns**: Added after all component totals (Knowledge, Output, CP, Activities, Assignments, Attitude)  
✅ **Backend Calculations**: Handled by Grade model methods  
✅ **Attendance Integration**: Behavior scores (50% of Attitude, 10% of KSA weight)  
✅ **Database Ready**: All migration fields present and casting configured  

---

**Report Generated**: February 12, 2026  
**System**: EduTrack v1.0  
**Institution**: Central Philippines State University
