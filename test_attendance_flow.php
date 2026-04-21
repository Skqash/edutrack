<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ATTENDANCE E-SIGNATURE FLOW TEST ===\n\n";

// Test 1: Check students have permanent signatures
echo "TEST 1: Student Permanent Signatures\n";
echo "-------------------------------------\n";
$students = \App\Models\Student::whereIn('id', [1, 9, 10, 13, 19])->get();
foreach ($students as $student) {
    $hasSig = !empty($student->e_signature);
    echo "Student {$student->id} ({$student->first_name} {$student->last_name}): ";
    echo $hasSig ? "✓ HAS SIGNATURE\n" : "✗ NO SIGNATURE\n";
}

// Test 2: Check attendance records
echo "\nTEST 2: Attendance Records (class_id=6, date=2026-04-14)\n";
echo "--------------------------------------------------------\n";
$attendances = \App\Models\Attendance::where('class_id', 6)
    ->where('date', '2026-04-14')
    ->get();

if ($attendances->count() === 0) {
    echo "No attendance records found.\n";
} else {
    foreach ($attendances as $att) {
        $student = \App\Models\Student::find($att->student_id);
        $studentName = $student ? $student->first_name : 'Unknown';
        $hasSig = !empty($att->e_signature);
        echo "Student {$att->student_id} ({$studentName}): ";
        echo "Status={$att->status}, ";
        echo "SigType={$att->signature_type}, ";
        echo $hasSig ? "✓ HAS E-SIGNATURE\n" : "✗ NO E-SIGNATURE\n";
    }
}

// Test 3: Simulate controller logic
echo "\nTEST 3: Simulating Controller Logic\n";
echo "------------------------------------\n";
echo "If we save attendance now, students should get signatures from their permanent records.\n";

$testStudent = \App\Models\Student::find(1);
if ($testStudent) {
    echo "\nExample: Student 1 ({$testStudent->first_name})\n";
    echo "  Permanent signature: " . (!empty($testStudent->e_signature) ? "YES (" . strlen($testStudent->e_signature) . " chars)" : "NO") . "\n";
    echo "  Controller will use: " . (!empty($testStudent->e_signature) ? "Student's permanent signature" : "Manual (no signature)") . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
