# Grading System Analysis - EduTrack Project

## Executive Summary

This document provides a detailed analysis of the grading system located in `grading system/` folder. The analysis is based on examination of the Excel file structure, PNG images, and assessment component organization used in CENTRAL PHILIPPINES STATE UNIVERSITY (CPSU), Victorias Campus.

---

## 1. VISUAL DESIGN & LAYOUT (From PNG Images)

### Image Files

- **final.PNG** (175 KB) - Shows the final grading interface/report
- **final2.PNG** (137 KB) - Alternative final grading view
- **midterm.PNG** (194 KB) - Midterm grading interface

**Note:** The PNG images appear to be screenshots of the grading system interface showing how grades are displayed and organized visually. Due to technical limitations, visual analysis details would require direct inspection. However, based on the Excel structure, these likely show:

- Student list with identification numbers and names
- Grade entry fields for various assessment components
- Calculated totals and equivalencies
- Final grades with remarks (Passed/Conditional/Failure)
- Color-coded entries (Black/Blue ink for passing, Red ink for conditional/failure)

---

## 2. EXCEL FILE STRUCTURE & ORGANIZATION

### File Information

- **Filename:** grading system excel.xlsx
- **File Size:** 459 KB
- **Worksheet:** Active sheet contains 1000+ rows
- **Columns:** 79 columns (A through CA)
- **Merged Cells:** 42 merged cell ranges for header organization

### Sheet Metadata

- **Subject Code:** PCIT 08
- **Subject Title:** SYSTEM INTEGRATION & ARCHITECTURE 1
- **Program:** Bachelor of Science in Information Technology (BSIT)
- **Year/Section:** BSIT 2B
- **Semester:** 2nd Semester
- **Academic Year:** AY 2024-2025
- **Institution:** CENTRAL PHILIPPINES STATE UNIVERSITY (CPSU)
- **Campus:** Victorias Campus, Negros Occidental
- **Contact:** cpsuvictoriascampus@yahoo.com | 09171704473

### Header Structure (Rows 1-7)

#### Row 1-4: Institution Header

- University name and location information
- Campus identification

#### Row 5-6: Main Grading Categories with Weightings

**PRIMARY GRADING STRUCTURE:**

```
MIDTERM (40%)
├── KNOWLEDGE (40%)
├── SKILLS (50%)
└── ATTITUDE (10%)

FINAL (60%)
├── EXAM (60%)
├── QUIZZES (40%)
└── OUTPUT (40%)
```

**EVALUATION COMPONENTS:**

```
CLASS PARTICIPATION & AWARENESS (30%)
├── CLASS PART (30%)
├── ACTIVITIES (15%)
└── ASSIGNMENTS (15%)

BEHAVIOR & CONDUCT (50%)
└── BEHAVIOR (50%)
    └── CLASS PARTI, AWARENESS (50%)
```

### Column Organization (Summary)

| Section            | Columns  | Purpose                                 |
| ------------------ | -------- | --------------------------------------- |
| Student Info       | A-B      | Student Number, Names                   |
| Quizzes            | Multiple | Q1, Q2, Q3, Q4, Q5                      |
| Quiz Total         | Variable | Total Quiz Score + Equivalent           |
| Midterm Components | Multiple | Knowledge, Skills, Attitude assessments |
| Midterm Grade      | 2-3      | Raw grade + Numerical Equivalent        |
| Final Exam         | Multiple | Exam scores and calculations            |
| Final Grade        | Multiple | Raw score + Equivalent grade            |
| Final Results      | Multiple | 40% Mid + 60% Final calculation         |
| Numeric Equivalent | 1-2      | Letter grade conversion                 |
| Credit             | 1        | Credit status                           |
| Remarks            | 1        | Pass/Conditional/Failure                |

---

## 3. GRADING SCALE & EQUIVALENCIES

### Grade Scale Reference (Documented in Sheet)

```
1.0 - 97-100 above         → Excellent
1.25 - 94-96               → Excellent
1.50 - 91-93               → Excellent
1.75 - 88-90               → Excellent
2.0 - 85-87                → Thorough
2.25 - 82-84               → Thorough
2.50 - 79-81               → Thorough
2.75 - 76-78               → Lowest Passing
3.0 - 75                   → Lowest Passing Grade
4.0 - 70-74                → Conditional
5.0 - 69 & below           → Failure
```

### Assessment Codes

- **PR** - Provisional/Pending
- **%** - Percentage
- **MD** - Midterm
- **MID** - Midterm
- **Equi** - Equivalent
- **N. Eqv.** - Numeric Equivalent
- **Inc** - Incomplete
- **NN** - No Name
- **NG** - No Grade
- **NGs** - No Grading Sheet
- **S** - No Equivalent

