<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add term indication to classes (indicates which term teacher is currently entering)
        if (!Schema::hasColumn('classes', 'current_term')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->enum('current_term', ['midterm', 'final'])->default('midterm')->comment('Term teacher is currently entering grades for');
            });
        }

        // Create grade_entries table for raw input per term per student
        if (!Schema::hasTable('grade_entries')) {
            Schema::create('grade_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('term', ['midterm', 'final']);

            // KNOWLEDGE COMPONENT
            $table->decimal('exam_pr', 5, 2)->nullable()->comment('Exam Preliminary Score');
            $table->decimal('exam_md', 5, 2)->nullable()->comment('Exam Midterm Score');
            $table->decimal('quiz_1', 5, 2)->nullable();
            $table->decimal('quiz_2', 5, 2)->nullable();
            $table->decimal('quiz_3', 5, 2)->nullable();
            $table->decimal('quiz_4', 5, 2)->nullable();
            $table->decimal('quiz_5', 5, 2)->nullable();

            // SKILLS COMPONENT
            $table->decimal('output_1', 5, 2)->nullable();
            $table->decimal('output_2', 5, 2)->nullable();
            $table->decimal('output_3', 5, 2)->nullable();
            $table->decimal('classpart_1', 5, 2)->nullable();
            $table->decimal('classpart_2', 5, 2)->nullable();
            $table->decimal('classpart_3', 5, 2)->nullable();
            $table->decimal('activity_1', 5, 2)->nullable();
            $table->decimal('activity_2', 5, 2)->nullable();
            $table->decimal('activity_3', 5, 2)->nullable();
            $table->decimal('assignment_1', 5, 2)->nullable();
            $table->decimal('assignment_2', 5, 2)->nullable();
            $table->decimal('assignment_3', 5, 2)->nullable();

            // ATTITUDE COMPONENT
            $table->decimal('behavior_1', 5, 2)->nullable();
            $table->decimal('behavior_2', 5, 2)->nullable();
            $table->decimal('behavior_3', 5, 2)->nullable();
            $table->decimal('awareness_1', 5, 2)->nullable();
            $table->decimal('awareness_2', 5, 2)->nullable();
            $table->decimal('awareness_3', 5, 2)->nullable();

            // COMPUTED AVERAGES (auto-calculated)
            $table->decimal('exam_average', 5, 2)->nullable();
            $table->decimal('quiz_average', 5, 2)->nullable();
            $table->decimal('knowledge_average', 5, 2)->nullable();

            $table->decimal('output_average', 5, 2)->nullable();
            $table->decimal('classpart_average', 5, 2)->nullable();
            $table->decimal('activity_average', 5, 2)->nullable();
            $table->decimal('assignment_average', 5, 2)->nullable();
            $table->decimal('skills_average', 5, 2)->nullable();

            $table->decimal('behavior_average', 5, 2)->nullable();
            $table->decimal('awareness_average', 5, 2)->nullable();
            $table->decimal('attitude_average', 5, 2)->nullable();

            // TERM GRADE (before final calculation)
            $table->decimal('term_grade', 5, 2)->nullable();
            $table->text('remarks')->nullable();

            // Unique constraint per student per class per term
            $table->unique(['student_id', 'class_id', 'term']);

            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_entries');
        
        if (Schema::hasColumn('classes', 'current_term')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropColumn('current_term');
            });
        }
    }
};
