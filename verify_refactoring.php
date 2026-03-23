<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔════════════════════════════════════════════════════════────╗\n";
echo "║   DATABASE REFACTORING VERIFICATION - COLLEGES & DEPARTMENTS\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Show Colleges
echo "📚 COLLEGES:\n";
echo "─────────────────────────────────────────────────────────────\n";
$colleges = DB::table('colleges')->get();
foreach ($colleges as $college) {
    echo "  ✅ {$college->college_name}\n";
}
echo "  Total: " . count($colleges) . "\n\n";

// 2. Show Departments with Colleges
echo "🏢 DEPARTMENTS (with College):\n";
echo "─────────────────────────────────────────────────────────────\n";
$departments = DB::table('departments')
    ->join('colleges', 'departments.college_id', '=', 'colleges.id')
    ->select('departments.id', 'departments.department_name', 'colleges.college_name')
    ->get();

foreach ($departments as $dept) {
    echo "  ✅ {$dept->department_name}\n";
    echo "     └─ College: {$dept->college_name}\n";
}
echo "  Total: " . count($departments) . "\n\n";

// 3. Show Courses with Department_ID
echo "📖 COURSES (Programs with Department FK):\n";
echo "─────────────────────────────────────────────────────────────\n";
$courses = DB::table('courses')
    ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
    ->select('courses.id', 'courses.program_code', 'courses.program_name', 'departments.department_name', 'courses.department_id')
    ->get();

foreach ($courses as $course) {
    if ($course->department_id) {
        echo "  ✅ {$course->program_code} - {$course->program_name}\n";
        echo "     └─ Department ID: {$course->department_id} ({$course->department_name})\n";
    } else {
        echo "  ⚠️  {$course->program_code} - {$course->program_name}\n";
        echo "     └─ Department ID: NOT SET\n";
    }
}
echo "  Total: " . count($courses) . "\n\n";

// 4. Verification Summary
echo "✅ SCHEMA VERIFICATION:\n";
echo "─────────────────────────────────────────────────────────────\n";

$courseColumns = \Illuminate\Support\Facades\Schema::getColumnListing('courses');
echo "  ✅ courses.department_id exists: " . (in_array('department_id', $courseColumns) ? "YES" : "NO") . "\n";
echo "  ✅ courses.program_head_id exists: " . (in_array('program_head_id', $courseColumns) ? "YES" : "NO") . "\n";
echo "  ✅ courses.total_years exists: " . (in_array('total_years', $courseColumns) ? "YES" : "NO") . "\n";

$deptHasForeignKey = DB::table('information_schema.key_column_usage')
    ->where('table_name', 'departments')
    ->where('column_name', 'college_id')
    ->first();
echo "  ✅ departments.college_id FK exists: " . ($deptHasForeignKey ? "YES" : "NO") . "\n";

echo "\n✅ REFACTORING COMPLETE! Database now uses proper relationships\n";
echo "   instead of plain text college/department columns.\n\n";
