<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ASSIGNING SCHOOL IDS TO USERS ===\n\n";

// Campus to school_code mapping
$campusMapping = [
    'Kabankalan' => 'CPSU-MAIN',
    'Victorias' => 'CPSU-VIC',
    'Sipalay' => 'CPSU-SIP',
    'Cauayan' => 'CPSU-CAU',
    'Candoni' => 'CPSU-CAN',
    'Hinoba-an' => 'CPSU-HIN',
    'Ilog' => 'CPSU-ILO',
    'Hinigaran' => 'CPSU-HIG',
    'Moises Padilla' => 'CPSU-MP',
    'San Carlos' => 'CPSU-SC',
];

// Get all schools
$schools = DB::table('schools')->get()->keyBy('school_code');

// Update users
$updated = 0;
foreach ($campusMapping as $campus => $schoolCode) {
    if (isset($schools[$schoolCode])) {
        $schoolId = $schools[$schoolCode]->id;
        
        $count = DB::table('users')
            ->where('campus', $campus)
            ->whereNull('school_id')
            ->update(['school_id' => $schoolId]);
        
        if ($count > 0) {
            echo "Updated {$count} users with campus '{$campus}' to school_id {$schoolId} ({$schoolCode})\n";
            $updated += $count;
        }
    }
}

echo "\nTotal users updated: {$updated}\n\n";

// Now update teachers from users
echo "=== UPDATING TEACHERS FROM USERS ===\n\n";

$teacherUpdated = DB::statement('
    UPDATE teachers t
    INNER JOIN users u ON t.user_id = u.id
    SET t.school_id = u.school_id, t.campus = u.campus
    WHERE (t.school_id IS NULL OR t.campus IS NULL OR t.campus != u.campus)
    AND u.school_id IS NOT NULL
');

echo "Updated teachers table from users\n\n";

// Verify
echo "=== VERIFICATION ===\n\n";

$users = DB::table('users')
    ->whereNotNull('campus')
    ->select('id', 'email', 'campus', 'school_id', 'role')
    ->orderBy('campus')
    ->get();

foreach ($users as $user) {
    $school = isset($user->school_id) ? DB::table('schools')->find($user->school_id) : null;
    $schoolName = $school ? $school->short_name : 'NULL';
    echo "User: {$user->email} ({$user->role})\n";
    echo "  Campus: {$user->campus}, School ID: {$user->school_id} ({$schoolName})\n\n";
}

echo "=== COMPLETE ===\n";