---

## 4. ASSESSMENT COMPONENTS & ORGANIZATION

### A. Quizzes Section

**Components:** Q1, Q2, Q3, Q4, Q5

- **Weight in Midterm:** Part of overall knowledge assessment (40% of midterm)
- **Total Quiz Score:** Summed with equivalent grade calculated
- **Purpose:** Continuous assessment of learning progress
- **Frequency:** 5 quiz items tracked

### B. Midterm Assessment (40% of Final Grade)

**Structure:**

```
MIDTERM EVALUATION (40%)
├── KNOWLEDGE (40% of midterm)
│   └── Assessed via quizzes (Q1-Q5)
├── SKILLS (50% of midterm)
│   └── Practical application assessment
└── ATTITUDE (10% of midterm)
    └── Professional conduct & engagement
```

**Calculation:**

- K (Knowledge) × 0.40
- A (Attitude) × 0.10
- S (Skills) × 0.50
- **Midterm Grade = (K×0.4 + S×0.5 + A×0.1)**
- Converted to Numeric Equivalent (1.0-5.0 scale)

### C. Final Assessment (60% of Final Grade)

**Structure:**

```
FINAL EVALUATION (60%)
├── EXAM (60% of final assessment)
│   └── Comprehensive written/practical exam
├── QUIZZES (40% of final assessment)
│   └── Additional quiz assessments
└── OUTPUT (40% - Alternative/Complementary)
    └── Project work, assignments, deliverables
```

**Calculation:**

- Exam component (60% weight)
- Quizzes component (40% weight) OR Output component (40% weight)
- Raw grade converted to numeric equivalent

### D. Class Participation & Conduct (Separate Category)

**Components:**

```
CLASS PARTICIPATION & AWARENESS (30%)
├── CLASS PARTICIPATION (30%)
├── ACTIVITIES (15%)
└── ASSIGNMENTS (15%)

BEHAVIOR/CONDUCT (50%)
└── Includes class participation awareness
```

---

## 5. FINAL GRADE CALCULATION FORMULA

### Comprehensive Formula

```
FINAL GRADE = (Midterm Grade × 0.40) + (Final Grade × 0.60)

Where:
Midterm Grade = (Knowledge×0.40 + Skills×0.50 + Attitude×0.10)
Final Grade = (Exam×0.60 + Quizzes×0.40) OR (Exam×0.60 + Output×0.40)

Then:
Numeric Equivalent is determined using the grade scale
Final Remarks: Pass (1.0-3.0) / Conditional (4.0) / Failure (5.0)
```

---

## 6. SPECIAL FEATURES & INNOVATIONS

### A. Multi-Dimensional Assessment

- **Not just exam-based:** Combines knowledge, skills, and attitude
- **Continuous evaluation:** Through quizzes throughout semester
- **Behavioral assessment:** Tracks class participation and conduct
- **Output-based assessment:** Alternative/complementary to traditional exams

### B. Color-Coded Grading System

- **BLACK or BLUE ink:** Passing grades (1.0-3.0)
- **RED ink:** Conditional (4.0) or Failure (5.0) grades
- Purpose: Visual distinction of performance levels
- Facilitates quick scanning of results

### C. Weighted Percentage System

- **Flexible weightings:** Different components have different weights
- **Midterm-Final split:** 40%-60% distribution
- **Component breakdown:** Allows granular assessment tracking
- **Numeric equivalent scale:** Professional GPA-like conversion

### D. Comprehensive Data Fields

- **Student identification:** Number and Names columns
- **Multiple assessment types:** Quizzes, exams, outputs, behavioral
- **Status tracking:** Credit, Remarks, Equivalency fields
- **Incomplete handling:** Codes for missing data (NG, NGs, NN, Inc)

### E. Merge Cells for Visual Organization

- 42 merged cell ranges for hierarchical headers
- Section grouping: Visually organizes related components
- Clean hierarchical structure for data entry

### F. Professional Formatting

- Multiple sections clearly delineated
- Institutional letterhead and information
- Signature blocks for instructor, program head, registrar
- Instructions for grade notation (colored ink)
- ISO certification information

---

## 7. DATA ORGANIZATION PATTERNS

### Row Structure

- **Row 1-4:** Header/Title information
- **Row 5-6:** Grading category headers with percentages
- **Row 7:** Column subheaders
- **Row 8-1000:** Student data rows

### Column Patterns

