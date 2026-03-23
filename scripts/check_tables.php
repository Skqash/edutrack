<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Teacher;

$tables = DB::select("SHOW TABLES LIKE 'teachers'");
$teacherCount = Teacher::count();
$userTeacherCount = User::where('role', 'teacher')->count();

$columns = DB::select("SHOW COLUMNS FROM users LIKE 'employee_id'");

$sampleTeacher = User::where('role', 'teacher')->first();
$missingEmployeeCount = User::whereIn('role', ['teacher','admin'])->whereNull('employee_id')->count();

echo "Tables matching 'teachers':\n";
var_dump($tables);

echo "\nTeacher rows: $teacherCount\n";
echo "User role=teacher rows: $userTeacherCount\n";

if ($sampleTeacher) {
    echo "\nSample teacher user:\n";
    var_dump([
        'id' => $sampleTeacher->id,
        'name' => $sampleTeacher->name,
        'email' => $sampleTeacher->email,
        'employee_id' => $sampleTeacher->employee_id,
        'status' => $sampleTeacher->status,
        'specialization' => $sampleTeacher->specialization,
        'department' => $sampleTeacher->department,
        'campus' => $sampleTeacher->campus,
        'connected_school' => $sampleTeacher->connected_school,
        'bio' => $sampleTeacher->bio,
    ]);
}

echo "\nUsers missing employee_id (teacher/admin): $missingEmployeeCount\n";

echo "\nEmployees column exists in users table?\n";
var_dump($columns);
