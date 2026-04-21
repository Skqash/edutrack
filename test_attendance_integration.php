<?php

/**
 * Test Script: Attendance Integration with Grade Calculation
 * 
 * This script tests that attendance is properly integrated into final grade calculations
 * based on the KSA settings (enabled, weight, target category).
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\KsaSetting;
use App\Models\Attendance;
use App\Services\DynamicGradeCalculationService;
use App\Services\AttendanceCalculationService;

echo "===========================================\n";
echo "ATTENDANCE INTEGRATION TEST\n";
echo "===========================================\n\n";

// Get a test class with students
$class = ClassModel::with('students')->find(6); // Class with students

if (!$class) {
    echo "❌ No classes found in database\n";
    exit(1);
}

echo "📚 Testing Class: {$class->class_name} (ID: {$class->id})\n";
echo "👨‍🏫 Teacher ID: {$class->teacher_id}\n\n";

// Get or create KSA settings
$term = 'midterm';
$ksaSettings = KsaSetting::where('class_id', $class->id)
    ->where('term', $term)
    ->first();

if (!$ksaSettings) {
    echo "⚠️  No KSA settings found, creating default...\n";
    $ksaSettings = KsaSetting::create([
        'class_id' => $class->id,
        'teacher_id' => $class->teacher_id,
        'term' => $term,
        'knowledge_weight' => 40,
        'skills_weight' => 50,
        'attitude_weight' => 10,
        'total_meetings' => 18,
        'attendance_weight' => 10,
        'attendance_category' => 'skills',
        'enable_attendance_ksa' => true,
        'passing_grade' => 75,
    ]);
}

echo "\n📊 KSA Settings:\n";
echo "   - Knowledge Weight: {$ksaSettings->knowledge_weight}%\n";
echo "   - Skills Weight: {$ksaSettings->skills_weight}%\n";
echo "   - Attitude Weight: {$ksaSettings->attitude_weight}%\n";
echo "   - Attendance Enabled: " . ($ksaSettings->enable_attendance_ksa ? 'YES' : 'NO') . "\n";
echo "   - Attendance Weight: {$ksaSettings->attendance_weight}%\n";
echo "   - Attendance Category: " . ucfirst($ksaSettings->attendance_category) . "\n";
echo "   - Total Meetings: {$ksaSettings->total_meetings}\n\n";

// Get first student
$student = $class->students->first();

if (!$student) {
    echo "❌ No students found in class\n";
    exit(1);
}

echo "👤 Testing Student: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";
echo "   Student Number: {$student->student_id}\n\n";

// Calculate attendance score
$attendanceService = new AttendanceCalculationService();
$attendanceData = $attendanceService->calculateAttendanceScore($student->id, $class->id, $term);

echo "📅 Attendance Data:\n";
echo "   - Present: {$attendanceData['present_count']}\n";
echo "   - Late: {$attendanceData['late_count']}\n";
echo "   - Absent: {$attendanceData['absent_count']}\n";
echo "   - Leave: {$attendanceData['leave_count']}\n";
echo "   - Total Recorded: {$attendanceData['total_recorded']}\n";
echo "   - Total Meetings: {$attendanceData['total_meetings']}\n";
echo "   - Attendance Count: {$attendanceData['attendance_count']}\n";
echo "   - Attendance Percentage: {$attendanceData['attendance_percentage']}%\n";
echo "   - Attendance Score (x50+50): {$attendanceData['attendance_score']}\n\n";

// Calculate grades WITHOUT attendance (simulate disabled)
echo "🧮 Calculating Grades...\n\n";

// Test 1: With attendance enabled
echo "TEST 1: Attendance ENABLED (affects {$ksaSettings->attendance_category})\n";
echo "-----------------------------------------------------------\n";
$gradesWithAttendance = DynamicGradeCalculationService::calculateCategoryAverages(
    $student->id,
    $class->id,
    $term
);

echo "   Knowledge Average: {$gradesWithAttendance['knowledge_average']}\n";
echo "   Skills Average: {$gradesWithAttendance['skills_average']}\n";
echo "   Attitude Average: {$gradesWithAttendance['attitude_average']}\n";
echo "   Attendance Score: {$gradesWithAttendance['attendance_score']}\n";
echo "   Attendance Applied: " . ($gradesWithAttendance['attendance_applied'] ? 'YES' : 'NO') . "\n";
echo "   Final Grade: {$gradesWithAttendance['final_grade']}\n\n";

// Test 2: Disable attendance and recalculate
echo "TEST 2: Attendance DISABLED\n";
echo "-----------------------------------------------------------\n";
$ksaSettings->enable_attendance_ksa = false;
$ksaSettings->save();

$gradesWithoutAttendance = DynamicGradeCalculationService::calculateCategoryAverages(
    $student->id,
    $class->id,
    $term
);

echo "   Knowledge Average: {$gradesWithoutAttendance['knowledge_average']}\n";
echo "   Skills Average: {$gradesWithoutAttendance['skills_average']}\n";
echo "   Attitude Average: {$gradesWithoutAttendance['attitude_average']}\n";
echo "   Attendance Score: {$gradesWithoutAttendance['attendance_score']}\n";
echo "   Attendance Applied: " . ($gradesWithoutAttendance['attendance_applied'] ? 'YES' : 'NO') . "\n";
echo "   Final Grade: {$gradesWithoutAttendance['final_grade']}\n\n";

// Re-enable attendance
$ksaSettings->enable_attendance_ksa = true;
$ksaSettings->save();

// Test 3: Test different target categories
echo "TEST 3: Attendance affecting KNOWLEDGE category\n";
echo "-----------------------------------------------------------\n";
$ksaSettings->attendance_category = 'knowledge';
$ksaSettings->save();

$gradesKnowledge = DynamicGradeCalculationService::calculateCategoryAverages(
    $student->id,
    $class->id,
    $term
);

echo "   Knowledge Average: {$gradesKnowledge['knowledge_average']}\n";
echo "   Skills Average: {$gradesKnowledge['skills_average']}\n";
echo "   Attitude Average: {$gradesKnowledge['attitude_average']}\n";
echo "   Final Grade: {$gradesKnowledge['final_grade']}\n\n";

echo "TEST 4: Attendance affecting ATTITUDE category\n";
echo "-----------------------------------------------------------\n";
$ksaSettings->attendance_category = 'attitude';
$ksaSettings->save();

$gradesAttitude = DynamicGradeCalculationService::calculateCategoryAverages(
    $student->id,
    $class->id,
    $term
);

echo "   Knowledge Average: {$gradesAttitude['knowledge_average']}\n";
echo "   Skills Average: {$gradesAttitude['skills_average']}\n";
echo "   Attitude Average: {$gradesAttitude['attitude_average']}\n";
echo "   Final Grade: {$gradesAttitude['final_grade']}\n\n";

// Reset to original settings
$ksaSettings->attendance_category = 'skills';
$ksaSettings->save();

// Summary
echo "===========================================\n";
echo "SUMMARY\n";
echo "===========================================\n\n";

$difference = $gradesWithAttendance['final_grade'] - $gradesWithoutAttendance['final_grade'];
$percentDiff = $gradesWithoutAttendance['final_grade'] > 0 
    ? ($difference / $gradesWithoutAttendance['final_grade']) * 100 
    : 0;

echo "✅ Attendance Integration Status: " . ($gradesWithAttendance['attendance_applied'] ? 'WORKING' : 'NOT APPLIED') . "\n";
echo "📊 Grade Difference (With vs Without Attendance): " . number_format($difference, 2) . " (" . number_format($percentDiff, 2) . "%)\n";
echo "🎯 Attendance Weight: {$ksaSettings->attendance_weight}%\n";
echo "📍 Target Category: " . ucfirst($ksaSettings->attendance_category) . "\n\n";

if ($gradesWithAttendance['attendance_applied']) {
    echo "✅ SUCCESS: Attendance is properly integrated into grade calculation!\n";
    echo "   - Attendance score ({$attendanceData['attendance_score']}) is applied to {$ksaSettings->attendance_category} category\n";
    echo "   - Final grade reflects attendance impact\n";
} else {
    echo "❌ ISSUE: Attendance is not being applied to grades\n";
    echo "   - Check if attendance is enabled in settings\n";
    echo "   - Verify attendance data exists for this student\n";
}

echo "\n===========================================\n";
echo "TEST COMPLETE\n";
echo "===========================================\n";
