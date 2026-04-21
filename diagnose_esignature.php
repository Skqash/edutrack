<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassModel;

echo "=== Diagnostic: E-Signature Issue ===\n\n";

$classId = 7; // BSIT 1-B Programming Fundamentals
$class = ClassModel::find($classId);

if (!$class) {
    echo "Class not found!\n";
    exit(1);
}

echo "Class: {$class->class_name}\n";
echo "Course ID: {$class->course_id}\n\n";

// Get students
$students = Student::where('course_id', $class->course_id)
    ->where('school_id', $class->school_id)
    ->orderBy('last_name')
    ->get();

echo "Students in class: {$students->count()}\n\n";

foreach ($students as $student) {
    echo "Student: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
    echo "  Has e_signature in students table: " . (!empty($student->e_signature) ? 'YES (' . strlen($student->e_signature) . ' chars)' : 'NO') . "\n";
    
    // Check today's attendance
    $attendance = Attendance::where('student_id', $student->id)
        ->where('class_id', $classId)
        ->where('date', now()->format('Y-m-d'))
        ->where('term', 'Midterm')
        ->first();
    
    if ($attendance) {
        echo "  Today's attendance: {$attendance->status}\n";
        echo "  Has e_signature in attendance: " . (!empty($attendance->e_signature) ? 'YES (' . strlen($attendance->e_signature) . ' chars)' : 'NO') . "\n";
    } else {
        echo "  Today's attendance: NOT RECORDED\n";
    }
    echo "\n";
}
