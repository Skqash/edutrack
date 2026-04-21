<?php
/**
 * Test Mode API Endpoint
 * 
 * Usage: php test_mode_api.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GradingScaleSetting;
use App\Models\ClassModel;

echo "\n=== Testing Mode API ===\n\n";

// Get first class
$class = ClassModel::first();

if (!$class) {
    echo "❌ No classes found in database\n";
    exit(1);
}

echo "📚 Testing with class: {$class->class_name} (ID: {$class->id})\n";
echo "👨‍🏫 Teacher ID: {$class->teacher_id}\n\n";

// Test for both terms
foreach (['midterm', 'final'] as $term) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📅 Term: " . strtoupper($term) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Get or create settings
    $settings = GradingScaleSetting::getOrCreateDefault($class->id, $class->teacher_id, $term);
    
    echo "✅ Settings found/created:\n";
    echo "   • ID: {$settings->id}\n";
    echo "   • Class ID: {$settings->class_id}\n";
    echo "   • Teacher ID: {$settings->teacher_id}\n";
    echo "   • Term: {$settings->term}\n";
    echo "   • Component Weight Mode: " . ($settings->component_weight_mode ?? 'NOT SET') . "\n";
    echo "   • Knowledge %: {$settings->knowledge_percentage}\n";
    echo "   • Skills %: {$settings->skills_percentage}\n";
    echo "   • Attitude %: {$settings->attitude_percentage}\n";
    echo "   • Created: {$settings->created_at}\n";
    echo "   • Updated: {$settings->updated_at}\n\n";
    
    // Test API endpoint simulation
    echo "🔍 Simulating API Response:\n";
    $response = [
        'gradingScaleSettings' => $settings,
        'component_weight_mode' => $settings->component_weight_mode,
    ];
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Test Complete!\n\n";

echo "📝 To test the actual API endpoint, visit:\n";
echo "   http://your-domain/teacher/grade-settings/{$class->id}/midterm/settings\n\n";
