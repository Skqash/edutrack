<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SCHOOLS IN DATABASE ===\n\n";

$schools = DB::table('schools')
    ->select('id', 'school_code', 'short_name', 'school_name')
    ->orderBy('short_name')
    ->get();

foreach ($schools as $school) {
    echo "ID: {$school->id}\n";
    echo "  Code: {$school->school_code}\n";
    echo "  Short: {$school->short_name}\n";
    echo "  Name: {$school->school_name}\n\n";
}

echo "Total schools: " . $schools->count() . "\n\n";

// Check users and their campuses
echo "=== USERS WITHOUT SCHOOL_ID ===\n\n";
$users = DB::table('users')
    ->whereNull('school_id')
    ->select('id', 'email', 'campus', 'role')
    ->get();

foreach ($users as $user) {
    echo "User ID: {$user->id}, Email: {$user->email}\n";
    echo "  Campus: {$user->campus}, Role: {$user->role}\n\n";
}
