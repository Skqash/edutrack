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
            // MIDTERM PERIOD - Knowledge Component
            $table->decimal('mid_exam_pr', 5, 2)->nullable()->comment('Midterm Exam Preliminary Raw Score');
            $table->decimal('mid_exam_md', 5, 2)->nullable()->comment('Midterm Exam Midterm Raw Score');
            $table->decimal('mid_quiz_1', 5, 2)->nullable();
            $table->decimal('mid_quiz_2', 5, 2)->nullable();
            $table->decimal('mid_quiz_3', 5, 2)->nullable();
            $table->decimal('mid_quiz_4', 5, 2)->nullable();
            $table->decimal('mid_quiz_5', 5, 2)->nullable();

            // MIDTERM PERIOD - Skills Component
            $table->decimal('mid_output_1', 5, 2)->nullable();
            $table->decimal('mid_output_2', 5, 2)->nullable();
            $table->decimal('mid_output_3', 5, 2)->nullable();
            $table->decimal('mid_classpart_1', 5, 2)->nullable();
            $table->decimal('mid_classpart_2', 5, 2)->nullable();
            $table->decimal('mid_classpart_3', 5, 2)->nullable();
            $table->decimal('mid_activity_1', 5, 2)->nullable();
            $table->decimal('mid_activity_2', 5, 2)->nullable();
            $table->decimal('mid_activity_3', 5, 2)->nullable();
            $table->decimal('mid_assignment_1', 5, 2)->nullable();
            $table->decimal('mid_assignment_2', 5, 2)->nullable();
            $table->decimal('mid_assignment_3', 5, 2)->nullable();

            // MIDTERM PERIOD - Attitude Component
            $table->decimal('mid_behavior_1', 5, 2)->nullable();
            $table->decimal('mid_behavior_2', 5, 2)->nullable();
            $table->decimal('mid_behavior_3', 5, 2)->nullable();
            $table->decimal('mid_awareness_1', 5, 2)->nullable();
            $table->decimal('mid_awareness_2', 5, 2)->nullable();
            $table->decimal('mid_awareness_3', 5, 2)->nullable();

            // FINAL PERIOD - Knowledge Component
            $table->decimal('final_exam_pr', 5, 2)->nullable()->comment('Final Exam Preliminary Raw Score');
            $table->decimal('final_exam_md', 5, 2)->nullable()->comment('Final Exam Midterm Raw Score');
            $table->decimal('final_quiz_1', 5, 2)->nullable();
            $table->decimal('final_quiz_2', 5, 2)->nullable();
            $table->decimal('final_quiz_3', 5, 2)->nullable();
            $table->decimal('final_quiz_4', 5, 2)->nullable();
            $table->decimal('final_quiz_5', 5, 2)->nullable();

            // FINAL PERIOD - Skills Component
            $table->decimal('final_output_1', 5, 2)->nullable();
            $table->decimal('final_output_2', 5, 2)->nullable();
            $table->decimal('final_output_3', 5, 2)->nullable();
            $table->decimal('final_classpart_1', 5, 2)->nullable();
            $table->decimal('final_classpart_2', 5, 2)->nullable();
            $table->decimal('final_classpart_3', 5, 2)->nullable();
            $table->decimal('final_activity_1', 5, 2)->nullable();
            $table->decimal('final_activity_2', 5, 2)->nullable();
            $table->decimal('final_activity_3', 5, 2)->nullable();
            $table->decimal('final_assignment_1', 5, 2)->nullable();
            $table->decimal('final_assignment_2', 5, 2)->nullable();
            $table->decimal('final_assignment_3', 5, 2)->nullable();

            // FINAL PERIOD - Attitude Component
            $table->decimal('final_behavior_1', 5, 2)->nullable();
            $table->decimal('final_behavior_2', 5, 2)->nullable();
            $table->decimal('final_behavior_3', 5, 2)->nullable();
            $table->decimal('final_awareness_1', 5, 2)->nullable();
            $table->decimal('final_awareness_2', 5, 2)->nullable();
            $table->decimal('final_awareness_3', 5, 2)->nullable();

            // COMPUTED AVERAGES BY PERIOD
            $table->decimal('mid_knowledge_average', 5, 2)->nullable();
            $table->decimal('mid_skills_average', 5, 2)->nullable();
            $table->decimal('mid_attitude_average', 5, 2)->nullable();
            $table->decimal('mid_final_grade', 5, 2)->nullable();

            $table->decimal('final_knowledge_average', 5, 2)->nullable();
            $table->decimal('final_skills_average', 5, 2)->nullable();
            $table->decimal('final_attitude_average', 5, 2)->nullable();
            $table->decimal('final_final_grade', 5, 2)->nullable();

            // Note: overall_grade, grade_5pt_scale, and grade_remarks already exist
            // Only add if they don't exist yet
            if (!Schema::hasColumn('grades', 'grade_5pt_scale')) {
                $table->string('grade_5pt_scale')->nullable()->comment('5-point scale: 5.0, 4.0, 3.0, 2.0, 1.0, 0.0');
            }
            if (!Schema::hasColumn('grades', 'grade_remarks')) {
                $table->text('grade_remarks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop all new columns
            $table->dropColumn([
                // Midterm Knowledge
                'mid_exam_pr', 'mid_exam_md',
                'mid_quiz_1', 'mid_quiz_2', 'mid_quiz_3', 'mid_quiz_4', 'mid_quiz_5',
                // Midterm Skills
                'mid_output_1', 'mid_output_2', 'mid_output_3',
                'mid_classpart_1', 'mid_classpart_2', 'mid_classpart_3',
                'mid_activity_1', 'mid_activity_2', 'mid_activity_3',
                'mid_assignment_1', 'mid_assignment_2', 'mid_assignment_3',
                // Midterm Attitude
                'mid_behavior_1', 'mid_behavior_2', 'mid_behavior_3',
                'mid_awareness_1', 'mid_awareness_2', 'mid_awareness_3',
                // Final Knowledge
                'final_exam_pr', 'final_exam_md',
                'final_quiz_1', 'final_quiz_2', 'final_quiz_3', 'final_quiz_4', 'final_quiz_5',
                // Final Skills
                'final_output_1', 'final_output_2', 'final_output_3',
                'final_classpart_1', 'final_classpart_2', 'final_classpart_3',
                'final_activity_1', 'final_activity_2', 'final_activity_3',
                'final_assignment_1', 'final_assignment_2', 'final_assignment_3',
                // Final Attitude
                'final_behavior_1', 'final_behavior_2', 'final_behavior_3',
                'final_awareness_1', 'final_awareness_2', 'final_awareness_3',
                // Computed
                'mid_knowledge_average', 'mid_skills_average', 'mid_attitude_average', 'mid_final_grade',
                'final_knowledge_average', 'final_skills_average', 'final_attitude_average', 'final_final_grade',
                'overall_grade', 'grade_5pt_scale', 'grade_remarks',
            ]);
        });
    }
};
