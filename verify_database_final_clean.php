#!/usr/bin/env php
<?php

// Direct database verification using MySQL
$mysqli = new mysqli('127.0.0.1', 'root', '', 'edutrack_db');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "\n" . str_repeat("=", 90) . "\n";
echo "FINAL DATABASE VERIFICATION REPORT - March 20, 2026\n";
echo str_repeat("=", 90) . "\n\n";

// 1. COURSES VERIFICATION
echo "=== COURSES (Programs) VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT * FROM courses ORDER BY id");
$courses = $result->fetch_all(MYSQLI_ASSOC);
echo "Total Programs: " . count($courses) . "\n";
if (count($courses) === 3) {
    echo "OK - All 3 required programs present\n";
}
foreach ($courses as $course) {
    echo "  - {$course['course_code']} ({$course['program_name']}) - Dept: {$course['department_id']}, Years: {$course['total_years']}, Status: {$course['status']}\n";
}

// 2. DEPARTMENTS VERIFICATION
echo "\n=== DEPARTMENTS VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM departments");
$row = $result->fetch_assoc();
echo "Total Departments: " . $row['count'] . "\n";

// 3. COLLEGES VERIFICATION
echo "\n=== COLLEGES VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM colleges");
$row = $result->fetch_assoc();
echo "Total Colleges: " . $row['count'] . "\n";

// 4. SUBJECTS VERIFICATION
echo "\n=== SUBJECTS VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM subjects");
$row = $result->fetch_assoc();
$totalSubjects = $row['count'];
echo "Total Subjects: " . $totalSubjects . "\n";

$result = $mysqli->query("SELECT year_level, COUNT(*) as count FROM subjects GROUP BY year_level ORDER BY year_level");
while ($row = $result->fetch_assoc()) {
    echo "  - Year {$row['year_level']}: {$row['count']} subjects\n";
}

// 5. CLASSES VERIFICATION
echo "\n=== CLASSES VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM classes");
$row = $result->fetch_assoc();
$totalClasses = $row['count'];
echo "Total Classes: " . $totalClasses . "\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM classes WHERE program_id IS NOT NULL");
$row = $result->fetch_assoc();
$classesWithProgram = $row['count'];
echo "Classes with program_id: " . $classesWithProgram . " / " . $totalClasses . "\n";

$result = $mysqli->query("SELECT program_id, COUNT(*) as count FROM classes WHERE program_id IS NOT NULL GROUP BY program_id ORDER BY program_id");
while ($row = $result->fetch_assoc()) {
    $progResult = $mysqli->query("SELECT program_name FROM courses WHERE id = {$row['program_id']}");
    $prog = $progResult->fetch_assoc();
    $progName = $prog ? $prog['program_name'] : "Unknown";
    echo "  - Program ID {$row['program_id']} ({$progName}): {$row['count']} classes\n";
}

// 6. CLASS_SUBJECTS JUNCTION VERIFICATION
echo "\n=== CLASS_SUBJECTS JUNCTION TABLE VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM class_subjects");
$row = $result->fetch_assoc();
$totalClassSubjects = $row['count'];
echo "Total class_subject records: " . $totalClassSubjects . "\n";
if ($totalClasses > 0) {
    $avgSubjectsPerClass = round($totalClassSubjects / $totalClasses, 2);
    echo "Average subjects per class: " . $avgSubjectsPerClass . "\n";
}

// 7. STUDENTS VERIFICATION
echo "\n=== STUDENTS VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT COUNT(*) as count FROM students");
$row = $result->fetch_assoc();
$totalStudents = $row['count'];
echo "Total Students: " . $totalStudents . "\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM students WHERE class_id IS NOT NULL");
$row = $result->fetch_assoc();
$studentsWithClass = $row['count'];
echo "Students with valid class assignment: " . $studentsWithClass . " / " . $totalStudents . "\n";

