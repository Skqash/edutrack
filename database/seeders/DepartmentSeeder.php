<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'department_code' => 'CS',
                'department_name' => 'Computer Science',
                'description' => 'Department of Computer Science and Engineering',
            ],
            [
                'department_code' => 'ECE',
                'department_name' => 'Electronics & Communication',
                'description' => 'Department of Electronics and Communication Engineering',
            ],
            [
                'department_code' => 'ME',
                'department_name' => 'Mechanical Engineering',
                'description' => 'Department of Mechanical Engineering',
            ],
            [
                'department_code' => 'CE',
                'department_name' => 'Civil Engineering',
                'description' => 'Department of Civil Engineering',
            ],
            [
                'department_code' => 'EE',
                'department_name' => 'Electrical Engineering',
                'description' => 'Department of Electrical Engineering',
            ],
        ];

        // Get first 5 teachers as department heads
        $teachers = User::where('role', 'teacher')->limit(5)->get();

        foreach ($departments as $index => $dept) {
            Department::create([
                'department_code' => $dept['department_code'],
                'department_name' => $dept['department_name'],
                'head_id' => $teachers[$index]->id ?? null,
                'description' => $dept['description'],
                'status' => 'Active',
            ]);
        }
    }
}
