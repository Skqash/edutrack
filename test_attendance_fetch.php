<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Attendance;
use App\Models\ClassModel;

echo "===========================================\n";
echo "ATTENDANCE FETCH TEST\n";
echo "===========================================\n\n";

// Test parameters from the screenshot
$classId = 7; // BSIT 1-B Programming Fundamentals
$date = '2026-04-16';
$term = 'Midterm';

echo "Testing with:\n";
echo "Class ID: $classId\n";
echo "Date: $date\n";
echo "Term: $term\n\n";

// Check if class exists
$class = ClassModel::find($classId);
if (!$class) {
    echo "❌ Class not found\n";
    exit(1);
}

echo "✅ Class found: {$class->class_name}\n\n";

// Fetch attendance records
echo "Fetching attendance records...\n";
$records = Attendance::where('class_id', $classId)
    ->where('date', $date)
    ->where('term', $term)
    ->with('student')
    ->get();

echo "Found {$records->count()} records\n\n";

if ($records->count() > 0) {
    echo "Records:\n";
    echo "========================================\n";
    foreach ($records as $record) {
        $studentName = $record->student 
            ? $record->student->first_name . ' ' . $record->student->last_name 
            : 'Unknown';
        echo "Student: $studentName\n";
        echo "Status: {$record->status}\n";
        echo "Signature: " . ($record->e_signature ? 'Yes' : 'No') . "\n";
        echo "---\n";
    }
} else {
    echo "❌ No records found\n\n";
    
    // Check with different date formats
    echo "Checking with different date formats...\n";
    
    // Try with date only (no time)
    $records2 = Attendance::where('class_id', $classId)
        ->whereDate('date', $date)
        ->where('term', $term)
        ->count();
    echo "Using whereDate: $records2 records\n";
    
    // Check what dates exist for this class
    echo "\nDates in database for this class:\n";
    $dates = Attendance::where('class_id', $classId)
        ->select('date', 'term', \DB::raw('count(*) as count'))
        ->groupBy('date', 'term')
        ->orderBy('date', 'desc')
        ->take(10)
        ->get();
    
    foreach ($dates as $d) {
        echo "Date: {$d->date}, Term: {$d->term}, Count: {$d->count}\n";
    }
}

echo "\n===========================================\n";
echo "TEST COMPLETE\n";
echo "===========================================\n";
