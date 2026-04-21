<?php

use App\Models\Student;
use Illuminate\Support\Facades\DB;

try {
    echo "=== CREATING SAMPLE E-SIGNATURES FOR ALL STUDENTS ===\n\n";

    // Create a simple sample signature (base64 encoded placeholder)
    // This could be replaced with actual signature data later
    $sampleSignature = base64_encode('SAMPLE_DIGITAL_SIGNATURE_' . date('Y-m-d H:i:s'));
    
    echo "Sample signature created.\n";
    echo "Signature data length: " . strlen($sampleSignature) . " chars\n\n";

    // Get all students with user_id set
    $students = Student::whereNotNull('user_id')->get();
    
    if ($students->isEmpty()) {
        echo "❌ No students found with user_id\n";
        exit;
    }

    echo "Found {$students->count()} students to update\n\n";

    $updated = 0;
    $failed = 0;

    foreach ($students as $student) {
        try {
            // Update student with signature and signature date
            $student->update([
                'e_signature' => $sampleSignature,
                'signature_date' => now(),
            ]);
            
            $updated++;
            
            if ($updated % 5 === 0) {
                echo "  ✓ Updated $updated students...\n";
            }
        } catch (\Exception $e) {
            $failed++;
            echo "  ❌ Failed to update student {$student->student_id}: " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== RESULTS ===\n";
    echo "✅ Successfully updated: $updated students\n";
    if ($failed > 0) {
        echo "❌ Failed: $failed students\n";
    }

    // Verify
    echo "\nVerifying signatures...\n";
    $verified = Student::whereNotNull('e_signature')->where('e_signature', '<>', '')->count();
    echo "✓ Students with signatures: $verified\n";

    // Show sample students
    echo "\nSample updated students:\n";
    $samples = Student::whereNotNull('e_signature')->limit(3)->get();
    foreach ($samples as $sample) {
        $hasSignature = !empty($sample->e_signature);
        $status = $hasSignature ? '✅' : '❌';
        echo "  $status {$sample->student_id} ({$sample->first_name} {$sample->last_name})\n";
        echo "     Signature date: {$sample->signature_date}\n";
    }

    echo "\n=== E-SIGNATURE SETUP COMPLETE ===\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
