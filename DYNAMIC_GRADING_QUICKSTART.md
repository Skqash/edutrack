php artisan migrate# Dynamic Grade Entry - Quick Start Guide

## What Changed?

Previously, the grading system was **fixed** with hardcoded components:
- Always 5 quizzes
- Always 3 outputs
- Always 3 class participation entries
- Teachers couldn't add or remove components

**Now it's fully DYNAMIC:**
- ✅ Add/Remove quizzes (1, 2, 3, 5, 10, or any number)
- ✅ Add/Remove outputs, activities, assignments, etc.
- ✅ Customize max scores per component
- ✅ Set custom weights for each category
- ✅ All calculations adapt automatically

## For Teachers

### Step-by-Step: Setting Up a Class

#### 1. Create Class
Go to Classes → Create New → Fill class details

#### 2. Add Students
- Manually import student list
- Or add existing students from the system

#### 3. Configure Components
Navigate to: **Classes → [Class Name] → Configure Components**

OR use the new route: `/teacher/grades/dynamic/{classId}`

#### 4. Add Components
Click "Configure Components" modal:
- Click category (Knowledge, Skills, Attitude)
- Click "+ Add [Category] Component"
- Enter: Name, Max Score, Weight
- Repeat for all needed components

**Example Setup for Midterm:**

**KNOWLEDGE (40% of grade):**
| Component | Max Score | Weight |
|-----------|-----------|--------|
| Quiz 1    | 25        | 25%    |
| Quiz 2    | 25        | 25%    |
| Quiz 3    | 25        | 25%    |
| Exam      | 100       | 25%    |

**SKILLS (50% of grade):**
| Component | Max Score | Weight |
|-----------|-----------|--------|
| Output 1  | 100       | 40%    |
| Activity  | 100       | 30%    |
| Assignment| 50        | 30%    |

**ATTITUDE (10% of grade):**
| Component | Max Score | Weight |
|-----------|-----------|--------|
| Behavior  | 100       | 50%    |
| Attendance| 30        | 50%    |

#### 5. Enter Grades
- Fill in scores for each student/component
- System auto-validates against max score
- Averages calculate in real-time

#### 6. Save & View Results
- Click "Save Grades"
- View term grade in "Results" section
- See decimal grade (1.0-5.0 scale)

## Data Flow

```
Teacher Entry
   ↓
ComponentEntry (raw score + component)
   ↓
Auto-Normalize to 0-100 Scale
   ↓
Calculate Weighted Category Average
   ↓
ComponentAverage (K, S, A avg + final grade)
   ↓
Convert to Decimal Grade (1.0-5.0)
   ↓
Display to Teacher/Student
```

## Grade Calculation Formula

### Step 1: Normalize Each Score
```
Each raw score → 0-100 scale
Example: Quiz score 24/25 = 96.00
```

### Step 2: Weight Within Category
```
Category Average = (score₁ × weight₁ + score₂ × weight₂) / Σweights

Example Knowledge:
= (96.00 × 0.25 + 88.00 × 0.25 + 92.00 × 0.25 + 85.00 × 0.25) / 1.0
= 90.25
```

### Step 3: Calculate Final Grade
```
Final Grade = (K × 0.40) + (S × 0.50) + (A × 0.10)

Example:
= (90.25 × 0.40) + (82.50 × 0.50) + (88.00 × 0.10)
= 36.10 + 41.25 + 8.80
= 86.15
```

### Step 4: Convert to Decimal Scale
```
86.15 → 2.00 (Philippine grading scale)

Grade Scale:
≥98  → 1.00    ≥86  → 2.00    ≥74  → 3.00
≥95  → 1.25    ≥83  → 2.25    ≥71  → 3.25
≥92  → 1.50    ≥80  → 2.50    ≥70  → 3.50
≥89  → 1.75    ≥77  → 2.75    <70  → 5.00
```

## Component Management

### Add Component
1. Click "Configure Components" → "Add [Category] Component"
2. Enter: Name (e.g., "Quiz 1"), Max Score (e.g., 25), Weight (e.g., 25%)
3. Component appears in table
4. Automatically updates grade entry form

### Delete Component
1. Click trash icon next to component
2. Confirm deletion
3. All data for that component is deleted
4. Grade entry form updates

### Edit Component
1. Click component row
2. Change Max Score or Weight
3. Changes apply immediately
4. All averages recalculate

### View Templates
Pre-built component sets for quick setup:
- **Standard Quizzes:** 5 × 25 pts each
- **Outputs:** 3 × 100 pts each
- **Activities:** 3 × 100 pts each
- ... and more

## Database Structure

```
Classes
   ↓
AssessmentComponents (flexible component definitions)
   ├── Knowledge: Quiz, Exam, etc.
   ├── Skills: Output, Activity, Assignment
   └── Attitude: Behavior, Attendance

   ↓
ComponentEntries (actual student scores)
   └── student_id, component_id, raw_score, normalized_score

   ↓
ComponentAverages (cached calculations)
   └── knowledge_avg, skills_avg, attitude_avg, final_grade
```

## API Endpoints (For Developers)

### Component Management
```
GET    /teacher/components/{classId}
POST   /teacher/components/{classId}
PUT    /teacher/components/{classId}/{componentId}
DELETE /teacher/components/{classId}/{componentId}
POST   /teacher/components/{classId}/reorder
```

### Grade Entry
```
GET    /teacher/grades/dynamic/{classId}
GET    /teacher/grades/dynamic/{classId}/entries
POST   /teacher/grades/dynamic/{classId}/entries
GET    /teacher/grades/dynamic/{classId}/averages
```

## Migration from Old System

If you're currently using the fixed grading system:

1. **Old components stored in:** `assessment_ranges` table
2. **Convert to:** `assessment_components` table
3. **Old entries in:** `grade_entries` table (fixed columns)
4. **Convert to:** `component_entries` table (flexible)

**Automatic Migration:**
- Run migration command: `php artisan migrate`
- System creates new tables
- Option to import old data (coming soon)

## Troubleshooting

### Problem: Grades not calculating?
→ Ensure you've added at least one component to each category (Knowledge, Skills, Attitude)
→ Check that max_score > 0 for each component

### Problem: Can't modify grades after saving?
→ Click component to re-enter grades
→ Edit cell directly and save again

### Problem: Missing components?
→ Make sure components are marked as `is_active = true`
→ Check you're in the right term (Midterm/Final)

### Problem: Strange decimal grades?
→ Verify grade scale conversion
→ Check final grade is in 0-100 range

## Tips & Tricks

1. **Quick Setup:** Use component templates from Settings
2. **Batch Edit:** Copy/paste grades from Excel
3. **Export:** View → Export to PDF/Excel
4. **Reorder:** Drag components to sort display order
5. **Weights:** Hover over % to see weight contribution
6. **Validation:** Invalid scores show red border and reject on save
7. **Mobile:** Optimized for tablets and phones

## Contact & Support

For issues or feature requests:
- Contact School Admin
- Check Documentation at: `/DYNAMIC_GRADING_GUIDE.md`
- Report bugs via: Help → Report Issue
