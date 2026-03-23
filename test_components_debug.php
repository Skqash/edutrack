<?php
// Test to verify components are being fetched and grouped correctly

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AssessmentComponent;
use App\Models\KsaSetting;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;

// Manually set auth for testing
Auth::loginUsingId(1);

$classId = 1;
$term = 'midterm';

echo "=== TESTING COMPONENTS FETCH ===\n";
echo "Class ID: $classId, Term: $term\n\n";

// Test 1: Direct query
echo "1. Direct Database Query:\n";
$rawComponents = AssessmentComponent::where('class_id', $classId)
    ->where('is_active', true)
    ->orderBy('category')
    ->orderBy('order')
    ->get();

echo "   - Raw components count: " . count($rawComponents) . "\n";
foreach ($rawComponents as $comp) {
    echo "   - {$comp->category}: {$comp->name} (ID: {$comp->id})\n";
}

// Test 2: Grouped
echo "\n2. Grouped Components:\n";
$components = $rawComponents->groupBy('category');
echo "   - Grouped keys: " . implode(', ', $components->keys()->toArray()) . "\n";
echo "   - Grouped count: " . count($components) . "\n";
echo "   - Is empty: " . (empty($components) ? 'YES' : 'NO') . "\n";
echo "   - Check !empty(\$components) && count(\$components) > 0: " . ((!empty($components) && count($components) > 0) ? 'TRUE' : 'FALSE') . "\n";

// Test 3: KSA Settings
echo "\n3. KSA Settings:\n";
$ksaSettings = KsaSetting::where('class_id', $classId)
    ->where('term', $term)
    ->first();

if ($ksaSettings) {
    echo "   - Found: Knowledge {$ksaSettings->knowledge_percentage}%, Skills {$ksaSettings->skills_percentage}%, Attitude {$ksaSettings->attitude_percentage}%\n";
} else {
    echo "   - Not found (will use defaults)\n";
}

// Test 4: Simulate controller logic
echo "\n4. Controller Logic Simulation:\n";
$componentsCollection = AssessmentComponent::where('class_id', $classId)
    ->where('is_active', true)
    ->orderBy('category')
    ->orderBy('order')
    ->get();

$components = $componentsCollection->groupBy('category');

echo "   - Components passed to view:\n";
echo "     Type: " . gettype($components) . "\n";
echo "     Count: " . count($components) . "\n";
echo "     Is collection: " . ($components instanceof \Illuminate\Support\Collection ? 'YES' : 'NO') . "\n";

// Test the exact condition from blade
echo "\n   - Blade condition evaluation:\n";
echo "     !empty(\$components): " . (!empty($components) ? 'TRUE' : 'FALSE') . "\n";
echo "     count(\$components) > 0: " . (count($components) > 0 ? 'TRUE' : 'FALSE') . "\n";
echo "     COMBINED: " . ((!empty($components) && count($components) > 0) ? 'TRUE ✓' : 'FALSE ✗') . "\n";

echo "\n=== END DEBUG ===\n";
?>
