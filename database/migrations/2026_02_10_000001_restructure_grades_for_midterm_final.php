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
            // Add midterm and final distinction
            if (!Schema::hasColumn('grades', 'assessment_period')) {
                $table->enum('assessment_period', ['midterm', 'final'])->nullable()->default('midterm');
            }

            // Knowledge Component - Exams (Prelim, Midterm, Final)
            if (!Schema::hasColumn('grades', 'exam_prelim')) {
                $table->decimal('exam_prelim', 5, 2)->nullable()->comment('Preliminary Exam Score');
            }
            if (!Schema::hasColumn('grades', 'exam_midterm')) {
                $table->decimal('exam_midterm', 5, 2)->nullable()->comment('Midterm Exam Score');
            }
            if (!Schema::hasColumn('grades', 'exam_final')) {
                $table->decimal('exam_final', 5, 2)->nullable()->comment('Final Exam Score');
            }

            // Knowledge Component - Quizzes (5 quizzes)
            if (!Schema::hasColumn('grades', 'quiz_1')) {
                $table->decimal('quiz_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'quiz_2')) {
                $table->decimal('quiz_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'quiz_3')) {
                $table->decimal('quiz_3', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'quiz_4')) {
                $table->decimal('quiz_4', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'quiz_5')) {
                $table->decimal('quiz_5', 5, 2)->nullable();
            }

            // Skills Component - Output (3 entries)
            if (!Schema::hasColumn('grades', 'output_1')) {
                $table->decimal('output_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'output_2')) {
                $table->decimal('output_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'output_3')) {
                $table->decimal('output_3', 5, 2)->nullable();
            }

            // Skills Component - Class Participation (3 entries)
            if (!Schema::hasColumn('grades', 'class_participation_1')) {
                $table->decimal('class_participation_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'class_participation_2')) {
                $table->decimal('class_participation_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'class_participation_3')) {
                $table->decimal('class_participation_3', 5, 2)->nullable();
            }

            // Skills Component - Activities (3 entries)
            if (!Schema::hasColumn('grades', 'activities_1')) {
                $table->decimal('activities_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'activities_2')) {
                $table->decimal('activities_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'activities_3')) {
                $table->decimal('activities_3', 5, 2)->nullable();
            }

            // Skills Component - Assignments (3 entries)
            if (!Schema::hasColumn('grades', 'assignments_1')) {
                $table->decimal('assignments_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'assignments_2')) {
                $table->decimal('assignments_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'assignments_3')) {
                $table->decimal('assignments_3', 5, 2)->nullable();
            }

            // Attitude Component - Behavior (3 entries)
            if (!Schema::hasColumn('grades', 'behavior_1')) {
                $table->decimal('behavior_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'behavior_2')) {
                $table->decimal('behavior_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'behavior_3')) {
                $table->decimal('behavior_3', 5, 2)->nullable();
            }

            // Attitude Component - Awareness (3 entries)
            if (!Schema::hasColumn('grades', 'awareness_1')) {
                $table->decimal('awareness_1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'awareness_2')) {
                $table->decimal('awareness_2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('grades', 'awareness_3')) {
                $table->decimal('awareness_3', 5, 2)->nullable();
            }

            // Component Averages
            if (!Schema::hasColumn('grades', 'knowledge_average')) {
                $table->decimal('knowledge_average', 5, 2)->nullable()->comment('Average of Exams + Quizzes (40% weight)');
            }
            if (!Schema::hasColumn('grades', 'skills_average')) {
                $table->decimal('skills_average', 5, 2)->nullable()->comment('Average of all Skills components (50% weight)');
            }
            if (!Schema::hasColumn('grades', 'attitude_average')) {
                $table->decimal('attitude_average', 5, 2)->nullable()->comment('Average of Behavior + Awareness (10% weight)');
            }

            // Midterm and Final Grades
            if (!Schema::hasColumn('grades', 'midterm_grade')) {
                $table->decimal('midterm_grade', 5, 2)->nullable()->comment('Midterm final grade');
            }
            if (!Schema::hasColumn('grades', 'final_grade_value')) {
                $table->decimal('final_grade_value', 5, 2)->nullable()->comment('Final exam grade');
            }

            // Overall grade (weighted: Midterm 40% + Final 60%)
            if (!Schema::hasColumn('grades', 'overall_grade')) {
                $table->decimal('overall_grade', 5, 2)->nullable()->comment('Overall grade (Midterm*0.40 + Final*0.60)');
            }

            // Grade point
            if (!Schema::hasColumn('grades', 'grade_point')) {
                $table->decimal('grade_point', 5, 2)->nullable()->comment('Grade point on 4.0 scale');
            }
            if (!Schema::hasColumn('grades', 'letter_grade')) {
                $table->enum('letter_grade', ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'F', 'INC'])->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop all the new columns
            $columns = [
                'assessment_period',
                'exam_prelim', 'exam_midterm', 'exam_final',
                'quiz_1', 'quiz_2', 'quiz_3', 'quiz_4', 'quiz_5',
                'output_1', 'output_2', 'output_3',
                'class_participation_1', 'class_participation_2', 'class_participation_3',
                'activities_1', 'activities_2', 'activities_3',
                'assignments_1', 'assignments_2', 'assignments_3',
                'behavior_1', 'behavior_2', 'behavior_3',
                'awareness_1', 'awareness_2', 'awareness_3',
                'knowledge_average', 'skills_average', 'attitude_average',
                'midterm_grade', 'final_grade_value', 'overall_grade',
                'grade_point', 'letter_grade'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('grades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
