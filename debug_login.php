<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    echo "=== LOGIN SYSTEM DEBUG ===\n\n";

    // Test 1: Check if the email exists
    $testEmail = 'student1.victorias@cpsu.edu.ph';
    echo "Test 1: Looking for email '{$testEmail}'\n";
    $user = User::where('email', $testEmail)->first();
    
    if ($user) {
        echo "  ✅ Found in users table\n";
        echo "  ID: {$user->id}\n";
        echo "  Name: {$user->name}\n";
        echo "  Role: {$user->role}\n";
    } else {
        echo "  ❌ NOT found in users table - THIS IS WHY LOGIN FAILS\n";
        echo "  → User must use a valid email from the database\n\n";
    }

    // Test 2: Try with a valid email
    echo "\nTest 2: Testing login with valid student email\n";
    $validStudent = 'dela.cruz.main0001@cpsu.edu.ph';
    $user = User::where('email', $validStudent)->first();
    
    if ($user) {
        echo "  ✅ User found\n";
        echo "  Email: {$user->email}\n";
        echo "  Name: {$user->name}\n";
        echo "  Role: {$user->role}\n";
        
        // Test password
        if (Hash::check('student123', $user->password)) {
            echo "  ✅ Password 'student123' is VALID\n";
        } else {
            echo "  ❌ Password 'student123' is INVALID\n";
        }
    }

    // Test 3: Show available emails
    echo "\n\nTest 3: Sample of available login emails:\n";
    $sampleUsers = User::limit(15)->get();
    foreach ($sampleUsers as $u) {
        echo "  - {$u->email} (role: {$u->role})\n";
    }

    echo "\n=== SOLUTION ===\n";
    echo "The user tried: student1.victorias@cpsu.edu.ph\n";
    echo "This email does NOT exist in the database.\n\n";
    echo "✅ CORRECT emails to use:\n";
    echo "  Super: super@cpsu.edu.ph (password: super123)\n";
    echo "  Admin: admin.main@cpsu.edu.ph (password: admin123)\n";
    echo "  Teacher: maria.santos@cpsu.edu.ph (password: teacher123)\n";
    echo "  Student: dela.cruz.main0001@cpsu.edu.ph (password: student123)\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
