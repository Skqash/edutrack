<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update students who are in course but not assigned to specific class
        // Assign them to the first class of their course
        DB::statement('
            UPDATE students s 
            SET class_id = (
                SELECT c.id 
                FROM classes c 
                WHERE c.course_id = s.course_id 
                AND c.campus = s.campus 
                AND (c.school_id = s.school_id OR c.school_id IS NULL OR s.school_id IS NULL)
                ORDER BY c.id 
                LIMIT 1
            )
            WHERE s.course_id IS NOT NULL 
            AND s.class_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as this is a data migration
        // Students will keep their class assignments
    }
};
