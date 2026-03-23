<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING TEACHER VIEW DATA ===\n\n";

// Get Victorias teacher
$teacher = User::where('email', 'teacher1.CPSU-VIC@cpsu.edu.ph')->first();

if (!$teacher) {
    echo "Teacher not found!\n";
    exit(1);
}

echo "Teacher: {$teacher->name} ({$teacher->email})\n";
echo "Campus: {$teacher->campus}\n";
echo "School ID: {$teacher->school_id}\n";
echo "Status: {$teacher->campus_status}\n\n";

// Get classes with students (simulating what the view sees)
$classes = ClassModel::where('teacher_id', $teacher->id)
    ->where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->with(['students', 'course', 'subject'])
    ->get();

echo "=== CLASSES ({$classes->count()}) ===\n\n";

foreach ($classes->take(5) as $class) {
    echo "Class: {$class->class_name}\n";
    echo "  ID: {$class->id}\n";
    echo "  Course: " . ($class->course ? $class->course->program_name : 'N/A') . "\n";
    echo "  Subject: " . ($class->subject ? $class->subject->subject_name : 'N/A') . "\n";
    echo "  Campus: {$class->campus}\n";
    echo "  School ID: {$class->school_id}\n";
    echo "  Students count: {$class->students->count()}\n";
    
    if ($class->students->count() > 0) {
        $student = $class->students->first();
        echo "  Sample student: {$student->first_name} {$student->last_name}\n";
        echo "    Student campus: {$student->campus}\n";
        echo "    Student school_id: {$student->school_id}\n";
    }
    echo "\n";
}

// Test the exact code used in the view
echo "=== VIEW CODE TEST ===\n\n";
foreach ($classes->take(3) as $class) {
    $studentCount = $class->students->count();
    echo "Class: {$class->class_name}\n";
    echo "  \$class->students->count() = {$studentCount}\n";
    echo "  Display: {$studentCount} Enrolled Students\n\n";
}

echo "=== TEST COMPLETE ===\n";
