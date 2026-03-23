# Comprehensive KSA Grading System

## Overview
This document describes the complete grading structure implemented in the EduTrack system with automatic component initialization and x50+50 transmutation formula.

## Grading Structure

### MIDTERM PERIOD (40% of Overall Grade)

#### Knowledge (40% of Midterm)
- **EXAM (60% of Knowledge)**
  - Midterm Exam (100 points)
  
- **QUIZZES (40% of Knowledge)**
  - Quiz 1 (13.33%, 100 points)
  - Quiz 2 (13.33%, 100 points)
  - Quiz 3 (13.34%, 100 points)
  - Average calculated automatically

#### Skills (50% of Midterm)
- **OUTPUT (40% of Skills)**
  - Output 1 (13.33%, 100 points)
  - Output 2 (13.33%, 100 points)
  - Output 3 (13.34%, 100 points)
  
- **CLASS PARTICIPATION (30% of Skills)**
  - Class Participation 1 (10%, 100 points)
  - Class Participation 2 (10%, 100 points)
  - Class Participation 3 (10%, 100 points)
  
- **ACTIVITIES (15% of Skills)**
  - Activity 1 (5%, 100 points)
  - Activity 2 (5%, 100 points)
  - Activity 3 (5%, 100 points)
  
- **ASSIGNMENTS (15% of Skills)**
  - Assignment 1 (5%, 100 points)
  - Assignment 2 (5%, 100 points)
  - Assignment 3 (5%, 100 points)

#### Attitude (10% of Midterm)
- **BEHAVIOR (50% of Attitude)**
  - Behavior 1 (16.67%, 100 points)
  - Behavior 2 (16.67%, 100 points)
  - Behavior 3 (16.66%, 100 points)
  
- **AWARENESS (50% of Attitude)**
  - Awareness 1 (16.67%, 100 points)
  - Awareness 2 (16.67%, 100 points)
  - Awareness 3 (16.66%, 100 points)

### FINAL PERIOD (60% of Overall Grade)
Same structure as Midterm, but with "Final Exam" instead of "Midterm Exam"

## Calculation Formula

### Step 1: Normalize Scores
Each component score is normalized to 100-point scale:
```
Normalized Score = (Raw Score / Max Score) × 100
```

### Step 2: Calculate Category Averages
For each category (Knowledge, Skills, Attitude):
```
Category Average = Σ(Normalized Score × Component Weight) / 100
```

### Step 3: Calculate Raw Grade
```
Raw Grade = (Knowledge Avg × 0.40) + (Skills Avg × 0.50) + (Attitude Avg × 0.10)
```

### Step 4: Apply Transmutation (x50+50)
```
Final Grade = (Raw Grade × 0.50) + 50
```

## Example Calculation

### Sample Scores:
- **Knowledge:**
  - Midterm Exam: 85/100 → 85% × 60% = 51%
  - Quiz 1: 90/100 → 90% × 13.33% = 12%
  - Quiz 2: 88/100 → 88% × 13.33% = 11.73%
  - Quiz 3: 92/100 → 92% × 13.34% = 12.27%
  - **Knowledge Total: 87%**

- **Skills:**
  - Output 1: 88/100 → 88% × 13.33% = 11.73%
  - Output 2: 90/100 → 90% × 13.33% = 12%
  - Output 3: 85/100 → 85% × 13.34% = 11.34%
  - Class Part 1: 95/100 → 95% × 10% = 9.5%
  - Class Part 2: 93/100 → 93% × 10% = 9.3%
  - Class Part 3: 94/100 → 94% × 10% = 9.4%
  - Activity 1: 90/100 → 90% × 5% = 4.5%
  - Activity 2: 88/100 → 88% × 5% = 4.4%
  - Activity 3: 92/100 → 92% × 5% = 4.6%
  - Assignment 1: 85/100 → 85% × 5% = 4.25%
  - Assignment 2: 87/100 → 87% × 5% = 4.35%
  - Assignment 3: 89/100 → 89% × 5% = 4.45%
  - **Skills Total: 89.82%**

- **Attitude:**
  - Behavior 1: 95/100 → 95% × 16.67% = 15.84%
  - Behavior 2: 93/100 → 93% × 16.67% = 15.50%
  - Behavior 3: 94/100 → 94% × 16.66% = 15.66%
  - Awareness 1: 96/100 → 96% × 16.67% = 16%
  - Awareness 2: 95/100 → 95% × 16.67% = 15.84%
  - Awareness 3: 97/100 → 97% × 16.66% = 16.16%
  - **Attitude Total: 95%**

### Final Calculation:
```
Raw Grade = (87 × 0.40) + (89.82 × 0.50) + (95 × 0.10)
Raw Grade = 34.8 + 44.91 + 9.5
Raw Grade = 89.21

Transmuted Grade = (89.21 × 0.50) + 50
Transmuted Grade = 44.605 + 50
Transmuted Grade = 94.61
```

## Features

### Automatic Initialization
- When a teacher first accesses the grade entry for a class, all standard components are automatically created
- No manual setup required
- Components can be customized after initialization

### Real-time Calculation
- Grades are calculated automatically as scores are entered
- Total and Average columns show category performance
- Color-coded pass/fail indicators (green = passed, red = failed)

### Validation
- Prevents scores exceeding maximum values
- Highlights invalid entries with red borders
- Shows error messages for invalid data

### User-Friendly Interface
- Horizontal scrolling for many components
- Sticky student name column
- Arrow key navigation between inputs
- Auto-select on focus for quick data entry

## Grading Scale
- **90-100**: Excellent (Green badge)
- **75-89**: Passed (Blue badge)
- **60-74**: Conditional (Yellow badge)
- **Below 60**: Failed (Red badge)

## Component Management
Teachers can:
- Add new components via Settings & Components tab
- Delete existing components
- Apply templates for quick setup
- Customize weights and max scores
- Set passing scores for each component

## Notes
- All components use 100-point scale for consistency
- Weights within each category must sum to 100%
- KSA weights (40%, 50%, 10%) are fixed but can be adjusted in Advanced Settings
- The x50+50 transmutation ensures grades fall within the 50-100 range
