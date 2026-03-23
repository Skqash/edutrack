<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Repopulate courses table with the 3 CPSU programs
     * BSIT - Bachelor of Science in Information Technology
     * BEED - Bachelor of Elementary Education  
     * BSHM - Bachelor of Science in Hospitality Management
     */
    public function up(): void
    {
        // Disable foreign key  checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear any existing data
        DB::table('courses')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Insert the 3 core CPSU programs
        DB::table('courses')->insertOrIgnore([
            [
                'id' => 1,
                'course_code' => 'BSIT',
                'program_code' => 'BSIT',
                'program_name' => 'Bachelor of Science in Information Technology',
                'department_id' => 1,
                'program_head_id' => null,
                'total_years' => 4,
                'description' => 'Advanced information technology program focusing on software development, systems administration, and emerging technologies',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'course_code' => 'BEED',
                'program_code' => 'BEED',
                'program_name' => 'Bachelor of Elementary Education',
                'department_id' => 2,
                'program_head_id' => null,
                'total_years' => 4,
                'description' => 'Comprehensive teacher education program for elementary school educators with professional development and classroom management',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'course_code' => 'BSHM',
                'program_code' => 'BSHM',
                'program_name' => 'Bachelor of Science in Hospitality Management',
                'department_id' => 3,
                'program_head_id' => null,
                'total_years' => 4,
                'description' => 'Professional hospitality management program covering hotel operations, tourism, and customer service excellence',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('courses')->truncate();
    }
};
