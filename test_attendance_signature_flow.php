<?php

/**
 * Test Attendance E-Signature Flow
 * 
 * This script tests the complete flow of capturing and saving e-signatures
 * for attendance records.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Attendance E-Signature Flow Test ===\n\n";

// Test 1: Check database structure
echo "Test 1: Verifying database structure...\n";
$attendanceColumns = DB::select("SHOW COLUMNS FROM attendance");
$hasESignature = false;
$hasSignatureType = false;
$hasSignatureTimestamp = false;

foreach ($attendanceColumns as $column) {
    if ($column->Field === 'e_signature') $hasESignature = true;
    if ($column->Field === 'signature_type') $hasSignatureType = true;
    if ($column->Field === 'signature_timestamp') $hasSignatureTimestamp = true;
}

echo "  ✓ e_signature column: " . ($hasESignature ? "EXISTS" : "MISSING") . "\n";
echo "  ✓ signature_type column: " . ($hasSignatureType ? "EXISTS" : "MISSING") . "\n";
echo "  ✓ signature_timestamp column: " . ($hasSignatureTimestamp ? "EXISTS" : "MISSING") . "\n";

if (!$hasESignature || !$hasSignatureType || !$hasSignatureTimestamp) {
    echo "\n❌ ERROR: Required columns are missing. Run migrations:\n";
    echo "   php artisan migrate\n\n";
    exit(1);
}

// Test 2: Check model fillable
echo "\nTest 2: Verifying Attendance model configuration...\n";
$attendance = new Attendance();
$fillable = $attendance->getFillable();

$hasESignatureFillable = in_array('e_signature', $fillable);
$hasSignatureTypeFillable = in_array('signature_type', $fillable);
$hasSignatureTimestampFillable = in_array('signature_timestamp', $fillable);

echo "  ✓ e_signature fillable: " . ($hasESignatureFillable ? "YES" : "NO") . "\n";
echo "  ✓ signature_type fillable: " . ($hasSignatureTypeFillable ? "YES" : "NO") . "\n";
echo "  ✓ signature_timestamp fillable: " . ($hasSignatureTimestampFillable ? "YES" : "NO") . "\n";

if (!$hasESignatureFillable || !$hasSignatureTypeFillable || !$hasSignatureTimestampFillable) {
    echo "\n❌ ERROR: Required fields are not fillable in Attendance model\n\n";
    exit(1);
}

// Test 3: Find a test class and student
echo "\nTest 3: Finding test data...\n";
$teacher = User::where('role', 'teacher')->first();
if (!$teacher) {
    echo "  ⚠ No teacher found. Please create a teacher account first.\n";
    exit(1);
}
echo "  ✓ Teacher found: {$teacher->name} (ID: {$teacher->id})\n";

$class = ClassModel::where('teacher_id', $teacher->id)->first();
if (!$class) {
    echo "  ⚠ No class found for this teacher. Please create a class first.\n";
    exit(1);
}
echo "  ✓ Class found: {$class->class_name} (ID: {$class->id})\n";

$student = Student::where('class_id', $class->id)->first();
if (!$student) {
    echo "  ⚠ No students found in this class. Please add students first.\n";
    exit(1);
}
echo "  ✓ Student found: {$student->first_name} {$student->last_name} (ID: {$student->id})\n";

// Test 4: Create a test attendance record with e-signature
echo "\nTest 4: Creating test attendance record with e-signature...\n";

// Generate a small test signature (base64 encoded 1x1 PNG)
$testSignature = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

$testDate = now()->format('Y-m-d');
$testTerm = 'Midterm';

try {
    $attendance = Attendance::updateOrCreate(
        [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'date' => $testDate,
            'term' => $testTerm,
        ],
        [
            'status' => 'Present',
            'teacher_id' => $teacher->id,
            'campus' => $class->campus,
            'school_id' => $class->school_id,
            'e_signature' => $testSignature,
            'signature_type' => 'e-signature',
            'signature_timestamp' => now(),
        ]
    );

    echo "  ✓ Attendance record created/updated (ID: {$attendance->id})\n";
    echo "  ✓ Status: {$attendance->status}\n";
    echo "  ✓ Signature type: {$attendance->signature_type}\n";
    echo "  ✓ Signature length: " . strlen($attendance->e_signature) . " characters\n";
    echo "  ✓ Signature timestamp: {$attendance->signature_timestamp}\n";

} catch (\Exception $e) {
    echo "  ❌ ERROR creating attendance record: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 5: Verify the record can be retrieved
echo "\nTest 5: Retrieving attendance record...\n";
$retrieved = Attendance::where('student_id', $student->id)
    ->where('class_id', $class->id)
    ->where('date', $testDate)
    ->where('term', $testTerm)
    ->first();

if ($retrieved && $retrieved->e_signature) {
    echo "  ✓ Record retrieved successfully\n";
    echo "  ✓ E-signature present: YES\n";
    echo "  ✓ Signature length: " . strlen($retrieved->e_signature) . " characters\n";
} else {
    echo "  ❌ ERROR: Record not found or e-signature missing\n";
    exit(1);
}

// Test 6: Test the sheet view data
echo "\nTest 6: Testing attendance sheet data structure...\n";
$attendanceRecords = Attendance::where('class_id', $class->id)
    ->where('date', $testDate)
    ->where('term', $testTerm)
    ->get()
    ->keyBy('student_id');

if (isset($attendanceRecords[$student->id])) {
    $record = $attendanceRecords[$student->id];
    echo "  ✓ Record found in sheet data\n";
    echo "  ✓ Has e_signature: " . ($record->e_signature ? "YES" : "NO") . "\n";
    
    if ($record->e_signature) {
        echo "  ✓ Signature can be displayed in view\n";
    }
} else {
    echo "  ⚠ Record not found in sheet data structure\n";
}

echo "\n=== All Tests Completed Successfully! ===\n\n";

echo "Next Steps:\n";
echo "1. Open browser and navigate to: /teacher/attendance/manage/{$class->id}\n";
echo "2. Click the signature button (pen icon) for a student\n";
echo "3. Draw a signature in the modal\n";
echo "4. Click 'Save Signature' - you should see a checkmark on the button\n";
echo "5. Mark the student's attendance status (Present/Absent/Late)\n";
echo "6. Click 'Save' to submit the form\n";
echo "7. View the attendance sheet to verify signature appears\n";
echo "\nTest URL: /teacher/attendance/sheet/{$class->id}?date={$testDate}&term={$testTerm}\n\n";

echo "Debugging Tips:\n";
echo "- Open browser console (F12) to see JavaScript logs\n";
echo "- Look for 'SignaturePad initialized successfully' message\n";
echo "- Check for 'Signature captured' and 'Signature stored' messages\n";
echo "- Verify form submission shows signature count in console\n\n";
