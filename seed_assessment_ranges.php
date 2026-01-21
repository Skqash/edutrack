<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$classes = \App\Models\ClassModel::all();
$count = 0;

foreach ($classes as $class) {
    if ($class->subject_id) {
        // Use the teacher_id from the class if available, otherwise use the first teacher
        $teacherId = $class->teacher_id ?? 1;
        
        $exists = \App\Models\AssessmentRange::where('class_id', $class->id)
            ->where('subject_id', $class->subject_id)
            ->where('teacher_id', $teacherId)
            ->exists();
        
        if (!$exists) {
            \App\Models\AssessmentRange::create([
                'class_id' => $class->id,
                'subject_id' => $class->subject_id,
                'teacher_id' => $teacherId,
            ]);
        }
        $count++;
    }
}

echo "✓ Assessment ranges created/verified for $count classes\n";
