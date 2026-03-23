<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\CourseAccessRequest;
use Illuminate\Support\Facades\DB;

echo "=== TEACHER COURSES & SUBJECTS TEST ===\n\n";

// Get Victorias teacher
$teacher = User::where('email', 'teacher1.CPSU-VIC@cpsu.edu.ph')->first();

if (!$teacher) {
    echo "Teacher not found!\n";
    exit(1);
}

echo "Teacher: {$teacher->name}\n";
echo "Campus: {$teacher->campus}\n";
echo "School ID: {$teacher->school_id}\n";
echo "Status: {$teacher->campus_status}\n\n";

// Test 1: Available Courses for Teacher's Campus
echo "=== TEST 1: AVAILABLE COURSES ===\n";
$availableCourses = Course::where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->orderBy('program_name')
    ->get();

echo "Courses available for {$teacher->campus} campus: {$availableCourses->count()}\n";
foreach ($availableCourses as $course) {
    echo "  - {$course->program_name} ({$course->program_code})\n";
    echo "    Campus: {$course->campus}, School ID: {$course->school_id}\n";
}
echo "\n";

// Test 2: Course Access Requests
echo "=== TEST 2: COURSE ACCESS REQUESTS ===\n";
$approvedRequests = CourseAccessRequest::where('teacher_id', $teacher->id)
    ->where('status', 'approved')
    ->where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->with('course')
    ->get();

echo "Approved course access requests: {$approvedRequests->count()}\n";
foreach ($approvedRequests as $request) {
    echo "  - {$request->course->program_name}\n";
}

$pendingRequests = CourseAccessRequest::where('teacher_id', $teacher->id)
    ->where('status', 'pending')
    ->get();

echo "Pending course access requests: {$pendingRequests->count()}\n";
foreach ($pendingRequests as $request) {
    echo "  - {$request->course->program_name}\n";
}
echo "\n";

// Test 3: Subjects for Teacher's Campus
echo "=== TEST 3: AVAILABLE SUBJECTS ===\n";
$subjects = Subject::where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->orderBy('subject_name')
    ->get();

echo "Subjects available for {$teacher->campus} campus: {$subjects->count()}\n";
foreach ($subjects->take(10) as $subject) {
    echo "  - {$subject->subject_code}: {$subject->subject_name}\n";
    echo "    Year Level: {$subject->year_level}, Semester: {$subject->semester}\n";
}
if ($subjects->count() > 10) {
    echo "  ... and " . ($subjects->count() - 10) . " more\n";
}
echo "\n";

// Test 4: Teacher's Classes by Course
echo "=== TEST 4: TEACHER'S CLASSES BY COURSE ===\n";
$classes = ClassModel::where('teacher_id', $teacher->id)
    ->where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->with(['course', 'subject'])
    ->get();

$classesByCourse = $classes->groupBy('course_id');

foreach ($classesByCourse as $courseId => $courseClasses) {
    $course = $courseClasses->first()->course;
    if ($course) {
        echo "Course: {$course->program_name}\n";
        echo "  Classes: {$courseClasses->count()}\n";
        foreach ($courseClasses->take(3) as $class) {
            echo "    - {$class->class_name}\n";
            echo "      Subject: " . ($class->subject ? $class->subject->subject_name : 'N/A') . "\n";
        }
        if ($courseClasses->count() > 3) {
            echo "    ... and " . ($courseClasses->count() - 3) . " more\n";
        }
    }
}
echo "\n";

// Test 5: Subjects Used in Teacher's Classes
echo "=== TEST 5: SUBJECTS USED IN TEACHER'S CLASSES ===\n";
$usedSubjectIds = $classes->pluck('subject_id')->unique()->filter();
$usedSubjects = Subject::whereIn('id', $usedSubjectIds)->get();

echo "Subjects currently taught by teacher: {$usedSubjects->count()}\n";
foreach ($usedSubjects as $subject) {
    $classCount = $classes->where('subject_id', $subject->id)->count();
    echo "  - {$subject->subject_code}: {$subject->subject_name}\n";
    echo "    Used in {$classCount} class(es)\n";
}
echo "\n";

// Test 6: Check if teacher can request course access
echo "=== TEST 6: COURSE ACCESS REQUEST ELIGIBILITY ===\n";
$isApproved = empty($teacher->campus) || $teacher->campus_status === 'approved';
echo "Teacher campus approval status: " . ($isApproved ? "APPROVED" : "NOT APPROVED") . "\n";

if ($isApproved) {
    $requestedCourseIds = CourseAccessRequest::where('teacher_id', $teacher->id)
        ->whereIn('status', ['pending', 'approved'])
        ->pluck('course_id');
    
    $availableForRequest = Course::whereNotIn('id', $requestedCourseIds)
        ->where('campus', $teacher->campus)
        ->where('school_id', $teacher->school_id)
        ->count();
    
    echo "Courses available to request: {$availableForRequest}\n";
} else {
    echo "Teacher must be approved before requesting course access\n";
}
echo "\n";

// Test 7: Check for any data isolation issues
echo "=== TEST 7: DATA ISOLATION CHECK ===\n";

// Check if teacher has any classes from other campuses
$wrongCampusClasses = ClassModel::where('teacher_id', $teacher->id)
    ->where('campus', '!=', $teacher->campus)
    ->count();

echo "Classes from other campuses: {$wrongCampusClasses}\n";

// Check if teacher has any classes from other schools
$wrongSchoolClasses = ClassModel::where('teacher_id', $teacher->id)
    ->where('school_id', '!=', $teacher->school_id)
    ->count();

echo "Classes from other schools: {$wrongSchoolClasses}\n";

if ($wrongCampusClasses == 0 && $wrongSchoolClasses == 0) {
    echo "✓ PASS: Data isolation is working correctly\n";
} else {
    echo "✗ FAIL: Data isolation issues detected\n";
}

echo "\n=== TEST COMPLETE ===\n";
