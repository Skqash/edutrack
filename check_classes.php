<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ClassModel;

$classes = ClassModel::withCount('students')->get();

echo "Classes with student counts:\n";
echo "============================\n";

foreach ($classes as $class) {
    echo "Class {$class->id}: {$class->class_name} - {$class->students_count} students\n";
}
