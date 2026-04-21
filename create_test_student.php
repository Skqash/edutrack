<?php
require __DIR__ . '/vendor/autoload.php';

// Start Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel');

// Create test student
$pdo = new PDO('mysql:host=127.0.0.1;dbname=edutrack_db', 'root', '');

// Hash password for test student
$testPassword = hash('sha256', 'TestStudent@123');
$hashedPassword = password_hash('TestStudent@123', PASSWORD_BCRYPT);

// Check if test student exists
$result = $pdo->query("SELECT * FROM students WHERE email = 'testStudent@cpsu.edu.ph'");
$testStudent = $result->fetch(PDO::FETCH_ASSOC);

if ($testStudent) {
    echo "Test student already exists. Updating password...\n";
    $stmt = $pdo->prepare("UPDATE students SET password = ? WHERE email = 'testStudent@cpsu.edu.ph'");
    $stmt->execute([$hashedPassword]);
    echo "✓ Test student password updated\n";
} else {
    echo "Creating test student...\n";
    $stmt = $pdo->prepare("
        INSERT INTO students (
            student_id, first_name, last_name, email, password, 
            status, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        'TEST-2026-0001',
        'Test',
        'Student',
        'testStudent@cpsu.edu.ph',
        $hashedPassword,
        'Active',
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    echo "✓ Test student created\n";
}

echo "\n=== Test Credentials ===\n";
echo "Email: testStudent@cpsu.edu.ph\n";
echo "Password: TestStudent@123\n";
echo "\nYou can now use these credentials to test the student login.\n";
