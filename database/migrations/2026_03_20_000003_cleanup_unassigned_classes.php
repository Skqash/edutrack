<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remove classes that have no program assigned and no students
     * These are either legacy data or test classes
     */
    public function up(): void
    {
        // Delete classes with no course_id and no students
        DB::statement(
            "DELETE FROM classes WHERE course_id IS NULL AND id NOT IN (SELECT DISTINCT class_id FROM students WHERE class_id IS NOT NULL)"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot fully reverse since data is deleted, but restore could be manual
        // This is a one-way cleanup migration
    }
};
