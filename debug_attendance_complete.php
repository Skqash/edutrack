<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassModel;

echo "=== COMPREHENSIVE ATTENDANCE E-SIGNATURE DEBUG ===\n\n";

// Test 1: Check students table
echo "1. Checking Students Table:\n";
$students = Student::where('course_id', 4)->take(5)->get();
foreach($students as $s) {
    $hasSig = !empty($s->e_signature);
    echo "  - {$s->first_name} {$s->last_name} (ID: {$s->id}): " . ($hasSig ? "HAS signature" : "NO signature") . "\n";
}

// Test 2: Check attendance table
echo "\n2. Checking Attendance Records (Apr 14, 2026):\n";
$attendances = Attendance::where('date', '2026-04-14')->where('class_id', 6)->get();
echo "  Found: {$attendances->count()} records\n";
foreach($attendances->take(5) as $a) {
    $student = Student::find($a->student_id);
    $name = $student ? "{$student->first_name} {$student->last_name}" : "Unknown";
    $hasSig = !empty($a->e_signature);
    echo "  - {$name}: Status={$a->status}, Sig=" . ($hasSig ? "YES" : "NO") . ", Type={$a->signature_type}\n";
}

// Test 3: Clear old attendance and create fresh test data
echo "\n3. Creating Fresh Test Data:\n";
Attendance::where('date', '2026-04-14')->where('class_id', 6)->delete();
echo "  Cleared old attendance records\n";

// Create test signature
$testSig = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

// Add signatures to first 3 students and create attendance
$testStudents = Student::where('course_id', 4)->take(3)->get();
foreach($testStudents as $s) {
    // Update student signature
    $s->update(['e_signature' => $testSig, 'signature_date' => now()]);
    
    // Create attendance with signature
    Attendance::create([
        'student_id' => $s->id,
        'class_id' => 6,
        'date' => '2026-04-14',
        'term' => 'Midterm',
        'status' => 'Present',
        'teacher_id' => 6,
        'e_signature' => $testSig,
        'signature_type' => 'e-signature',
        'signature_timestamp' => now(),
    ]);
    
    echo "  ✓ Added signature for: {$s->first_name} {$s->last_name}\n";
}

// Test 4: Verify the data
echo "\n4. Verification:\n";
$verifyAtt = Attendance::where('date', '2026-04-14')->where('class_id', 6)->get();
$withSigs = $verifyAtt->filter(fn($a) => !empty($a->e_signature))->count();
echo "  Total attendance records: {$verifyAtt->count()}\n";
echo "  Records with e-signatures: {$withSigs}\n";

// Test 5: Check what the controller will see
echo "\n5. Controller View Simulation:\n";
$class = ClassModel::find(6);
$allStudents = Student::where('course_id', $class->course_id)->orderBy('last_name')->get();
$attendanceRecords = Attendance::where('class_id', 6)
    ->where('date', '2026-04-14')
    ->where('term', 'Midterm')
    ->get()
    ->keyBy('student_id');

echo "  Total students in class: {$allStudents->count()}\n";
echo "  Students with signatures in DB:\n";
foreach($allStudents as $s) {
    $att = $attendanceRecords[$s->id] ?? null;
    $hasSig = $att && !empty($att->e_signature);
    if($hasSig) {
        echo "    ✓ {$s->first_name} {$s->last_name} - Signature length: " . strlen($att->e_signature) . "\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
echo "Now test in browser:\n";
echo "1. Go to: /teacher/attendance/manage/6\n";
echo "2. Notice should show 2 students without signatures\n";
echo "3. View sheet: /teacher/attendance/sheet/6?date=2026-04-14&term=Midterm\n";
echo "4. Should see 3 signatures displayed\n";
