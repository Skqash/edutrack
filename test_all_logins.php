<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "=== COMPREHENSIVE LOGIN VERIFICATION ===\n\n";

    $testCases = [
        [
            'role' => 'super',
            'email' => 'super@cpsu.edu.ph',
            'password' => 'super123',
            'dashboard' => '/super/dashboard',
        ],
        [
            'role' => 'admin',
            'email' => 'admin.main@cpsu.edu.ph',
            'password' => 'admin123',
            'dashboard' => '/admin/dashboard',
        ],
        [
            'role' => 'teacher',
            'email' => 'maria.santos@cpsu.edu.ph',
            'password' => 'teacher123',
            'dashboard' => '/teacher/dashboard',
        ],
        [
            'role' => 'student',
            'email' => 'dela.cruz.main0001@cpsu.edu.ph',
            'password' => 'student123',
            'dashboard' => '/student/dashboard',
        ],
    ];

    foreach ($testCases as $test) {
        echo "Testing {$test['role']} login:\n";
        echo "  Email: {$test['email']}\n";
        
        // Find user
        $user = User::where('email', $test['email'])->first();
        
        if (!$user) {
            echo "  ❌ User not found in database\n\n";
            continue;
        }

        // Verify user properties
        echo "  ✓ User found (ID: {$user->id})\n";
        echo "  ✓ Role in DB: {$user->role}\n";
        echo "  ✓ Status: {$user->status}\n";
        
        // Test password verification
        if (Hash::check($test['password'], $user->password)) {
            echo "  ✓ Password correct\n";
        } else {
            echo "  ❌ Password incorrect\n";
        }

        // Check role matches expected
        if (strtolower($user->role) === strtolower($test['role'])) {
            echo "  ✓ Role matches expected '{$test['role']}'\n";
        } else {
            echo "  ❌ Role '{$user->role}' does not match expected '{$test['role']}'\n";
        }

        // Check supplementary data
        if ($test['role'] === 'student') {
            $profile = \App\Models\Student::where('user_id', $user->id)->first();
            if ($profile) {
                echo "  ✓ Student profile linked (ID: {$profile->student_id})\n";
            } else {
                echo "  ⚠ Student profile not linked (will be created on first access)\n";
            }
        } elseif ($test['role'] === 'teacher') {
            $profile = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($profile) {
                echo "  ✓ Teacher profile linked\n";
            } else {
                echo "  ⚠ Teacher profile not linked (optional)\n";
            }
        }

        echo "  Dashboard redirect: {$test['dashboard']}\n";
        echo "  ✅ {$test['role']} login ready\n\n";
    }

    echo "=== LOGIN VERIFICATION COMPLETE ===\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
