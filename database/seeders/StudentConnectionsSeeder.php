<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Grade;
use App\Models\Attendance;
use Carbon\Carbon;

class StudentConnectionsSeeder extends Seeder
{
    /**
     * Create sample grades and attendance records for students
     */
    public function run(): void
    {
        $this->command->info('🎓 Creating sample grades and attendance records...');

        // Get all students with class assignments
        $students = Student::whereNotNull('class_id')->with('class')->get();
        
        $gradesCreated = 0;
        $attendanceCreated = 0;

        foreach ($students as $student) {
            $class = $student->class;
            if (!$class) continue;

            // Create sample grade record
            $grade = Grade::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'teacher_id' => $class->teacher_id,
                    'term' => 'midterm',
                ],
                [
                    'subject_id' => $class->subject_id,
                    'campus' => $class->campus,
                    'school_id' => $class->school_id,
                    'mid_quiz_1' => rand(75, 95),
                    'mid_quiz_2' => rand(75, 95),
                    'mid_exam_md' => rand(75, 95),
                    'mid_output_1' => rand(75, 95),
                    'mid_classpart_1' => rand(80, 100),
                    'mid_behavior_1' => rand(80, 100),
                    'mid_awareness_1' => rand(80, 100),
                    'mid_final_grade' => rand(75, 95),
                    'grade' => rand(75, 95),
                    'remarks' => 'Passed',
                ]
            );

            if ($grade->wasRecentlyCreated) {
                $gradesCreated++;
            }

            // Create sample attendance records for the past week
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i);
                
                $attendance = Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'date' => $date->format('Y-m-d'),
                        'term' => 'midterm',
                    ],
                    [
                        'teacher_id' => $class->teacher_id,
                        'status' => $this->getRandomAttendanceStatus(),
                        'campus' => $class->campus,
                        'school_id' => $class->school_id,
                    ]
                );

                if ($attendance->wasRecentlyCreated) {
                    $attendanceCreated++;
                }
            }

            // Progress indicator
            if (($gradesCreated + $attendanceCreated) % 100 == 0) {
                $this->command->info("Created {$gradesCreated} grades and {$attendanceCreated} attendance records...");
            }
        }

        $this->command->info("✅ Created {$gradesCreated} grade records");
        $this->command->info("✅ Created {$attendanceCreated} attendance records");
        $this->command->info("🎉 Student connections completed!");
    }

    /**
     * Get random attendance status
     */
    private function getRandomAttendanceStatus(): string
    {
        $statuses = ['Present', 'Present', 'Present', 'Present', 'Absent', 'Late'];
        return $statuses[array_rand($statuses)];
    }
}