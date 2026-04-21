<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "=== Testing Student Login ===\n\n";

    // Get a student from users table
    $studentUser = User::where('role', 'student')->first();
    
    if (!$studentUser) {
        echo "❌ No students found in users table\n";
        exit(1);
    }

    echo "✓ Found student: {$studentUser->email}  (ID: {$studentUser->id})\n";
    echo "  Name: {$studentUser->name}\n";
    echo "  Role: {$studentUser->role}\n";
    echo "  Status: {$studentUser->status}\n\n";

    // Check if student profile exists in students table
    $studentProfile = \App\Models\Student::where('student_id', 'LIKE', '%' . $studentUser->id . '%')
        ->orWhere('email', $studentUser->email)
        ->first();

    if ($studentProfile) {
        echo "✓ Found student profile in students table\n";
        echo "  Student ID: {$studentProfile->student_id}\n";
        echo "  Year: {$studentProfile->year}\n";
        echo "  Section: {$studentProfile->section}\n";
        echo "  Course ID: {$studentProfile->course_id}\n\n";
    } else {
        echo "⚠ No corresponding profile in students table (needs to be created)\n\n";
    }

    // Test password verification
    $testPassword = 'student123';
    if (Hash::check($testPassword, $studentUser->password)) {
        echo "✓ Password verification works (test password: {$testPassword})\n";
    } else {
        echo "✗ Password verification failed\n";
    }

    echo "\n✅ Student login testing complete!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
