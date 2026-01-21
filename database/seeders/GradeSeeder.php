<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::limit(30)->get();
        $subjects = Subject::all();
        $semesters = ['1', '2'];
        $academicYears = ['2023-24', '2024-25'];

        foreach ($students as $student) {
            foreach ($subjects->random(rand(3, 5)) as $subject) {
                foreach ($semesters as $semester) {
                    $marksObtained = rand(40, 100);
                    $totalMarks = 100;
                    $percentage = ($marksObtained / $totalMarks) * 100;

                    // Determine grade
                    if ($percentage >= 90) {
                        $grade = 'A';
                    } elseif ($percentage >= 80) {
                        $grade = 'B';
                    } elseif ($percentage >= 70) {
                        $grade = 'C';
                    } elseif ($percentage >= 60) {
                        $grade = 'D';
                    } else {
                        $grade = 'F';
                    }

                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'marks_obtained' => $marksObtained,
                        'total_marks' => $totalMarks,
                        'grade' => $grade,
                        'semester' => $semester,
                        'academic_year' => $academicYears[0],
                    ]);
                }
            }
        }
    }
}
