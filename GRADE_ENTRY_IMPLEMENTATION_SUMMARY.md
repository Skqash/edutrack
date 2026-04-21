# EduTrack Grade Entry System - Complete Implementation Guide

## 📋 Overview
The EduTrack grade entry system implements a comprehensive KSA (Knowledge, Skills, Attitude) grading framework with dynamic component management, multiple grading modes, and flexible entry options (manual/automated/hybrid).

---

## 🎯 1. CORE FILES & ARCHITECTURE

### **View Files (Grade Entry UI)**
| File | Purpose |
|------|---------|
| [resources/views/teacher/grades/grade_entry.blade.php](resources/views/teacher/grades/grade_entry.blade.php) | Main grade entry form with hardcoded KSA structure |
| [resources/views/teacher/grades/grade_results.blade.php](resources/views/teacher/grades/grade_results.blade.php) | Display grade calculation results |
| [resources/views/teacher/grades/grade_settings.blade.php](resources/views/teacher/grades/grade_settings.blade.php) | Grade settings configuration page |
| [resources/views/teacher/grades/grading-mode-selector.blade.php](resources/views/teacher/grades/grading-mode-selector.blade.php) | Mode selection interface (Standard/Manual/Automated/Hybrid) |
| [resources/views/teacher/grades/index.blade.php](resources/views/teacher/grades/index.blade.php) | Grade management dashboard |
| [resources/views/teacher/grades/components/component-manager-modal.blade.php](resources/views/teacher/grades/components/component-manager-modal.blade.php) | Modal for adding/editing/managing components |
| [resources/views/teacher/grades/advanced_grade_entry.blade.php](resources/views/teacher/grades/advanced_grade_entry.blade.php) | Advanced entry interface |

### **Controller Files (Business Logic)**
| File | Responsibility |
|------|-----------------|
| [app/Http/Controllers/GradeEntryDynamicController.php](app/Http/Controllers/GradeEntryDynamicController.php) | Grade entry display & CRUD operations |
| [app/Http/Controllers/GradingModeController.php](app/Http/Controllers/GradingModeController.php) | Grading mode selection & configuration |
| [app/Http/Controllers/GradeSettingsController.php](app/Http/Controllers/GradeSettingsController.php) | Component & KSA settings management |
| [app/Http/Controllers/AssessmentComponentController.php](app/Http/Controllers/AssessmentComponentController.php) | Component CRUD (add/edit/delete/reorder) |

### **Model Files (Data Layer)**
| File | Data Structure |
|------|-----------------|
| [app/Models/AssessmentComponent.php](app/Models/AssessmentComponent.php) | Component definition (name, max_score, weight, entry_mode) |
| [app/Models/ComponentEntry.php](app/Models/ComponentEntry.php) | Individual student component scores |
| [app/Models/ComponentAverage.php](app/Models/ComponentAverage.php) | Calculated category averages |
| [app/Models/KsaSetting.php](app/Models/KsaSetting.php) | KSA percentage weights (Knowledge%, Skills%, Attitude%) |
| [app/Models/GradeComponent.php](app/Models/GradeComponent.php) | Alternative/legacy component model |
| [app/Models/GradingScaleSetting.php](app/Models/GradingScaleSetting.php) | Grading mode settings (standard/manual/automated/hybrid) |

### **Service Files (Business Logic)**
| File | Purpose |
|------|---------|
| [app/Services/GradingCalculationService.php](app/Services/GradingCalculationService.php) | Calculates grades based on mode (standard/manual/automated/hybrid) |

### **Configuration Files**
| File | Content |
|------|---------|
| [config/grade_schemes.php](config/grade_schemes.php) | Predefined grading schemes (CHED KSA, DepEd Basic, etc.) |

---

## 🏗️ 2. GRADING SCHEMES & MODES

### **Predefined Grade Schemes** (config/grade_schemes.php)
```
1. CHED KSA (Default)
   - Knowledge: 40% | Skills: 50% | Attitude: 10%
   - Term weights: Midterm 40%, Final 60%

2. 60/30/10 Rule
   - Knowledge: 60% | Skills: 30% | Attitude: 10%

3. DepEd Basic (Public Schools)
   - Knowledge: 50% | Skills: 40% | Attitude: 10%

4. Private School Variant
   - Knowledge: 45% | Skills: 45% | Attitude: 10%
   - Term weights: Midterm 30%, Final 70%

5. University/College (Customizable)
   - Knowledge: 40% | Skills: 50% | Attitude: 10%
```

### **Grading Modes** (GradingModeController.php)

