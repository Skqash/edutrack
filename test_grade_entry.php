<?php
// Diagnostic test for grade entry method

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\TeacherController;
use App\Http\Controllers\TeacherController as TeacherCtrl;
use Illuminate\Http\Request;
use App\Models\AssessmentComponent;
use App\Models\KsaSetting;
use App\Models\ClassModel;

echo "\n✅ GRADE ENTRY DIAGNOSTIC TEST\n";
echo str_repeat("=", 80) . "\n\n";

// Get first teacher and their class
$teacher = \App\Models\User::where('role', 'teacher')->first();
if (!$teacher) {
    echo "❌ No teachers found in database\n";
    exit;
}

echo "📚 Teacher: {$teacher->name}\n";

// Get teacher's class
$class = ClassModel::where('teacher_id', $teacher->id)->first();
if (!$class) {
    echo "❌ No classes found for this teacher\n";
    exit;
}

echo "📌 Class: {$class->class_name} (ID: {$class->id})\n\n";

// Test what the controller would fetch
echo "🔍 Checking data that would be passed to view:\n";
echo str_repeat("-", 80) . "\n";

// Test components
$components = AssessmentComponent::where('class_id', $class->id)
    ->where('is_active', true)
    ->orderBy('category')
    ->orderBy('order')
    ->get();

echo "Components found: " . $components->count() . "\n";
if ($components->count() > 0) {
    $grouped = $components->groupBy('category');
    echo "Components by category:\n";
    foreach ($grouped as $category => $comps) {
        echo "  - $category: " . $comps->count() . " components\n";
        foreach ($comps as $comp) {
            echo "    • {$comp->name} (max: {$comp->max_score}, weight: {$comp->weight}%)\n";
        }
    }
} else {
    echo "⚠️ No components found\n";
}

echo "\n";

// Test KSA settings
$ksaSettings = KsaSetting::where('class_id', $class->id)
    ->where('term', 'midterm')
    ->first();

if ($ksaSettings) {
    echo "KSA Settings found:\n";
    echo "  - Knowledge: {$ksaSettings->knowledge_percentage}%\n";
    echo "  - Skills: {$ksaSettings->skills_percentage}%\n";
    echo "  - Attitude: {$ksaSettings->attitude_percentage}%\n";
} else {
    echo "⚠️ No KSA settings found (will use defaults: 40-50-10)\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ DIAGNOSTIC COMPLETE\n";
echo "If data shows above, the controller should pass it to the view correctly.\n";
echo "If no data shows, add some assessment components first.\n\n";
