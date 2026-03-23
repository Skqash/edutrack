<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\School;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;

echo "=== VICTORIAS CAMPUS DATA CHECK ===\n\n";

// Check schools with Victorias campus
echo "1. Schools with 'Victorias' campus:\n";
$victoriasSchools = School::where('campus', 'LIKE', '%Victorias%')->get();
echo "   Found: {$victoriasSchools->count()}\n";
foreach ($victoriasSchools as $school) {
    echo "   - ID: {$school->id}, Campus: '{$school->campus}', Name: {$school->school_name}\n";
}
echo "\n";

// Check all unique campus values
echo "2. All unique campus values in schools table:\n";
$allCampuses = School::distinct()->pluck('campus')->sort();
foreach ($allCampuses as $campus) {
    $count = School::where('campus', $campus)->count();
    echo "   - '{$campus}' ({$count} schools)\n";
}
echo "\n";

// Check teachers with Victorias campus
echo "3. Teachers with 'Victorias' campus:\n";
$victoriasTeachers = User::where('role', 'teacher')
    ->where('campus', 'LIKE', '%Victorias%')
    ->get(['id', 'name', 'campus', 'school_id']);
echo "   Found: {$victoriasTeachers->count()}\n";
foreach ($victoriasTeachers as $teacher) {
    echo "   - ID: {$teacher->id}, Name: {$teacher->name}, Campus: '{$teacher->campus}', School ID: {$teacher->school_id}\n";
}
echo "\n";

// Check courses for Victorias
if ($victoriasSchools->count() > 0) {
    $victoriasSchool = $victoriasSchools->first();
    echo "4. Courses for school ID {$victoriasSchool->id} ('{$victoriasSchool->campus}'):\n";
    $courses = Course::where('school_id', $victoriasSchool->id)->get();
    echo "   Found: {$courses->count()}\n";
    foreach ($courses->take(5) as $course) {
        echo "   - {$course->program_code}: {$course->program_name}\n";
    }
    echo "\n";
    
    // Check students for Victorias
    echo "5. Students for school ID {$victoriasSchool->id} ('{$victoriasSchool->campus}'):\n";
    $students = Student::where('school_id', $victoriasSchool->id)->count();
    echo "   Found: {$students}\n";
    
    if ($students > 0) {
        $sampleStudents = Student::where('school_id', $victoriasSchool->id)->take(5)->get();
        foreach ($sampleStudents as $student) {
            echo "   - {$student->student_id}: {$student->full_name}\n";
        }
    }
    echo "\n";
}

// Check if there's a mismatch in campus naming
echo "6. Checking for campus naming mismatches:\n";
$teacherCampuses = User::where('role', 'teacher')->distinct()->pluck('campus')->filter()->sort();
$schoolCampuses = School::distinct()->pluck('campus')->filter()->sort();

echo "   Teacher campuses:\n";
foreach ($teacherCampuses as $campus) {
    $count = User::where('role', 'teacher')->where('campus', $campus)->count();
    echo "     - '{$campus}' ({$count} teachers)\n";
}

echo "\n   School campuses:\n";
foreach ($schoolCampuses as $campus) {
    $count = School::where('campus', $campus)->count();
    echo "     - '{$campus}' ({$count} schools)\n";
}

echo "\n   Mismatches (teachers with campus not in schools):\n";
$mismatches = $teacherCampuses->diff($schoolCampuses);
if ($mismatches->count() > 0) {
    foreach ($mismatches as $campus) {
        $teacherCount = User::where('role', 'teacher')->where('campus', $campus)->count();
        echo "     ⚠ '{$campus}' - {$teacherCount} teachers but no matching school\n";
    }
} else {
    echo "     ✓ No mismatches found\n";
}

echo "\n=== CHECK COMPLETE ===\n";