#### **1. Standard KSA (Default)**
- Uses predefined component structure
- Manual component entry per student
- Knowledge, Skills, Attitude categories
- Flexible KSA percentages
- Formula: `FinalGrade = (Knowledge% × K_avg) + (Skills% × S_avg) + (Attitude% × A_avg)`

#### **2. Full Manual Entry**
- Teachers manually enter all grades
- No automatic calculations
- Component scores are optional
- Full control over grading
- Flexible weighting

#### **3. Fully Automated**
- Auto-calculation from component scores
- Consistent formula application
- No manual entry errors
- Real-time grade updates
- Audit trail of calculations

#### **4. Hybrid (Mixed Mode)**
- Mix manual and automated entry at component level
- Choose per-component mode
- Maximum flexibility
- Gradual automation transition

---

## 🧩 3. COMPONENT MANAGEMENT

### **Component Structure** (AssessmentComponent Model)
```php
Fields:
- id: Primary key
- class_id: Class this component belongs to
- teacher_id: Teacher ownership
- category: 'Knowledge' | 'Skills' | 'Attitude'
- subcategory: 'Quiz', 'Exam', 'Output', 'Activity', etc.
- name: Display name (e.g., "Quiz 1", "Final Exam")
- max_score: Maximum points (1-500)
- weight: Weight percentage within category
- passing_score: Minimum score to pass (optional)
- order: Display order
- is_active: Soft delete flag
- entry_mode: 'manual' | 'automated' | 'hybrid'
- is_quiz_component: Boolean
- quiz_type: 'objective' | 'subjective' | 'mixed'
- show_in_report: Include in grading sheets
- use_best_attempt: For automated grading
- use_average_attempt: For automated grading
```

### **Component Categories (Subcategory Templates)**
```
Knowledge:
  - Quiz
  - Exam
  - Test
  - Pre-test

Skills:
  - Output
  - Project
  - Assignment
  - Activity
  - Participation
  - Presentation

Attitude:
  - Behavior
  - Attendance
  - Awareness
  - Collaboration
  - Punctuality
```

### **Component CRUD Operations**

#### **ADD Component**
**Route:** `POST /teacher/components/{classId}`  
**Controller:** `AssessmentComponentController@addComponent()`  
**Fields:**
- name (required, max 100, alphanumeric + dash)
- category (required: Knowledge/Skills/Attitude)
- subcategory (required)
- max_score (required, 1-500)
- weight (required, 0-100%)
- passing_score (optional)

**Behavior:**
- Automatically redistributes weights among all components in category
- Adds 1 to max order in category
- Returns JSON response with created component

#### **UPDATE Component**
**Route:** `PUT /teacher/components/{classId}/{componentId}`  
**Controller:** `AssessmentComponentController@updateComponent()`  
**Fields:** name, max_score, weight, passing_score, subcategory

**Advanced Behavior:**
- If weight changes, validates new weight won't exceed 100%
- Redistributes remaining weight among other components
- Validates total doesn't exceed 100%

#### **DELETE Component**
**Route:** `DELETE /teacher/components/{classId}/{componentId}`  
**Controller:** `AssessmentComponentController@deleteComponent()`

**Behavior:**
- Deletes all associated ComponentEntry records
- Reorders remaining components in category
- Redistributes weights automatically
- Cascading delete via foreign key

#### **REORDER Components**
**Route:** `POST /teacher/components/{classId}/reorder`  
**Controller:** `AssessmentComponentController@reorderComponents()`  
**Payload:**
```json
{
  "components": [
    {"id": 1, "order": 1},
    {"id": 2, "order": 2},
    {"id": 3, "order": 3}
  ]
}
```

#### **DUPLICATE Component**
**Route:** `POST /teacher/components/{classId}/{componentId}/duplicate`  
**Controller:** `AssessmentComponentController@duplicateComponent()`

#### **APPLY TEMPLATE**
**Route:** `POST /teacher/components/{classId}/apply-template`  
**Templates:**
- Knowledge Template (Quizzes & Exams)
- Skills Template (Outputs, Activities, Assignments)
- Attitude Template (Behavior & Attendance)

#### **GET Components (List)**
**Route:** `GET /teacher/components/{classId}`  
**Controller:** `AssessmentComponentController@getComponents()`  
**Returns:** Grouped by category (knowledge, skills, attitude) with counts

#### **GET Subcategories**
**Route:** `GET /teacher/components/{classId}/subcategories/{category}`  
**Returns:** Available subcategories for a given KSA category

---

## 📊 4. GRADE ENTRY INTERFACE

### **Static KSA Structure** (grade_entry.blade.php)
The main grade entry form uses a hardcoded table structure:

