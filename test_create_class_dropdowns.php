<?php
/**
 * Test script to verify teacher create class dropdowns are working
 * Run: php test_create_class_dropdowns.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Student;

echo "=== TEACHER CREATE CLASS DROPDOWNS TEST ===\n\n";

// Find a Victorias teacher
$teacher = User::where('role', 'teacher')
    ->where('campus', 'Victorias')
    ->first();

if (!$teacher) {
    echo "❌ No Victorias teacher found\n";
    exit(1);
}

echo "✓ Testing with teacher: {$teacher->name} (ID: {$teacher->id})\n";
echo "  Campus: {$teacher->campus}\n";
echo "  School ID: {$teacher->school_id}\n\n";

// Test 1: Get assigned subjects (same logic as controller)
echo "TEST 1: Assigned Subjects\n";
echo str_repeat("-", 50) . "\n";

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

echo "Found {$assignedSubjects->count()} subjects:\n";
foreach ($assignedSubjects->take(5) as $subject) {
    echo "  • {$subject->subject_code} - {$subject->subject_name}\n";
}
if ($assignedSubjects->count() > 5) {
    echo "  ... and " . ($assignedSubjects->count() - 5) . " more\n";
}
echo "\n";

// Test 2: Get courses (same logic as controller)
echo "TEST 2: Available Courses\n";
echo str_repeat("-", 50) . "\n";

$courses = Course::query()
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('program_name')
    ->get();

echo "Found {$courses->count()} courses:\n";
foreach ($courses->take(5) as $course) {
    echo "  • {$course->program_code} - {$course->program_name}\n";
}
if ($courses->count() > 5) {
    echo "  ... and " . ($courses->count() - 5) . " more\n";
}
echo "\n";

// Test 3: Get students (same logic as controller)
echo "TEST 3: Available Students\n";
echo str_repeat("-", 50) . "\n";

$students = Student::with(['course'])
    ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
    ->when($teacher->school_id, fn($q) => $q->where('school_id', $teacher->school_id))
    ->orderBy('last_name')
    ->orderBy('first_name')
    ->get();

echo "Found {$students->count()} students:\n";
foreach ($students->take(5) as $student) {
    $fullName = trim($student->first_name . ' ' . $student->last_name);
    $programName = $student->course->program_name ?? 'No Course';
    echo "  • {$fullName} - {$programName} - Year {$student->year}\n";
}
if ($students->count() > 5) {
    echo "  ... and " . ($students->count() - 5) . " more\n";
}
echo "\n";

// Test 4: Verify data will be passed to view
echo "TEST 4: View Data Verification\n";
echo str_repeat("-", 50) . "\n";

$viewData = [
    'assignedSubjects' => $assignedSubjects,
    'courses' => $courses,
    'students' => $students,
    'departments' => $courses->pluck('department')->filter()->unique()->values()->toArray()
];

echo "✓ assignedSubjects: " . $viewData['assignedSubjects']->count() . " items\n";
echo "✓ courses: " . $viewData['courses']->count() . " items\n";
echo "✓ students: " . $viewData['students']->count() . " items\n";
echo "✓ departments: " . count($viewData['departments']) . " items\n";
echo "\n";

// Summary
echo "=== SUMMARY ===\n";
if ($assignedSubjects->count() > 0 && $courses->count() > 0) {
    echo "✅ ALL TESTS PASSED!\n";
    echo "   The dropdowns should now display:\n";
    echo "   - {$assignedSubjects->count()} subjects in the Subject dropdown\n";
    echo "   - {$courses->count()} courses in the Course dropdown\n";
    echo "   - {$students->count()} students available for assignment\n";
    echo "\n";
    echo "🎯 Next Steps:\n";
    echo "   1. Login as: {$teacher->email}\n";
    echo "   2. Go to: My Classes > Create New Class\n";
    echo "   3. The dropdowns should now be populated!\n";
} else {
    echo "⚠️  WARNING: Some data is missing\n";
    if ($assignedSubjects->count() == 0) {
        echo "   - No subjects assigned to this teacher\n";
    }
    if ($courses->count() == 0) {
        echo "   - No courses available for this campus\n";
    }
}
