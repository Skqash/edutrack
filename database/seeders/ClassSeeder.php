<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            [
                'class_code' => 'CS-1A',
                'class_name' => 'Computer Science Batch 1-A',
                'capacity' => 40,
                'description' => 'First year Computer Science students - Section A',
            ],
            [
                'class_code' => 'CS-1B',
                'class_name' => 'Computer Science Batch 1-B',
                'capacity' => 40,
                'description' => 'First year Computer Science students - Section B',
            ],
            [
                'class_code' => 'CS-2A',
                'class_name' => 'Computer Science Batch 2-A',
                'capacity' => 35,
                'description' => 'Second year Computer Science students - Section A',
            ],
            [
                'class_code' => 'CS-2B',
                'class_name' => 'Computer Science Batch 2-B',
                'capacity' => 35,
                'description' => 'Second year Computer Science students - Section B',
            ],
            [
                'class_code' => 'ECE-1A',
                'class_name' => 'ECE Batch 1-A',
                'capacity' => 40,
                'description' => 'First year Electronics & Communication students - Section A',
            ],
            [
                'class_code' => 'ECE-1B',
                'class_name' => 'ECE Batch 1-B',
                'capacity' => 40,
                'description' => 'First year Electronics & Communication students - Section B',
            ],
            [
                'class_code' => 'ME-1A',
                'class_name' => 'Mechanical Engineering Batch 1-A',
                'capacity' => 35,
                'description' => 'First year Mechanical Engineering students - Section A',
            ],
            [
                'class_code' => 'CE-1A',
                'class_name' => 'Civil Engineering Batch 1-A',
                'capacity' => 35,
                'description' => 'First year Civil Engineering students - Section A',
            ],
        ];

        foreach ($classes as $class) {
            ClassModel::create([
                'class_code' => $class['class_code'],
                'class_name' => $class['class_name'],
                'capacity' => $class['capacity'],
                'description' => $class['description'],
                'status' => 'Active',
            ]);
        }
    }
}