```
Knowledge (40%)
├── Exam (60%)
├── Quiz 1 (20%)
└── Quiz 2 (20%)

Skills (50%)
├── Output (40%)
├── Class Participation (30%)
└── Activities (30%)

Attitude (10%)
├── Behavior (50%)
└── Awareness (50%)
```

### **Grade Entry Features**
- Real-time calculation of weighted averages
- Row-by-row final grade calculation
- Clear all grades button
- Calculate all button
- Sticky table header for scrolling
- Auto-save on form submission
- Responsive table design

### **JavaScript Calculation (grade_entry.blade.php)**
```javascript
// Auto-calculate on value change
calculateRowGrade(row) {
  1. Calculates weighted average per category
  2. Applies KSA percentages
  3. Updates final grade display
}

// Batch calculation
calculateAllGrades() {
  - Iterates all rows
  - Calls calculateRowGrade for each
}
```

---

## 🔄 5. DYNAMIC ENTRY SYSTEM

### **GradeEntryDynamicController Endpoints**

| Route | Method | Purpose |
|-------|--------|---------|
| `/grades/dynamic/{classId}` | GET | Show dynamic grade entry form |
| `/grades/dynamic/{classId}/entries` | GET | Get entries for class/term (JSON) |
| `/grades/dynamic/{classId}/entries` | POST | Save multiple component entries |
| `/grades/dynamic/{classId}/averages` | GET | Get calculated averages for all students |
| `/grades/dynamic/{classId}/{studentId}/entries` | GET | Get single student's entries |
| `/grades/dynamic/entries/{entryId}` | DELETE | Delete individual component entry |
| `/grades/dynamic/{classId}/{studentId}/entries` | DELETE | Bulk delete all student entries |

### **Entry Storage (ComponentEntry Model)**
```php
Fields:
- student_id: Student being graded
- class_id: Class context
- component_id: Which component
- term: 'midterm' | 'final'
- raw_score: Original score entered
- normalized_score: Auto-calculated (raw/max × 50 + 50)
- remarks: Teacher notes (optional)
```

### **Normalization Formula**
```
normalized_score = (raw_score / max_score) × 50 + 50

Range: 50 (0% correct) to 100 (100% correct)
This ensures all scores are on 0-100 scale regardless of max_score
```

---

## ⚙️ 6. KSA SETTINGS & CONFIGURATION

### **KsaSetting Model**
```php
Fields:
- class_id
- teacher_id
- term: 'midterm' | 'final'
- knowledge_weight: 0-100 (default 40)
- skills_weight: 0-100 (default 50)
- attitude_weight: 0-100 (default 10)
- attendance_weight: 0-100
- passing_grade: 0-100 (default 75)
- minimum_attendance: 0-100 (default 75)
- use_weighted_average: Boolean
- round_final_grade: Boolean
- decimal_places: Integer
- include_attendance_in_attitude: Boolean
- auto_calculate: Boolean
- is_locked: Boolean (prevent changes)
```

### **Update KSA Percentages**
**Route:** `POST /grade-settings/{classId}/{term}/percentages`  
**Validation:**
- knowledge_weight + skills_weight + attitude_weight = 100%
- Allows floating point precision (±0.01%)

### **Default KSA Settings**
**Method:** `KsaSetting::getOrCreateDefault($classId, $term, $teacherId)`  
Creates default 40/50/10 split if none exists

---

## 📈 7. GRADE CALCULATION SERVICE

### **GradingCalculationService**

#### **Standard Mode Calculation**
```
1. Get category averages for student
   K_avg = Average of all Knowledge component scores
   S_avg = Average of all Skills component scores
   A_avg = Average of all Attitude component scores

2. Apply KSA percentages
   Final = (K_avg × knowledge%) + (S_avg × skills%) + (A_avg × attitude%)

3. Save to ComponentAverage table
```

#### **Manual Mode Calculation**
- No automatic calculation
- Teacher enters all values manually
- Component scores optional
- Returns null grades until manually filled

#### **Automated Mode Calculation**
- Auto-normalize all component scores
- Auto-weight within categories
- Auto-calculate category averages
- Auto-calculate final grade
- Consistent application across all students

#### **Hybrid Mode Calculation**
- Per-component choice (manual vs automated)
- Flexible entry strategies
- Transition from manual to automated possible

---

## 🛣️ 8. ROUTING & API ENDPOINTS

### **Grade Entry Routes** (routes/web.php)

