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
        Schema::table('courses', function (Blueprint $table) {
            // Add department-like fields to courses
            $table->foreignId('head_id')->nullable()->after('instructor_id')->constrained('users')->onDelete('set null');
            $table->string('department_code')->nullable()->after('course_code')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('courses', 'head_id')) {
                $table->dropForeign(['head_id']);
            }
            $table->dropColumn(['head_id', 'department_code']);
        });
    }
};
