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
        Schema::table('grades', function (Blueprint $table) {
            // Term field (Midterm or Final)
            if (!Schema::hasColumn('grades', 'term')) {
                $table->enum('term', ['midterm', 'final'])->default('midterm')->after('class_id');
            }

            // Knowledge Components - Quizzes (Q1-Q5) - Total 25 items
            if (!Schema::hasColumn('grades', 'q1')) {
                $table->decimal('q1', 5, 2)->nullable()->after('term');
            }
            if (!Schema::hasColumn('grades', 'q2')) {
                $table->decimal('q2', 5, 2)->nullable()->after('q1');
            }
            if (!Schema::hasColumn('grades', 'q3')) {
                $table->decimal('q3', 5, 2)->nullable()->after('q2');
            }
            if (!Schema::hasColumn('grades', 'q4')) {
                $table->decimal('q4', 5, 2)->nullable()->after('q3');
            }
            if (!Schema::hasColumn('grades', 'q5')) {
                $table->decimal('q5', 5, 2)->nullable()->after('q4');
            }

            // Knowledge Components - Exams
            if (!Schema::hasColumn('grades', 'prelim_exam')) {
                $table->decimal('prelim_exam', 5, 2)->nullable()->after('q5');
            }
            if (!Schema::hasColumn('grades', 'midterm_exam')) {
                $table->decimal('midterm_exam', 5, 2)->nullable()->after('prelim_exam');
            }
            if (!Schema::hasColumn('grades', 'final_exam')) {
                $table->decimal('final_exam', 5, 2)->nullable()->after('midterm_exam');
            }

            // Knowledge Calculated Score
            if (!Schema::hasColumn('grades', 'knowledge_score')) {
                $table->decimal('knowledge_score', 5, 2)->nullable()->after('final_exam');
            }

            // Skills Components
            if (!Schema::hasColumn('grades', 'output_score')) {
                $table->decimal('output_score', 5, 2)->nullable()->after('knowledge_score');
            }
            if (!Schema::hasColumn('grades', 'class_participation_score')) {
                $table->decimal('class_participation_score', 5, 2)->nullable()->after('output_score');
            }
            if (!Schema::hasColumn('grades', 'activities_score')) {
                $table->decimal('activities_score', 5, 2)->nullable()->after('class_participation_score');
            }
            if (!Schema::hasColumn('grades', 'assignments_score')) {
                $table->decimal('assignments_score', 5, 2)->nullable()->after('activities_score');
            }

            // Skills Calculated Score
            if (!Schema::hasColumn('grades', 'skills_score')) {
                $table->decimal('skills_score', 5, 2)->nullable()->after('assignments_score');
            }

            // Attitude Components
            if (!Schema::hasColumn('grades', 'behavior_score')) {
                $table->decimal('behavior_score', 5, 2)->nullable()->after('skills_score');
            }
            if (!Schema::hasColumn('grades', 'awareness_score')) {
                $table->decimal('awareness_score', 5, 2)->nullable()->after('behavior_score');
            }

            // Attitude Calculated Score
            if (!Schema::hasColumn('grades', 'attitude_score')) {
                $table->decimal('attitude_score', 5, 2)->nullable()->after('awareness_score');
            }

            // Final Grade Calculation (Knowledge 40% + Skills 50% + Attitude 10%)
            if (!Schema::hasColumn('grades', 'final_grade')) {
                $table->decimal('final_grade', 5, 2)->nullable()->after('attitude_score');
            }

            // Grade letter
            if (!Schema::hasColumn('grades', 'grade_letter')) {
                $table->char('grade_letter', 1)->nullable()->after('final_grade');
            }

            // Remarks
            if (!Schema::hasColumn('grades', 'remarks')) {
                $table->text('remarks')->nullable()->after('grade_letter');
            }

            // Grading Period
            if (!Schema::hasColumn('grades', 'grading_period')) {
                $table->string('grading_period')->nullable()->after('remarks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop all columns if they exist
            $columns = [
                'term', 'q1', 'q2', 'q3', 'q4', 'q5',
                'prelim_exam', 'midterm_exam', 'final_exam',
                'knowledge_score', 'output_score', 'class_participation_score',
                'activities_score', 'assignments_score', 'skills_score',
                'behavior_score', 'awareness_score', 'attitude_score',
                'final_grade', 'grade_letter', 'remarks', 'grading_period'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('grades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
