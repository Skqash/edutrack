<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing teacher subjects...\n";

try {
    // Find the teacher
    $teacher = App\Models\User::where('email', 'teacher@example.com')->first();
    
    if (!$teacher) {
        echo "❌ Teacher not found\n";
        exit;
    }
    
    echo "✅ Teacher found: {$teacher->name}\n";
    
    // Test the subjects relationship
    $subjects = $teacher->subjects;
    echo "📚 Subjects count: {$subjects->count()}\n";
    
    if ($subjects->count() > 0) {
        echo "Subjects:\n";
        foreach ($subjects as $subject) {
            echo "- {$subject->subject_code}: {$subject->subject_name}\n";
        }
    }
    
    // Test the controller method logic
    $teacherId = $teacher->id;
    
    // Get subjects assigned to this teacher
    $assignedSubjects = App\Models\Subject::whereHas('teachers', function ($query) use ($teacherId) {
        $query->where('teacher_id', $teacherId)
              ->where('teacher_subject.status', 'active');
    })->with(['program', 'teachers'])->get();
    
    echo "📋 Assigned subjects count: {$assignedSubjects->count()}\n";
    
    // Get courses from classes
    $courseIds = App\Models\ClassModel::where('teacher_id', $teacherId)
        ->distinct()
        ->pluck('course_id');
    
    echo "🏫 Course IDs: " . $courseIds->implode(', ') . "\n";
    
    $courses = App\Models\Course::whereIn('id', $courseIds)
        ->orderBy('program_name')
        ->get();
    
    echo "📖 Courses count: {$courses->count()}\n";
    
    if ($courses->count() > 0) {
        echo "Courses:\n";
        foreach ($courses as $course) {
            echo "- {$course->program_code}: {$course->program_name}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}