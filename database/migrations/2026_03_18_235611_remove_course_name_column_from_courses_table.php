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
            // Only drop if the column exists
            if (Schema::hasColumn('courses', 'course_name')) {
                $table->dropColumn('course_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Add back the course_name column if it was dropped
            $table->string('course_name')->nullable()->after('program_code');
        });
    }
};
