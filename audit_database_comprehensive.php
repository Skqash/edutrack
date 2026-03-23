<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "\n" . str_repeat("=", 80) . "\n";
echo "COMPREHENSIVE DATABASE AUDIT - MARCH 19, 2026\n";
echo str_repeat("=", 80) . "\n\n";

// 1. COURSES
echo "1. COURSES TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$courses = DB::table('courses')->get();
echo "Total Records: " . count($courses) . "\n";
foreach ($courses as $course) {
    echo "  - ID: $course->id, Code: $course->course_code, Name: $course->program_name, DeptID: $course->department_id, Years: $course->total_years, Status: $course->status\n";
}

// 2. DEPARTMENTS
echo "\n2. DEPARTMENTS TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$depts = DB::table('departments')->get();
echo "Total Records: " . count($depts) . "\n";
foreach ($depts as $dept) {
    echo "  - ID: $dept->id, Name: $dept->department_name, College: $dept->college_id\n";
}

// 3. COLLEGES
echo "\n3. COLLEGES TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$colleges = DB::table('colleges')->get();
echo "Total Records: " . count($colleges) . "\n";
foreach ($colleges as $college) {
    echo "  - ID: $college->id, Name: $college->college_name\n";
}

// 4. SUBJECTS by Year
echo "\n4. SUBJECTS TABLE AUDIT (By Year Level):\n";
echo "--" . str_repeat("-", 78) . "\n";
$subjectsByYear = DB::table('subjects')
    ->selectRaw('year_level, COUNT(*) as count')
    ->groupBy('year_level')
    ->orderBy('year_level')
    ->get();
echo "Distribution by Year Level:\n";
foreach ($subjectsByYear as $row) {
    echo "  - Year $row->year_level: " . $row->count . " subjects\n";
}

$subjectsByProgram = DB::table('subjects')
    ->selectRaw('program_id, COUNT(*) as count')
    ->groupBy('program_id')
    ->get();
echo "\nDistribution by Program:\n";
foreach ($subjectsByProgram as $row) {
    $progName = DB::table('courses')->where('id', $row->program_id)->value('program_name') ?: "Unknown";
    echo "  - Program ID $row->program_id ($progName): " . $row->count . " subjects\n";
}

// 5. CLASSES
echo "\n5. CLASSES TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$classes = DB::table('classes')->count();
$classesWithProgram = DB::table('classes')->whereNotNull('program_id')->count();
echo "Total Classes: $classes\n";
echo "Classes with program_id set: $classesWithProgram\n";
echo "Classes with NULL program_id: " . ($classes - $classesWithProgram) . "\n";

// 6. CLASS_SUBJECTS
echo "\n6. CLASS_SUBJECTS JUNCTION TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$classSubjects = DB::table('class_subjects')->count();
echo "Total class_subject records: $classSubjects\n";

// 7. STUDENTS
echo "\n7. STUDENTS TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$students = DB::table('students')->count();
$studentsWithClass = DB::table('students')->whereNotNull('class_id')->count();
echo "Total Students: $students\n";
echo "Students with class_id set: $studentsWithClass\n";

$studentsByClass = DB::table('students')
    ->where('class_id', '!=', null)
    ->selectRaw('class_id, COUNT(*) as count')
    ->groupBy('class_id')
    ->orderByDesc('count')
    ->limit(10)
    ->get();
echo "\nTop 10 Classes by Student Count:\n";
foreach ($studentsByClass as $row) {
    $className = DB::table('classes')->where('id', $row->class_id)->value('class_name') ?: "Unknown";
    echo "  - Class #$row->class_id ($className): " . $row->count . " students\n";
}

// 8. USERS
echo "\n8. USERS TABLE AUDIT:\n";
echo "--" . str_repeat("-", 78) . "\n";
$users = DB::table('users')->selectRaw('role, COUNT(*) as count')->groupBy('role')->get();
echo "Users by Role:\n";
foreach ($users as $row) {
    echo "  - $row->role: " . $row->count . "\n";
}

// 9. LEGACY TABLES CHECK
echo "\n9. LEGACY/REDUNDANT TABLES CHECK:\n";
echo "--" . str_repeat("-", 78) . "\n";
$legacyTables = ['admins', 'teachers', 'super_admins', 'class_students', 'teacher_subject', 'course_instructors', 'subject_instructors', 'teacher_assignments'];
foreach ($legacyTables as $table) {
    $exists = DB::select("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'edutrack_db' AND TABLE_NAME = '$table'");
    if ($exists) {
        $count = DB::table($table)->count();
        echo "  ⚠️  $table: EXISTS ($count records)\n";
    } else {
        echo "  ✓ $table: REMOVED\n";
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "END OF AUDIT\n";
echo str_repeat("=", 80) . "\n\n";
