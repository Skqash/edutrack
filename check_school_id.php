<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ALL TEACHER USERS AND THEIR COURSES ===\n";
$teachers = App\Models\User::where('role', 'teacher')->get();
foreach ($teachers as $teacher) {
    $classCount = App\Models\ClassModel::where('teacher_id', $teacher->id)->count();
    $courseIds = App\Models\ClassModel::where('teacher_id', $teacher->id)
        ->when($teacher->campus, fn($q) => $q->where('campus', $teacher->campus))
        ->distinct()->pluck('course_id');
    echo "ID:{$teacher->id} {$teacher->name} | campus:'{$teacher->campus}' | classes:{$classCount} | courseIds:[{$courseIds->implode(',')}]\n";
}

echo "\n=== COURSES TABLE ===\n";
$courses = App\Models\Course::all(['id', 'program_name', 'program_code', 'campus']);
foreach ($courses as $c) {
    echo "ID:{$c->id} {$c->program_name} ({$c->program_code}) campus:'{$c->campus}'\n";
}
