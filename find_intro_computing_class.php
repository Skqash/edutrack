<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ClassModel;
use App\Models\Student;

echo "=== Finding 'Intro to Computing' Class ===\n\n";

$classes = ClassModel::with('subject', 'course')->get();

foreach ($classes as $class) {
    $subjectName = $class->subject->subject_name ?? 'N/A';
    if (stripos($subjectName, 'Intro') !== false || stripos($subjectName, 'Computing') !== false) {
        echo "Found Class:\n";
        echo "  ID: {$class->id}\n";
        echo "  Name: {$class->class_name}\n";
        echo "  Subject: {$subjectName}\n";
        echo "  Campus: " . ($class->campus ?? 'NULL') . "\n";
        echo "  Course ID: " . ($class->course_id ?? 'NULL') . "\n";
        echo "  Course: " . ($class->course->program_name ?? 'N/A') . "\n\n";
        
        // Check students with both queries
        echo "  Students (WITHOUT campus filter):\n";
        $students1 = Student::query()
            ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name')
            ->get();
        echo "    Count: {$students1->count()}\n";
        foreach ($students1 as $s) {
            echo "      - {$s->first_name} {$s->last_name} (Campus: " . ($s->campus ?? 'NULL') . ")\n";
        }
        
        echo "\n  Students (WITH campus filter):\n";
        $students2 = Student::query()
            ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
            ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
            ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
            ->orderBy('last_name')->orderBy('first_name')
            ->get();
        echo "    Count: {$students2->count()}\n";
        foreach ($students2 as $s) {
            echo "      - {$s->first_name} {$s->last_name} (Campus: " . ($s->campus ?? 'NULL') . ")\n";
        }
        
        echo "\n" . str_repeat("=", 60) . "\n\n";
    }
}
