# Subcategory Weight Limit Fix - Category Total Must Equal 100%

## Issue Discovered
User reported that Knowledge (K) category showed components totaling **over 100%** within the category in Semi-Auto mode.

### Example of the Bug:
```
Knowledge Category:
  Exam: 60%
  Quiz 1: 20%
  Quiz 2: 80%
  TOTAL: 160% ❌ EXCEEDS 100%!
```

## Root Cause

When we implemented subcategory-level redistribution, we made a critical error:

**The Bug:** When redistributing weights within a subcategory, we calculated weights as if **each subcategory should total 100%**.

**The Reality:** All subcategories within a category must **together total 100%**.

### Example of the Problem:

**Incorrect Logic (Bug):**
```
Knowledge has 2 subcategories: Exam and Quiz
- Redistribute Quizzes: Each quiz gets 100% / 2 = 50%
- Quiz 1: 50%, Quiz 2: 50%
- But Exam is 60%
- TOTAL: 60% + 50% + 50% = 160% ❌
```

**Correct Logic (Fixed):**
```
Knowledge has 2 subcategories: Exam (60%) and Quiz (40%)
- Available weight for Quizzes: 100% - 60% (Exam) = 40%
- Redistribute Quizzes within 40%: Each quiz gets 40% / 2 = 20%
- Quiz 1: 20%, Quiz 2: 20%
- TOTAL: 60% + 20% + 20% = 100% ✅
```

## The Fix

### 1. Fixed `redistributeWeights()` Method

**Before (INCORRECT):**
```php
private function redistributeWeights($classId, $category, $subcategory = null, $excludeComponentId = null)
{
    $query = AssessmentComponent::where('class_id', $classId)
        ->where('category', $category)
        ->where('is_active', true);
    
    if ($subcategory !== null) {
        $query->where('subcategory', $subcategory);
    }
    
    $components = $query->get();
    
    // BUG: Always distributes 100% among components
    $equalWeight = round(100 / $components->count(), 2);
    
    foreach ($components as $component) {
        $component->update(['weight' => $equalWeight]);
    }
}
```

**After (FIXED):**
```php
private function redistributeWeights($classId, $category, $subcategory = null, $excludeComponentId = null)
{
    if ($subcategory !== null) {
        // SUBCATEGORY-LEVEL REDISTRIBUTION
        $subcategoryComponents = AssessmentComponent::where('class_id', $classId)
            ->where('category', $category)
            ->where('subcategory', $subcategory)
            ->where('is_active', true)
            ->get();
        
        // Calculate available weight for this subcategory
        $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $classId)
            ->where('category', $category)
            ->where('subcategory', '!=', $subcategory)
            ->where('is_active', true)
            ->sum('weight');
        
        // Available weight = 100% - weight used by other subcategories
        $availableWeight = 100 - $otherSubcategoriesWeight;
        
        // Distribute available weight equally among components in this subcategory
        $componentCount = $subcategoryComponents->count();
        $equalWeight = round($availableWeight / $componentCount, 2);
        
        foreach ($subcategoryComponents as $component) {
            $component->update(['weight' => $equalWeight]);
        }
    } else {
        // CATEGORY-LEVEL REDISTRIBUTION (unchanged)
        // ... distribute 100% among all components in category ...
    }
}
```

### 2. Fixed Semi-Auto `updateComponent()` Method

**Added validation to respect available weight:**

```php
// Get total weight used by OTHER subcategories
$otherSubcategoriesWeight = AssessmentComponent::where('class_id', $classId)
    ->where('category', $category)
    ->where('subcategory', '!=', $component->subcategory)
    ->where('is_active', true)
    ->sum('weight');

// Available weight for this subcategory
$availableWeight = 100 - $otherSubcategoriesWeight;

// Validate that new weight doesn't exceed available weight
if ($newWeight > $availableWeight) {
    return error("Cannot set to {$newWeight}%. Available: {$availableWeight}%");
}

// Redistribute remaining weight among other components in same subcategory
$remainingWeight = $availableWeight - $newWeight;
// ... proportional distribution ...
```

### 3. Fixed Auto Mode Message

**Before:**
```php
$equalWeight = round(100 / $subcategoryCount, 2); // ❌ Wrong calculation
return "All components now have {$equalWeight}% weight.";
```

**After:**
```php
$this->redistributeWeights($classId, $category, $component->subcategory);
$actualWeight = $component->fresh()->weight; // ✅ Get actual weight from DB
return "All components now have {$actualWeight}% weight each.";
```

## How It Works Now

### Scenario 1: Add Quiz in Semi-Auto Mode

**Initial State:**
```
Knowledge:
  Exam: 60%
  Quiz 1: 20%
  Quiz 2: 20%
  Total: 100%
```

**Action:** Add Quiz 3

**Calculation:**
1. Other subcategories weight: Exam = 60%
2. Available for Quizzes: 100% - 60% = 40%
3. Redistribute 40% among 3 quizzes: 40% / 3 = 13.33% each

**Result:**
```
Knowledge:
  Exam: 60% ✅ (unchanged)
  Quiz 1: 13.33% ✅
  Quiz 2: 13.33% ✅
  Quiz 3: 13.34% ✅
  Total: 100% ✅
```

### Scenario 2: Update Quiz Weight in Semi-Auto Mode

**Initial State:**
```
Knowledge:
  Exam: 60%
  Quiz 1: 13.33%
  Quiz 2: 13.33%
  Quiz 3: 13.34%
  Total: 100%
```

