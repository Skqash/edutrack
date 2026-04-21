<?php
/**
 * Database Check Script for Grade Settings
 * 
 * This script helps verify that the Component Weight Mode and Attendance Settings
 * are being saved correctly to the database.
 * 
 * Usage: php check_settings_database.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\GradingScaleSetting;
use App\Models\KsaSetting;
use App\Models\ClassModel;

echo "\n=== Grade Settings Database Check ===\n\n";

// Get all classes
$classes = ClassModel::with('teacher')->get();

if ($classes->isEmpty()) {
    echo "❌ No classes found in database.\n";
    exit(1);
}

echo "Found " . $classes->count() . " classes:\n\n";

foreach ($classes as $class) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📚 Class: {$class->class_name} (ID: {$class->id})\n";
    echo "👨‍🏫 Teacher: {$class->teacher->name ?? 'N/A'}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    foreach (['midterm', 'final'] as $term) {
        echo "  📅 {$term} Term:\n";
        echo "  " . str_repeat("─", 50) . "\n";

        // Check GradingScaleSetting (Component Weight Mode)
        $gradingSetting = GradingScaleSetting::where('class_id', $class->id)
            ->where('term', $term)
            ->first();

        if ($gradingSetting) {
            echo "  ✅ GradingScaleSetting found:\n";
            echo "     • Component Weight Mode: " . ($gradingSetting->component_weight_mode ?? 'NOT SET') . "\n";
            echo "     • Knowledge %: " . ($gradingSetting->knowledge_percentage ?? 'N/A') . "\n";
            echo "     • Skills %: " . ($gradingSetting->skills_percentage ?? 'N/A') . "\n";
            echo "     • Attitude %: " . ($gradingSetting->attitude_percentage ?? 'N/A') . "\n";
        } else {
            echo "  ⚠️  GradingScaleSetting NOT found (will use defaults)\n";
        }

        echo "\n";

        // Check KsaSetting (Attendance Settings)
        $ksaSetting = KsaSetting::where('class_id', $class->id)
            ->where('term', $term)
            ->first();

        if ($ksaSetting) {
            echo "  ✅ KsaSetting found:\n";
            echo "     • Enable Attendance in KSA: " . ($ksaSetting->enable_attendance_ksa ? '✓ ENABLED' : '✗ DISABLED') . "\n";
            echo "     • Attendance Weight: " . ($ksaSetting->attendance_weight ?? 'N/A') . "%\n";
            echo "     • Attendance Category: " . ($ksaSetting->attendance_category ?? 'N/A') . "\n";
            echo "     • Total Meetings: " . ($ksaSetting->total_meetings ?? 'N/A') . "\n";
            echo "     • Knowledge Weight: " . ($ksaSetting->knowledge_weight ?? 'N/A') . "%\n";
            echo "     • Skills Weight: " . ($ksaSetting->skills_weight ?? 'N/A') . "%\n";
            echo "     • Attitude Weight: " . ($ksaSetting->attitude_weight ?? 'N/A') . "%\n";
        } else {
            echo "  ⚠️  KsaSetting NOT found (will use defaults)\n";
        }

        echo "\n";
    }

    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Database check complete!\n\n";

echo "📝 Notes:\n";
echo "   • If settings are 'NOT found', they will be created with defaults on first access\n";
echo "   • Default Component Weight Mode: semi-auto\n";
echo "   • Default Enable Attendance: true (ENABLED)\n";
echo "   • Default KSA Distribution: K=40%, S=50%, A=10%\n";
echo "\n";
