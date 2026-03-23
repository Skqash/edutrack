<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== DATABASE ARCHITECTURE AUDIT ===\n\n";

// Get all tables
$tables = DB::select('SHOW TABLES');
$database = env('DB_DATABASE');
$tableKey = "Tables_in_{$database}";

echo "=== CHECKING KEY TABLES FOR DATA ISOLATION ===\n\n";

$keyTables = [
    'users',
    'teachers', 
    'students',
    'classes',
    'courses',
    'subjects',
    'attendance',
    'grades',
    'grade_entries',
    'schools',
    'course_instructors',
    'course_access_requests',
    'teacher_assignments'
];

foreach ($keyTables as $table) {
    if (!Schema::hasTable($table)) {
        echo "❌ TABLE MISSING: {$table}\n";
        continue;
    }
    
    echo "✓ {$table}\n";
    $columns = DB::select("SHOW COLUMNS FROM {$table}");
    
    $columnNames = array_map(fn($col) => $col->Field, $columns);
    
    // Check for isolation fields
    $hasSchoolId = in_array('school_id', $columnNames);
    $hasCampus = in_array('campus', $columnNames);
    $hasTeacherId = in_array('teacher_id', $columnNames);
    $hasUserId = in_array('user_id', $columnNames);
    $hasClassId = in_array('class_id', $columnNames);
    
    echo "  Columns: " . implode(', ', $columnNames) . "\n";
    echo "  Isolation: school_id=" . ($hasSchoolId ? '✓' : '✗') . 
         ", campus=" . ($hasCampus ? '✓' : '✗') . "\n";
    
    if (in_array($table, ['teachers', 'classes', 'attendance', 'grades', 'course_instructors'])) {
        echo "  Teacher ref: teacher_id=" . ($hasTeacherId ? '✓' : '✗') . 
             ", user_id=" . ($hasUserId ? '✓' : '✗') . "\n";
    }
    
    echo "\n";
}

echo "\n=== CHECKING DATA CONSISTENCY ===\n\n";

// Check teachers table
echo "TEACHERS:\n";
$teacherCount = DB::table('teachers')->count();
$teachersWithSchool = DB::table('teachers')->whereNotNull('school_id')->count();
$teachersWithCampus = DB::table('teachers')->whereNotNull('campus')->count();
echo "  Total: {$teacherCount}\n";
echo "  With school_id: {$teachersWithSchool}\n";
echo "  With campus: {$teachersWithCampus}\n";

// Check if teachers have user_id
$teachersWithUserId = DB::table('teachers')->whereNotNull('user_id')->count();
echo "  With user_id: {$teachersWithUserId}\n\n";

// Check students table
echo "STUDENTS:\n";
$studentCount = DB::table('students')->count();
$studentsWithSchool = DB::table('students')->whereNotNull('school_id')->count();
$studentsWithCampus = DB::table('students')->whereNotNull('campus')->count();
$studentsWithClass = DB::table('students')->whereNotNull('class_id')->count();
echo "  Total: {$studentCount}\n";
echo "  With school_id: {$studentsWithSchool}\n";
echo "  With campus: {$studentsWithCampus}\n";
echo "  With class_id: {$studentsWithClass}\n\n";

// Check classes table
echo "CLASSES:\n";
$classCount = DB::table('classes')->count();
$classesWithSchool = DB::table('classes')->whereNotNull('school_id')->count();
$classesWithCampus = DB::table('classes')->whereNotNull('campus')->count();
$classesWithTeacher = DB::table('classes')->whereNotNull('teacher_id')->count();
echo "  Total: {$classCount}\n";
echo "  With school_id: {$classesWithSchool}\n";
echo "  With campus: {$classesWithCampus}\n";
echo "  With teacher_id: {$classesWithTeacher}\n\n";

// Check attendance table
echo "ATTENDANCE:\n";
$attendanceCount = DB::table('attendance')->count();
$attendanceWithSchool = DB::table('attendance')->whereNotNull('school_id')->count();
$attendanceWithCampus = DB::table('attendance')->whereNotNull('campus')->count();
$attendanceWithTeacher = DB::table('attendance')->whereNotNull('teacher_id')->count();
echo "  Total: {$attendanceCount}\n";
echo "  With school_id: {$attendanceWithSchool}\n";
echo "  With campus: {$attendanceWithCampus}\n";
echo "  With teacher_id: {$attendanceWithTeacher}\n\n";

// Check grades table
echo "GRADES:\n";
$gradeCount = DB::table('grades')->count();
$gradesWithSchool = DB::table('grades')->whereNotNull('school_id')->count();
$gradesWithCampus = DB::table('grades')->whereNotNull('campus')->count();
echo "  Total: {$gradeCount}\n";
echo "  With school_id: {$gradesWithSchool}\n";
echo "  With campus: {$gradesWithCampus}\n\n";

// Check course_instructors
if (Schema::hasTable('course_instructors')) {
    echo "COURSE_INSTRUCTORS:\n";
    $ciCount = DB::table('course_instructors')->count();
    echo "  Total: {$ciCount}\n";
    
    // Sample records
    $samples = DB::table('course_instructors')->limit(3)->get();
    foreach ($samples as $sample) {
        echo "  Sample: " . json_encode($sample) . "\n";
    }
    echo "\n";
}

echo "\n=== RELATIONSHIP ISSUES ===\n\n";

// Check for orphaned students (class_id doesn't exist)
$orphanedStudents = DB::table('students')
    ->whereNotNull('class_id')
    ->whereNotExists(function($query) {
        $query->select(DB::raw(1))
              ->from('classes')
              ->whereRaw('classes.id = students.class_id');
    })
    ->count();
echo "Orphaned students (invalid class_id): {$orphanedStudents}\n";

// Check for orphaned classes (teacher_id doesn't match any user)
$orphanedClasses = DB::table('classes')
    ->whereNotNull('teacher_id')
    ->whereNotExists(function($query) {
        $query->select(DB::raw(1))
              ->from('users')
              ->whereRaw('users.id = classes.teacher_id');
    })
    ->count();
echo "Orphaned classes (invalid teacher_id): {$orphanedClasses}\n";

// Check for campus mismatches
echo "\n=== CAMPUS CONSISTENCY CHECKS ===\n\n";

// Students vs Classes campus mismatch
$campusMismatches = DB::table('students')
    ->join('classes', 'students.class_id', '=', 'classes.id')
    ->whereRaw('students.campus != classes.campus')
    ->count();
echo "Students with campus != class campus: {$campusMismatches}\n";

// Classes vs Teacher campus mismatch
$teacherClassMismatches = DB::table('classes')
    ->join('users', 'classes.teacher_id', '=', 'users.id')
    ->whereRaw('classes.campus != users.campus')
    ->count();
echo "Classes with campus != teacher campus: {$teacherClassMismatches}\n";

echo "\n=== AUDIT COMPLETE ===\n";
