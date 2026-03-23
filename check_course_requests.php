<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== COURSE ACCESS REQUESTS TABLE ===\n";
$rows = DB::table('course_access_requests')->get();
if ($rows->isEmpty()) {
    echo "EMPTY — no records at all\n";
} else {
    foreach ($rows as $r) {
        echo "teacher_id:{$r->teacher_id} course_id:{$r->course_id} status:{$r->status}\n";
    }
}

echo "\n=== TEACHER CLASSES (direct assignment) ===\n";
$teachers = App\Models\User::where('role', 'teacher')->whereNotNull('campus')->where('campus', '!=', '')->get();
foreach ($teachers as $t) {
    $courseIds = App\Models\ClassModel::where('teacher_id', $t->id)->distinct()->pluck('course_id');
    if ($courseIds->isNotEmpty()) {
        echo "Teacher {$t->name} (ID:{$t->id}) has classes in courses: " . $courseIds->implode(', ') . "\n";
    }
}
