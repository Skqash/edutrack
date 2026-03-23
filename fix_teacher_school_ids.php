<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIXING TEACHER SCHOOL IDS ===\n\n";

// Update teachers.school_id from users.school_id
$updated = DB::statement('
    UPDATE teachers t
    INNER JOIN users u ON t.user_id = u.id
    SET t.school_id = u.school_id
    WHERE t.school_id IS NULL AND u.school_id IS NOT NULL
');

echo "Updated teachers with school_id from users table\n";

// Verify
$teachers = DB::table('teachers')
    ->join('users', 'teachers.user_id', '=', 'users.id')
    ->select('teachers.*', 'users.email', 'users.campus as user_campus', 'users.school_id as user_school_id')
    ->get();

echo "\nTeachers after update:\n";
foreach ($teachers as $teacher) {
    echo "  ID: {$teacher->id}, Email: {$teacher->email}\n";
    echo "    Teacher campus: {$teacher->campus}, school_id: {$teacher->school_id}\n";
    echo "    User campus: {$teacher->user_campus}, school_id: {$teacher->user_school_id}\n\n";
}

echo "=== COMPLETE ===\n";
