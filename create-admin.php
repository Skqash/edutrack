<?php
/**
 * Create Admin Account Script
 * Run with: railway run php create-admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Creating admin account...\n\n";

try {
    // Create user
    $user = new App\Models\User();
    $user->name = 'Admin';
    $user->email = 'admin@edutrack.com';
    $user->password = Hash::make('Admin123!');
    $user->role = 'admin';
    $user->status = 'Active';
    $user->campus_status = 'approved';
    $user->save();
    
    echo "✓ User created: {$user->email}\n";
    
    // Create admin profile
    $admin = new App\Models\Admin();
    $admin->user_id = $user->id;
    $admin->employee_id = 'ADM-001';
    $admin->department = 'Administration';
    $admin->status = 'Active';
    $admin->save();
    
    echo "✓ Admin profile created\n\n";
    echo "=================================\n";
    echo "Admin Account Created Successfully!\n";
    echo "=================================\n";
    echo "Email: admin@edutrack.com\n";
    echo "Password: Admin123!\n";
    echo "=================================\n\n";
    echo "You can now login at your Railway URL!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
