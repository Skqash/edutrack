<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING ATTENDANCE E-SIGNATURES ===\n\n";

// Get all attendance records without e-signatures
$attendances = \App\Models\Attendance::whereNull('e_signature')
    ->orWhere('e_signature', '')
    ->get();

echo "Found {$attendances->count()} attendance records without e-signatures.\n\n";

$updated = 0;
$skipped = 0;

foreach ($attendances as $attendance) {
    $student = \App\Models\Student::find($attendance->student_id);
    
    if ($student && !empty($student->e_signature)) {
        // Update attendance record with student's permanent signature
        $attendance->update([
            'e_signature' => $student->e_signature,
            'signature_type' => 'e-signature',
            'signature_timestamp' => $student->signature_date ?? now(),
        ]);
        
        echo "✓ Updated attendance for Student {$student->id} ({$student->first_name} {$student->last_name})\n";
        $updated++;
    } else {
        $skipped++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Updated: {$updated} records\n";
echo "Skipped: {$skipped} records (no student signature available)\n";
echo "\nDone!\n";
?>
