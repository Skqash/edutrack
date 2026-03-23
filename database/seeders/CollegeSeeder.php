<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            [
                'college_name' => 'College of Computer Studies',
                'description' => 'Focuses on computer science, information technology, and related fields',
            ],
            [
                'college_name' => 'College of Education',
                'description' => 'Prepares educators and educational professionals',
            ],
            [
                'college_name' => 'College of Hospitality and Tourism',
                'description' => 'Trains professionals in hospitality management and tourism',
            ],
        ];

        foreach ($colleges as $college) {
            DB::table('colleges')->updateOrInsert(
                ['college_name' => $college['college_name']],
                [
                    'description' => $college['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        echo "✅ " . count($colleges) . " colleges seeded successfully\n";
    }
}
