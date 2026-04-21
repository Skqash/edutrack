<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING VIEW DATA ===\n\n";

$classId = 7;
$class = \App\Models\ClassModel::with('course')->find($classId);

// Load students using SAME logic as controller
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

// Test the filter logic from the view
$studentsWithoutSignatures = $students->filter(function($student) {
    return empty($student->e_signature);
});

echo "Students WITHOUT signatures (filtered): {$studentsWithoutSignatures->count()}\n";
foreach ($studentsWithoutSignatures as $student) {
    echo "  - {$student->first_name} {$student->last_name}\n";
}

$studentsWithSignatures = $students->count() - $studentsWithoutSignatures->count();
echo "\nStudents WITH signatures: {$studentsWithSignatures}\n";

// Now check each student individually
echo "\n=== INDIVIDUAL CHECK ===\n";
foreach ($students as $student) {
    $hasEsig = !empty($student->e_signature);
    $esigLength = $student->e_signature ? strlen($student->e_signature) : 0;
    echo "Student {$student->id} ({$student->first_name}): ";
    echo "e_signature field = " . ($hasEsig ? "HAS DATA ({$esigLength} chars)" : "EMPTY/NULL") . "\n";
}
?>
