<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Grade;

class SampleGradeEntrySeeder extends Seeder
{
    public function run()
    {
        // Create or get a demo teacher user
        $user = User::firstOrCreate([
            'email' => 'demo.teacher@example.com'
        ], [
            'name' => 'Demo Teacher',
            'password' => bcrypt('password'),
            'role' => 'teacher'
        ]);

        $teacher = Teacher::firstOrCreate([
            'user_id' => $user->id
        ], [
            'name' => $user->name
        ]);

        // Create a demo class
        $class = ClassModel::firstOrCreate([
            'name' => 'Demo Class 101',
            'teacher_id' => $teacher->id
        ], [
            'subject_id' => null
        ]);

        // Create 5 demo students
        for ($i = 1; $i <= 5; $i++) {
            $stuUser = User::firstOrCreate([
                'email' => "student{$i}@example.com"
            ], [
                'name' => "Student {$i}",
                'password' => bcrypt('password'),
                'role' => 'student'
            ]);

            $student = Student::firstOrCreate([
                'user_id' => $stuUser->id,
                'class_id' => $class->id
            ], [
                'student_id' => sprintf('2026-%04d-A', $i),
                'year' => 1,
                'section' => 'A',
                'status' => 'Active'
            ]);

            // Create blank grade row for midterm
            Grade::firstOrCreate([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'teacher_id' => $teacher->id,
                'term' => 'midterm'
            ], [
                'subject_id' => $class->subject_id ?? null
            ]);
        }

        $this->command->info('Sample class and students created: class id ' . $class->id);
    }
}
