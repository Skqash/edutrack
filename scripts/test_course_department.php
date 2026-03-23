<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;

$course = Course::with('department.college')->first();
if (!$course) {
    echo "No course found\n";
    exit(1);
}

echo "Course id={$course->id}, program_name={$course->program_name}\n";
echo "department raw: "; var_export($course->getAttributes()); echo "\n";
try {
    echo "department via accessor: ".$course->department."\n";
} catch (\Throwable $e) {
    echo "ERROR: ".get_class($e)." - ".$e->getMessage()."\n";
}

echo "College via accessor: ".$course->college."\n";
