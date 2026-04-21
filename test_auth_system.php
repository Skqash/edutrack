<?php

use App\Models\User;
use App\Models\SuperAdmin;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;

try {
    echo "=== EduTrack Auth System Verification ===\n\n";

    // Check Users table
    $userCount = User::count();
    echo "✓ Users table: {$userCount} records\n";

    $superCount = User::where('role', 'super')->count();
    $adminCount = User::where('role', 'admin')->count();
    $teacherCount = User::where('role', 'teacher')->count();
    $studentCount = User::where('role', 'student')->count();

    echo "  - Super Admins: {$superCount}\n";
    echo "  - Admins: {$adminCount}\n";
    echo "  - Teachers: {$teacherCount}\n";
    echo "  - Students (in users): {$studentCount}\n\n";

    // Check legacy tables
    echo "Legacy Role Tables:\n";
    try {
        $superAdminCount = SuperAdmin::count();
        echo "✓ SuperAdmins table: {$superAdminCount} records\n";
    } catch (\Exception $e) {
        echo "✗ SuperAdmins table error\n";
    }

    try {
        $adminLegacyCount = Admin::count();
        echo "✓ Admins table: {$adminLegacyCount} records\n";
    } catch (\Exception $e) {
        echo "✗ Admins table error\n";
    }

    try {
        $teacherCount = Teacher::count();
        echo "✓ Teachers table: {$teacherCount} records\n";
    } catch (\Exception $e) {
        echo "✗ Teachers table error\n";
    }

    try {
        $studentCount = Student::count();
        echo "✓ Students table: {$studentCount} records\n";
    } catch (\Exception $e) {
        echo "✗ Students table error\n";
    }

    echo "\n=== Test Credentials ===\n";
    echo "Super Admin: super@cpsu.edu.ph / super123\n";
    echo "Admin: admin.main@cpsu.edu.ph / admin123\n";
    echo "Teacher: maria.santos@cpsu.edu.ph / teacher123\n";
    echo "Student: student1.victorias@cpsu.edu.ph / student123\n";

    echo "\n✅ Database verification complete!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
