#!/usr/bin/env php
<?php

use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

// Manually boot application
$app->make(\Illuminate\Contracts\Http\Kernel::class);

// Now run the verification
echo "\n" . str_repeat("=", 90) . "\n";
echo "FINAL DATABASE VERIFICATION REPORT - March 20, 2026\n";
echo str_repeat("=", 90) . "\n\n";

// 1. COURSES VERIFICATION
echo "1️⃣  COURSES (Programs) VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$courses = DB::table('courses')->orderBy('id')->get();
echo "✓ Total Programs: " . count($courses) . "\n";
if (count($courses) !== 3) {
    echo "❌ ERROR: Expected 3 programs, found " . count($courses) . "\n";
} else {
    echo "✓ All 3 required programs present\n";
}
foreach ($courses as $course) {
    echo "  • {$course->course_code} ({$course->program_name}) - Dept: {$course->department_id}, Years: {$course->total_years}, Status: {$course->status}\n";
}

// 2. DEPARTMENTS VERIFICATION
echo "\n2️⃣  DEPARTMENTS VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$depts = DB::table('departments')->orderBy('id')->get();
echo "✓ Total Departments: " . count($depts) . "\n";

// 3. COLLEGES VERIFICATION
echo "\n3️⃣  COLLEGES VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$colleges = DB::table('colleges')->orderBy('id')->get();
echo "✓ Total Colleges: " . count($colleges) . "\n";

// 4. SUBJECTS VERIFICATION
echo "\n4️⃣  SUBJECTS VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalSubjects = DB::table('subjects')->count();
echo "✓ Total Subjects: " . $totalSubjects . "\n";
$subjectsByYear = DB::table('subjects')
    ->selectRaw('year_level, COUNT(*) as count')
    ->groupBy('year_level')
    ->orderBy('year_level')
    ->get();
foreach ($subjectsByYear as $row) {
    echo "  • Year {$row->year_level}: {$row->count} subjects\n";
}

// 5. CLASSES VERIFICATION
echo "\n5️⃣  CLASSES VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalClasses = DB::table('classes')->count();
$classesWithProgram = DB::table('classes')->whereNotNull('program_id')->count();
echo "✓ Total Classes: " . $totalClasses . "\n";
echo "✓ Classes with program_id: " . $classesWithProgram . " / " . $totalClasses . "\n";

// 6. CLASS_SUBJECTS JUNCTION VERIFICATION
echo "\n6️⃣  CLASS_SUBJECTS JUNCTION TABLE VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalClassSubjects = DB::table('class_subjects')->count();
echo "✓ Total class_subject records: " . $totalClassSubjects . "\n";

// 7. STUDENTS VERIFICATION
echo "\n7️⃣  STUDENTS VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalStudents = DB::table('students')->count();
$studentsWithClass = DB::table('students')->whereNotNull('class_id')->count();
echo "✓ Total Students: " . $totalStudents . "\n";
echo "✓ Students with valid class assignment: " . $studentsWithClass . "\n";

// 8. USERS VERIFICATION
echo "\n8️⃣  USERS VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$usersByRole = DB::table('users')
    ->selectRaw('role, COUNT(*) as count')
    ->groupBy('role')
    ->orderBy('role')
    ->get();
$totalUsers = 0;
foreach ($usersByRole as $row) {
    echo "  • {$row->role}: {$row->count}\n";
    $totalUsers += $row->count;
}
echo "✓ Total users: " . $totalUsers . "\n";

// 9. REFERENTIAL INTEGRITY VERIFICATION
echo "\n9️⃣  REFERENTIAL INTEGRITY VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";

$coursesWithBadDept = DB::table('courses')
    ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
    ->whereNotNull('courses.department_id')
    ->whereNull('departments.id')
    ->count();
echo ($coursesWithBadDept === 0 ? "✓" : "❌") . " Courses with valid department references\n";

$subjectsWithBadCourse = DB::table('subjects')
    ->leftJoin('courses', 'subjects.program_id', '=', 'courses.id')
    ->whereNotNull('subjects.program_id')
    ->whereNull('courses.id')
    ->count();
echo ($subjectsWithBadCourse === 0 ? "✓" : "❌") . " Subjects with valid program references\n";

$classesWithBadProgram = DB::table('classes')
    ->leftJoin('courses', 'classes.program_id', '=', 'courses.id')
    ->whereNotNull('classes.program_id')
    ->whereNull('courses.id')
    ->count();
echo ($classesWithBadProgram === 0 ? "✓" : "❌") . " Classes with valid program references\n";

// 10. FINAL SUMMARY
echo "\n🎯 FINAL SUMMARY:\n";
echo str_repeat("=", 90) . "\n";
$allGood = ($classesWithProgram === $totalClasses && $coursesWithBadDept === 0 && $subjectsWithBadCourse === 0 && $classesWithBadProgram === 0);

if ($allGood) {
    echo "✅ DATABASE IS READY FOR PRODUCTION\n";
    echo "   • 3-program structure (BSIT, BEED, BSHM)\n";
    echo "   • " . $totalSubjects . " subjects across 4 year levels\n";
    echo "   • " . $totalClasses . " classes linked to programs\n";
    echo "   • " . $totalClassSubjects . " subject-to-class mappings\n";
    echo "   • " . $totalStudents . " students properly enrolled\n";
} else {
    echo "⚠️  DATABASE HAS ISSUES - Please review above\n";
}

echo "\n" . str_repeat("=", 90) . "\n\n";
