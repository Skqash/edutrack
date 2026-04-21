<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$studentCount = \App\Models\Student::count();
echo "Total students: {$studentCount}\n";

if ($studentCount > 0) {
    $sample = \App\Models\Student::first();
    echo "Sample: {$sample->first_name} {$sample->last_name} ({$sample->student_id})\n";
} else {
    echo "No students found!\n";
    
    // Check if Course table has data
    $courseCount = \App\Models\Course::count();
    echo "Courses: {$courseCount}\n";
    
    // Check School table
    $schoolCount = \App\Models\School::count();
    echo "Schools: {$schoolCount}\n";
}

// List schools
$schools = \App\Models\School::all();
echo "\nAvailable Schools:\n";
foreach ($schools as $school) {
    echo "- {$school->short_name}: {$school->name}\n";
}
?>
