<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking attendance e-signatures:\n";
$records = App\Models\Attendance::where('date', '2026-04-14')->get();
echo "Found: " . $records->count() . " records\n\n";

foreach($records->take(5) as $r) {
    echo "Student ID: " . $r->student_id . "\n";
    echo "  Has e_signature: " . (!empty($r->e_signature) ? "YES (" . strlen($r->e_signature) . " chars)" : "NO") . "\n";
    echo "  Signature type: " . ($r->signature_type ?? 'NULL') . "\n\n";
}
