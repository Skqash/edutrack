<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║        COMPLETE DATABASE STRUCTURE AUDIT\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Check Courses table
echo "📚 COURSES TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
$courseColumns = Schema::getColumnListing('courses');
echo '  Columns: '.implode(', ', $courseColumns)."\n";
echo '  Records: '.DB::table('courses')->count()."\n";
$courses = DB::table('courses')->select('id', 'course_code', 'program_name', 'program_code', 'department_id')->get();
foreach ($courses as $c) {
    echo "    • {$c->course_code} | {$c->program_name}\n";
}

// 2. Check Subjects table
echo "\n📖 SUBJECTS TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
$subjectColumns = Schema::getColumnListing('subjects');
echo '  Columns: '.implode(', ', $subjectColumns)."\n";
echo '  Records: '.DB::table('subjects')->count()."\n";
echo "  Subjects per Year Level:\n";
$subjectsByYear = DB::table('subjects')->groupBy('year_level')->selectRaw('year_level, COUNT(*) as count')->orderBy('year_level')->get();
foreach ($subjectsByYear as $row) {
    echo "    • Year {$row->year_level}: {$row->count} subjects\n";
}

// 3. Check Classes table
echo "\n🏫 CLASSES TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
$classColumns = Schema::getColumnListing('classes');
echo '  Columns: '.implode(', ', $classColumns)."\n";
echo '  Records: '.DB::table('classes')->count()."\n";
echo "  Sample classes:\n";
$sampleClasses = DB::table('classes')->select('id', 'class_name', 'program_id', 'year_level', 'semester', 'section')->limit(5)->get();
foreach ($sampleClasses as $cls) {
    echo "    • {$cls->class_name} (Program: {$cls->program_id}, Year: {$cls->year_level})\n";
}

// 4. Check class_subjects table
echo "\n🔗 CLASS_SUBJECTS TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
if (Schema::hasTable('class_subjects')) {
    echo "  Status: ✅ EXISTS\n";
    $classSubjects = DB::table('class_subjects')->count();
    echo "  Records: {$classSubjects}\n";
} else {
    echo "  Status: ❌ DOES NOT EXIST\n";
}

// 5. Check Students table
echo "\n👥 STUDENTS TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
$studentColumns = Schema::getColumnListing('students');
echo '  Columns: '.implode(', ', $studentColumns)."\n";
echo '  Records: '.DB::table('students')->count()."\n";
$studentsByClass = DB::table('students')->groupBy('class_id')->selectRaw('COUNT(*) as count')->get();
echo '  Classes with students: '.count($studentsByClass)."\n";

// 6. Check Users table
echo "\n👤 USERS TABLE:\n";
echo "─────────────────────────────────────────────────────────────\n";
$usersByRole = DB::table('users')->groupBy('role')->selectRaw('role, COUNT(*) as count')->get();
foreach ($usersByRole as $row) {
    echo "  {$row->role}: {$row->count}\n";
}

// 7. Check other tables
echo "\n📊 OTHER TABLES:\n";
echo "─────────────────────────────────────────────────────────────\n";
$allTables = DB::connection('mysql')->getSchemaBuilder()->getTables();
$expectTables = ['users', 'courses', 'subjects', 'classes', 'students', 'grades', 'colleges', 'departments', 'class_subjects'];
$extraTables = array_diff(array_column($allTables, 'name'), $expectTables);
if (count($extraTables) > 0) {
    echo "  ⚠️  Extra/Legacy Tables:\n";
    foreach ($extraTables as $t) {
        if (! in_array($t, ['migrations', 'personal_access_tokens', 'failed_jobs', 'password_reset_tokens', 'password_resets'])) {
            echo "    • {$t}\n";
        }
    }
}

echo "\n✅ AUDIT COMPLETE\n\n";
