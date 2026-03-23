<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'campus')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('campus')->nullable()->index();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'campus')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropIndex([$tableName . '_campus_index']);
                    $table->dropColumn('campus');
                });
            }
        }
    }
};
