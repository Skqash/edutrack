<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

echo "=== ADMIN STUDENTS FETCH TEST ===\n\n";

// Get Victorias admin
$admin = User::where('email', 'admin.CPSU-VIC@cpsu.edu.ph')->first();

if (!$admin) {
    echo "Admin not found!\n";
    exit(1);
}

echo "Admin: {$admin->name}\n";
echo "Campus: {$admin->campus}\n";
echo "School ID: {$admin->school_id}\n\n";

// Test 1: Get all students for admin's campus
echo "=== TEST 1: ALL STUDENTS FOR CAMPUS ===\n";
$students = Student::where('campus', $admin->campus)
    ->where('school_id', $admin->school_id)
    ->with(['class.course'])
    ->get();

echo "Total students: {$students->count()}\n";

if ($students->count() > 0) {
    echo "\nSample students:\n";
    foreach ($students->take(5) as $student) {
        echo "  - {$student->first_name} {$student->last_name} ({$student->student_id})\n";
        echo "    Campus: {$student->campus}, School ID: {$student->school_id}\n";
        echo "    Class: " . ($student->class ? $student->class->class_name : 'Unassigned') . "\n";
        echo "    Course: " . ($student->class && $student->class->course ? $student->class->course->program_name : 'N/A') . "\n";
    }
}

// Test 2: Get students by year level
echo "\n=== TEST 2: STUDENTS BY YEAR LEVEL ===\n";
$yearLevels = [1, 2, 3, 4];
foreach ($yearLevels as $year) {
    $count = Student::where('campus', $admin->campus)
        ->where('school_id', $admin->school_id)
        ->where('year_level', $year)
        ->count();
    echo "Year {$year}: {$count} students\n";
}

// Test 3: Get students by course
echo "\n=== TEST 3: STUDENTS BY COURSE ===\n";
$studentsByCourse = Student::where('campus', $admin->campus)
    ->where('school_id', $admin->school_id)
    ->with('class.course')
    ->get()
    ->groupBy(function($student) {
        return $student->class && $student->class->course ? $student->class->course->program_name : 'Unassigned';
    });

foreach ($studentsByCourse as $courseName => $courseStudents) {
    echo "{$courseName}: {$courseStudents->count()} students\n";
}

// Test 4: Check for students without classes
echo "\n=== TEST 4: STUDENTS WITHOUT CLASSES ===\n";
$unassignedStudents = Student::where('campus', $admin->campus)
    ->where('school_id', $admin->school_id)
    ->whereNull('class_id')
    ->count();
echo "Students without class_id: {$unassignedStudents}\n";

// Test 5: Simulate the API call
echo "\n=== TEST 5: SIMULATE API CALL ===\n";
$query = Student::with(['class.course']);

// Apply campus isolation
if ($admin->campus) {
    $query->where('campus', $admin->campus);
}
if ($admin->school_id) {
    $query->where('school_id', $admin->school_id);
}

$apiStudents = $query->get();

echo "API would return: {$apiStudents->count()} students\n";

if ($apiStudents->count() > 0) {
    $studentData = $apiStudents->map(function ($student) {
        return [
            'id' => $student->id,
            'name' => $student->first_name . ' ' . $student->last_name,
            'student_id' => $student->student_id,
            'course_id' => $student->class->course->id ?? null,
            'program_name' => $student->class->course->program_name ?? 'Unknown',
            'year' => $student->year_level,
            'section' => $student->section,
            'class_id' => $student->class_id ?? null,
            'class_name' => $student->class->class_name ?? 'Unassigned',
        ];
    });
    
    echo "\nSample API response:\n";
    foreach ($studentData->take(3) as $data) {
        echo "  - {$data['name']} ({$data['student_id']})\n";
        echo "    Course: {$data['program_name']}\n";
        echo "    Class: {$data['class_name']}\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
