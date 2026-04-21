<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;
use App\Models\Attendance;

echo "=== Adding Sample E-Signatures to Students ===\n\n";

// Sample signature (small 1x1 PNG in base64)
$sampleSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

$students = Student::all();
$updated = 0;

foreach ($students as $student) {
    $student->update([
        'e_signature' => $sampleSignature,
        'signature_date' => now(),
    ]);
    $updated++;
    echo "✓ Added signature for: {$student->first_name} {$student->last_name}\n";
}

echo "\n=== Complete ===\n";
echo "Updated {$updated} student(s) with sample signatures.\n";
echo "You can now test the attendance system!\n";
