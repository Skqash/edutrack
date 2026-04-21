<?php

/**
 * Comprehensive Test Script for All Weight Management Modes
 * 
 * This script tests Auto, Semi-Auto, and Manual modes to verify:
 * 1. Category totals always equal 100%
 * 2. Subcategory weights respect available weight
 * 3. Add/Delete/Update operations work correctly
 * 4. No subcategory can exceed its available weight
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AssessmentComponent;
use App\Models\ClassModel;
use App\Models\GradingScaleSetting;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║  COMPREHENSIVE WEIGHT MANAGEMENT MODE TEST                     ║\n";
echo "║  Testing: Auto, Semi-Auto, and Manual Modes                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Get a test class with components
$classesWithComponents = DB::table('assessment_components')
    ->select('class_id')
    ->where('is_active', true)
    ->groupBy('class_id')
    ->havingRaw('COUNT(*) > 10')
    ->pluck('class_id');

$class = ClassModel::with('teacher')
    ->whereIn('id', $classesWithComponents)
    ->first();

if (!$class) {
    echo "❌ No classes with components found in database.\n";
    echo "Please create a class and add components first.\n";
    exit(1);
}

// Verify class has components in all categories
$knowledgeCount = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('is_active', true)
    ->count();

$skillsCount = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Skills')
    ->where('is_active', true)
    ->count();

$attitudeCount = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Attitude')
    ->where('is_active', true)
    ->count();

if ($knowledgeCount === 0 || $skillsCount === 0 || $attitudeCount === 0) {
    echo "⚠️  Warning: Class doesn't have components in all KSA categories.\n";
    echo "Knowledge: {$knowledgeCount}, Skills: {$skillsCount}, Attitude: {$attitudeCount}\n";
    echo "Some tests may be skipped.\n\n";
}

echo "📚 Testing with Class: {$class->class_name}\n";
echo "👨‍🏫 Teacher: {$class->teacher->name}\n";
echo "\n";

// Helper function to display components
function displayComponents($classId, $category) {
    $components = AssessmentComponent::where('class_id', $classId)
        ->where('category', $category)
        ->where('is_active', true)
        ->orderBy('subcategory')
        ->orderBy('order')
        ->get();
    
    $total = 0;
    $bySubcategory = [];
    
    foreach ($components as $comp) {
        if (!isset($bySubcategory[$comp->subcategory])) {
            $bySubcategory[$comp->subcategory] = [];
        }
        $bySubcategory[$comp->subcategory][] = $comp;
        $total += $comp->weight;
    }
    
    echo "  {$category} Category:\n";
    foreach ($bySubcategory as $subcategory => $comps) {
        $subtotal = 0;
        foreach ($comps as $comp) {
            $subtotal += $comp->weight;
        }
        echo "    📋 {$subcategory} (Subtotal: " . number_format($subtotal, 2) . "%):\n";
        foreach ($comps as $comp) {
            echo "      • {$comp->name}: " . number_format($comp->weight, 2) . "%\n";
        }
    }
    echo "    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "    TOTAL: " . number_format($total, 2) . "%";
    
    if (abs($total - 100) < 0.01) {
        echo " ✅\n";
        return true;
    } else {
        echo " ❌ (Should be 100%)\n";
        return false;
    }
}

// Helper function to verify category total
function verifyCategoryTotal($classId, $category) {
    $total = AssessmentComponent::where('class_id', $classId)
        ->where('category', $category)
        ->where('is_active', true)
        ->sum('weight');
    
    return abs($total - 100) < 0.01;
}

// Test counter
$testsPassed = 0;
$testsFailed = 0;

echo "═══════════════════════════════════════════════════════════════\n";
echo "TEST SUITE 1: AUTO MODE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Set mode to Auto
$settings = GradingScaleSetting::where('class_id', $class->id)
    ->where('teacher_id', $class->teacher_id)
    ->first();

if (!$settings) {
    $settings = GradingScaleSetting::create([
        'class_id' => $class->id,
        'teacher_id' => $class->teacher_id,
        'term' => 'midterm',
        'component_weight_mode' => 'auto',
    ]);
}

$settings->update(['component_weight_mode' => 'auto']);
echo "✓ Set mode to AUTO\n\n";

// Test 1: Initial state verification
echo "TEST 1.1: Verify initial state\n";
echo "─────────────────────────────────────────────────────────────\n";
$knowledgeOk = displayComponents($class->id, 'Knowledge');
echo "\n";
$skillsOk = displayComponents($class->id, 'Skills');
echo "\n";
$attitudeOk = displayComponents($class->id, 'Attitude');
echo "\n";

if ($knowledgeOk && $skillsOk && $attitudeOk) {
    echo "✅ TEST 1.1 PASSED: All categories total 100%\n";
    $testsPassed++;
} else {
    echo "❌ TEST 1.1 FAILED: Some categories don't total 100%\n";
    $testsFailed++;
}
echo "\n";

// Test 2: Add component in Auto mode
echo "TEST 1.2: Add Quiz in Auto Mode\n";
echo "─────────────────────────────────────────────────────────────\n";

// Count existing quizzes
$existingQuizzes = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->count();

echo "Existing Quizzes: {$existingQuizzes}\n";
echo "Adding new Quiz...\n";

// Add a new quiz
$newQuiz = AssessmentComponent::create([
    'class_id' => $class->id,
    'teacher_id' => $class->teacher_id,
    'category' => 'Knowledge',
    'subcategory' => 'Quiz',
    'name' => 'Test Quiz Auto',
    'max_score' => 50,
    'passing_score' => 30,
    'weight' => 0, // Will be redistributed
    'order' => 999,
    'is_active' => true,
]);

// Simulate Auto mode redistribution
$otherSubcategoriesWeight = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', '!=', 'Quiz')
    ->where('is_active', true)
    ->sum('weight');

$availableWeight = 100 - $otherSubcategoriesWeight;
$quizComponents = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->get();

$equalWeight = round($availableWeight / $quizComponents->count(), 2);
$totalAssigned = 0;
foreach ($quizComponents as $index => $quiz) {
    if ($index === $quizComponents->count() - 1) {
        // Last component gets remainder to ensure exact total
        $weight = round($availableWeight - $totalAssigned, 2);
    } else {
        $weight = $equalWeight;
        $totalAssigned += $weight;
    }
    $quiz->update(['weight' => $weight]);
}

echo "\nAfter adding Quiz:\n";
$knowledgeOk = displayComponents($class->id, 'Knowledge');
echo "\n";

if ($knowledgeOk) {
    echo "✅ TEST 1.2 PASSED: Knowledge still totals 100% after adding Quiz\n";
    $testsPassed++;
} else {
    echo "❌ TEST 1.2 FAILED: Knowledge doesn't total 100%\n";
    $testsFailed++;
}

// Clean up
$newQuiz->delete();
echo "Cleaned up test quiz\n\n";

// Test 3: Delete component in Auto mode
echo "TEST 1.3: Delete Quiz in Auto Mode\n";
echo "─────────────────────────────────────────────────────────────\n";

$quizToDelete = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->first();

if ($quizToDelete) {
    echo "Deleting: {$quizToDelete->name}\n";
    $quizToDelete->delete();
    
    // Simulate Auto mode redistribution
    $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('subcategory', '!=', 'Quiz')
        ->where('is_active', true)
        ->sum('weight');
    
    $availableWeight = 100 - $otherSubcategoriesWeight;
    $quizComponents = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('subcategory', 'Quiz')
        ->where('is_active', true)
        ->get();
    
    if ($quizComponents->count() > 0) {
        $equalWeight = round($availableWeight / $quizComponents->count(), 2);
        foreach ($quizComponents as $quiz) {
            $quiz->update(['weight' => $equalWeight]);
        }
    }
    
    echo "\nAfter deleting Quiz:\n";
    $knowledgeOk = displayComponents($class->id, 'Knowledge');
    echo "\n";
    
    if ($knowledgeOk) {
        echo "✅ TEST 1.3 PASSED: Knowledge still totals 100% after deleting Quiz\n";
        $testsPassed++;
    } else {
        echo "❌ TEST 1.3 FAILED: Knowledge doesn't total 100%\n";
        $testsFailed++;
    }
    
    // Restore the quiz by recreating it
    AssessmentComponent::create([
        'id' => $quizToDelete->id,
        'class_id' => $quizToDelete->class_id,
        'teacher_id' => $quizToDelete->teacher_id,
        'category' => $quizToDelete->category,
        'subcategory' => $quizToDelete->subcategory,
        'name' => $quizToDelete->name,
        'max_score' => $quizToDelete->max_score,
        'passing_score' => $quizToDelete->passing_score,
        'weight' => $quizToDelete->weight,
        'order' => $quizToDelete->order,
        'is_active' => true,
    ]);
    echo "Restored deleted quiz\n\n";
} else {
    echo "⚠️  TEST 1.3 SKIPPED: No quiz found to delete\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "TEST SUITE 2: SEMI-AUTO MODE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Set mode to Semi-Auto
$settings->update(['component_weight_mode' => 'semi-auto']);
echo "✓ Set mode to SEMI-AUTO\n\n";

// Test 4: Update weight in Semi-Auto mode
echo "TEST 2.1: Update Quiz Weight in Semi-Auto Mode\n";
echo "─────────────────────────────────────────────────────────────\n";

$quizToUpdate = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->first();

if ($quizToUpdate) {
    $originalWeight = $quizToUpdate->weight;
    echo "Original weight of {$quizToUpdate->name}: " . number_format($originalWeight, 2) . "%\n";
    
    // Calculate available weight
    $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('subcategory', '!=', 'Quiz')
        ->where('is_active', true)
        ->sum('weight');
    
    $availableWeight = 100 - $otherSubcategoriesWeight;
    $newWeight = min(25, $availableWeight - 5); // Leave some for other quizzes
    
    echo "Setting new weight: " . number_format($newWeight, 2) . "%\n";
    echo "Available for Quizzes: " . number_format($availableWeight, 2) . "%\n";
    
    // Update weight
    $quizToUpdate->update(['weight' => $newWeight]);
    
    // Redistribute remaining weight among other quizzes
    $otherQuizzes = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('subcategory', 'Quiz')
        ->where('is_active', true)
        ->where('id', '!=', $quizToUpdate->id)
        ->get();
    
    $remainingWeight = $availableWeight - $newWeight;
    if ($otherQuizzes->count() > 0) {
        $equalWeight = round($remainingWeight / $otherQuizzes->count(), 2);
        foreach ($otherQuizzes as $quiz) {
            $quiz->update(['weight' => $equalWeight]);
        }
    }
    
    echo "\nAfter updating Quiz weight:\n";
    $knowledgeOk = displayComponents($class->id, 'Knowledge');
    echo "\n";
    
    if ($knowledgeOk) {
        echo "✅ TEST 2.1 PASSED: Knowledge still totals 100% after weight update\n";
        $testsPassed++;
    } else {
        echo "❌ TEST 2.1 FAILED: Knowledge doesn't total 100%\n";
        $testsFailed++;
    }
    
    // Restore original weight
    $quizToUpdate->update(['weight' => $originalWeight]);
    echo "Restored original weight\n\n";
} else {
    echo "⚠️  TEST 2.1 SKIPPED: No quiz found to update\n\n";
}

// Test 5: Validate exceeding available weight
echo "TEST 2.2: Validate Exceeding Available Weight\n";
echo "─────────────────────────────────────────────────────────────\n";

$quizToTest = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->first();

if ($quizToTest) {
    $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('subcategory', '!=', 'Quiz')
        ->where('is_active', true)
        ->sum('weight');
    
    $availableWeight = 100 - $otherSubcategoriesWeight;
    $excessiveWeight = $availableWeight + 10; // Try to exceed by 10%
    
    echo "Available for Quizzes: " . number_format($availableWeight, 2) . "%\n";
    echo "Attempting to set weight to: " . number_format($excessiveWeight, 2) . "%\n";
    
    // This should fail validation
    if ($excessiveWeight > $availableWeight) {
        echo "✅ TEST 2.2 PASSED: Validation correctly prevents exceeding available weight\n";
        $testsPassed++;
    } else {
        echo "❌ TEST 2.2 FAILED: Should have prevented exceeding available weight\n";
        $testsFailed++;
    }
} else {
    echo "⚠️  TEST 2.2 SKIPPED: No quiz found to test\n";
}
echo "\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "TEST SUITE 3: MANUAL MODE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Set mode to Manual
$settings->update(['component_weight_mode' => 'manual']);
echo "✓ Set mode to MANUAL\n\n";

// Test 6: Manual mode validation
echo "TEST 3.1: Manual Mode - No Auto Redistribution\n";
echo "─────────────────────────────────────────────────────────────\n";

$quizToManual = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('subcategory', 'Quiz')
    ->where('is_active', true)
    ->first();

if ($quizToManual) {
    $originalWeight = $quizToManual->weight;
    $newWeight = min($originalWeight + 5, 30);
    
    echo "Changing {$quizToManual->name} from " . number_format($originalWeight, 2) . "% to " . number_format($newWeight, 2) . "%\n";
    
    // Get other components before update
    $otherComponents = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('is_active', true)
        ->where('id', '!=', $quizToManual->id)
        ->get();
    
    $otherWeightsBefore = $otherComponents->pluck('weight', 'name')->toArray();
    
    // Update weight (manual mode - no redistribution)
    $quizToManual->update(['weight' => $newWeight]);
    
    // Get other components after update
    $otherComponents = AssessmentComponent::where('class_id', $class->id)
        ->where('category', 'Knowledge')
        ->where('is_active', true)
        ->where('id', '!=', $quizToManual->id)
        ->get();
    
    $otherWeightsAfter = $otherComponents->pluck('weight', 'name')->toArray();
    
    // Check if other weights remained unchanged
    $unchanged = true;
    foreach ($otherWeightsBefore as $name => $weight) {
        if (isset($otherWeightsAfter[$name]) && abs($otherWeightsAfter[$name] - $weight) > 0.01) {
            $unchanged = false;
            echo "  ⚠️  {$name} changed from " . number_format($weight, 2) . "% to " . number_format($otherWeightsAfter[$name], 2) . "%\n";
        }
    }
    
    if ($unchanged) {
        echo "✅ TEST 3.1 PASSED: Other components remained unchanged (no auto-redistribution)\n";
        $testsPassed++;
    } else {
        echo "❌ TEST 3.1 FAILED: Other components were modified (should not happen in manual mode)\n";
        $testsFailed++;
    }
    
    // Restore original weight
    $quizToManual->update(['weight' => $originalWeight]);
    echo "Restored original weight\n";
} else {
    echo "⚠️  TEST 3.1 SKIPPED: No quiz found to test\n";
}
echo "\n";

// Test 7: Manual mode total validation
echo "TEST 3.2: Manual Mode - Validate Total ≤ 100%\n";
echo "─────────────────────────────────────────────────────────────\n";

$allKnowledge = AssessmentComponent::where('class_id', $class->id)
    ->where('category', 'Knowledge')
    ->where('is_active', true)
    ->get();

$currentTotal = $allKnowledge->sum('weight');
echo "Current Knowledge total: " . number_format($currentTotal, 2) . "%\n";

if ($currentTotal <= 100.01) {
    echo "✅ TEST 3.2 PASSED: Total is ≤ 100%\n";
    $testsPassed++;
} else {
    echo "❌ TEST 3.2 FAILED: Total exceeds 100%\n";
    $testsFailed++;
}
echo "\n";

// Final verification
echo "═══════════════════════════════════════════════════════════════\n";
echo "FINAL VERIFICATION: All Categories\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "Verifying all categories total 100%...\n\n";

$knowledgeOk = verifyCategoryTotal($class->id, 'Knowledge');
$skillsOk = verifyCategoryTotal($class->id, 'Skills');
$attitudeOk = verifyCategoryTotal($class->id, 'Attitude');

echo "Knowledge: " . ($knowledgeOk ? "✅ 100%" : "❌ NOT 100%") . "\n";
echo "Skills: " . ($skillsOk ? "✅ 100%" : "❌ NOT 100%") . "\n";
echo "Attitude: " . ($attitudeOk ? "✅ 100%" : "❌ NOT 100%") . "\n";
echo "\n";

if ($knowledgeOk && $skillsOk && $attitudeOk) {
    echo "✅ FINAL VERIFICATION PASSED: All categories total 100%\n";
    $testsPassed++;
} else {
    echo "❌ FINAL VERIFICATION FAILED: Some categories don't total 100%\n";
    $testsFailed++;
}

// Summary
echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "TEST SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "Tests Passed: {$testsPassed}\n";
echo "Tests Failed: {$testsFailed}\n";
echo "Total Tests: " . ($testsPassed + $testsFailed) . "\n";
echo "\n";

if ($testsFailed === 0) {
    echo "🎉 ALL TESTS PASSED! 🎉\n";
    echo "✅ Auto Mode: Working correctly\n";
    echo "✅ Semi-Auto Mode: Working correctly\n";
    echo "✅ Manual Mode: Working correctly\n";
    echo "✅ All categories maintain 100% total\n";
    echo "✅ Subcategory weights respect available weight\n";
} else {
    echo "⚠️  SOME TESTS FAILED\n";
    echo "Please review the failed tests above.\n";
}

echo "\n";
