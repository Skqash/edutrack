<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\Course;

echo "=== DEBUG CREATE CLASS VIEW DATA ===\n\n";

// Find a teacher (preferably Victorias)
$teacher = User::where('role', 'teacher')
    ->where('campus', 'Victorias')
    ->whereNotNull('school_id')
    ->first();

if (!$teacher) {
    // Fallback to any teacher
    $teacher = User::where('role', 'teacher')
        ->whereNotNull('campus')
        ->whereNotNull('school_id')
        ->first();
}

if (!$teacher) {
    echo "❌ No teacher found\n";
    exit(1);
}

echo "Testing with teacher:\n";
echo "  Name: {$teacher->name}\n";
echo "  Campus: {$teacher->campus}\n";
echo "  School ID: {$teacher->school_id}\n\n";

// Simulate the controller logic
$teacherId = $teacher->id;
$teacherCampus = $teacher->campus;
$teacherSchoolId = $teacher->school_id;

echo "1. ASSIGNED SUBJECTS:\n";
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

echo "  Count: {$assignedSubjects->count()}\n";
if ($assignedSubjects->count() > 0) {
    echo "  Subjects:\n";
    foreach ($assignedSubjects->take(5) as $subject) {
        echo "    - [{$subject->id}] {$subject->subject_code} - {$subject->subject_name}\n";
    }
} else {
    echo "  ⚠ No subjects assigned to this teacher\n";
    echo "\n  Checking why:\n";
    
    // Check if there are any subjects for this campus
    $campusSubjects = Subject::where('campus', $teacherCampus)
        ->where('school_id', $teacherSchoolId)
        ->count();
    echo "    - Subjects in campus: {$campusSubjects}\n";
    
    // Check teacher_subject pivot
    $pivotRecords = \DB::table('teacher_subject')
        ->where('teacher_id', $teacherId)
        ->get();
    echo "    - Teacher-subject pivot records: {$pivotRecords->count()}\n";
    if ($pivotRecords->count() > 0) {
        foreach ($pivotRecords->take(3) as $pivot) {
            echo "      - Subject ID: {$pivot->subject_id}, Status: {$pivot->status}\n";
        }
    }
}

echo "\n2. COURSES:\n";
$courses = Course::query()
    ->when($teacherCampus, fn($q) => $q->where('campus', $teacherCampus))
    ->when($teacherSchoolId, fn($q) => $q->where('school_id', $teacherSchoolId))
    ->orderBy('program_name')
    ->get();

echo "  Count: {$courses->count()}\n";
if ($courses->count() > 0) {
    echo "  Courses:\n";
    foreach ($courses->take(10) as $course) {
        echo "    - [{$course->id}] {$course->program_code} - {$course->program_name}\n";
    }
} else {
    echo "  ⚠ No courses found for this campus\n";
    echo "\n  Checking why:\n";
    
    // Check all courses
    $allCourses = Course::count();
    echo "    - Total courses in database: {$allCourses}\n";
    
    // Check courses with this school_id
    $schoolCourses = Course::where('school_id', $teacherSchoolId)->count();
    echo "    - Courses with school_id {$teacherSchoolId}: {$schoolCourses}\n";
    
    // Check courses with this campus
    $campusCourses = Course::where('campus', $teacherCampus)->count();
    echo "    - Courses with campus '{$teacherCampus}': {$campusCourses}\n";
    
    // Sample courses to see their campus/school_id values
    echo "\n    Sample courses:\n";
    $sampleCourses = Course::take(5)->get(['id', 'program_code', 'program_name', 'campus', 'school_id']);
    foreach ($sampleCourses as $course) {
        echo "      - [{$course->id}] {$course->program_code}: campus='{$course->campus}', school_id={$course->school_id}\n";
    }
}

echo "\n3. VIEW RENDERING TEST:\n";
echo "  If this were the view, the dropdowns would show:\n\n";

echo "  SUBJECT DROPDOWN:\n";
echo "    <option value=\"\">-- Select Subject --</option>\n";
if ($assignedSubjects->count() > 0) {
    foreach ($assignedSubjects as $subject) {
        echo "    <option value=\"{$subject->id}\">{$subject->subject_code} - {$subject->subject_name}</option>\n";
    }
} else {
    echo "    (no options - empty dropdown)\n";
}
echo "    <option value=\"new-subject\">+ Create New Subject</option>\n";

echo "\n  COURSE DROPDOWN:\n";
echo "    <option value=\"\">-- Select Course --</option>\n";
if ($courses->count() > 0) {
    foreach ($courses as $course) {
        echo "    <option value=\"{$course->id}\">{$course->program_code} - {$course->program_name}</option>\n";
    }
} else {
    echo "    (no options - empty dropdown)\n";
}
echo "    <option value=\"new-course\">+ Create New Course</option>\n";

echo "\n=== DEBUG COMPLETE ===\n";
