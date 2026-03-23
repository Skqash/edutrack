<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== Courses in Database ===\n\n";

$courses = DB::table('courses')
    ->select('id', 'course_code', 'program_name', 'program_code', 'department_id')
    ->get();

foreach ($courses as $c) {
    echo $c->course_code.' | '.$c->program_code.' | '.$c->program_name.' | Dept ID: '.($c->department_id ?? 'NULL')."\n";
}

echo "\n=== Checking Department Assignments ===\n\n";

$assignments = DB::table('courses')
    ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
    ->select('courses.program_code', 'courses.program_name', 'departments.department_name')
    ->get();

foreach ($assignments as $a) {
    echo $a->program_code.' should map to: '.($a->department_name ?? 'NOT ASSIGNED')."\n";
}
