<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Get all teacher users
        $teacherUsers = User::where('role', 'teacher')->get();

        $qualifications = ['M.Sc', 'M.Tech', 'Ph.D', 'B.Tech', 'MBA'];

        foreach ($teacherUsers as $index => $user) {
            Teacher::create([
                'user_id' => $user->id,
                'employee_id' => 'EMP' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'qualification' => $qualifications[array_rand($qualifications)],
                'status' => 'Active',
            ]);
        }
    }
}
