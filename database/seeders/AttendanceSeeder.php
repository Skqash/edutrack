<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::limit(30)->get();
        $classes = ClassModel::all();
        $statuses = ['Present', 'Absent', 'Late', 'Leave'];

        // Create attendance records for the last 10 days
        for ($day = 1; $day <= 10; $day++) {
            $date = Carbon::now()->subDays($day)->toDateString();

            foreach ($students as $student) {
                foreach ($classes->random(rand(1, 2)) as $class) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'class_id' => $class->id,
                        'date' => $date,
                        'status' => $statuses[array_rand($statuses)],
                        'notes' => rand(0, 1) ? 'Regular attendance' : null,
                    ]);
                }
            }
        }
    }
}
