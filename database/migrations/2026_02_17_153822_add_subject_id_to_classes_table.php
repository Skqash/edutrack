<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Add subject_id foreign key column
            $table->foreignId('subject_id')->nullable()->after('teacher_id')->constrained('subjects')->onDelete('set null');
            
            // Add year and course_id columns if needed
            if (!Schema::hasColumn('classes', 'year')) {
                $table->integer('year')->nullable()->after('class_level');
            }
            if (!Schema::hasColumn('classes', 'course_id')) {
                $table->foreignId('course_id')->nullable()->after('subject_id')->constrained('courses')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('classes', 'subject_id')) {
                $table->dropForeign(['subject_id']);
                $table->dropColumn('subject_id');
            }
            if (Schema::hasColumn('classes', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }
            if (Schema::hasColumn('classes', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
