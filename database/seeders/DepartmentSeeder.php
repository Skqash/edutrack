<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            // College of Computer Studies
            [
                'department_name' => 'BSIT',
                'college_name' => 'College of Computer Studies',
                'description' => 'Bachelor of Science in Information Technology',
            ],
            // College of Education  
            [
                'department_name' => 'BEED',
                'college_name' => 'College of Education',
                'description' => 'Bachelor of Elementary Education',
            ],
            // College of Hospitality
            [
                'department_name' => 'BSHM',
                'college_name' => 'College of Hospitality and Tourism',
                'description' => 'Bachelor of Science in Hospitality Management',
            ],
        ];

        foreach ($departments as $dept) {
            $college = DB::table('colleges')->where('college_name', $dept['college_name'])->first();
            
            if ($college) {
                DB::table('departments')->updateOrInsert(
                    ['department_name' => $dept['department_name']],
                    [
                        'college_id' => $college->id,
                        'description' => $dept['description'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        echo "✅ " . count($departments) . " departments seeded successfully\n";
    }
}
