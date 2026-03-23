<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendanceSettingsToClasses extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Total class meetings per term
            $table->integer('total_meetings_midterm')->default(17)->after('current_term');
            $table->integer('total_meetings_final')->default(17)->after('total_meetings_midterm');
            
            // Attendance percentage weight in overall grade
            $table->decimal('attendance_percentage', 5, 2)->default(10.00)->after('total_meetings_final');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn(['total_meetings_midterm', 'total_meetings_final', 'attendance_percentage']);
        });
    }
}
