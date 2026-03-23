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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('courses', 'program_code')) {
                $table->string('program_code')->default('GEN')->after('id');
            }
            if (!Schema::hasColumn('courses', 'program_name')) {
                $table->string('program_name')->default('General')->after('program_code');
            }
            if (!Schema::hasColumn('courses', 'college')) {
                $table->string('college')->nullable()->after('program_name');
            }
            if (!Schema::hasColumn('courses', 'department')) {
                $table->string('department')->nullable()->after('college');
            }
            if (!Schema::hasColumn('courses', 'duration')) {
                $table->string('duration')->nullable()->after('description');
            }
            if (!Schema::hasColumn('courses', 'max_students')) {
                $table->integer('max_students')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('courses', 'current_students')) {
                $table->integer('current_students')->default(0)->after('max_students');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            // Add new fields for subjects if they don't exist
            if (!Schema::hasColumn('subjects', 'type')) {
                $table->string('type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('subjects', 'program')) {
                $table->string('program')->nullable()->after('type');
            }
            
            // Make course_id nullable for general education subjects if not already
            if (Schema::hasColumn('subjects', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable()->change();
            }
            if (Schema::hasColumn('subjects', 'instructor_id')) {
                $table->unsignedBigInteger('instructor_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Only drop if they exist
            if (Schema::hasColumn('courses', 'program_code')) {
                $table->dropColumn('program_code');
            }
            if (Schema::hasColumn('courses', 'program_name')) {
                $table->dropColumn('program_name');
            }
            if (Schema::hasColumn('courses', 'college')) {
                $table->dropColumn('college');
            }
            if (Schema::hasColumn('courses', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('courses', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('courses', 'max_students')) {
                $table->dropColumn('max_students');
            }
            if (Schema::hasColumn('courses', 'current_students')) {
                $table->dropColumn('current_students');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('subjects', 'program')) {
                $table->dropColumn('program');
            }
        });
    }
};
