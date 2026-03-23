<?php

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== COURSES TABLE STRUCTURE ===\n";
$columns = Schema::getColumns('courses');
foreach ($columns as $col) {
    echo "- {$col['name']} ({$col['type']})\n";
}

echo "\n=== COURSES TABLE DATA ===\n";
$data = DB::table('courses')->get();
foreach ($data as $row) {
    echo "ID: {$row->id}, Code: {$row->course_code}, Name: {$row->program_name}, DeptID: {$row->department_id}\n";
}

echo "\n=== COLUMNS TO DROP ===\n";
$allColumns = array_column($columns, 'name');
$keepColumns = ['id', 'course_code', 'program_code', 'program_name', 'department_id', 'program_head_id', 'total_years', 'description', 'status', 'created_at', 'updated_at'];
$dropColumns = array_diff($allColumns, $keepColumns);
echo "Columns to remove: " . implode(', ', $dropColumns) . "\n";
