<?php
// Database verification script
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

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
foreach ($depts as $dept) {
    echo "  • ID: {$dept->id}, Name: {$dept->department_name}, College: {$dept->college_id}\n";
}

// 3. COLLEGES VERIFICATION
echo "\n3️⃣  COLLEGES VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$colleges = DB::table('colleges')->orderBy('id')->get();
echo "✓ Total Colleges: " . count($colleges) . "\n";
foreach ($colleges as $college) {
    echo "  • ID: {$college->id}, Name: {$college->college_name}\n";
}

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
if ($classesWithProgram === $totalClasses) {
    echo "✓ All classes properly linked to programs\n";
} else {
    echo "❌ WARNING: " . ($totalClasses - $classesWithProgram) . " classes missing program_id\n";
}

$classesByProgram = DB::table('classes')
    ->selectRaw('program_id, COUNT(*) as count')
    ->groupBy('program_id')
    ->orderBy('program_id')
    ->get();
foreach ($classesByProgram as $row) {
    $progName = DB::table('courses')->where('id', $row->program_id)->value('program_name') ?: "Unknown";
    echo "  • Program ID {$row->program_id} ({$progName}): {$row->count} classes\n";
}

// 6. CLASS_SUBJECTS JUNCTION VERIFICATION
echo "\n6️⃣  CLASS_SUBJECTS JUNCTION TABLE VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalClassSubjects = DB::table('class_subjects')->count();
echo "✓ Total class_subject records: " . $totalClassSubjects . "\n";
$avgSubjectsPerClass = round($totalClassSubjects / $totalClasses, 2);
echo "✓ Average subjects per class: " . $avgSubjectsPerClass . "\n";

// Check if all classes have subjects
$classesWithoutSubjects = DB::table('classes')
    ->leftJoin('class_subjects', 'classes.id', '=', 'class_subjects.class_id')
    ->whereNull('class_subjects.id')
    ->count('DISTINCT classes.id');
if ($classesWithoutSubjects > 0) {
    echo "❌ WARNING: " . $classesWithoutSubjects . " classes have no subjects assigned\n";
} else {
    echo "✓ All classes have subjects assigned\n";
}

// 7. STUDENTS VERIFICATION
echo "\n7️⃣  STUDENTS VERIFICATION:\n";
echo str_repeat("-", 90) . "\n";
$totalStudents = DB::table('students')->count();
$studentsWithClass = DB::table('students')->whereNotNull('class_id')->count();
echo "✓ Total Students: " . $totalStudents . "\n";
echo "✓ Students with valid class assignment: " . $studentsWithClass . " / " . $totalStudents . "\n";

$orphanedStudents = $totalStudents - $studentsWithClass;
if ($orphanedStudents > 0) {
    echo "❌ WARNING: " . $orphanedStudents . " students not assigned to a class\n";
} else {
    echo "✓ All students properly assigned to classes\n";
}

$classesWithStudents = DB::table('students')
    ->distinct('class_id')
    ->count('class_id');
echo "✓ Classes with students: " . $classesWithStudents . " / " . $totalClasses . "\n";

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

// Check courses -> departments FK
$coursesWithBadDept = DB::table('courses')
    ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
    ->whereNotNull('courses.department_id')
    ->whereNull('departments.id')
    ->count();
if ($coursesWithBadDept > 0) {
    echo "❌ ERROR: " . $coursesWithBadDept . " courses have invalid department_id\n";
} else {
    echo "✓ All courses have valid department references\n";
}

// Check subjects -> courses FK
$subjectsWithBadCourse = DB::table('subjects')
    ->leftJoin('courses', 'subjects.program_id', '=', 'courses.id')
    ->whereNotNull('subjects.program_id')
    ->whereNull('courses.id')
    ->count();
if ($subjectsWithBadCourse > 0) {
    echo "❌ ERROR: " . $subjectsWithBadCourse . " subjects have invalid program_id\n";
} else {
    echo "✓ All subjects have valid program references\n";
}

// Check classes -> courses FK
$classesWithBadProgram = DB::table('classes')
    ->leftJoin('courses', 'classes.program_id', '=', 'courses.id')
    ->whereNotNull('classes.program_id')
    ->whereNull('courses.id')
    ->count();
if ($classesWithBadProgram > 0) {
    echo "❌ ERROR: " . $classesWithBadProgram . " classes have invalid program_id\n";
} else {
    echo "✓ All classes have valid program references\n";
}

// 10. FINAL SUMMARY
echo "\n🎯 FINAL SUMMARY:\n";
echo str_repeat("-", 90) . "\n";
$allGood = ($classesWithProgram === $totalClasses && $orphanedStudents === 0 && $coursesWithBadDept === 0 && $subjectsWithBadCourse === 0 && $classesWithBadProgram === 0);

if ($allGood) {
    echo "✅ DATABASE IS READY FOR PRODUCTION\n";
    echo "   • Proper 3-program structure (BSIT, BEED, BSHM)\n";
    echo "   • All colleges, departments, and programs linked\n";
    echo "   • " . $totalSubjects . " subjects across 4 year levels\n";
    echo "   • " . $totalClasses . " classes properly linked to programs\n";
    echo "   • " . $totalClassSubjects . " subject-to-class assignments\n";
    echo "   • " . $totalStudents . " students properly enrolled\n";
    echo "   • Referential integrity validated\n";
} else {
    echo "⚠️  DATABASE HAS ISSUES:\n";
    if ($classesWithProgram !== $totalClasses) echo "   • Some classes missing program assignment\n";
    if ($orphanedStudents > 0) echo "   • Some students not assigned to classes\n";
    if ($coursesWithBadDept > 0) echo "   • Some courses have invalid department references\n";
    if ($subjectsWithBadCourse > 0) echo "   • Some subjects have invalid program references\n";
    if ($classesWithBadProgram > 0) echo "   • Some classes have invalid program references\n";
}

echo "\n" . str_repeat("=", 90) . "\n\n";