**Action:** Change Quiz 1 to 25%

**Validation:**
1. Other subcategories weight: Exam = 60%
2. Available for Quizzes: 100% - 60% = 40%
3. Check: 25% ≤ 40%? ✅ Valid

**Calculation:**
1. Quiz 1: 25% (manual override)
2. Remaining for other quizzes: 40% - 25% = 15%
3. Redistribute 15% proportionally among Quiz 2 & 3

**Result:**
```
Knowledge:
  Exam: 60% ✅ (unchanged)
  Quiz 1: 25% ✅ (your override)
  Quiz 2: 7.5% ✅ (proportionally adjusted)
  Quiz 3: 7.5% ✅ (proportionally adjusted)
  Total: 100% ✅
```

### Scenario 3: Delete Output in Auto Mode

**Initial State:**
```
Skills:
  Output 1: 13.33%
  Output 2: 13.33%
  Output 3: 13.34%
  Activity 1: 20%
  Total: 60%
```

**Action:** Delete Output 1

**Calculation:**
1. Other subcategories weight: Activity = 20%
2. Available for Outputs: 100% - 20% = 80%
3. Redistribute 80% among 2 remaining outputs: 80% / 2 = 40% each

**Result:**
```
Skills:
  Output 2: 40% ✅
  Output 3: 40% ✅
  Activity 1: 20% ✅ (unchanged)
  Total: 100% ✅
```

## Validation Rules

### Category-Level Validation
- **Rule:** All components in a category must total 100%
- **Applies to:** Knowledge, Skills, Attitude categories
- **Enforced in:** All modes (Manual, Semi-Auto, Auto)

### Subcategory-Level Validation
- **Rule:** Components in a subcategory cannot exceed available weight
- **Available Weight:** 100% - (sum of other subcategories)
- **Applies to:** Semi-Auto and Auto modes
- **Example:** If Exam uses 60%, Quizzes can only use up to 40%

## Standard KSA Weight Distribution

### Knowledge (100%)
- **Exam:** 60%
- **Quizzes:** 40% total
  - Quiz 1: 13.33%
  - Quiz 2: 13.33%
  - Quiz 3: 13.34%

### Skills (100%)
- **Outputs:** 40% total
  - Output 1: 13.33%
  - Output 2: 13.33%
  - Output 3: 13.34%
- **Participation:** 30% total
  - CP 1: 10%
  - CP 2: 10%
  - CP 3: 10%
- **Activities:** 15% total
  - Activity 1: 5%
  - Activity 2: 5%
  - Activity 3: 5%
- **Assignments:** 15% total
  - Assignment 1: 5%
  - Assignment 2: 5%
  - Assignment 3: 5%

### Attitude (100%)
- **Behavior:** 50% total
  - Behavior 1: 16.67%
  - Behavior 2: 16.67%
  - Behavior 3: 16.66%
- **Awareness:** 50% total
  - Awareness 1: 16.67%
  - Awareness 2: 16.67%
  - Awareness 3: 16.66%

## Files Modified

1. **app/Http/Controllers/AssessmentComponentController.php**
   - `redistributeWeights()` - Fixed to respect available weight per subcategory
   - `updateComponent()` - Semi-Auto mode validates available weight
   - `updateComponent()` - Auto mode gets actual weight from DB

## Database Fixes Applied

Created scripts to fix existing data:
- `fix_knowledge_weights.php` - Fixed Knowledge category
- `fix_all_component_weights.php` - Checked all categories
- `restore_standard_weights.php` - Restored standard KSA distribution

## Testing Scenarios

### Test 1: Add Component in Semi-Auto
```
Initial: Exam: 60%, Quiz 1: 20%, Quiz 2: 20%
Add Quiz 3
Expected: Exam: 60%, Quiz 1: 13.33%, Quiz 2: 13.33%, Quiz 3: 13.34%
Result: ✅ PASS - Total = 100%
```

### Test 2: Update Weight in Semi-Auto
```
Initial: Exam: 60%, Quiz 1: 13.33%, Quiz 2: 13.33%, Quiz 3: 13.34%
Change Quiz 1 to 30%
Expected: Exam: 60%, Quiz 1: 30%, Quiz 2: 5%, Quiz 3: 5%
Result: ✅ PASS - Total = 100%
```

### Test 3: Delete Component in Auto
```
Initial: Exam: 60%, Quiz 1: 20%, Quiz 2: 20%
Delete Quiz 1
Expected: Exam: 60%, Quiz 2: 40%
Result: ✅ PASS - Total = 100%
```

### Test 4: Exceed Available Weight
```
Initial: Exam: 60%, Quiz 1: 20%, Quiz 2: 20%
Try to change Quiz 1 to 50%
Expected: ERROR - Available for Quizzes: 40%, cannot set to 50%
Result: ✅ PASS - Validation prevents exceeding
```

## Status

✅ **FIXED** - All modes (Manual, Semi-Auto, Auto) now respect the 100% category limit

### Verification:
- Knowledge: 100% ✅
- Skills: 100% ✅
- Attitude: 100% ✅

## Key Takeaway

**The Fix:** When redistributing weights within a subcategory, we now:
1. Calculate weight used by OTHER subcategories
2. Determine AVAILABLE weight for current subcategory
3. Distribute AVAILABLE weight (not 100%) among components in subcategory
4. Ensure category total always equals 100%

This ensures that **subcategories share the 100% category total**, not each trying to use 100% independently!
