<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Start the application
$app->make('Illuminate\Contracts\Http\Kernel');

// Check student table
$pdo = new PDO('mysql:host=127.0.0.1;dbname=edutrack_db', 'root', '');

echo "=== Checking Students Table Structure ===\n";
$result = $pdo->query("DESCRIBE students");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);

foreach ($columns as $col) {
    echo $col['Field'] . " (" . $col['Type'] . ")\n";
}

echo "\n=== Total Students in Database ===\n";
$result = $pdo->query("SELECT COUNT(*) as count FROM students");
$count = $result->fetch(PDO::FETCH_ASSOC);
echo "Total: " . $count['count'] . " students\n";

echo "\n=== Sample Student Record ===\n";
$result = $pdo->query("SELECT student_id, first_name, last_name, email, password, e_signature FROM students LIMIT 1");
$student = $result->fetch(PDO::FETCH_ASSOC);

if ($student) {
    echo "Student ID: " . $student['student_id'] . "\n";
    echo "Name: " . $student['first_name'] . " " . $student['last_name'] . "\n";
    echo "Email: " . $student['email'] . "\n";
    echo "Has Password: " . (!empty($student['password']) ? "Yes" : "No") . "\n";
    echo "Has E-Signature: " . (!empty($student['e_signature']) ? "Yes" : "No") . "\n";
} else {
    echo "No students found\n";
}

echo "\n=== Checking Attendance Table E-Signature Column ===\n";
$result = $pdo->query("DESCRIBE attendance");
$columns = $result->fetchAll(PDO::FETCH_ASSOC);
$hasESignature = false;
$hasSignatureType = false;

foreach ($columns as $col) {
    if ($col['Field'] === 'e_signature') $hasESignature = true;
    if ($col['Field'] === 'signature_type') $hasSignatureType = true;
}

echo "Has e_signature column: " . ($hasESignature ? "Yes" : "No") . "\n";
echo "Has signature_type column: " . ($hasSignatureType ? "Yes" : "No") . "\n";

echo "\n✓ Database check complete!\n";
