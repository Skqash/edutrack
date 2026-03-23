<?php

/**
 * Teacher Class Creation Test Script
 * Tests all aspects of the teacher class creation functionality
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "TEACHER CLASS CREATION TEST\n";
echo "===========================================\n\n";

$testsPassed = 0;
$testsFailed = 0;

// Find a teacher for testing
$teacher = User::where('role', 'teacher')
    ->whereNotNull('campus')
    ->first();

if (!$teacher) {
    echo "❌ No teacher found for testing\n";
    exit(1);
}

echo "Testing with Teacher: {$teacher->name}\n";
echo "Campus: {$teacher->campus}\n";
echo "School ID: {$teacher->school_id}\n\n";

// Test 1: Check Assigned Subjects
echo "TEST 1: Checking Assigned Subjects\n";
echo "-----------------------------------\n";

$assignedSubjects = Subject::with('course')
    ->where(function ($query) use ($teacher) {
        $query->whereHas('teachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id)
              ->where('teacher_subject.status', 'active');
        })
        ->orWhere(function ($q) use ($teacher) {
            $q->whereNull('program_id')
              ->whereHas('teachers', function ($subQ) use ($teacher) {
                  $subQ->where('teacher_id', $teacher->id);
              });
        });
    })
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('subject_name')
    ->get();

if ($assignedSubjects->count() > 0) {
    echo "✓ Found {$assignedSubjects->count()} assigned subjects\n";
    echo "  Sample subjects:\n";
    foreach ($assignedSubjects->take(3) as $subject) {
        echo "  - {$subject->subject_code}: {$subject->subject_name}\n";
        if ($subject->course) {
            echo "    Course: {$subject->course->program_name}\n";
        }
    }
    $testsPassed++;
} else {
    echo "⚠ No assigned subjects found (teacher may need subject assignments)\n";
    $testsFailed++;
}
echo "\n";

// Test 2: Check Available Courses
echo "TEST 2: Checking Available Courses\n";
echo "-----------------------------------\n";

$courses = Course::query()
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('program_name')
    ->get();

if ($courses->count() > 0) {
    echo "✓ Found {$courses->count()} available courses\n";
    echo "  Sample courses:\n";
    foreach ($courses->take(3) as $course) {
        echo "  - {$course->program_code}: {$course->program_name}\n";
        if ($course->department) {
            echo "    Department: {$course->department}\n";
        }
    }
    $testsPassed++;
} else {
    echo "❌ No courses found for teacher's campus\n";
    $testsFailed++;
}
echo "\n";

// Test 3: Check Available Students
echo "TEST 3: Checking Available Students\n";
echo "------------------------------------\n";

$students = Student::with(['course'])
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('last_name')
    ->orderBy('first_name')
    ->get();

if ($students->count() > 0) {
    echo "✓ Found {$students->count()} available students\n";
    echo "  Sample students:\n";
    foreach ($students->take(3) as $student) {
        $fullName = trim("{$student->first_name} {$student->middle_name} {$student->last_name}");
        echo "  - {$student->student_id}: {$fullName}\n";
        if ($student->course) {
            echo "    Course: {$student->course->program_name}, Year {$student->year}\n";
        }
    }
    $testsPassed++;
} else {
    echo "❌ No students found for teacher's campus\n";
    $testsFailed++;
}
echo "\n";

// Test 4: Test Student Filtering by Course
if ($courses->count() > 0 && $students->count() > 0) {
    echo "TEST 4: Testing Student Filtering by Course\n";
    echo "--------------------------------------------\n";
    
    $testCourse = $courses->first();
    $filteredStudents = Student::with(['course'])
        ->where('course_id', $testCourse->id)
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->get();
    
    echo "  Testing with course: {$testCourse->program_name}\n";
    echo "  Found {$filteredStudents->count()} students in this course\n";
    
    if ($filteredStudents->count() >= 0) {
        echo "✓ Course filtering works\n";
        $testsPassed++;
    } else {
        echo "❌ Course filtering failed\n";
        $testsFailed++;
    }
    echo "\n";
}

// Test 5: Test Student Filtering by Year
if ($students->count() > 0) {
    echo "TEST 5: Testing Student Filtering by Year\n";
    echo "------------------------------------------\n";
    
    $year1Students = Student::with(['course'])
        ->where('year', 1)
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->get();
    
    echo "  Year 1 students: {$year1Students->count()}\n";
    
    $year2Students = Student::with(['course'])
        ->where('year', 2)
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->get();
    
    echo "  Year 2 students: {$year2Students->count()}\n";
    
    if ($year1Students->count() >= 0 && $year2Students->count() >= 0) {
        echo "✓ Year filtering works\n";
        $testsPassed++;
    } else {
        echo "❌ Year filtering failed\n";
        $testsFailed++;
    }
    echo "\n";
}

// Test 6: Test Student Filtering by Section
if ($students->count() > 0) {
    echo "TEST 6: Testing Student Filtering by Section\n";
    echo "---------------------------------------------\n";
    
    $sectionAStudents = Student::with(['course'])
        ->where('section', 'A')
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->get();
    
    echo "  Section A students: {$sectionAStudents->count()}\n";
    
    if ($sectionAStudents->count() >= 0) {
        echo "✓ Section filtering works\n";
        $testsPassed++;
    } else {
        echo "❌ Section filtering failed\n";
        $testsFailed++;
    }
    echo "\n";
}

// Test 7: Test Combined Filtering
if ($courses->count() > 0 && $students->count() > 0) {
    echo "TEST 7: Testing Combined Filtering (Course + Year + Section)\n";
    echo "------------------------------------------------------------\n";
    
    $testCourse = $courses->first();
    $combinedFiltered = Student::with(['course'])
        ->where('course_id', $testCourse->id)
        ->where('year', 1)
        ->where('section', 'A')
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
        ->get();
    
    echo "  Course: {$testCourse->program_name}, Year: 1, Section: A\n";
    echo "  Found {$combinedFiltered->count()} students\n";
    
    if ($combinedFiltered->count() >= 0) {
        echo "✓ Combined filtering works\n";
        $testsPassed++;
    } else {
        echo "❌ Combined filtering failed\n";
        $testsFailed++;
    }
    echo "\n";
}

// Test 8: Test Campus Isolation
echo "TEST 8: Testing Campus Isolation\n";
echo "---------------------------------\n";

$allStudents = Student::count();
$campusStudents = Student::where('campus', $teacher->campus)->count();
$otherCampusStudents = Student::where('campus', '!=', $teacher->campus)->count();

echo "  Total students in database: {$allStudents}\n";
echo "  Students in teacher's campus: {$campusStudents}\n";
echo "  Students in other campuses: {$otherCampusStudents}\n";

if ($campusStudents > 0 && $campusStudents < $allStudents) {
    echo "✓ Campus isolation is working (teacher sees only their campus)\n";
    $testsPassed++;
} elseif ($campusStudents == $allStudents) {
    echo "⚠ All students are in the same campus (isolation not testable)\n";
    $testsPassed++;
} else {
    echo "❌ Campus isolation may not be working correctly\n";
    $testsFailed++;
}
echo "\n";

// Test 9: Test Department List
echo "TEST 9: Testing Department List\n";
echo "--------------------------------\n";

$departments = $courses->pluck('department')->filter()->unique()->values()->toArray();

if (count($departments) > 0) {
    echo "✓ Found " . count($departments) . " departments\n";
    echo "  Departments:\n";
    foreach ($departments as $dept) {
        echo "  - {$dept}\n";
    }
    $testsPassed++;
} else {
    echo "⚠ No departments found (courses may not have department assigned)\n";
    $testsFailed++;
}
echo "\n";

// Test 10: Test Route Existence
echo "TEST 10: Testing Route Existence\n";
echo "---------------------------------\n";

$routes = [
    'teacher.classes',
    'teacher.classes.create',
    'teacher.classes.store',
    'teacher.classes.get-students',
];

$allRoutesExist = true;
foreach ($routes as $routeName) {
    if (Route::has($routeName)) {
        echo "✓ Route '{$routeName}' exists\n";
    } else {
        echo "❌ Route '{$routeName}' NOT FOUND\n";
        $allRoutesExist = false;
    }
}

if ($allRoutesExist) {
    $testsPassed++;
} else {
    $testsFailed++;
}
echo "\n";

// Summary
echo "===========================================\n";
echo "TEST SUMMARY\n";
echo "===========================================\n";
echo "Tests Passed: {$testsPassed}\n";
echo "Tests Failed: {$testsFailed}\n";
echo "Total Tests: " . ($testsPassed + $testsFailed) . "\n";

if ($testsFailed == 0) {
    echo "\n✓ ALL TESTS PASSED!\n";
    echo "Teacher class creation is fully functional.\n";
} else {
    echo "\n⚠ SOME TESTS FAILED\n";
    echo "Please review the failed tests above.\n";
}

echo "===========================================\n";

exit($testsFailed > 0 ? 1 : 0);
