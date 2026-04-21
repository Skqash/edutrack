<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AssessmentComponent;

// Fix Knowledge components
$exam = AssessmentComponent::find(1);
if ($exam) {
    $exam->update(['weight' => 60]);
    echo "Exam updated to 60%\n";
}

$quiz1 = AssessmentComponent::find(2);
if ($quiz1) {
    $quiz1->update(['weight' => 20]);
    echo "Quiz 1 updated to 20%\n";
}

$quiz2 = AssessmentComponent::find(3);
if ($quiz2) {
    $quiz2->update(['weight' => 20]);
    echo "Quiz 2 updated to 20%\n";
}

// Verify
$total = AssessmentComponent::where('category', 'Knowledge')
    ->where('is_active', true)
    ->sum('weight');

echo "\nTotal Knowledge weight: {$total}%\n";

if ($total == 100) {
    echo "✅ Fixed successfully!\n";
} else {
    echo "❌ Still incorrect, total should be 100%\n";
}