#### **Main Grade Entry Paths**
```
# Traditional Entry
GET    /teacher/grades/entry/{classId}              → TeacherController@showGradeEntryByTerm
POST   /teacher/grades/entry/{classId}              → TeacherController@storeGradeEntryByTerm

# Dynamic Entry System
GET    /teacher/grades/dynamic/{classId}            → GradeEntryDynamicController@show
GET    /teacher/grades/dynamic/{classId}/entries    → GradeEntryDynamicController@getEntries
POST   /teacher/grades/dynamic/{classId}/entries    → GradeEntryDynamicController@saveEntries
GET    /teacher/grades/dynamic/{classId}/averages   → GradeEntryDynamicController@getAverages
GET    /teacher/grades/dynamic/{classId}/{studentId}/entries 
                                                     → GradeEntryDynamicController@getStudentEntries
DELETE /teacher/grades/dynamic/entries/{entryId}    → GradeEntryDynamicController@deleteEntry
DELETE /teacher/grades/dynamic/{classId}/{studentId}/entries
                                                     → GradeEntryDynamicController@deleteStudentEntries
```

#### **Component Management Routes**
```
# Component CRUD
GET    /teacher/components/{classId}                → AssessmentComponentController@getComponents
POST   /teacher/components/{classId}                → AssessmentComponentController@addComponent
PUT    /teacher/components/{classId}/{componentId}  → AssessmentComponentController@updateComponent
DELETE /teacher/components/{classId}/{componentId}  → AssessmentComponentController@deleteComponent

# Advanced Operations
POST   /teacher/components/{classId}/reorder        → AssessmentComponentController@reorderComponents
POST   /teacher/components/{classId}/apply-template → AssessmentComponentController@applyTemplate
GET    /teacher/components/{classId}/templates      → AssessmentComponentController@getTemplates
GET    /teacher/components/{classId}/subcategories/{category}
                                                     → AssessmentComponentController@getSubcategories
POST   /teacher/components/{classId}/bulk-delete    → AssessmentComponentController@bulkDelete
POST   /teacher/components/{classId}/{componentId}/duplicate
                                                     → AssessmentComponentController@duplicateComponent
POST   /teacher/components/{classId}/update-weights → AssessmentComponentController@updateWeights
```

#### **Settings Routes**
```
# Grade Settings
GET    /teacher/grade-settings/{classId}/{term}    → GradeSettingsController@show
GET    /teacher/grade-settings/{classId}/{term}/settings
                                                    → GradeSettingsController@getSettings
POST   /teacher/grade-settings/{classId}/{term}/percentages
                                                    → GradeSettingsController@updatePercentages

# Grading Mode
GET    /teacher/grades/mode/{classId}              → GradingModeController@show
POST   /teacher/grades/mode/{classId}              → GradingModeController@update
```

---

## 💾 9. DATABASE SCHEMA

### **Key Tables**

#### **assessment_components**
```sql
- id (PK)
- class_id (FK → classes)
- teacher_id (FK → users)
- category (Knowledge/Skills/Attitude)
- subcategory
- name
- max_score
- weight (0-100)
- passing_score
- order
- is_active
- entry_mode (manual/automated/hybrid)
- is_quiz_component
- quiz_type
- show_in_report
- min_attempts
- use_best_attempt
- use_average_attempt
- component_metadata (JSON)
- timestamps
```

#### **component_entries**
```sql
- id (PK)
- student_id (FK → students)
- class_id (FK → classes)
- component_id (FK → assessment_components)
- term
- raw_score
- normalized_score
- remarks
- timestamps
```

#### **component_averages**
```sql
- id (PK)
- student_id (FK → students)
- class_id (FK → classes)
- term
- knowledge_average
- skills_average
- attitude_average
- final_grade
- timestamps
```

#### **ksa_settings**
```sql
- id (PK)
- class_id (FK → classes)
- teacher_id (FK → users)
- term
- knowledge_weight
- skills_weight
- attitude_weight
- passing_grade
- is_locked
- timestamps
```

#### **grading_scale_settings**
```sql
- id (PK)
- class_id (FK → classes)
- teacher_id (FK → users)
- term
- grading_mode (standard/manual/automated/hybrid)
- quiz_entry_mode
- output_format
- knowledge_percentage
- skills_percentage
- attitude_percentage
- attendance_weight_percentage
- timestamps
```

---

## 🔑 10. KEY IMPLEMENTATION DETAILS

### **Component Weight Distribution**
When a component is deleted or weight is changed:
1. Component is deleted/weight updated
2. Weights are recalculated to sum to 100%
3. Remaining components get equal redistribution
4. Floating point precision handled (±0.01%)

### **Score Normalization**
All raw scores normalized to 0-100 scale:
- Minimum: 50 (0% correct)
- Maximum: 100 (100% correct)
- Formula: `(raw / max) × 50 + 50`

