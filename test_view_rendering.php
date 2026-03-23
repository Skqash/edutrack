<?php
/**
 * Test what the view is actually rendering
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

echo "=== VIEW RENDERING TEST ===\n\n";

// Find a Victorias teacher
$teacher = User::where('role', 'teacher')
    ->where('campus', 'Victorias')
    ->first();

if (!$teacher) {
    echo "❌ No teacher found\n";
    exit(1);
}

echo "✓ Testing with teacher: {$teacher->name}\n\n";

// Simulate authentication
Auth::login($teacher);

// Get data exactly as controller does
$teacherId = $teacher->id;
$teacherCampus = $teacher->campus;
$teacherSchoolId = $teacher->school_id;

$assignedSubjects = Subject::with('course')
    ->where(function ($query) use ($teacherId, $teacherCampus, $teacherSchoolId) {
        $query->whereHas('teachers', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId)
              ->where('teacher_subject.status', 'active');
        })
        ->orWhere(function ($q) use ($teacherId) {
            $q->whereNull('program_id')
              ->whereHas('teachers', function ($subQ) use ($teacherId) {
                  $subQ->where('teacher_id', $teacherId);
              });
        });
    })
    ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
    ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
    ->orderBy('subject_name')
    ->get();

$courses = Course::query()
    ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
    ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
    ->orderBy('program_name')
    ->get();

echo "Data Available:\n";
echo "- Subjects: {$assignedSubjects->count()}\n";
echo "- Courses: {$courses->count()}\n\n";

// Try to render the view
try {
    $html = View::make('teacher.classes.create', compact('assignedSubjects', 'courses'))->render();
    
    // Check if select elements have options
    if (preg_match_all('/<option[^>]*value="(\d+)"[^>]*>([^<]+)<\/option>/', $html, $matches)) {
        echo "✅ Found " . count($matches[0]) . " option elements in rendered HTML\n\n";
        echo "Sample options:\n";
        for ($i = 0; $i < min(5, count($matches[2])); $i++) {
            echo "  • " . trim($matches[2][$i]) . "\n";
        }
    } else {
        echo "❌ NO option elements found in rendered HTML\n";
    }
    
    // Check for Select2
    if (strpos($html, 'select2') !== false) {
        echo "\n✅ Select2 classes found in HTML\n";
    } else {
        echo "\n❌ Select2 classes NOT found in HTML\n";
    }
    
    // Check for subject dropdown
    if (preg_match('/<select[^>]*id="subject_id"[^>]*>(.*?)<\/select>/s', $html, $match)) {
        $optionCount = substr_count($match[1], '<option');
        echo "✅ Subject dropdown found with {$optionCount} options\n";
    } else {
        echo "❌ Subject dropdown NOT found\n";
    }
    
    // Check for course dropdown
    if (preg_match('/<select[^>]*id="course_id"[^>]*>(.*?)<\/select>/s', $html, $match)) {
        $optionCount = substr_count($match[1], '<option');
        echo "✅ Course dropdown found with {$optionCount} options\n";
    } else {
        echo "❌ Course dropdown NOT found\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error rendering view: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
