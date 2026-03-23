<?php

/**
 * Test Script: Teacher Create Class Flow
 * 
 * This script tests the complete flow of creating a class as a teacher
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\School;

echo "=== TEACHER CREATE CLASS FLOW TEST ===\n\n";

// Find a teacher
$teacher = User::where('role', 'teacher')
    ->whereNotNull('campus')
    ->whereNotNull('school_id')
    ->first();

if (!$teacher) {
    echo "❌ No teacher found with campus and school_id\n";
    exit(1);
}

echo "✓ Testing with teacher: {$teacher->name}\n";
echo "  Campus: {$teacher->campus}\n";
echo "  School ID: {$teacher->school_id}\n\n";

// Test 1: Check assigned subjects
echo "TEST 1: Checking assigned subjects...\n";
$assignedSubjects = Subject::with('course')
    ->whereHas('teachers', function ($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id)
          ->where('teacher_subject.status', 'active');
    })
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('subject_name')
    ->get();

echo "  Found {$assignedSubjects->count()} assigned subjects\n";
if ($assignedSubjects->count() > 0) {
    echo "  Sample subjects:\n";
    foreach ($assignedSubjects->take(3) as $subject) {
        echo "    - {$subject->subject_code}: {$subject->subject_name}\n";
    }
} else {
    echo "  ⚠ No subjects assigned to this teacher\n";
}
echo "\n";

// Test 2: Check available courses
echo "TEST 2: Checking available courses...\n";
$courses = Course::query()
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('program_name')
    ->get();

echo "  Found {$courses->count()} courses\n";
if ($courses->count() > 0) {
    echo "  Sample courses:\n";
    foreach ($courses->take(3) as $course) {
        echo "    - {$course->program_code}: {$course->program_name}\n";
    }
} else {
    echo "  ⚠ No courses found for this campus\n";
}
echo "\n";

// Test 3: Check available students
echo "TEST 3: Checking available students...\n";
$students = Student::with(['course'])
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('last_name')
    ->orderBy('first_name')
    ->get();

echo "  Found {$students->count()} students\n";
if ($students->count() > 0) {
    echo "  Sample students:\n";
    foreach ($students->take(5) as $student) {
        $courseName = $student->course ? $student->course->program_name : 'No Course';
        echo "    - {$student->student_id}: {$student->full_name}\n";
        echo "      {$courseName} - Year {$student->year}{$student->section}\n";
    }
} else {
    echo "  ⚠ No students found for this campus\n";
}
echo "\n";

// Test 4: Test getStudents method simulation
if ($courses->count() > 0) {
    $testCourse = $courses->first();
    echo "TEST 4: Testing student filtering by course...\n";
    echo "  Course: {$testCourse->program_code} - {$testCourse->program_name}\n";
    
    $filteredStudents = Student::with(['course'])
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->where('course_id', $testCourse->id)
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->get();
    
    echo "  Found {$filteredStudents->count()} students in this course\n";
    
    if ($filteredStudents->count() > 0) {
        echo "  Sample students:\n";
        foreach ($filteredStudents->take(5) as $student) {
            echo "    - {$student->student_id}: {$student->full_name}\n";
            echo "      Year {$student->year}{$student->section}\n";
        }
        
        // Test JSON format (what the AJAX endpoint returns)
        echo "\n  Testing JSON format:\n";
        $studentData = $filteredStudents->take(2)->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'full_name' => $student->full_name,
                'student_id' => $student->student_id,
                'email' => $student->email,
                'course_id' => $student->course_id,
                'program_name' => $student->course->program_name ?? 'Unknown',
                'year' => $student->year,
                'section' => $student->section,
            ];
        });
        
        echo "  " . json_encode($studentData, JSON_PRETTY_PRINT) . "\n";
    }
    echo "\n";
}

// Test 5: Test filtering by year and section
if ($courses->count() > 0) {
    $testCourse = $courses->first();
    echo "TEST 5: Testing student filtering by year and section...\n";
    
    $year1Students = Student::with(['course'])
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->where('course_id', $testCourse->id)
        ->where('year', 1)
        ->where('section', 'A')
        ->count();
    
    echo "  Year 1, Section A students: {$year1Students}\n";
    
    $year2Students = Student::with(['course'])
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->where('course_id', $testCourse->id)
        ->where('year', 2)
        ->where('section', 'B')
        ->count();
    
    echo "  Year 2, Section B students: {$year2Students}\n";
    echo "\n";
}

// Test 6: Test search functionality
echo "TEST 6: Testing student search...\n";
$searchTerm = 'Juan';
$searchResults = Student::with(['course'])
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->where(function ($q) use ($searchTerm) {
        $q->where('student_id', 'like', "%{$searchTerm}%")
            ->orWhere('first_name', 'like', "%{$searchTerm}%")
            ->orWhere('last_name', 'like', "%{$searchTerm}%")
            ->orWhere('email', 'like', "%{$searchTerm}%");
    })
    ->limit(5)
    ->get();

echo "  Searching for: '{$searchTerm}'\n";
echo "  Found {$searchResults->count()} results\n";
if ($searchResults->count() > 0) {
    foreach ($searchResults as $student) {
        echo "    - {$student->student_id}: {$student->full_name}\n";
    }
}
echo "\n";

// Test 7: Check existing classes
echo "TEST 7: Checking existing classes for this teacher...\n";
$existingClasses = ClassModel::where('teacher_id', $teacher->id)
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->with('course', 'subject')
    ->get();

echo "  Found {$existingClasses->count()} existing classes\n";
if ($existingClasses->count() > 0) {
    echo "  Sample classes:\n";
    foreach ($existingClasses->take(3) as $class) {
        $courseName = $class->course ? $class->course->program_name : 'No Course';
        $subjectName = $class->subject ? $class->subject->subject_name : 'No Subject';
        echo "    - {$class->class_name}\n";
        echo "      Course: {$courseName}\n";
        echo "      Subject: {$subjectName}\n";
        echo "      Students: {$class->total_students}\n";
    }
}
echo "\n";

// Summary
echo "=== TEST SUMMARY ===\n";
echo "✓ Teacher: {$teacher->name} ({$teacher->campus})\n";
echo "✓ Assigned Subjects: {$assignedSubjects->count()}\n";
echo "✓ Available Courses: {$courses->count()}\n";
echo "✓ Available Students: {$students->count()}\n";
echo "✓ Existing Classes: {$existingClasses->count()}\n";
echo "\n";

// Check for potential issues
$issues = [];

if ($assignedSubjects->count() === 0) {
    $issues[] = "No subjects assigned to teacher - they won't be able to create classes";
}

if ($courses->count() === 0) {
    $issues[] = "No courses available for this campus - teacher needs courses to create classes";
}

if ($students->count() === 0) {
    $issues[] = "No students available for this campus - teacher won't be able to assign students";
}

if (count($issues) > 0) {
    echo "⚠ POTENTIAL ISSUES:\n";
    foreach ($issues as $issue) {
        echo "  - {$issue}\n";
    }
} else {
    echo "✓ ALL CHECKS PASSED - Teacher can create classes!\n";
}

echo "\n=== TEST COMPLETE ===\n";