1. **Student identifiers:** Name, ID
2. **Assessment scores:** Raw numerical entries
3. **Calculated fields:** Totals, averages, equivalents
4. **Status fields:** Remarks, credit status
5. **Reference info:** Equivalent grade scales, instructions

### Data Types

- **Text:** Student names, remarks, codes
- **Numeric:** Grade scores (typically 0-100 or 1-5 scale)
- **Calculated:** Weighted averages, numeric equivalents
- **Categorical:** Pass/Conditional/Failure status

---

## 8. KEY INNOVATIONS FOR EDUTRACK REPLICATION

### Must-Have Features:

1. **Multi-component assessment tracking** - Not just single grades
2. **Hierarchical weighting system** - Support nested percentages
3. **Numeric equivalent conversion** - Flexible grade scale mapping
4. **Color-coded status** - Visual feedback for performance levels
5. **Comprehensive remarks** - Automated pass/fail determination
6. **Flexible assessment types** - Support quizzes, exams, outputs, behavioral

### Database Structure Needed:

```
Students
├── student_id
├── student_name
└── program/section

Assessments
├── assessment_id
├── type (quiz, exam, output, behavioral)
├── weight
└── component (midterm/final/participation)

Grades
├── student_id
├── assessment_id
├── score
└── timestamp

GradeCalculations
├── student_id
├── component (midterm, final, behavior)
├── raw_grade
├── weighted_grade
├── numeric_equivalent
└── remarks (pass/conditional/failure)

GradeScales
├── numeric_equivalent
├── min_score
├── max_score
└── description
```

### Features to Implement:

1. **Dynamic component weighting** - Support arbitrary percentage distributions
2. **Cascading calculations** - Components roll up to final grade
3. **Numeric equivalency mapping** - Configure grade scales per course/subject
4. **Status determination** - Automatic pass/conditional/failure assignment
5. **Incomplete handling** - Support for missing data scenarios
6. **Multi-semester tracking** - Support for repeated courses
7. **PDF export** - Export grading sheets similar to the Excel format
8. **Color coding** - Apply visual indicators based on performance

---

## 9. Comparison: Excel vs. Proposed Laravel System

### Excel Strengths

- ✓ Visual organization with merged cells
- ✓ Color coding for quick reference
- ✓ Offline capability
- ✓ Complex formulas for calculations
- ✓ Print-friendly layout

### Laravel System Advantages

- ✓ Real-time data entry for multiple users
- ✓ Automated calculations without formula errors
- ✓ Audit trail and change tracking
- ✓ Integration with student information system
- ✓ Role-based access control
- ✓ Mobile access
- ✓ Scalable for multiple courses/semesters
- ✓ Data consistency and validation
- ✓ Report generation
- ✓ Analytics and trend analysis

---

## 10. Implementation Recommendations

### Phase 1: Core Structure

- Create assessment component management
- Build grade entry interface
- Implement numeric equivalent conversion
- Create student roster management

### Phase 2: Calculations

- Implement weighted percentage calculations
- Create cascade calculation engine
- Add automatic status determination
- Support for incomplete grades

### Phase 3: Reporting

- Create grade sheets similar to Excel layout
- PDF export functionality
- Printable student transcripts
- Class statistics and analytics

### Phase 4: Advanced Features

- Multi-semester tracking
- Grade appeal workflow
- Historical grade comparisons
- Academic standing determination

---

## 11. File Summary

| File                      | Size   | Purpose                                                 |
| ------------------------- | ------ | ------------------------------------------------------- |
| grading system excel.xlsx | 459 KB | Master grading template with all formulas and structure |
| final.PNG                 | 175 KB | Screenshot of final grade report interface              |
| final2.PNG                | 137 KB | Alternative final grade view                            |
| midterm.PNG               | 194 KB | Midterm grade interface screenshot                      |

**Total Folder Size:** ~965 KB (primarily images and spreadsheet)

---

## Conclusion

The CPSU grading system represents a comprehensive, multi-dimensional approach to student assessment that goes beyond simple exam scores. It incorporates knowledge, skills, attitude, class participation, and behavioral components with carefully weighted percentages. The color-coded system and detailed organization make it suitable for both digital and print environments.

For the EduTrack Laravel replication, this system should be implemented with dynamic component configuration, automated calculations, role-based access control, and comprehensive reporting capabilities. The key is to maintain the assessment philosophy while adding the benefits of a modern web-based system: real-time updates, data consistency, integration with other systems, and scalability across multiple courses and semesters.

---

**Analysis Date:** January 21, 2026  
**Analyst:** System Documentation  
**Status:** Complete
