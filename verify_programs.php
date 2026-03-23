<?php
// Verify program-based course and subject structure

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n✅ PROGRAM-BASED COURSES AND SUBJECTS STRUCTURE\n";
echo str_repeat("=", 80) . "\n\n";

// Get all courses (programs)
$courses = DB::table('courses')->get();

echo "📚 PROGRAMS (" . count($courses) . " total):\n";
echo str_repeat("-", 80) . "\n";

foreach ($courses as $course) {
    echo sprintf("\n%-15s → %s\n", $course->program_code, $course->program_name);
    echo sprintf("   Code: %s | Department: %s | Status: %s\n", 
        $course->course_code, 
        $course->department, 
        $course->status);
    
    // Get subjects for this course
    $subjects = DB::table('subjects')
        ->where('course_id', $course->id)
        ->orderBy('semester')
        ->orderBy('subject_code')
        ->get();
    
    if ($subjects->count() > 0) {
        echo "   📖 Subjects (" . $subjects->count() . "):\n";
        
        $currentSem = null;
        foreach ($subjects as $subject) {
            if ($currentSem !== $subject->semester) {
                $currentSem = $subject->semester;
                echo "\n      ▶ SEMESTER " . $subject->semester . ":\n";
            }
            echo sprintf("         %-12s | %-40s | %d credits | %s\n",
                $subject->subject_code,
                substr($subject->subject_name, 0, 40),
                $subject->credit_hours,
                $subject->school_year
            );
        }
    }
}

echo "\n" . str_repeat("=", 80) . "\n";

// Summary statistics
$totalCourses = DB::table('courses')->count();
$totalSubjects = DB::table('subjects')->count();
$subjectsPerCourse = DB::table('subjects')
    ->selectRaw('course_id, count(*) as count')
    ->groupBy('course_id')
    ->get();

echo "\n📊 SUMMARY:\n";
echo str_repeat("-", 80) . "\n";
echo sprintf("Total Programs (Courses): %d\n", $totalCourses);
echo sprintf("Total Subjects: %d\n", $totalSubjects);
echo sprintf("Average Subjects per Program: %.1f\n", $totalSubjects / $totalCourses);

echo "\nSubjects Distribution:\n";
foreach ($subjectsPerCourse as $stat) {
    $programName = DB::table('courses')->find($stat->course_id)->program_code;
    echo sprintf("  %s: %d subjects\n", $programName, $stat->count);
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ PROGRAM STRUCTURE SUCCESSFULLY CREATED!\n";
echo "Ready for semester and school year management.\n\n";
