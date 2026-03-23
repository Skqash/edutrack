<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * ClassSeeder - Phase 4 Enhanced
 * Creates comprehensive class structure with support for multiple instructors
 * Classes are organized by:
 * - Program (BSIT, BEED, BSHM)
 * - Year Level (1-4)
 * - Semester (1-2)
 * - Section (A-B)
 * - Department organization
 */
class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y');
        $schoolYear = $currentYear . '-' . ($currentYear + 1);

        // Get courses to associate with classes
        $courses = Course::all();
        
        if ($courses->isEmpty()) {
            echo "⚠️  No courses found. Please run CourseSeeder first.\n";
            return;
        }

        // Get teachers (users with teacher role)
        $teachers = User::where('role', 'teacher')
            ->orWhere('role', 'admin')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        if ($teachers->isEmpty()) {
            echo "⚠️  No teachers found. Please run UserSeeder first.\n";
            return;
        }

        $classes = [];

        // Define program structure: program_name => [department, years, semesters, sections]
        $programs = [
            'BSIT' => [
                'department' => 'BSIT',
                'years' => [1, 2, 3, 4],
                'semesters' => [1, 2],
                'sections' => ['A', 'B'],
            ],
            'BEED' => [
                'department' => 'BEED',
                'years' => [1, 2, 3, 4],
                'semesters' => [1, 2],
                'sections' => ['A', 'B'],
            ],
            'BSHM' => [
                'department' => 'BSHM',
                'years' => [1, 2, 3, 4],
                'semesters' => [1, 2],
                'sections' => ['A', 'B'],
            ],
        ];

        $createdCount = 0;
        $teacherIndex = 0;

        // Generate classes for each program - using program_name to match existing courses
        $programMapping = [
            'BSIT' => 'Bachelor of Science in Information Technology',
            'BEED' => 'Bachelor of Elementary Education',
            'BSHM' => 'Bachelor of Science in Hospitality Management',
        ];

        foreach ($programMapping as $programCode => $programName) {
            $config = $programs[$programCode];
            $course = $courses->firstWhere('program_name', $programName);
            
            if (!$course) {
                echo "⚠️  Course '{$programName}' not found. Skipping program.\n";
                continue;
            }

            foreach ($config['years'] as $year) {
                foreach ($config['semesters'] as $semester) {
                    foreach ($config['sections'] as $section) {
                        $classData = [
                            'class_name' => "{$programCode} - Year {$year} - Semester {$semester} - Section {$section}",
                            'class_level' => $year, // Same as year
                            'year' => $year,
                            'section' => $section,
                            'semester' => $semester,
                            'school_year' => $schoolYear,
                            'total_students' => 0, // Will be updated when students are assigned
                            'teacher_id' => $teachers[$teacherIndex % $teachers->count()]->id,
                            'course_id' => $course->id,
                            'description' => "{$programCode} - Year {$year}, Semester {$semester}, Section {$section}",
                            'status' => 'Active',
                        ];

                        $classes[] = $classData;
                        $createdCount++;

                        // Rotate teacher for variety
                        $teacherIndex++;
                    }
                }
            }
        }

        // Insert all classes
        foreach ($classes as $classData) {
            $classModel = ClassModel::create($classData);
            
            // Assign multiple instructors to the course via course_instructorUsers pivot
            $course = $classModel->course;
            if ($course) {
                // Assign primary teacher
                $primaryTeacher = User::find($classData['teacher_id']);
                if ($primaryTeacher) {
                    $course->instructorUsers()->syncWithoutDetaching([
                        $primaryTeacher->id => ['role' => 'Instructor']
                    ]);
                }

                // Randomly add a co-instructor for some classes (30% chance)
                if (rand(1, 100) <= 30 && $teachers->count() > 1) {
                    $coInstructor = $teachers->random();
                    if ($coInstructor->id !== $primaryTeacher->id) {
                        $course->instructorUsers()->syncWithoutDetaching([
                            $coInstructor->id => ['role' => 'Co-Instructor']
                        ], false);
                    }
                }
            }
        }

        echo "✅ " . $createdCount . " classes seeded successfully!\n";
        echo "📊 Programs: BSIT, BEED, BSHM\n";
        echo "📊 Years per program: 1-4\n";
        echo "📊 Semesters per year: 1-2\n";
        echo "📊 Sections per semester: A, B\n";
        echo "📊 Total structure: 3 programs × 4 years × 2 semesters × 2 sections = " . count($classes) . " classes\n";
    }
}

