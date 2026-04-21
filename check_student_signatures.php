<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking student e-signatures:\n\n";

$students = \App\Models\Student::whereIn('id', [1, 9, 10, 13, 19])->get();

foreach ($students as $student) {
    echo "Student ID: {$student->id} - {$student->first_name} {$student->last_name}\n";
    echo "  Has e_signature: " . (!empty($student->e_signature) ? "YES (" . strlen($student->e_signature) . " chars)" : "NO") . "\n";
    echo "  Signature date: " . ($student->signature_date ?? 'N/A') . "\n\n";
}
?>
