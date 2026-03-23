# Dynamic Grade Entry System - Implementation Guide

## Overview
The dynamic grade entry system allows teachers to flexibly add, remove, and customize assessment components (quizzes, outputs, activities, etc.) for each class. Each component can have custom maximum scores and weights, and grades automatically calculate based on KSA (Knowledge, Skills, Attitude) categories.

## Architecture

### Database Schema

#### `assessment_components` Table
Stores the structure of grade components
```
- id (primary key)
- class_id (foreign key)
- teacher_id (foreign key)
- category (enum: Knowledge, Skills, Attitude)
- subcategory (Display grouping)
- name (User-friendly name)
- max_score (Maximum points)
- weight (Weight % within category)
- order (Display sequence)
- is_active (Boolean flag)
```

#### `component_entries` Table
Stores individual student component scores per term
```
- id (primary key)
- student_id (foreign key)
- class_id (foreign key)
- component_id (foreign key)
- term (enum: midterm, final)
- raw_score (Input score)
- normalized_score (Auto-calculated 0-100)
- remarks (Optional notes)
- unique constraint: (student_id, component_id, term)
```

#### `component_averages` Table
Cache layer for calculated category averages
```
- id (primary key)
- student_id (foreign key)
- class_id (foreign key)
- term (enum: midterm, final)
- knowledge_average (0-100)
- skills_average (0-100)
- attitude_average (0-100)
- final_grade (0-100)
- unique constraint: (student_id, class_id, term)
```

## Models

### AssessmentComponent
Represents a single assessment component (e.g., "Quiz 1", "Output 3")
```php
$component = AssessmentComponent::find($id);
$normalized = $component->normalizeScore($rawScore);
```

### ComponentEntry
Individual student score entry
- Auto-normalizes score to 0-100 on save
- Cascades to ComponentAverage recalculation

### ComponentAverage
Cached calculation results for all category averages
```php
ComponentAverage::calculateAndUpdate($studentId, $classId, $term);
```

## Controllers

### AssessmentComponentController
**Endpoints:**
```
GET  /teacher/components/{classId}           - Get all components
POST /teacher/components/{classId}           - Add new component
PUT  /teacher/components/{classId}/{id}      - Update component
DELETE /teacher/components/{classId}/{id}    - Delete component
POST /teacher/components/{classId}/reorder   - Reorder components
GET  /teacher/components/templates/all       - Get templates
```

### GradeEntryDynamicController
**Endpoints:**
```
GET  /teacher/grades/dynamic/{classId}                      - Show entry form
GET  /teacher/grades/dynamic/{classId}/entries              - Get all entries
POST /teacher/grades/dynamic/{classId}/entries              - Save entries
GET  /teacher/grades/dynamic/{classId}/averages             - Get averages
GET  /teacher/grades/dynamic/{classId}/{studentId}/entries  - Get student entries
DELETE /teacher/grades/dynamic/entries/{entryId}            - Delete entry
DELETE /teacher/grades/dynamic/{classId}/{studentId}/entries - Clear student
```

## Services

### DynamicGradeCalculationService
Helper service for calculations

**Methods:**
```php
// Calculate all averages for a student
DynamicGradeCalculationService::calculateCategoryAverages($studentId, $classId, $term);
// Returns: [knowledge_average, skills_average, attitude_average, final_grade]

// Get specific component group average (e.g., all quiz scores)
DynamicGradeCalculationService::calculateComponentGroupAverage($studentId, $classId, $term, 'Quiz');

// Get detailed breakdown
DynamicGradeCalculationService::getDetailedReport($studentId, $classId, $term);

// Check validation
DynamicGradeCalculationService::validateCompletion($studentId, $classId, $term);
```

## Grade Calculation Logic

### Normalization
Each raw score is normalized to 0-100 scale:
```
normalized_score = (raw_score / max_score) * 100
```

### Category Average
Component scores within a category are weighted:
```
category_average = (score₁ × weight₁ + score₂ × weight₂ + ...) / Σweights
```

### Final Grade
Three categories combined with fixed weights:
```
final_grade = (knowledge × 0.40) + (skills × 0.50) + (attitude × 0.10)
```

### Decimal Grade Conversion
```
98-100 → 1.00    |    80-87 → 2.50    |    70-74 → 3.00
95-98  → 1.25    |    77-80 → 2.75    |    71-74 → 3.25
92-95  → 1.50    |    74-77 → 3.00    |    70-71 → 3.50
89-92  → 1.75    |    71-74 → 3.25    |    <70  → 5.00
86-89  → 2.00    |
83-86  → 2.25    |
```

## Frontend Integration

### Dynamic Grade Entry Form
Located at: `resources/views/teacher/grades/grade_entry_dynamic.blade.php`

**Features:**
1. **Component Manager Modal**
   - Add/Delete components within categories
   - View templates for quick setup
   - Reorder components

2. **Grade Table**
   - Dynamically generated based on configured components
   - Auto-calculates category averages
   - Real-time computation

3. **Student Rows**
   - Input fields for each component
   - Real-time validation against max scores
   - Auto-populated from saved entries

