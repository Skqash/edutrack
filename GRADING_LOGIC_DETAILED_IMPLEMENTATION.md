# 📊 Correct Grading System Logic - Implementation Guide

## Final Grade Calculation Structure

### Overall Weights

```
Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)
```

---

## 1. KNOWLEDGE COMPONENT (40% of Total Grade)

### Weights Breakdown:

- **Quizzes: 40% of Knowledge = 16% of total grade**
- **Exam: 60% of Knowledge = 24% of total grade**

### Details:

#### Quizzes (16% of Total Grade):

- Total of 5 quizzes
- Each quiz is equally weighted: 20% of quizzes portion
- Each quiz contributes: **3.2% of total grade**
- Formula: `Quiz Average = (Q1 + Q2 + Q3 + Q4 + Q5) / 5`
- Quizzes Part: `Quiz Average × 0.40`

#### Exam (24% of Total Grade):

- Exams taken in different periods (Prelim, Midterm, Final)
- For **Midterm Term**: Average of Prelim + Midterm exams
- For **Final Term**: Average of Midterm + Final exams
- Exam Average: `(Exam1 + Exam2) / 2`
- Exam Part: `Exam Average × 0.60`

### Knowledge Score Calculation:

```
Knowledge Score = (Quiz Average × 0.40) + (Exam Average × 0.60)

Where:
  Quiz Average = (Q1 + Q2 + Q3 + Q4 + Q5) / 5 (normalized to 0-100)
  Exam Average = (Exam1 + Exam2) / 2 (normalized to 0-100)
```

### Contribution to Total Grade:

- 5 quizzes combined: 16% of total grade
- 2 exams average: 24% of total grade

---

## 2. SKILLS COMPONENT (50% of Total Grade)

### Weights Breakdown:

- **Output: 40% of Skills = 20% of total grade**
- **Class Participation: 30% of Skills = 15% of total grade**
- **Activities: 15% of Skills = 7.5% of total grade**
- **Assignments: 15% of Skills = 7.5% of total grade**

### Details:

Each skill component has **3 period-based inputs** (Prelim, Midterm, Final):

#### Output (20% of Total Grade):

- 3 inputs: Prelim Output, Midterm Output, Final Output
- Each input: **6.67% of total grade**
- Formula: `Output Score = (Prelim + Midterm + Final) / 3`
- Skills contribution: `Output Score × 0.40`

#### Class Participation (15% of Total Grade):

- 3 inputs: Prelim CP, Midterm CP, Final CP
- Each input: **5% of total grade**
- Formula: `CP Score = (Prelim + Midterm + Final) / 3`
- Skills contribution: `CP Score × 0.30`

#### Activities (7.5% of Total Grade):

- 3 inputs: Prelim Activities, Midterm Activities, Final Activities
- Each input: **2.5% of total grade**
- Formula: `Activities Score = (Prelim + Midterm + Final) / 3`
- Skills contribution: `Activities Score × 0.15`

#### Assignments (7.5% of Total Grade):

- 3 inputs: Prelim Assignments, Midterm Assignments, Final Assignments
- Each input: **2.5% of total grade**
- Formula: `Assignments Score = (Prelim + Midterm + Final) / 3`
- Skills contribution: `Assignments Score × 0.15`

### Skills Score Calculation:

```
Skills Score = (Output × 0.40) + (CP × 0.30) + (Activities × 0.15) + (Assignments × 0.15)

Where:
  Output = (Prelim Output + Midterm Output + Final Output) / 3
  CP = (Prelim CP + Midterm CP + Final CP) / 3
  Activities = (Prelim Act + Midterm Act + Final Act) / 3
  Assignments = (Prelim Assign + Midterm Assign + Final Assign) / 3
```

### Contribution to Total Grade:

- Output: 20% of total grade
- Class Participation: 15% of total grade
- Activities: 7.5% of total grade
- Assignments: 7.5% of total grade
- **Total Skills: 50% of total grade**

---

## 3. ATTITUDE COMPONENT (10% of Total Grade)

### Weights Breakdown:

- **Behavior: 50% of Attitude = 5% of total grade**
- **Engagement: 50% of Attitude = 5% of total grade**

### Engagement Breakdown:

- **Attendance: 60% of Engagement = 3% of total grade**
- **Class Participation & Awareness: 40% of Engagement = 2% of total grade**

### Details:

#### Behavior (5% of Total Grade):

- Single score representing overall behavior
- Attitude contribution: `Behavior × 0.50`

#### Engagement (5% of Total Grade):

- Composed of Attendance and Awareness/Class Participation
- Attendance: 60% of engagement = **3% of total grade**
- Awareness: 40% of engagement = **2% of total grade**
- Formula: `Engagement = (Attendance × 0.60) + (Awareness × 0.40)`
- Attitude contribution: `Engagement × 0.50`

### Attitude Score Calculation:

```
Engagement = (Attendance × 0.60) + (Awareness × 0.40)
Attitude Score = (Behavior × 0.50) + (Engagement × 0.50)

Where:
  Behavior = Single normalized score (0-100)
  Attendance = Single normalized score (0-100), contributes 3% to total
  Awareness = Single normalized score (0-100), contributes 2% to total
```

### Contribution to Total Grade:

- Behavior: 5% of total grade
- Attendance (within engagement): 3% of total grade
- Awareness/Class Participation (within engagement): 2% of total grade
- **Total Attitude: 10% of total grade**

---

## 4. FINAL GRADE CALCULATION

### Formula:

```
Final Grade = (Knowledge × 0.40) + (Skills × 0.50) + (Attitude × 0.10)

Final Grade = (K × 0.40) + (S × 0.50) + (A × 0.10)
```

