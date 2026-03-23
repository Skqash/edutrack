<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refactors subjects table to follow proper normalization:
     * - Removes school_year (belongs to classes, not subjects)
     * - Removes instructor_id (teachers assigned to classes, not subjects)
     * - Removes program column (redundant with course_id)
     * - Removes type column (not needed)
     * - Renames course_id → program_id for clarity
     * - Renames year → year_level for consistency
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop unnecessary columns
            if (Schema::hasColumn('subjects', 'school_year')) {
                $table->dropColumn('school_year');
            }
            
            if (Schema::hasColumn('subjects', 'type')) {
                $table->dropColumn('type');
            }
            
            if (Schema::hasColumn('subjects', 'program')) {
                $table->dropColumn('program');
            }
            
            if (Schema::hasColumn('subjects', 'instructor_id')) {
                // First, drop the foreign key constraint
                try {
                    $table->dropForeign(['instructor_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('instructor_id');
            }
        });

        // Rename course_id to program_id
        if (Schema::hasColumn('subjects', 'course_id') && !Schema::hasColumn('subjects', 'program_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->renameColumn('course_id', 'program_id');
            });
        }

        // Rename year to year_level
        if (Schema::hasColumn('subjects', 'year') && !Schema::hasColumn('subjects', 'year_level')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->renameColumn('year', 'year_level');
            });
        }

        // Add comment to clarify the structure
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'program_id')) {
                $table->unsignedBigInteger('program_id')->comment('FK to courses (programs)')->change();
            }
            if (Schema::hasColumn('subjects', 'year_level')) {
                $table->unsignedTinyInteger('year_level')->comment('Academic year level: 1-4')->change();
            }
            if (Schema::hasColumn('subjects', 'category')) {
                $table->string('category')->comment('Core / General Ed / Major / Specialization')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Rename back
            if (Schema::hasColumn('subjects', 'year_level') && !Schema::hasColumn('subjects', 'year')) {
                $table->renameColumn('year_level', 'year');
            }
            
            if (Schema::hasColumn('subjects', 'program_id') && !Schema::hasColumn('subjects', 'course_id')) {
                $table->renameColumn('program_id', 'course_id');
            }

            // Restore dropped columns
            if (!Schema::hasColumn('subjects', 'school_year')) {
                $table->string('school_year')->nullable()->after('semester');
            }
            
            if (!Schema::hasColumn('subjects', 'type')) {
                $table->string('type')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('subjects', 'program')) {
                $table->string('program')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('subjects', 'instructor_id')) {
                $table->unsignedBigInteger('instructor_id')->nullable()->after('course_id');
            }
        });
    }
};
