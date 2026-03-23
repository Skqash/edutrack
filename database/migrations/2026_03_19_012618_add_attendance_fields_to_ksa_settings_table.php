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
        Schema::table('ksa_settings', function (Blueprint $table) {
            // Attendance configuration fields
            $table->integer('total_meetings')->default(20)->after('attitude_weight');
            $table->decimal('attendance_weight', 5, 2)->default(10.00)->after('total_meetings');
            $table->enum('attendance_category', ['knowledge', 'skills', 'attitude'])->default('skills')->after('attendance_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ksa_settings', function (Blueprint $table) {
            $table->dropColumn(['total_meetings', 'attendance_weight', 'attendance_category']);
        });
    }
};
