<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Attendance;

echo "=== Testing Attendance Fixes ===\n\n";

// Test 1: Remove signatures from some students
echo "1. Removing signatures from 3 students for testing...\n";
$students = Student::limit(3)->get();
foreach ($students as $student) {
    $student->update(['e_signature' => null]);
    echo "   ✓ Removed signature from: {$student->first_name} {$student->last_name}\n";
}

// Test 2: Check sheet numbering logic
echo "\n2. Testing sheet numbering with 5 students...\n";
$class = ClassModel::first();
$testStudents = Student::where('course_id', $class->course_id)->limit(5)->get()->sortBy('last_name')->values();
$totalStudents = count($testStudents);
$midpoint = ceil($totalStudents / 2);
$leftColumn = $testStudents->slice(0, $midpoint);
$rightColumn = $testStudents->slice($midpoint);
$maxRowsPerColumn = max($midpoint, 25);

echo "   Total students: {$totalStudents}\n";
echo "   Midpoint: {$midpoint}\n";
echo "   Left column: {$leftColumn->count()} students (rows 1-{$leftColumn->count()})\n";
echo "   Right column: {$rightColumn->count()} students (rows " . ($leftColumn->count() + 1) . "-{$totalStudents})\n";
echo "   Max rows per column: {$maxRowsPerColumn}\n";

// Calculate empty rows
$leftEmptyStart = $leftColumn->count() + 1;
$leftEmptyEnd = $maxRowsPerColumn;
$rightEmptyStart = $leftColumn->count() + $rightColumn->count() + 1;
$rightEmptyEnd = $leftColumn->count() + ($maxRowsPerColumn - $leftColumn->count());

echo "   Left empty rows: {$leftEmptyStart} to {$leftEmptyEnd}\n";
echo "   Right empty rows: {$rightEmptyStart} to {$rightEmptyEnd}\n";

// Test 3: Check students without signatures
echo "\n3. Students without e-signatures:\n";
$withoutSig = Student::whereNull('e_signature')->orWhere('e_signature', '')->get();
echo "   Found: {$withoutSig->count()} students\n";
foreach ($withoutSig as $s) {
    echo "   - {$s->first_name} {$s->last_name}\n";
}

echo "\n=== Tests Complete ===\n";
echo "Now test in browser:\n";
echo "1. Go to /teacher/attendance/manage/{$class->id}\n";
echo "2. You should see 3 students in the warning notice\n";
echo "3. Capture signatures for 2 of them\n";
echo "4. Notice should update to show only 1 remaining\n";
echo "5. Save attendance and check sheet numbering\n";
