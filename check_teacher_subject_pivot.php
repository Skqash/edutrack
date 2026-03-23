<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== TEACHER_SUBJECT PIVOT TABLE CHECK ===\n\n";

// Check if table exists
if (!Schema::hasTable('teacher_subject')) {
    echo "❌ teacher_subject table does NOT exist\n";
    exit(1);
}

echo "✓ teacher_subject table exists\n\n";

// Get columns
$columns = DB::select("SHOW COLUMNS FROM teacher_subject");
echo "Columns:\n";
foreach ($columns as $col) {
    echo "  - {$col->Field} ({$col->Type})\n";
}
echo "\n";

// Check for isolation fields
$hasCampus = Schema::hasColumn('teacher_subject', 'campus');
$hasSchoolId = Schema::hasColumn('teacher_subject', 'school_id');

echo "Isolation fields:\n";
echo "  campus: " . ($hasCampus ? "✓" : "✗") . "\n";
echo "  school_id: " . ($hasSchoolId ? "✓" : "✗") . "\n\n";

// Get record count
$totalRecords = DB::table('teacher_subject')->count();
echo "Total records: {$totalRecords}\n\n";

if ($totalRecords > 0) {
    // Sample records
    echo "Sample records:\n";
    $samples = DB::table('teacher_subject')
        ->join('users', 'teacher_subject.teacher_id', '=', 'users.id')
        ->join('subjects', 'teacher_subject.subject_id', '=', 'subjects.id')
        ->select(
            'teacher_subject.*',
            'users.email as teacher_email',
            'users.campus as teacher_campus',
            'subjects.subject_code',
            'subjects.subject_name',
            'subjects.campus as subject_campus'
        )
        ->limit(5)
        ->get();
    
    foreach ($samples as $sample) {
        echo "  ID: {$sample->id}\n";
        echo "    Teacher: {$sample->teacher_email} (Campus: {$sample->teacher_campus})\n";
        echo "    Subject: {$sample->subject_code} - {$sample->subject_name} (Campus: {$sample->subject_campus})\n";
        echo "    Status: {$sample->status}\n";
        if ($hasCampus) {
            echo "    Pivot Campus: {$sample->campus}\n";
        }
        if ($hasSchoolId) {
            echo "    Pivot School ID: {$sample->school_id}\n";
        }
        echo "\n";
    }
    
    // Check for Victorias teacher
    echo "=== VICTORIAS TEACHER CHECK ===\n";
    $vicTeacher = DB::table('users')->where('email', 'teacher1.CPSU-VIC@cpsu.edu.ph')->first();
    
    if ($vicTeacher) {
        $vicAssignments = DB::table('teacher_subject')
            ->where('teacher_id', $vicTeacher->id)
            ->count();
        
        echo "Teacher: {$vicTeacher->email}\n";
        echo "Subject assignments: {$vicAssignments}\n";
        
        if ($vicAssignments > 0) {
            $assignments = DB::table('teacher_subject')
                ->join('subjects', 'teacher_subject.subject_id', '=', 'subjects.id')
                ->where('teacher_subject.teacher_id', $vicTeacher->id)
                ->select('teacher_subject.*', 'subjects.subject_code', 'subjects.subject_name')
                ->get();
            
            foreach ($assignments as $assignment) {
                echo "  - {$assignment->subject_code}: {$assignment->subject_name} (Status: {$assignment->status})\n";
            }
        }
    }
} else {
    echo "⚠ No records in teacher_subject table\n";
    echo "This means teachers have no subject assignments yet.\n";
    echo "Teachers can still see subjects through their classes.\n";
}

echo "\n=== CHECK COMPLETE ===\n";
