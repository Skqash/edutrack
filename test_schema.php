<?php
require 'bootstrap/app.php';
$db = app(\Illuminate\Database\DatabaseManager::class);
$cols = $db->select("SHOW COLUMNS FROM grades");

echo "=== GRADES TABLE COLUMNS ===\n";
echo "Total columns: " . count($cols) . "\n\n";

$requiredColumns = [
    'output_total',
    'class_participation_total', 
    'activities_total',
    'assignments_total',
    'behavior_total',
    'awareness_total',
    'knowledge_average',
    'skills_average',
    'attitude_average'
];

echo "=== CHECKING REQUIRED COLUMNS ===\n";
$colNames = array_column($cols, 'Field');
foreach($requiredColumns as $col) {
    $exists = in_array($col, $colNames) ? "✓ EXISTS" : "✗ MISSING";
    echo "{$col}: {$exists}\n";
}

echo "\n=== ALL COLUMNS ===\n";
foreach($cols as $col) {
    echo "- {$col->Field} ({$col->Type})\n";
}
