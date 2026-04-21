<?php

use App\Models\User;

try {
    echo "=== ALL AVAILABLE LOGIN CREDENTIALS ===\n\n";

    $users = User::select('id', 'email', 'password', 'name', 'role', 'status')
        ->orderBy('role')
        ->orderBy('id')
        ->get();

    if ($users->isEmpty()) {
        echo "❌ No users found in database\n";
        exit;
    }

    echo "Total users: " . $users->count() . "\n\n";

    $byRole = $users->groupBy('role');

    foreach ($byRole as $role => $userGroup) {
        echo "--- " . strtoupper($role) . " (" . $userGroup->count() . ") ---\n";
        foreach ($userGroup as $user) {
            echo "  Email: {$user->email}\n";
            echo "  Name: {$user->name}\n";
            echo "  Password: (check database for hash)\n";
            echo "  Status: {$user->status}\n";
            echo "\n";
        }
    }

    echo "\n=== RECOMMENDED TEST CREDENTIALS ===\n";
    echo "Use these for login testing:\n\n";
    
    $testUsers = [
        'super' => ['email' => 'super@cpsu.edu.ph', 'password' => 'super123'],
        'admin' => ['email' => 'admin.main@cpsu.edu.ph', 'password' => 'admin123'],
        'teacher' => ['email' => 'maria.santos@cpsu.edu.ph', 'password' => 'teacher123'],
        'student' => ['email' => 'dela.cruz.main0001@cpsu.edu.ph', 'password' => 'student123'],
    ];

    foreach ($testUsers as $role => $creds) {
        echo ucfirst($role) . ":\n";
        echo "  Email: {$creds['email']}\n";
        echo "  Password: {$creds['password']}\n";
        echo "\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
