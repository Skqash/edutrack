<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Database\Seeder;

/**
 * StudentSeeder - Phase 4 Enhanced
 * Creates comprehensive student enrollment organized by:
 * - Department (BSIT, BEED, BSHM)
 * - Year Level (1-4)
 * - Class Assignment (with proper class_id)
 * - Distribution across sections (A, B)
 */
class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Define student generation structure
        $programs = [
            'BSIT' => [
                'department' => 'BSIT',
                'total_students' => 120, // 120 students for BSIT
                'students_per_class' => 30, // Approx 30 per class
            ],
            'BEED' => [
                'department' => 'BEED',
                'total_students' => 100, // 100 students for BEED
                'students_per_class' => 25,
            ],
            'BSHM' => [
                'department' => 'BSHM',
                'total_students' => 90, // 90 students for BSHM
                'students_per_class' => 22,
            ],
        ];

        $studentCount = 0;
        $yearOffset = 2021; // Start year for generating student IDs

        // Program mapping: program_code => program_name (from courses table)
        $programMapping = [
            'BSIT' => 'Bachelor of Science in Information Technology',
            'BEED' => 'Bachelor of Elementary Education',
            'BSHM' => 'Bachelor of Science in Hospitality Management',
        ];

        foreach ($programMapping as $programCode => $programName) {
            $config = $programs[$programCode];
            $department = $config['department'];
            $totalStudents = $config['total_students'];
            $studentsPerClass = $config['students_per_class'];

            // Get all classes for this program based on course program_name
            $programClasses = ClassModel::whereHas('course', function ($query) use ($programName) {
                $query->where('program_name', $programName);
            })->orderBy('year')->orderBy('semester')->orderBy('section')->get();

            if ($programClasses->isEmpty()) {
                echo "⚠️  No classes found for program '{$programName}'. Skipping.\n";
                continue;
            }

            // Generate students and distribute across classes
            $studentsForProgram = 0;

            foreach ($programClasses as $class) {
                // Check if class already has students assigned
                $existingStudentCount = Student::where('class_id', $class->id)->count();
                if ($existingStudentCount > 0) {
                    echo "⚠️  Class {$class->class_name} already has students. Skipping.\n";
                    continue;
                }

                // Calculate students for this class
                $studentsInClass = min(
                    $studentsPerClass,
                    $totalStudents - $studentsForProgram
                );

                if ($studentsInClass <= 0) {
                    break;
                }

                // Generate students for this class
                for ($i = 0; $i < $studentsInClass; $i++) {
                    // Create a user for the student
                    $studentFirstName = fake()->firstName();
                    $studentLastName = fake()->lastName();
                    $studentEmail = strtolower($studentFirstName . '.' . $studentLastName) . 
                                   rand(1000, 9999) . '@student.college.edu';

                    $user = User::firstOrCreate(
                        ['email' => $studentEmail],
                        [
                            'name' => $studentFirstName . ' ' . $studentLastName,
                            'password' => bcrypt('password123'),
                            'role' => 'student',
                            'email_verified_at' => now(),
                        ]
                    );

                    // Generate student ID: YYYY-XXXX-S (e.g., 2021-0001-V)
                    // Make it unique by including a random component
                    $uniqueSequence = $studentsForProgram + $i + 1 + rand(1000, 9999);
                    $enrollmentYear = $yearOffset + ((int)$class->year - 1);
                    $studentId = sprintf(
                        '%d-%04d-%s',
                        $enrollmentYear,
                        $uniqueSequence % 10000, // Keep it 4 digits
                        $this->getStudentIdSuffix()
                    );

                    // Create student record
                    Student::create([
                        'user_id' => $user->id,
                        'student_id' => $studentId,
                        'year' => (int)$class->year,
                        'year_level' => (int)$class->year, // Alias for year
                        'section' => $class->section,
                        'class_id' => $class->id,
                        'department' => $department,
                        'gpa' => round(rand(20, 40) / 10, 2), // Random GPA between 2.0 and 4.0
                        'status' => 'Active',
                        'school' => 'College University',
                    ]);

                    $studentCount++;
                    $studentsForProgram++;
                }

                // Update class total_students count
                $class->update([
                    'total_students' => $studentsInClass,
                ]);
            }

            echo "✅ " . $studentsForProgram . " students seeded for {$department}\n";
        }

        echo "\n✅ Total students seeded: " . $studentCount . "\n";
        echo "📊 Distribution:\n";
        echo "   - BSIT: 120 students\n";
        echo "   - BEED: 100 students\n";
        echo "   - BSHM: 90 students\n";
        echo "📊 Organization: Students properly assigned to classes by department and year level\n";
    }

    /**
     * Generate random student ID suffix (A-Z)
     */
    private function getStudentIdSuffix(): string
    {
        $suffixes = ['A', 'B', 'C', 'D', 'E', 'S', 'V', 'X'];
        return $suffixes[array_rand($suffixes)];
    }
}

