<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ComponentEntry;
use App\Models\AssessmentComponent;

echo "Checking component entries for class 6:\n";
echo "========================================\n\n";

$components = AssessmentComponent::where('class_id', 6)->get();
echo "Components in class 6: " . $components->count() . "\n";

foreach ($components as $comp) {
    echo "  - {$comp->name} ({$comp->category}/{$comp->subcategory})\n";
}

echo "\n";

$entries = ComponentEntry::where('class_id', 6)->count();
echo "Component entries for class 6: $entries\n";

if ($entries > 0) {
    $sampleEntries = ComponentEntry::where('class_id', 6)
        ->with('component', 'student')
        ->take(5)
        ->get();
    
    echo "\nSample entries:\n";
    foreach ($sampleEntries as $entry) {
        $studentName = $entry->student->first_name . ' ' . $entry->student->last_name;
        $componentName = $entry->component->name;
        echo "  - Student: $studentName, Component: $componentName, Score: {$entry->raw_score}/{$entry->component->max_score}\n";
    }
}
