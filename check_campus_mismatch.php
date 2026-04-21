<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ClassModel;
use App\Models\Student;

echo "=== Checking Campus Mismatch Issue ===\n\n";

$classId = 4; // From the URL in the screenshot
$class = ClassModel::find($classId);

if (!$class) {
    echo "Class not found!\n";
    exit(1);
}

echo "Class Information:\n";
echo "  Name: {$class->class_name}\n";
echo "  Campus: " . ($class->campus ?? 'NULL') . "\n";
echo "  Course ID: " . ($class->course_id ?? 'NULL') . "\n";
echo "  School ID: " . ($class->school_id ?? 'NULL') . "\n\n";

// Query 1: manageAttendance logic (WITHOUT campus filter)
echo "Query 1: manageAttendance (WITHOUT campus filter)\n";
$students1 = Student::query()
    ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
    ->orderBy('last_name')->orderBy('first_name')
    ->get();

echo "  Found {$students1->count()} students:\n";
foreach ($students1 as $s) {
    echo "    - {$s->first_name} {$s->last_name} (ID: {$s->id}) - Campus: " . ($s->campus ?? 'NULL') . "\n";
}

// Query 2: attendanceSheet logic (WITH campus filter)
echo "\nQuery 2: attendanceSheet (WITH campus filter)\n";
$students2 = Student::query()
    ->when($class->course_id, fn($q) => $q->where('course_id', $class->course_id))
    ->when($class->campus, fn($q) => $q->where('campus', $class->campus))
    ->when($class->school_id, fn($q) => $q->where('school_id', $class->school_id))
    ->orderBy('last_name')->orderBy('first_name')
    ->get();

echo "  Found {$students2->count()} students:\n";
foreach ($students2 as $s) {
    echo "    - {$s->first_name} {$s->last_name} (ID: {$s->id}) - Campus: " . ($s->campus ?? 'NULL') . "\n";
}

echo "\n=== Analysis ===\n";
echo "Students in manageAttendance but NOT in attendanceSheet:\n";
$missing = $students1->diff($students2);
if ($missing->count() > 0) {
    foreach ($missing as $s) {
        echo "  - {$s->first_name} {$s->last_name} (ID: {$s->id}) - Campus: " . ($s->campus ?? 'NULL') . "\n";
        echo "    Reason: Student campus '" . ($s->campus ?? 'NULL') . "' doesn't match class campus '" . ($class->campus ?? 'NULL') . "'\n";
    }
} else {
    echo "  None - Both queries return the same students\n";
}

echo "\n=== Solution ===\n";
echo "The attendanceSheet method should use the SAME query as manageAttendance\n";
echo "to ensure consistency. Remove the campus filter from attendanceSheet.\n\n";