### All Components Combined:

```
Final Grade = (Q×0.16 + E×0.24) + (O×0.20 + CP×0.15 + Ac×0.075 + As×0.075) + (B×0.05 + At×0.03 + Aw×0.02)

Where:
  Q = Quiz Average (5 quizzes)
  E = Exam Average (2 exams per term)
  O = Output Average (3 periods)
  CP = Class Participation Average (3 periods)
  Ac = Activities Average (3 periods)
  As = Assignments Average (3 periods)
  B = Behavior Score
  At = Attendance Score
  Aw = Awareness Score
```

---

## 5. WEIGHT DISTRIBUTION SUMMARY TABLE

| Component     | Subcomponent            | # of Inputs          | Weight of Subcomponent | % of Total Grade |
| ------------- | ----------------------- | -------------------- | ---------------------- | ---------------- |
| **KNOWLEDGE** |                         |                      | **40%**                | **40%**          |
|               | Quizzes (5)             | 5                    | 40%                    | 16%              |
|               | Exam (2 avg)            | 2                    | 60%                    | 24%              |
| **SKILLS**    |                         |                      | **50%**                | **50%**          |
|               | Output                  | 3 (prelim/mid/final) | 40%                    | 20%              |
|               | Class Participation     | 3 (prelim/mid/final) | 30%                    | 15%              |
|               | Activities              | 3 (prelim/mid/final) | 15%                    | 7.5%             |
|               | Assignments             | 3 (prelim/mid/final) | 15%                    | 7.5%             |
| **ATTITUDE**  |                         |                      | **10%**                | **10%**          |
|               | Behavior                | 1                    | 50%                    | 5%               |
|               | Engagement - Attendance | 1                    | 30% (of 50%)           | 3%               |
|               | Engagement - Awareness  | 1                    | 20% (of 50%)           | 2%               |

---

## 6. PERCENTAGE BREAKDOWN TO PERCENTAGES OF TOTAL GRADE

```
Knowledge (16% quizzes + 24% exams) ......................................... 40%

Skills:
  Output (3 inputs × 6.67%) ............... 20%
  Class Participation (3 inputs × 5%) ... 15%
  Activities (3 inputs × 2.5%) ........... 7.5%
  Assignments (3 inputs × 2.5%) ......... 7.5%
  Subtotal ......................................................... 50%

Attitude:
  Behavior .............. 5%
  Attendance (in engagement) ............... 3%
  Awareness (in engagement) ............... 2%
  Subtotal ......................................................... 10%

TOTAL ................................................................ 100%
```

---

## 7. EXAMPLE CALCULATION

### Sample Student Data:

**Knowledge Component:**

- Quizzes: [85, 88, 92, 79, 86]
- Exam 1: 87
- Exam 2: 91

**Skills Component:**

- Output: [Prelim: 90, Midterm: 88, Final: 92]
- CP: [Prelim: 85, Midterm: 87, Final: 89]
- Activities: [Prelim: 80, Midterm: 82, Final: 85]
- Assignments: [Prelim: 88, Midterm: 90, Final: 91]

**Attitude Component:**

- Behavior: 85
- Attendance: 92
- Awareness: 88

### Calculations:

**Knowledge:**

```
Quiz Avg = (85+88+92+79+86)/5 = 86
Exam Avg = (87+91)/2 = 89
Knowledge = (86 × 0.40) + (89 × 0.60) = 34.4 + 53.4 = 87.8
```

**Skills:**

```
Output = (90+88+92)/3 = 90
CP = (85+87+89)/3 = 87
Activities = (80+82+85)/3 = 82.33
Assignments = (88+90+91)/3 = 89.67

Skills = (90 × 0.40) + (87 × 0.30) + (82.33 × 0.15) + (89.67 × 0.15)
       = 36 + 26.1 + 12.35 + 13.45
       = 87.9
```

**Attitude:**

```
Engagement = (92 × 0.60) + (88 × 0.40) = 55.2 + 35.2 = 90.4
Attitude = (85 × 0.50) + (90.4 × 0.50) = 42.5 + 45.2 = 87.7
```

**Final Grade:**

```
Final Grade = (87.8 × 0.40) + (87.9 × 0.50) + (87.7 × 0.10)
            = 35.12 + 43.95 + 8.77
            = 87.84
```

**Letter Grade:** B (88.7 → 87.84)

---

## 8. Implementation Status

✅ **Grade Model Updated**

- `calculateKnowledge()` - Properly weights quizzes (40%) and exams (60%)
- `calculateSkills()` - Averages 3-period inputs for each category
- `calculateAttitude()` - Includes attendance weighting (60%) and awareness (40%)
- `calculateFinalGrade()` - Combines all three components with correct weights

⏳ **Pending:**

- TeacherController updates to pass 3-period data
- View updates to support period-based entry
- Database migration if needed
- Teacher documentation

---

## 9. Notes for Teachers

### When Entering Grades:

1. **Knowledge:**
    - Enter 5 quiz scores
    - Enter relevant exam scores (Prelim/Midterm for midterm term, Midterm/Final for final term)

2. **Skills:**
    - Enter 3 scores for each category (Prelim, Midterm, Final periods)
    - Output, Class Participation, Activities, and Assignments each have 3 period entries

3. **Attitude:**
    - Behavior: Single score for overall behavior
    - Attendance: Single score representing attendance rate
    - Awareness/Class Participation: Single score for awareness and participation

### Verification:

- Each component should normalize to 0-100 scale
- Final grade will automatically weight components correctly
- System will show component contributions to final grade

---

**Last Updated:** January 22, 2026  
**Status:** ✅ Code Implementation Complete - Awaiting Integration Testing
