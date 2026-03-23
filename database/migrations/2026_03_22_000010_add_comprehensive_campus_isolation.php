<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add comprehensive campus isolation to all relevant tables
     * This ensures complete data privacy and security between campuses
     */
    public function up(): void
    {
        // Add campus field to grades table
        if (Schema::hasTable('grades') && !Schema::hasColumn('grades', 'campus')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to grade_entries table
        if (Schema::hasTable('grade_entries') && !Schema::hasColumn('grade_entries', 'campus')) {
            Schema::table('grade_entries', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to attendance table
        if (Schema::hasTable('attendance') && !Schema::hasColumn('attendance', 'campus')) {
            Schema::table('attendance', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('notes')->index();
            });
        }

        // Add campus field to student_attendance table
        if (Schema::hasTable('student_attendance') && !Schema::hasColumn('student_attendance', 'campus')) {
            Schema::table('student_attendance', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('remarks')->index();
            });
        }

        // Add campus field to assessment_components table
        if (Schema::hasTable('assessment_components') && !Schema::hasColumn('assessment_components', 'campus')) {
            Schema::table('assessment_components', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to assessment_ranges table
        if (Schema::hasTable('assessment_ranges') && !Schema::hasColumn('assessment_ranges', 'campus')) {
            Schema::table('assessment_ranges', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to component_averages table
        if (Schema::hasTable('component_averages') && !Schema::hasColumn('component_averages', 'campus')) {
            Schema::table('component_averages', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('class_id')->index();
            });
        }

        // Add campus field to component_entries table
        if (Schema::hasTable('component_entries') && !Schema::hasColumn('component_entries', 'campus')) {
            Schema::table('component_entries', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('class_id')->index();
            });
        }

        // Add campus field to grading_scale_settings table
        if (Schema::hasTable('grading_scale_settings') && !Schema::hasColumn('grading_scale_settings', 'campus')) {
            Schema::table('grading_scale_settings', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to ksa_settings table
        if (Schema::hasTable('ksa_settings') && !Schema::hasColumn('ksa_settings', 'campus')) {
            Schema::table('ksa_settings', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('teacher_id')->index();
            });
        }

        // Add campus field to teacher_assignments table
        if (Schema::hasTable('teacher_assignments') && !Schema::hasColumn('teacher_assignments', 'campus')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('department')->index();
            });
        }

        // Add campus field to assignment_students table
        if (Schema::hasTable('assignment_students') && !Schema::hasColumn('assignment_students', 'campus')) {
            Schema::table('assignment_students', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('student_id')->index();
            });
        }

        // Add campus field to teacher_subject table
        if (Schema::hasTable('teacher_subject') && !Schema::hasColumn('teacher_subject', 'campus')) {
            Schema::table('teacher_subject', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('subject_id')->index();
            });
        }

        // Add campus field to notifications table for campus-specific notifications
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'campus')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('user_id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'campus')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropIndex(['campus']);
                    $table->dropColumn('campus');
                });
            }
        }
    }
};