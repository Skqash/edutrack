<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;
use App\Models\ClassModel;

// Test that migrations completed
echo "\n=== DATABASE SCHEMA VERIFICATION ===\n\n";

// Check courses table
echo "Courses Table Columns:\n";
$courseColumns = \Illuminate\Support\Facades\Schema::getColumnListing('courses');
$hasTotal = in_array('total_students', $courseColumns);
$hasMax = in_array('max_students', $courseColumns);

if ($hasTotal) {
    echo "✅ Field 'total_students' EXISTS in courses table\n";
} elseif ($hasMax) {
    echo "❌ Field 'max_students' STILL EXISTS (should be renamed)\n";
} else {
    echo "❌ Neither 'total_students' nor 'max_students' found\n";
}

// Check classes table
echo "\nClasses Table Columns:\n";
$classColumns = \Illuminate\Support\Facades\Schema::getColumnListing('classes');
$hasTotalClasses = in_array('total_students', $classColumns);
$hasCapacity = in_array('capacity', $classColumns);

if ($hasTotalClasses) {
    echo "✅ Field 'total_students' EXISTS in classes table\n";
} elseif ($hasCapacity) {
    echo "⚠️  Field 'capacity' STILL EXISTS in classes table\n";
} else {
    echo "❌ Neither 'total_students' nor 'capacity' found in classes table\n";
}

// Check subjects table
echo "\nSubjects Table Columns:\n";
$subjectColumns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
echo "Relevant columns: " . implode(', ', array_intersect($subjectColumns, ['total_students', 'max_students', 'capacity'])) . "\n";
echo "✅ Subjects table does not need a capacity field (correct)\n";

// Show counts
echo "\n=== DATABASE RECORD COUNTS ===\n";
echo "Courses: " . Course::count() . "\n";
echo "Classes: " . ClassModel::count() . "\n";
echo "Students: " . \App\Models\Student::count() . "\n";
echo "Users: " . \App\Models\User::count() . "\n";

echo "\n✅ Database schema verification complete!\n";