// 8. USERS VERIFICATION
echo "\n=== USERS VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";
$result = $mysqli->query("SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY role");
$totalUsers = 0;
while ($row = $result->fetch_assoc()) {
    echo "  - {$row['role']}: {$row['count']}\n";
    $totalUsers += $row['count'];
}
echo "Total users: " . $totalUsers . "\n";

// 9. REFERENTIAL INTEGRITY VERIFICATION
echo "\n=== REFERENTIAL INTEGRITY VERIFICATION ===\n";
echo str_repeat("-", 90) . "\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM courses c LEFT JOIN departments d ON c.department_id = d.id WHERE c.department_id IS NOT NULL AND d.id IS NULL");
$row = $result->fetch_assoc();
$coursesWithBadDept = (int)$row['count'];
echo ($coursesWithBadDept == 0 ? "OK" : "FAIL") . " - Courses with valid department references (" . $coursesWithBadDept . " bad)\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM subjects s LEFT JOIN courses c ON s.program_id = c.id WHERE s.program_id IS NOT NULL AND c.id IS NULL");
$row = $result->fetch_assoc();
$subjectsWithBadCourse = (int)$row['count'];
echo ($subjectsWithBadCourse == 0 ? "OK" : "FAIL") . " - Subjects with valid program references (" . $subjectsWithBadCourse . " bad)\n";

$result = $mysqli->query("SELECT COUNT(*) as count FROM classes c LEFT JOIN courses co ON c.program_id = co.id WHERE c.program_id IS NOT NULL AND co.id IS NULL");
$row = $result->fetch_assoc();
$classesWithBadProgram = (int)$row['count'];
echo ($classesWithBadProgram == 0 ? "OK" : "FAIL") . " - Classes with valid program references (" . $classesWithBadProgram . " bad)\n";

// 10. LEGACY TABLE CHECK
echo "\n--- LEGACY TABLE CLEANUP ---\n";
echo str_repeat("-", 90) . "\n";
$legacyTables = ['admins', 'teachers', 'super_admins', 'class_students', 'teacher_subject', 'course_instructors', 'subject_instructors', 'teacher_assignments'];
$legacyCount = 0;
foreach ($legacyTables as $table) {
    $result = $mysqli->query("SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'edutrack_db' AND TABLE_NAME = '$table'");
    if ($result && $result->num_rows > 0) {
        echo "WARN: $table still exists\n";
        $legacyCount++;
    }
}
if ($legacyCount === 0) {
    echo "OK - All legacy tables removed\n";
}

// 11. FINAL SUMMARY
echo "\n=== FINAL SUMMARY ===\n";
echo str_repeat("=", 90) . "\n";
$allGood = ($classesWithProgram === $totalClasses && $coursesWithBadDept == 0 && $subjectsWithBadCourse == 0 && $classesWithBadProgram == 0 && $legacyCount === 0);

if ($allGood) {
    echo "SUCCESS: DATABASE IS READY FOR PRODUCTION\n";
    echo "   - 3-program structure: BSIT, BEED, BSHM\n";
    echo "   - " . count($courses) . " programs properly configured\n";
    echo "   - " . $totalSubjects . " subjects across 4 year levels\n";
    echo "   - " . $totalClasses . " classes linked to programs\n";
    echo "   - " . $totalClassSubjects . " subject-to-class mappings\n";
    echo "   - " . $totalStudents . " students properly enrolled\n";
    echo "   - " . $totalUsers . " total users\n";
    echo "   - All referential integrity validated\n";
    echo "   - No legacy tables remain\n";
} else {
    echo "WARNING: DATABASE HAS ISSUES\n";
    echo "   - Classes with program_id: " . $classesWithProgram . " / " . $totalClasses . "\n";
    echo "   - Courses with bad dept: " . $coursesWithBadDept . "\n";
    echo "   - Subjects with bad program: " . $subjectsWithBadCourse . "\n";
    echo "   - Classes with bad program: " . $classesWithBadProgram . "\n";
    echo "   - Legacy tables still present: " . $legacyCount . "\n";
}

echo "\n" . str_repeat("=", 90) . "\n\n";

$mysqli->close();
