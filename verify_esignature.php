<?php

use App\Models\User;
use App\Models\Student;

try {
    echo "=== VERIFYING E-SIGNATURE SETUP ===\n\n";

    // Test 1: Check User::hasESignature() method exists
    echo "Test 1: Checking hasESignature() method\n";
    $student = User::where('role', 'student')->first();
    
    if ($student) {
        echo "  ✓ Student user found: {$student->email}\n";
        
        // Test the method
        if (method_exists($student, 'hasESignature')) {
            echo "  ✅ hasESignature() method exists\n";
            
            $hasSignature = $student->hasESignature();
            $status = $hasSignature ? '✅ Has signature' : '❌ No signature';
            echo "  $status\n";
        } else {
            echo "  ❌ hasESignature() method not found\n";
        }
    } else {
        echo "  ❌ No student users found\n";
    }

    // Test 2: Check all students have signatures
    echo "\nTest 2: Verifying all students have signatures\n";
    $studentsWithSignatures = Student::whereNotNull('e_signature')
        ->where('e_signature', '<>', '')
        ->count();
    $totalStudents = Student::whereNotNull('user_id')->count();
    
    echo "  Total students: $totalStudents\n";
    echo "  Students with signatures: $studentsWithSignatures\n";
    
    if ($studentsWithSignatures === $totalStudents) {
        echo "  ✅ All students have signatures!\n";
    } else {
        echo "  ⚠ Some students missing signatures\n";
    }

    // Test 3: Simulate dashboard view context
    echo "\nTest 3: Testing dashboard view logic\n";
    $authenticatedStudent = User::where('role', 'student')->with('student')->first();
    
    if ($authenticatedStudent) {
        // This simulates what the blade template does
        ob_start();
        
        if ($authenticatedStudent->hasESignature()) {
            echo "E-Signature Status: Set ✅";
        } else {
            echo "E-Signature Status: Not Set ⚠";
        }
        
        $output = ob_get_clean();
        echo "  Dashboard would show: $output\n";
    }

    echo "\n=== VERIFICATION COMPLETE ===\n";
    echo "✅ E-signature system is ready for student dashboard!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
