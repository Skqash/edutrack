<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove max_students limits and create many-to-many relationships for multiple teachers per subject/course
     */
    public function up(): void
    {
        // Rename max_students to total_students and drop current_students from courses
        Schema::table('courses', function (Blueprint $table) {
            // Rename max_students to total_students to standardize naming
            if (Schema::hasColumn('courses', 'max_students') && !Schema::hasColumn('courses', 'total_students')) {
                $table->renameColumn('max_students', 'total_students');
            }
            if (Schema::hasColumn('courses', 'current_students')) {
                $table->dropColumn('current_students');
            }
        });

        // Create pivot table for multiple instructors per subject
        if (!Schema::hasTable('subject_instructors')) {
            Schema::create('subject_instructors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('role')->default('Instructor')->comment('Instructor, Co-Instructor, TA, etc.');
                $table->timestamps();
                $table->unique(['subject_id', 'user_id', 'role']);
            });
        }

        // Create pivot table for multiple instructors per course
        if (!Schema::hasTable('course_instructors')) {
            Schema::create('course_instructors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('role')->default('Instructor')->comment('Course Lead, Co-Instructor, Coordinator, etc.');
                $table->timestamps();
                $table->unique(['course_id', 'user_id', 'role']);
            });
        }

        // Ensure students table has proper department tracking
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'department')) {
                $table->string('department')->nullable()->after('year')->comment('Department name for organization');
            }
            if (!Schema::hasColumn('students', 'year_level')) {
                // Note: 'year' already exists, year_level is alias for querying
                $table->unsignedTinyInteger('year_level')->nullable()->after('year')->comment('Alias for year field');
            }
        });

        // Update subjects table to support multiple instructors
        Schema::table('subjects', function (Blueprint $table) {
            // Make instructor_id nullable since we use pivot now
            // Note: Only change if column exists and has no NULL values causing conflicts
            if (Schema::hasColumn('subjects', 'instructor_id')) {
                // First, set default for any NULL values if needed
                \Illuminate\Support\Facades\DB::table('subjects')->whereNull('instructor_id')->update(['instructor_id' => 1]);
                // Then modify the column
                $table->unsignedBigInteger('instructor_id')->nullable()->change();
            }
        });

        // Update courses table to support multiple instructors
        Schema::table('courses', function (Blueprint $table) {
            // Make instructor_id nullable since we use pivot now
            if (Schema::hasColumn('courses', 'instructor_id')) {
                // First, set default for any NULL values if needed
                \Illuminate\Support\Facades\DB::table('courses')->whereNull('instructor_id')->update(['instructor_id' => 1]);
                $table->unsignedBigInteger('instructor_id')->nullable()->change();
            }
        });

        // Create index on students for efficient department/year/class queries
        Schema::table('students', function (Blueprint $table) {
            // Check if table has columns before adding index
            if (Schema::hasColumn('students', 'department') && 
                Schema::hasColumn('students', 'year') && 
                Schema::hasColumn('students', 'class_id')) {
                $table->index(['department', 'year', 'class_id'], 'idx_students_department_year_class');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes - check if they exist first
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasIndex('students', 'idx_students_department_year_class')) {
                $table->dropIndex('idx_students_department_year_class');
            }
        });

        // Drop pivot tables
        Schema::dropIfExists('subject_instructors');
        Schema::dropIfExists('course_instructors');

        // Restore students table
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('students', 'year_level')) {
                $table->dropColumn('year_level');
            }
        });

        // Restore courses table
        Schema::table('courses', function (Blueprint $table) {
            // Rename total_students back to max_students
            if (Schema::hasColumn('courses', 'total_students') && !Schema::hasColumn('courses', 'max_students')) {
                $table->renameColumn('total_students', 'max_students');
            }
            if (!Schema::hasColumn('courses', 'current_students')) {
                $table->integer('current_students')->default(0)->after('max_students');
            }
        });

        // Restore subjects table
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'instructor_id')) {
                // No need to restore to NOT NULL as it was originally nullable
            }
        });
    }
};
