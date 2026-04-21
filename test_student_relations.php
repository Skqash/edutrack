<?php

use App\Models\User;
use App\Models\Student;

try {
    echo "=== Checking Student-User Relationships ===\n\n";

    // Get a few students from users table
    $studentsInUsers = User::where('role', 'student')->limit(3)->get();
    
    echo "Students in users table: {$studentsInUsers->count()}\n\n";

    foreach ($studentsInUsers as $user) {
        $studentProfile = Student::where('user_id', $user->id)->first();
        
        if ($studentProfile) {
            echo "✓ User ID {$user->id} ({$user->email}) has student profile\n";
            echo "  Student ID: {$studentProfile->student_id}\n";
            echo "  Course: {$studentProfile->course_id}\n";
        } else {
            echo "⚠ User ID {$user->id} ({$user->email}) MISSING student profile (needs linking)\n";
            echo "  Will be created on first dashboard access\n";
        }
    }

    echo "\n✅ Relationship check complete!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
