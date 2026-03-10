<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class DegreeProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'program_code' => 'BSIT',
                'program_name' => 'Bachelor of Science in Information Technology',
                'college' => 'College of Engineering and Information Technology',
                'department' => 'Department of Computer Science',
                'description' => 'Comprehensive program covering software development, network administration, and IT management',
                'duration' => '4 Years',
                'max_students' => 60,
                'current_students' => 45,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BEED',
                'program_name' => 'Bachelor of Elementary Education',
                'college' => 'College of Education',
                'department' => 'Department of Teacher Education',
                'description' => 'Program focused on preparing future elementary school teachers with modern pedagogical skills',
                'duration' => '4 Years',
                'max_students' => 50,
                'current_students' => 38,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BS-Agri',
                'program_name' => 'Bachelor of Science in Agriculture',
                'college' => 'College of Agriculture',
                'department' => 'Department of Crop Science',
                'description' => 'Program covering modern agricultural practices, crop management, and sustainable farming',
                'duration' => '4 Years',
                'max_students' => 40,
                'current_students' => 32,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BSCS',
                'program_name' => 'Bachelor of Science in Computer Science',
                'college' => 'College of Engineering and Information Technology',
                'department' => 'Department of Computer Science',
                'description' => 'Advanced program focusing on algorithms, data structures, and computational theory',
                'duration' => '4 Years',
                'max_students' => 55,
                'current_students' => 48,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BSBA',
                'program_name' => 'Bachelor of Science in Business Administration',
                'college' => 'College of Business and Management',
                'department' => 'Department of Management',
                'description' => 'Program covering business principles, management strategies, and entrepreneurial skills',
                'duration' => '4 Years',
                'max_students' => 65,
                'current_students' => 52,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BSN',
                'program_name' => 'Bachelor of Science in Nursing',
                'college' => 'College of Nursing',
                'department' => 'Department of Nursing',
                'description' => 'Comprehensive nursing program with clinical training and healthcare management',
                'duration' => '4 Years',
                'max_students' => 45,
                'current_students' => 44,
                'status' => 'Active',
            ],
            [
                'program_code' => 'BSTM',
                'program_name' => 'Bachelor of Science in Tourism Management',
                'college' => 'College of Hospitality Management',
                'department' => 'Department of Tourism',
                'description' => 'Program focusing on tourism operations, hospitality services, and event management',
                'duration' => '4 Years',
                'max_students' => 35,
                'current_students' => 28,
                'status' => 'Active',
            ],
        ];

        foreach ($programs as $program) {
            Course::create(array_merge($program, [
                'course_code' => $program['program_code'], // For backward compatibility
                'course_name' => $program['program_name'], // For backward compatibility
                'department_code' => substr($program['program_code'], 0, 3), // Generate dept code
                'credit_hours' => 3, // Default credit hours
                'instructor_id' => 1, // Use first user as instructor
            ]));
        }

        $this->command->info('Sample degree programs created successfully!');
    }
}
