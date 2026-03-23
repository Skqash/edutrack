<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

echo "=== ASSIGN SUBJECTS TO TEACHERS ===\n\n";

// Get all teachers
$teachers = User::where('role', 'teacher')
    ->whereNotNull('campus')
    ->whereNotNull('school_id')
    ->get();

echo "Found {$teachers->count()} teachers\n\n";

$totalAssignments = 0;

foreach ($teachers as $teacher) {
    echo "Processing: {$teacher->name} ({$teacher->campus})\n";
    
    // Get subjects for this teacher's campus
    $subjects = Subject::where('campus', $teacher->campus)
        ->where('school_id', $teacher->school_id)
        ->get();
    
    if ($subjects->isEmpty()) {
        echo "  ⚠ No subjects found for campus: {$teacher->campus}\n";
        continue;
    }
    
    echo "  Found {$subjects->count()} subjects for this campus\n";
    
    // Assign 5-10 random subjects to this teacher
    $subjectsToAssign = $subjects->random(min(rand(5, 10), $subjects->count()));
    
    foreach ($subjectsToAssign as $subject) {
        // Check if already assigned
        $exists = DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('subject_id', $subject->id)
            ->exists();
        
        if (!$exists) {
            DB::table('teacher_subject')->insert([
                'teacher_id' => $teacher->id,
                'subject_id' => $subject->id,
                'status' => 'active',
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $totalAssignments++;
        }
    }
    
    echo "  ✓ Assigned {$subjectsToAssign->count()} subjects\n";
}

echo "\n✓ Total assignments created: {$totalAssignments}\n";

// Show summary
echo "\nSummary by campus:\n";
$campuses = User::where('role', 'teacher')
    ->whereNotNull('campus')
    ->distinct()
    ->pluck('campus');

foreach ($campuses as $campus) {
    $teacherCount = User::where('role', 'teacher')->where('campus', $campus)->count();
    $assignmentCount = DB::table('teacher_subject')
        ->join('users', 'teacher_subject.teacher_id', '=', 'users.id')
        ->where('users.campus', $campus)
        ->where('teacher_subject.status', 'active')
        ->count();
    
    echo "  {$campus}: {$teacherCount} teachers, {$assignmentCount} subject assignments\n";
}

echo "\n=== ASSIGNMENT COMPLETE ===\n";