### JavaScript API
All interactions use FETCH API with CSRF protection:
```javascript
// Load components
await fetch(`/teacher/components/${classId}`);

// Save grades
await fetch(`/teacher/grades/dynamic/${classId}/entries`, {
    method: 'POST',
    body: JSON.stringify({term, entries})
});

// Get averages
await fetch(`/teacher/grades/dynamic/${classId}/averages?term=${term}`);
```

## Usage Example

### Step 1: Create Class and Students
```php
$class = ClassModel::create([...]);
$students = Student::whereIn('id', $studentIds)->get();
```

### Step 2: Configure Components
```php
// Add a quiz component
AssessmentComponent::create([
    'class_id' => $classId,
    'teacher_id' => $teacherId,
    'category' => 'Knowledge',
    'subcategory' => 'Quiz',
    'name' => 'Quiz 1',
    'max_score' => 25,
    'weight' => 10,
]);

// Add output component
AssessmentComponent::create([
    'class_id' => $classId,
    'teacher_id' => $teacherId,
    'category' => 'Skills',
    'subcategory' => 'Output',
    'name' => 'Output 1',
    'max_score' => 100,
    'weight' => 40,
]);
```

### Step 3: Enter Grades
```php
// Teacher enters grades via UI or API
ComponentEntry::create([
    'student_id' => $studentId,
    'class_id' => $classId,
    'component_id' => $componentId,
    'term' => 'midterm',
    'raw_score' => 23, // out of 25
]);
// Auto-normalizes to: 92.00 (0-100 scale)
```

### Step 4: View Results
```php
// Get calculated averages
$average = ComponentAverage::where('student_id', $studentId)
    ->where('class_id', $classId)
    ->where('term', 'midterm')
    ->first();

echo $average->knowledge_average;   // 85.50
echo $average->skills_average;      // 78.20
echo $average->attitude_average;    // 82.10
echo $average->final_grade;         // 79.87
echo $average->getDecimalGrade();   // 2.50
```

## Migration Instructions

### 1. Run Database Migration
```bash
php artisan migrate
```

### 2. Update TeacherController
Modify `showGradeEntryByTerm` to support both legacy and dynamic systems:
```php
// Check if class uses dynamic components
$usesDynamic = AssessmentComponent::where('class_id', $classId)->exists();

if ($usesDynamic) {
    return view('teacher.grades.grade_entry_dynamic', ...);
} else {
    return view('teacher.grades.grade_entry', ...);
}
```

### 3. Add to Teacher Dashboard
Add button to switch to dynamic entry:
```blade
@if (AssessmentComponent::where('class_id', $class->id)->exists())
    <a href="{{ route('teacher.grades.dynamic.show', $class->id) }}" class="btn btn-info">
        Dynamic Grade Entry
    </a>
@endif
```

## Backward Compatibility

The system supports both old and new grading tables:
- Old system uses fixed columns (quiz_1, quiz_2, output_1, etc.)
- New system uses dynamic component_entries table
- Both can coexist

To migrate an existing class:
1. Create AssessmentComponents based on existing structure
2. Import data from grade_entries or grades table
3. Recalculate ComponentAverages

## API Response Examples

### Get Components
```json
{
  "success": true,
  "components": {
    "Knowledge": [
      {
        "id": 1,
        "name": "Quiz 1",
        "max_score": 25,
        "weight": 10,
        "order": 1
      }
    ],
    "Skills": [...],
    "Attitude": [...]
  }
}
```

### Save Entries Response
```json
{
  "success": true,
  "saved": 42,
  "errors": [],
  "message": "✅ Saved 42 component entries"
}
```

### Get Averages
```json
{
  "success": true,
  "averages": [
    {
      "student_id": 1,
      "knowledge_average": 85.50,
      "skills_average": 78.20,
      "attitude_average": 82.10,
      "final_grade": 79.87
    }
  ]
}
```

## Best Practices

1. **Weight Distribution**
   - Sum weights within each category should ideally = 100
   - If they don't, they auto-normalize

2. **Component Naming**
   - Use consistent naming: "Quiz 1", "Quiz 2" or "Q1", "Q2"
   - Include subcategory for grouping

3. **Max Scores**
   - Keep them reasonable (10-500 range)
   - Use same max for similar items (all quizzes = 25, all outputs = 100)

4. **Term Management**
   - Always specify term (midterm/final)
   - Averages calculated per term

5. **Data Validation**
   - Input validation happens on frontend and API
   - Scores > max_score are rejected

## Troubleshooting

### Averages Not Calculated
- Run: `ComponentAverage::calculateAndUpdate($studentId, $classId, $term)`
- Check if ComponentEntry records exist

### Missing Components
- Verify `is_active = true` for active components
- Check teacher_id matches current user

### Normalization Issues
- If max_score = 0, normalized_score = 0
- Check component max_score configuration

## Future Enhancements

1. **Import/Export**
   - Bulk import components from templates
   - Export results to Excel

2. **Advanced Filtering**
   - Filter by component, category, date range
   - Different views (by student, by component)

3. **Analytics**
   - Component difficulty analysis
   - Student progress tracking
   - Grade distribution charts

4. **Collaboration**
   - Multiple teacher grading
   - Moderation workflows
   - Audit trails
