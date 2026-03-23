<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== Detailed Course Information ===\n\n";

$courses = DB::table('courses')
    ->select('*')
    ->get();

foreach ($courses as $c) {
    echo "Course ID: {$c->id}\n";
    echo "  Code: {$c->course_code} | Name: {$c->program_name}\n";
    echo "  Program Code: {$c->program_code} | Program Name: {$c->program_name}\n";
    echo '  Department ID: '.($c->department_id ?? 'NULL')."\n";
    echo '  Program Head ID: '.($c->program_head_id ?? 'NULL')."\n";
    echo '  Total Years: '.($c->total_years ?? 'NULL')."\n";
    echo "\n";
}

// Also check the departments table to make sure departments exist
echo "=== Departments ===\n\n";
$departments = DB::table('departments')->get();
foreach ($departments as $d) {
    echo "ID: {$d->id} | Name: {$d->department_name} | College ID: {$d->college_id}\n";
}
