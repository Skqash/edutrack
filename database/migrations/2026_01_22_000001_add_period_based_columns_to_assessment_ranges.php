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
        Schema::table('assessment_ranges', function (Blueprint $table) {
            // Skills Components - Period based (Prelim, Midterm, Final)
            if (!Schema::hasColumn('assessment_ranges', 'class_participation_prelim')) {
                $table->integer('class_participation_prelim')->default(5)->after('class_participation_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'class_participation_midterm')) {
                $table->integer('class_participation_midterm')->default(5)->after('class_participation_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'class_participation_final')) {
                $table->integer('class_participation_final')->default(10)->after('class_participation_midterm');
            }
            
            if (!Schema::hasColumn('assessment_ranges', 'activities_prelim')) {
                $table->integer('activities_prelim')->default(5)->after('activities_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'activities_midterm')) {
                $table->integer('activities_midterm')->default(5)->after('activities_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'activities_final')) {
                $table->integer('activities_final')->default(10)->after('activities_midterm');
            }
            
            if (!Schema::hasColumn('assessment_ranges', 'assignments_prelim')) {
                $table->integer('assignments_prelim')->default(5)->after('assignments_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'assignments_midterm')) {
                $table->integer('assignments_midterm')->default(5)->after('assignments_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'assignments_final')) {
                $table->integer('assignments_final')->default(10)->after('assignments_midterm');
            }
            
            if (!Schema::hasColumn('assessment_ranges', 'output_prelim')) {
                $table->integer('output_prelim')->default(5)->after('output_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'output_midterm')) {
                $table->integer('output_midterm')->default(5)->after('output_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'output_final')) {
                $table->integer('output_final')->default(10)->after('output_midterm');
            }
            
            // Attitude Components - Period based (Prelim, Midterm, Final)
            if (!Schema::hasColumn('assessment_ranges', 'behavior_prelim')) {
                $table->integer('behavior_prelim')->default(2)->after('behavior_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'behavior_midterm')) {
                $table->integer('behavior_midterm')->default(3)->after('behavior_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'behavior_final')) {
                $table->integer('behavior_final')->default(5)->after('behavior_midterm');
            }
            
            if (!Schema::hasColumn('assessment_ranges', 'awareness_prelim')) {
                $table->integer('awareness_prelim')->default(2)->after('awareness_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'awareness_midterm')) {
                $table->integer('awareness_midterm')->default(3)->after('awareness_prelim');
            }
            if (!Schema::hasColumn('assessment_ranges', 'awareness_final')) {
                $table->integer('awareness_final')->default(5)->after('awareness_midterm');
            }
            
            // Quiz and Exam configuration
            if (!Schema::hasColumn('assessment_ranges', 'quiz_max')) {
                $table->integer('quiz_max')->default(20)->nullable()->after('quiz_5_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'exam_max')) {
                $table->integer('exam_max')->default(100)->nullable()->after('final_exam_max');
            }
            if (!Schema::hasColumn('assessment_ranges', 'attendance_min_percentage')) {
                $table->integer('attendance_min_percentage')->default(75)->nullable()->after('attendance_required');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_ranges', function (Blueprint $table) {
            // Drop the newly added columns
            $table->dropColumn([
                'class_participation_prelim',
                'class_participation_midterm',
                'class_participation_final',
                'activities_prelim',
                'activities_midterm',
                'activities_final',
                'assignments_prelim',
                'assignments_midterm',
                'assignments_final',
                'output_prelim',
                'output_midterm',
                'output_final',
                'behavior_prelim',
                'behavior_midterm',
                'behavior_final',
                'awareness_prelim',
                'awareness_midterm',
                'awareness_final',
                'quiz_max',
                'exam_max',
                'num_quizzes',
                'attendance_min_percentage',
            ]);
        });
    }
};
