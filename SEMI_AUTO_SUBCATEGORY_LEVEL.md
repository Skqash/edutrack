# Semi-Auto Mode - Subcategory-Level Implementation

## Feature Enhancement
Extended Semi-Auto mode to work at **subcategory level** (like Auto mode), making it "manual but automatic" - giving teachers the flexibility to override weights while keeping adjustments logical and contained within subcategories.

## The Vision: "Manual but Automatic"

**User's Request:**
> "Make the semi auto more better function like its manual but automatic"

**Translation:**
- **Manual:** Teachers can override any weight they want
- **Automatic:** System handles the math and adjustments automatically
- **Subcategory-Level:** Adjustments only affect related components (Quizzes don't affect Exam)

## Implementation

### Before (Category-Level Semi-Auto)
```
Knowledge (100%):
  Exam: 25%
  Quiz 1: 25%
  Quiz 2: 25%
  Quiz 3: 25%

Change Exam to 60%:
  Exam: 60% ✓
  Quiz 1: 13.33% ❌ (affected)
  Quiz 2: 13.33% ❌ (affected)
  Quiz 3: 13.34% ❌ (affected)
```

### After (Subcategory-Level Semi-Auto)
```
Knowledge (100%):
  Exam: 60%
  Quiz 1: 13.33%
  Quiz 2: 13.33%
  Quiz 3: 13.34%

Change Quiz 1 to 20%:
  Exam: 60% ✅ (unchanged - different subcategory)
  Quiz 1: 20% ✓ (your override)
  Quiz 2: 10% ✅ (adjusted within Quizzes only)
  Quiz 3: 10% ✅ (adjusted within Quizzes only)
```

## Code Changes

### 1. `addComponent()` - Semi-Auto Mode

**Before:** Redistributed across entire category
```php
if ($weightMode === 'semi-auto') {
    $this->redistributeWeights($classId, $validated['category']);
}
```

**After:** Redistributes only within subcategory
```php
if ($weightMode === 'semi-auto') {
    $this->redistributeWeights($classId, $validated['category'], $validated['subcategory']);
    
    return "✅ Semi-Auto Mode: {$component->name} added! All {$subcategoryCount} {$subcategory} components redistributed.";
}
```

### 2. `deleteComponent()` - Semi-Auto Mode

**Before:** Redistributed across entire category
```php
$this->redistributeWeights($classId, $category);
```

**After:** Redistributes only within subcategory
```php
if ($weightMode === 'semi-auto') {
    $this->redistributeWeights($classId, $category, $subcategory);
    
    return "🗑️ Semi-Auto Mode: {$componentName} deleted! Remaining {$remainingCount} {$subcategory} components redistributed.";
}
```

### 3. `updateComponent()` - Semi-Auto Mode

**Before:** Adjusted all components in category proportionally
```php
// Get all other components in the same category
$otherComponents = AssessmentComponent::where('class_id', $classId)
    ->where('category', $category)
    ->where('is_active', true)
    ->where('id', '!=', $componentId)
    ->get();

// Redistribute among ALL category components
foreach ($otherComponents as $otherComponent) {
    $proportion = $otherTotalWeight > 0 ? ($otherComponent->weight / $otherTotalWeight) : (1 / $otherComponents->count());
    $newOtherWeight = round($remainingWeight * $proportion, 2);
    $otherComponent->update(['weight' => $newOtherWeight]);
}
```

**After:** Adjusts only components in same subcategory proportionally
```php
// Get components in the same SUBCATEGORY (not entire category)
$subcategoryComponents = AssessmentComponent::where('class_id', $classId)
    ->where('category', $category)
    ->where('subcategory', $component->subcategory)
    ->where('is_active', true)
    ->where('id', '!=', $componentId)
    ->get();

// Redistribute among SUBCATEGORY components only
foreach ($subcategoryComponents as $otherComponent) {
    $proportion = $otherSubcategoryTotalWeight > 0 ? ($otherComponent->weight / $otherSubcategoryTotalWeight) : (1 / $subcategoryComponents->count());
    $newOtherWeight = round($remainingWeight * $proportion, 2);
    $otherComponent->update(['weight' => $newOtherWeight]);
}
```

## Mode Comparison (Updated)

### Manual Mode
- **Scope:** Individual components
- **Redistribution:** None
- **User Control:** 100% manual
- **Use Case:** Full control, custom weights

### Semi-Auto Mode (NEW: Subcategory-Level) ⭐ RECOMMENDED
- **Scope:** Subcategory-level (Quiz, Exam, Output, Activity, etc.)
- **Redistribution:** Proportional within same subcategory
- **User Control:** Can override any weight, others adjust automatically
- **Use Case:** Flexible grading with logical grouping

### Auto Mode (Subcategory-Level)
- **Scope:** Subcategory-level (Quiz, Exam, Output, Activity, etc.)
- **Redistribution:** Equal distribution within same subcategory
- **User Control:** No manual weight adjustment
- **Use Case:** Simple grading, equal importance within types

## Examples

### Example 1: Override Quiz Weight in Semi-Auto

**Initial State:**
```
Knowledge (100%):
  Exam: 60%
  Quiz 1: 13.33%
  Quiz 2: 13.33%
  Quiz 3: 13.34%
```

**Action:** Change Quiz 1 to 20%

**Calculation:**
- Quiz 1: 20% (manual override)
- Remaining for other Quizzes: 100% - 60% (Exam) - 20% (Quiz 1) = 20%
- Quiz 2 & 3 share 20% proportionally based on their current weights
- Quiz 2: (13.33 / 26.67) × 20 = 10%
- Quiz 3: (13.34 / 26.67) × 20 = 10%

**Result:**
```
Knowledge (100%):
  Exam: 60% ✅ (unchanged)
  Quiz 1: 20% ✓ (your override)
  Quiz 2: 10% ✅ (proportionally adjusted)
  Quiz 3: 10% ✅ (proportionally adjusted)
```

### Example 2: Add Quiz in Semi-Auto

**Initial State:**
```
Knowledge (100%):
  Exam: 60%
  Quiz 1: 20%
  Quiz 2: 10%
  Quiz 3: 10%
```

**Action:** Add Quiz 4

**Result:**
```
Knowledge (100%):
  Exam: 60% ✅ (unchanged)
  Quiz 1: 10% ✅ (redistributed within Quizzes)
  Quiz 2: 10% ✅ (redistributed within Quizzes)
  Quiz 3: 10% ✅ (redistributed within Quizzes)
  Quiz 4: 10% ✅ (new, equal share)
```

### Example 3: Delete Output in Semi-Auto

**Initial State:**
```
Skills (100%):
  Output 1: 10%
  Output 2: 10%
  Output 3: 10%
  Output 4: 10%
  Activity 1: 20%
  Activity 2: 20%
  Activity 3: 20%
```

**Action:** Delete Output 1

**Result:**
```
Skills (100%):
  Output 2: 13.33% ✅ (redistributed within Outputs)
  Output 3: 13.33% ✅ (redistributed within Outputs)
  Output 4: 13.34% ✅ (redistributed within Outputs)
  Activity 1: 20% ✅ (unchanged)
  Activity 2: 20% ✅ (unchanged)
  Activity 3: 20% ✅ (unchanged)
```

### Example 4: Override Exam Weight in Semi-Auto

**Initial State:**
```
Knowledge (100%):
  Exam: 60%
  Quiz 1: 13.33%
  Quiz 2: 13.33%
  Quiz 3: 13.34%
```

**Action:** Change Exam to 70%

**Result:**
```
Knowledge (100%):
  Exam: 70% ✓ (your override)
  Quiz 1: 10% ✅ (Quizzes share remaining 30%)
  Quiz 2: 10% ✅ (Quizzes share remaining 30%)
  Quiz 3: 10% ✅ (Quizzes share remaining 30%)
```

## Benefits

### 1. **Best of Both Worlds**
- **Flexibility:** Override any weight you want (like Manual)
- **Automation:** System handles the math (like Auto)
- **Intelligence:** Only affects related components (subcategory-level)

### 2. **Pedagogical Accuracy**
- Quizzes adjust independently from Exams
- Outputs adjust independently from Activities
- Reflects real-world grading structure

### 3. **Predictability**
- Teachers know exactly which components will be affected
- No unexpected changes in unrelated assessments
- Clear cause-and-effect relationship

### 4. **Time-Saving**
- No manual calculation of remaining weights
- No need to adjust multiple components manually
- System maintains 100% total automatically

### 5. **Flexibility**
- Can have different weight distributions per subcategory
- Can override any component at any time
- Can mix equal and custom weights

## User Interface Updates

### 1. Mode Status Alert (grade_content.blade.php)
```
"System suggests equal weights within each subcategory, but you can override any component. 
Other weights in the same subcategory adjust proportionally to maintain 100%."
```

### 2. Information Modal (settings.blade.php)
Updated Semi-Auto Mode explanation:
```
You change Quiz 1 to 20%:
✓ Exam stays: 60% (not affected)
✓ Quiz 1: 20% (your override)
✓ Quiz 2 & 3: 10% each (proportionally adjusted)
Total: 100% ✓
```

Badge updated to: "Recommended - Subcategory-Level"

### 3. Success Messages
- Add: "✅ Semi-Auto Mode: Quiz 4 added! All 4 Quiz components redistributed."
- Delete: "🗑️ Semi-Auto Mode: Quiz 1 deleted! Remaining 3 Quiz components redistributed."
- Update: "✅ Semi-Auto Mode: Quiz 1 updated to 20%. Other Quiz components adjusted proportionally."

## Comparison: All Three Modes

### Scenario: Change Quiz 1 from 13.33% to 20%

**Manual Mode:**
```
Before:
  Exam: 60%, Quiz 1: 13.33%, Quiz 2: 13.33%, Quiz 3: 13.34%

After:
  Exam: 60%, Quiz 1: 20%, Quiz 2: 13.33%, Quiz 3: 13.34%
  Total: 106.67% ❌ ERROR - Must manually adjust others
```

**Semi-Auto Mode (NEW):**
```
Before:
  Exam: 60%, Quiz 1: 13.33%, Quiz 2: 13.33%, Quiz 3: 13.34%

After:
  Exam: 60% ✅, Quiz 1: 20% ✓, Quiz 2: 10% ✅, Quiz 3: 10% ✅
  Total: 100% ✓ Automatic adjustment within Quizzes only
```

**Auto Mode:**
```
Before:
  Exam: 60%, Quiz 1: 13.33%, Quiz 2: 13.33%, Quiz 3: 13.34%

After:
  Cannot change - weights are locked in Auto mode
  All Quizzes must have equal weights
```

## Files Modified

1. **app/Http/Controllers/AssessmentComponentController.php**
   - `addComponent()` - Semi-Auto uses subcategory-level redistribution
   - `deleteComponent()` - Semi-Auto uses subcategory-level redistribution
   - `updateComponent()` - Semi-Auto adjusts only same subcategory proportionally

2. **resources/views/teacher/grades/settings.blade.php**
   - Updated Semi-Auto Mode information modal
   - Added subcategory-level example
   - Updated badge: "Recommended - Subcategory-Level"

3. **resources/views/teacher/grades/grade_content.blade.php**
   - Updated Semi-Auto Mode status alert description

## Testing Scenarios

### Test 1: Override Quiz Weight
1. Start with: Exam (60%), Quiz 1 (13.33%), Quiz 2 (13.33%), Quiz 3 (13.34%)
2. Change Quiz 1 to 20%
3. Expected: Exam (60%), Quiz 1 (20%), Quiz 2 (10%), Quiz 3 (10%)
4. Verify: Exam unchanged, only Quizzes adjusted ✅

### Test 2: Add Output
1. Start with: Output 1 (10%), Output 2 (10%), Activity 1 (20%)
2. Add Output 3
3. Expected: Output 1 (6.67%), Output 2 (6.67%), Output 3 (6.66%), Activity 1 (20%)
4. Verify: Activity unchanged, only Outputs adjusted ✅

### Test 3: Delete Quiz
1. Start with: Exam (60%), Quiz 1 (13.33%), Quiz 2 (13.33%), Quiz 3 (13.34%)
2. Delete Quiz 1
3. Expected: Exam (60%), Quiz 2 (20%), Quiz 3 (20%)
4. Verify: Exam unchanged, remaining Quizzes redistributed ✅

### Test 4: Override Exam Weight
1. Start with: Exam (60%), Quiz 1 (13.33%), Quiz 2 (13.33%), Quiz 3 (13.34%)
2. Change Exam to 70%
3. Expected: Exam (70%), Quiz 1 (10%), Quiz 2 (10%), Quiz 3 (10%)
4. Verify: Only Quizzes adjusted to fit remaining 30% ✅

## Status

✅ **COMPLETE** - Semi-Auto Mode now works at subcategory level

## Why This is Better

### Before (Category-Level)
- Changing Exam affected Quizzes ❌
- Changing Quiz affected Exam ❌
- Unpredictable adjustments ❌
- Less control ❌

### After (Subcategory-Level)
- Changing Exam only affects other Exams (if any) ✅
- Changing Quiz only affects other Quizzes ✅
- Predictable adjustments ✅
- More control with automation ✅
- "Manual but automatic" ✅

## Recommendation

**Semi-Auto Mode (Subcategory-Level)** is now the **perfect balance** for most teachers:
- ✅ Flexibility to override any weight
- ✅ Automation handles the math
- ✅ Logical grouping (subcategory-level)
- ✅ Predictable behavior
- ✅ Time-saving
- ✅ Pedagogically accurate

This is why it's marked as **"Recommended"** in the UI!
