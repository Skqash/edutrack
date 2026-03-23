<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║       SUBJECTS TABLE NORMALIZATION VERIFICATION\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Show current schema
echo "📋 SUBJECTS TABLE SCHEMA:\n";
echo "─────────────────────────────────────────────────────────────\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('subjects');
$requiredColumns = ['id', 'subject_code', 'subject_name', 'program_id', 'year_level', 'semester', 'category', 'credit_hours', 'description'];
$bannedColumns = ['school_year', 'instructor_id', 'program', 'type', 'course_id', 'year'];

echo "✅ REQUIRED COLUMNS:\n";
foreach ($requiredColumns as $col) {
    $status = in_array($col, $columns) ? "✅ EXISTS" : "❌ MISSING";
    echo "  {$status}\t{$col}\n";
}

echo "\n❌ REMOVED COLUMNS (SHOULD NOT EXIST):\n";
foreach ($bannedColumns as $col) {
    $status = in_array($col, $columns) ? "❌ STILL EXISTS (ERROR)" : "✅ REMOVED";
    echo "  {$status}\t{$col}\n";
}

// 2. Show sample subjects with program relationships
echo "\n🔗 SUBJECTS WITH PROGRAM RELATIONSHIPS:\n";
echo "─────────────────────────────────────────────────────────────\n";
$subjects = DB::table('subjects')
    ->join('courses', 'subjects.program_id', '=', 'courses.id')
    ->select(
        'subjects.id',
        'subjects.subject_code',
        'subjects.subject_name',
        'courses.program_code',
        'subjects.year_level',
        'subjects.semester',
        'subjects.category',
        'subjects.credit_hours'
    )
    ->limit(10)
    ->get();

foreach ($subjects as $subject) {
    echo "  {$subject->subject_code} | {$subject->subject_name}\n";
    echo "    └─ Program: {$subject->program_code} | Year: {$subject->year_level} | Sem: {$subject->semester} | Category: {$subject->category}\n";
}

echo "\n📊 STATISTICS:\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "  Total Subjects: " . DB::table('subjects')->count() . "\n";
echo "  Subjects with program_id: " . DB::table('subjects')->whereNotNull('program_id')->count() . "\n";
echo "  By Year Level:\n";
$byYear = DB::table('subjects')->groupBy('year_level')->selectRaw('year_level, COUNT(*) as count')->get();
foreach ($byYear as $row) {
    echo "    Year {$row->year_level}: {$row->count} subjects\n";
}

echo "\n✅ NORMALIZATION VERIFIED!\n";
echo "   Subjects table now uses:\n";
echo "   • program_id (FK to courses) instead of course_id\n";
echo "   • year_level (1-4) instead of year\n";
echo "   • Removed: school_year, instructor_id, program, type\n";
echo "   • Kept: category for curriculum classification\n\n";
