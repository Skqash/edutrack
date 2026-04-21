<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Attendance;
use App\Models\ClassModel;

echo "===========================================\n";
echo "ATTENDANCE DATABASE CHECK\n";
echo "===========================================\n\n";

// Get total attendance records
$totalRecords = Attendance::count();
echo "📊 Total Attendance Records: $totalRecords\n\n";

if ($totalRecords > 0) {
    // Get recent records
    $recentRecords = Attendance::with(['student', 'class'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    echo "📅 Recent Attendance Records (Last 10):\n";
    echo "========================================\n";
    
    foreach ($recentRecords as $record) {
        $studentName = $record->student 
            ? $record->student->first_name . ' ' . $record->student->last_name 
            : 'Unknown';
        $className = $record->class ? $record->class->class_name : 'Unknown';
        
        echo "Date: {$record->date}\n";
        echo "Student: $studentName (ID: {$record->student_id})\n";
        echo "Class: $className (ID: {$record->class_id})\n";
        echo "Status: {$record->status}\n";
        echo "Term: {$record->term}\n";
        echo "Signature: " . ($record->e_signature ? 'Yes' : 'No') . "\n";
        echo "Created: {$record->created_at}\n";
        echo "---\n";
    }
    
    // Group by class
    echo "\n📚 Attendance by Class:\n";
    echo "========================================\n";
    
    $byClass = Attendance::select('class_id', \DB::raw('count(*) as total'))
        ->groupBy('class_id')
        ->with('class')
        ->get();
    
    foreach ($byClass as $classData) {
        $className = $classData->class ? $classData->class->class_name : 'Unknown';
        echo "Class: $className - {$classData->total} records\n";
    }
    
    // Group by date
    echo "\n📅 Attendance by Date:\n";
    echo "========================================\n";
    
    $byDate = Attendance::select('date', \DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->take(10)
        ->get();
    
    foreach ($byDate as $dateData) {
        echo "Date: {$dateData->date} - {$dateData->total} records\n";
    }
    
} else {
    echo "❌ No attendance records found in database\n";
    echo "\nPossible reasons:\n";
    echo "1. Attendance has not been taken yet\n";
    echo "2. Attendance is not being saved properly\n";
    echo "3. Database connection issue\n";
}

echo "\n===========================================\n";
echo "CHECK COMPLETE\n";
echo "===========================================\n";