### **Auto-Calculation Flow**
```
1. Component entry saved → raw_score entered
2. Model boot() fires → normalizes raw_score
3. Entry created with normalized_score
4. ComponentAverage::calculateAndUpdate() called
5. Category averages calculated
6. Final grade calculated using KSA weights
7. Grade entry updated with final_grade
```

### **Multi-Mode Support**
- Standard: Manual entry with fixed structure
- Manual: Complete teacher control
- Automated: System calculates everything
- Hybrid: Mixed approach per-component

### **Entry Modes per Component**
- **Manual**: Teacher enters specific value
- **Automated**: System calculates from subcomponents
- **Hybrid**: Teacher can choose per-entry

---

## 📝 11. COMPONENT MANAGER MODAL (UI)

### **Features**
- **Add Tab**: Create new components
- **Manage Tab**: Edit/delete existing components
- **Templates Tab**: Quick-apply predefined setups

### **Available Templates**
1. **Knowledge Template**
   - Quiz 1, Quiz 2
   - Final Exam

2. **Skills Template**
   - Major Output 1, 2, 3
   - Class Participation
   - Group Activities

3. **Attitude Template**
   - Behavior
   - Punctuality/Attendance

---

## 🚀 12. COMMON WORKFLOWS

### **Workflow 1: Set Up Grade Entry for a Class**
```
1. Go to Grade Settings → /grade-settings/{classId}/midterm
2. Configure KSA percentages (default 40/50/10)
3. Click "Select Grading Mode" → Choose mode
4. Add components via Component Manager modal
   - Add components to each KSA category
   - Set weights (auto-distributed)
   - Set passing scores
5. Save configuration
6. Go to Grade Entry → /grades/dynamic/{classId}
7. Enter student component scores
8. Click "Calculate All" → Final grades compute
9. Submit form → Save all grades
```

### **Workflow 2: Edit a Component (Add/Remove/Change)**
```
1. In Grade Entry or Settings page
2. Click Component Manager button
3. Manage Components tab
4. Edit component (change name, weight, max_score)
5. Weights auto-redistribute
6. Delete component → Related entries deleted, weights redistribute
7. Add component → Set category, name, weight
8. Save changes → Affects all future grades
```

### **Workflow 3: Change Entry Mode for Class**
```
1. Navigate to Grading Mode Configuration
2. View available modes (Standard/Manual/Automated/Hybrid)
3. Select new mode
4. Configure settings:
   - Set quiz entry mode (manual/automated/both)
   - Adjust KSA percentages
   - Enable/disable auto-calculation
5. Save → Applies to new grade entries
```

### **Workflow 4: Calculate Grades (Automated Mode)**
```
1. Components configured with entry_mode='automated'
2. Component entries saved (raw_score)
3. Trigger calculation:
   a. Manually: Click "Calculate All" button
   b. Automatically: On save if auto_calculate=true
4. System:
   - Normalizes scores
   - Calculates weighted averages per component
   - Sums component weights per category
   - Calculates category averages
   - Applies KSA weights
   - Updates final_grade
5. Display results in table
```

---

## 🔍 13. DEBUGGING & TROUBLESHOOTING

### **Common Issues**

**Issue:** Weights don't sum to 100%  
**Solution:** Check `KsaSetting::validateWeights()` - allows ±0.01% variance

**Issue:** Component won't delete  
**Solution:** Check for foreign key constraints; cascade delete should handle entries

**Issue:** Grades not calculating  
**Solution:** Verify `auto_calculate` flag in GradingScaleSetting; check ComponentEntry has raw_score

**Issue:** Normalized scores look wrong  
**Solution:** Verify max_score in component; formula is `(raw/max)×50+50`

---

## 📚 14. REFERENCES

### **Related Documentation**
- [GRADING_SYSTEM_MULTIMODE_FINAL.md](../GRADING_SYSTEM_MULTIMODE_FINAL.md)
- [IMPLEMENTATION_COMPLETE_GRADING_SYSTEM.md](../IMPLEMENTATION_COMPLETE_GRADING_SYSTEM.md)
- [PHASE3_COLLEGE_CURRICULUM_IMPLEMENTATION.md](../PHASE3_COLLEGE_CURRICULUM_IMPLEMENTATION.md)

### **Key Files to Review**
1. Start with: GradeEntryDynamicController.php
2. Then: AssessmentComponentController.php
3. Models: AssessmentComponent, ComponentEntry, KsaSetting
4. Services: GradingCalculationService
5. Views: grade_entry.blade.php, component-manager-modal.blade.php

---

**Last Updated:** April 7, 2026  
**System:** EduTrack Grade Entry & Management System
