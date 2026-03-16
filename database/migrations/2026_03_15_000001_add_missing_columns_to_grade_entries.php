<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grade_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('grade_entries', 'exam_fn')) {
                $table->decimal('exam_fn', 5, 2)->nullable()->after('exam_md')->comment('Final Exam Score');
            }
            if (!Schema::hasColumn('grade_entries', 'attendance_1')) {
                $table->decimal('attendance_1', 5, 2)->nullable()->after('behavior_3');
            }
            if (!Schema::hasColumn('grade_entries', 'attendance_2')) {
                $table->decimal('attendance_2', 5, 2)->nullable()->after('attendance_1');
            }
            if (!Schema::hasColumn('grade_entries', 'attendance_3')) {
                $table->decimal('attendance_3', 5, 2)->nullable()->after('attendance_2');
            }
            if (!Schema::hasColumn('grade_entries', 'attendance_average')) {
                $table->decimal('attendance_average', 5, 2)->nullable()->after('behavior_average');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grade_entries', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('grade_entries', 'exam_fn') ? 'exam_fn' : null,
                Schema::hasColumn('grade_entries', 'attendance_1') ? 'attendance_1' : null,
                Schema::hasColumn('grade_entries', 'attendance_2') ? 'attendance_2' : null,
                Schema::hasColumn('grade_entries', 'attendance_3') ? 'attendance_3' : null,
                Schema::hasColumn('grade_entries', 'attendance_average') ? 'attendance_average' : null,
            ]));
        });
    }
};
