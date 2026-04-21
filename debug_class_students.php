<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUGGING CLASS STUDENTS ===\n\n";

// Get the class from the URL (class_id = 7 based on screenshot)
$classId = 7;
$class = \App\Models\ClassModel::with('course')->find($classId);

if (!$class) {
    echo "Class not found!\n";
    exit;
}

echo "Class ID: {$class->id}\n";
echo "Class Name: {$class->class_name}\n";
echo "Course ID: {$class->course_id}\n";
echo "School ID: {$class->school_id}\n\n";

// Load students using SAME logic as manageAttendance
$studentsQuery = \App\Models\Student::query()
    ->when($class->course_id, fn ($q) => $q->where('course_id', $class->course_id))
    ->when($class->school_id, fn ($q) => $q->where('school_id', $class->school_id))
    ->orderBy('last_name')->orderBy('first_name');

if (!$class->course_id) {
    $studentsQuery = $class->students()
        ->when($class->school_id, fn ($q) => $q->where('school_id', $class->school_id))
        ->orderBy('last_name')->orderBy('first_name');
}

$students = $studentsQuery->get();

echo "Students loaded: {$students->count()}\n\n";

foreach ($students as $student) {
    $hasSig = !empty($student->e_signature);
    $studentName = $student->first_name . ' ' . $student->last_name;
    $sigDate = $student->signature_date ? $student->signature_date : 'N/A';
    echo "Student {$student->id}: {$studentName}\n";
    echo "  Student ID: {$student->student_id}\n";
    echo "  Has e_signature: " . ($hasSig ? "YES (" . strlen($student->e_signature) . " chars)" : "NO") . "\n";
    echo "  Signature date: {$sigDate}\n\n";
}

// Check attendance records for this class
echo "\n=== ATTENDANCE RECORDS ===\n";
$attendances = \App\Models\Attendance::where('class_id', $classId)
    ->where('date', '2026-04-14')
    ->where('term', 'Midterm')
    ->get();

echo "Found {$attendances->count()} attendance records\n\n";

foreach ($attendances as $att) {
    $student = \App\Models\Student::find($att->student_id);
    $studentName = $student ? $student->first_name : 'Unknown';
    $hasSig = !empty($att->e_signature);
    echo "Student {$att->student_id} ({$studentName}): ";
    echo "Status={$att->status}, ";
    echo "SigType={$att->signature_type}, ";
    echo $hasSig ? "✓ HAS E-SIGNATURE (" . strlen($att->e_signature) . " chars)\n" : "✗ NO E-SIGNATURE\n";
}
?>
