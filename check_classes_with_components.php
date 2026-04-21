<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ClassModel;
use App\Models\AssessmentComponent;

$classes = ClassModel::with('teacher')->get();

echo "Classes with Assessment Components:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($classes as $class) {
    $count = AssessmentComponent::where('class_id', $class->id)
        ->where('is_active', true)
        ->count();
    
    if ($count > 0) {
        echo "✓ Class: {$class->class_name}\n";
        echo "  Teacher: {$class->teacher->name}\n";
        echo "  Components: {$count}\n";
        
        $knowledge = AssessmentComponent::where('class_id', $class->id)
            ->where('category', 'Knowledge')
            ->where('is_active', true)
            ->count();
        $skills = AssessmentComponent::where('class_id', $class->id)
            ->where('category', 'Skills')
            ->where('is_active', true)
            ->count();
        $attitude = AssessmentComponent::where('class_id', $class->id)
            ->where('category', 'Attitude')
            ->where('is_active', true)
            ->count();
        
        echo "  - Knowledge: {$knowledge}\n";
        echo "  - Skills: {$skills}\n";
        echo "  - Attitude: {$attitude}\n";
        echo "\n";
    }
}
