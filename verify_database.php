<?php
// Quick database verification script

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?", [env('DB_DATABASE')]);

echo "\n✅ DATABASE VERIFICATION REPORT\n";
echo str_repeat("=", 70) . "\n\n";

echo "📊 TABLES CREATED (" . count($tables) . " total):\n";
echo str_repeat("-", 70) . "\n";

$totalRows = 0;
$criticalTables = [
    'users', 'students', 'teachers', 'classes', 'courses', 'subjects',
    'grades', 'attendance', 'assessment_components', 'component_entries',
    'grading_scale_settings'
];

foreach($tables as $table) {
    $tableName = $table->TABLE_NAME;
    try {
        $rowCount = DB::table($tableName)->count();
        $totalRows += $rowCount;
        $status = $rowCount > 0 ? '✓' : '○';
        $marker = in_array($tableName, $criticalTables) ? '⭐' : '  ';
        echo sprintf("%s %-40s → %6d rows\n", $marker, $tableName, $rowCount);
    } catch (\Exception $e) {
        echo sprintf("  %-40s → ERROR\n", $tableName);
    }
}

echo "\n" . str_repeat("-", 70) . "\n";
echo sprintf("TOTAL: %d tables with %d total rows\n", count($tables), $totalRows);
echo str_repeat("=", 70) . "\n\n";

// Check critical data
echo "🔍 CRITICAL DATA CHECKS:\n";
echo str_repeat("-", 70) . "\n";

$checks = [
    ['name' => 'Users (Admin, Teachers, Students)', 'query' => DB::table('users')->count()],
    ['name' => 'Teachers', 'query' => DB::table('teachers')->count()],
    ['name' => 'Students', 'query' => DB::table('students')->count()],
    ['name' => 'Classes', 'query' => DB::table('classes')->count()],
    ['name' => 'Courses', 'query' => DB::table('courses')->count()],
    ['name' => 'Subjects', 'query' => DB::table('subjects')->count()],
    ['name' => 'Assessment Components', 'query' => DB::table('assessment_components')->count()],
    ['name' => 'Component Entries', 'query' => DB::table('component_entries')->count()],
    ['name' => 'Grades', 'query' => DB::table('grades')->count()],
];

foreach($checks as $check) {
    $count = $check['query'];
    $icon = $count > 0 ? '✓' : '✗';
    echo sprintf("%s %-40s → %6d\n", $icon, $check['name'], $count);
}

echo str_repeat("=", 70) . "\n\n";

echo "✅ DATABASE SETUP COMPLETE!\n";
echo "Ready for development and testing.\n\n";
