<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AssessmentComponent;

echo "=== CHECKING ALL COMPONENT WEIGHTS ===\n\n";

$categories = ['Knowledge', 'Skills', 'Attitude'];

foreach ($categories as $category) {
    echo "--- {$category} Category ---\n";
    
    $components = AssessmentComponent::where('category', $category)
        ->where('is_active', true)
        ->orderBy('subcategory')
        ->orderBy('order')
        ->get();
    
    if ($components->isEmpty()) {
        echo "No components found.\n\n";
        continue;
    }
    
    // Group by subcategory
    $bySubcategory = $components->groupBy('subcategory');
    
    $totalWeight = 0;
    foreach ($bySubcategory as $subcategory => $subComponents) {
        $subcategoryTotal = $subComponents->sum('weight');
        $totalWeight += $subcategoryTotal;
        
        echo "  {$subcategory}: ";
        foreach ($subComponents as $comp) {
            echo "{$comp->name} ({$comp->weight}%) ";
        }
        echo "= {$subcategoryTotal}%\n";
    }
    
    echo "  TOTAL: {$totalWeight}%";
    
    if ($totalWeight > 100) {
        echo " ❌ EXCEEDS 100%!\n";
    } elseif ($totalWeight < 100) {
        echo " ⚠️ LESS THAN 100%\n";
    } else {
        echo " ✅ OK\n";
    }
    
    echo "\n";
}

echo "\n=== FIXING WEIGHTS ===\n\n";

// Fix function
function fixCategoryWeights($category) {
    echo "Fixing {$category}...\n";
    
    $components = AssessmentComponent::where('category', $category)
        ->where('is_active', true)
        ->get();
    
    if ($components->isEmpty()) {
        echo "  No components to fix.\n";
        return;
    }
    
    // Group by subcategory
    $bySubcategory = $components->groupBy('subcategory');
    $subcategoryCount = $bySubcategory->count();
    
    // Allocate equal weight to each subcategory
    $weightPerSubcategory = round(100 / $subcategoryCount, 2);
    $remainder = 100 - ($weightPerSubcategory * $subcategoryCount);
    
    $subcategoryIndex = 0;
    foreach ($bySubcategory as $subcategory => $subComponents) {
        $subcategoryWeight = $weightPerSubcategory;
        
        // Add remainder to first subcategory
        if ($subcategoryIndex == 0 && $remainder != 0) {
            $subcategoryWeight += $remainder;
        }
        
        // Distribute weight equally among components in this subcategory
        $componentCount = $subComponents->count();
        $weightPerComponent = round($subcategoryWeight / $componentCount, 2);
        $componentRemainder = $subcategoryWeight - ($weightPerComponent * $componentCount);
        
        $componentIndex = 0;
        foreach ($subComponents as $comp) {
            $newWeight = $weightPerComponent;
            
            // Add remainder to first component
            if ($componentIndex == 0 && $componentRemainder != 0) {
                $newWeight += $componentRemainder;
            }
            
            $comp->update(['weight' => $newWeight]);
            echo "  {$comp->name}: {$newWeight}%\n";
            
            $componentIndex++;
        }
        
        $subcategoryIndex++;
    }
}

// Fix all categories
foreach ($categories as $category) {
    fixCategoryWeights($category);
    echo "\n";
}

echo "=== VERIFICATION ===\n\n";

foreach ($categories as $category) {
    $total = AssessmentComponent::where('category', $category)
        ->where('is_active', true)
        ->sum('weight');
    
    $status = ($total == 100) ? '✅' : '❌';
    echo "{$category}: {$total}% {$status}\n";
}

echo "\nDone!\n";
