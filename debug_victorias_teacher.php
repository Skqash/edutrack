<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VICTORIAS TEACHER DEBUG ===\n\n";

// Get Victorias teacher
$teacher = App\Models\User::where('email', 'teacher1.CPSU-VIC@cpsu.edu.ph')->first();

if (!$teacher) {
    echo "Teacher not found!\n";
    exit;
}

echo "Teacher: {$teacher->name}\n";
echo "Campus: '{$teacher->campus}'\n";
echo "School ID: {$teacher->school_id}\n";
echo "Campus Status: {$teacher->campus_status}\n\n";

// Get teacher's classes
echo "=== CLASSES ===\n";
$classes = App\Models\ClassModel::where('teacher_id', $teacher->id)
    ->where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->get();

echo "Total classes: " . $classes->count() . "\n\n";

foreach ($classes->take(5) as $class) {
    echo "Class: {$class->class_name}\n";
    echo "  ID: {$class->id}\n";
    echo "  Course ID: {$class->course_id}\n";
    echo "  Campus: '{$class->campus}'\n";
    echo "  School ID: {$class->school_id}\n";
    
    // Count students WITHOUT campus filter
    $studentsNoFilter = App\Models\Student::where('class_id', $class->id)->count();
    echo "  Students (no filter): $studentsNoFilter\n";
    
    // Count students WITH campus filter
    $studentsWithFilter = App\Models\Student::where('class_id', $class->id)
        ->where('campus', $class->campus)
        ->where('school_id', $class->school_id)
        ->count();
    echo "  Students (with filter): $studentsWithFilter\n";
    
    // Check if students exist for this class
    if ($studentsNoFilter > 0) {
        $sampleStudent = App\Models\Student::where('class_id', $class->id)->first();
        echo "  Sample student campus: '{$sampleStudent->campus}'\n";
        echo "  Sample student school_id: {$sampleStudent->school_id}\n";
    }
    
    echo "---\n";
}

// Check courses
echo "\n=== COURSES ===\n";
$coursesForCampus = App\Models\Course::where('campus', $teacher->campus)
    ->where('school_id', $teacher->school_id)
    ->count();

echo "Courses for teacher's campus: $coursesForCampus\n";

$allCourses = App\Models\Course::where('school_id', $teacher->school_id)->count();
echo "All courses for teacher's school: $allCourses\n";

// Check course access
echo "\n=== COURSE ACCESS ===\n";
$courseInstructors = App\Models\CourseInstructor::where('teacher_id', $teacher->id)->count();
echo "CourseInstructor records: $courseInstructors\n";

// Check what courses exist for Victorias
$vicCourses = App\Models\Course::where('school_id', 72)->get(['id', 'program_name', 'campus', 'school_id']);
echo "\nVictorias courses:\n";
foreach ($vicCourses as $course) {
    echo "  ID: {$course->id}, Name: {$course->program_name}, Campus: '{$course->campus}'\n";
}