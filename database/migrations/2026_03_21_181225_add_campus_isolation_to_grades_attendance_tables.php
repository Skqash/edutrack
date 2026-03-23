<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add campus isolation to grades and attendance tables
     */
    public function up(): void
    {
        // Add campus and school_id to grades table
        if (Schema::hasTable('grades')) {
            Schema::table('grades', function (Blueprint $table) {
                if (!Schema::hasColumn('grades', 'campus')) {
                    $table->string('campus')->nullable()->after('status');
                }
                if (!Schema::hasColumn('grades', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
                }
            });
        }

        // Add campus and school_id to grade_entries table
        if (Schema::hasTable('grade_entries')) {
            Schema::table('grade_entries', function (Blueprint $table) {
                if (!Schema::hasColumn('grade_entries', 'campus')) {
                    $table->string('campus')->nullable()->after('status');
                }
                if (!Schema::hasColumn('grade_entries', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
                }
            });
        }

        // Add campus and school_id to attendance table
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (!Schema::hasColumn('attendance', 'campus')) {
                    $table->string('campus')->nullable()->after('status');
                }
                if (!Schema::hasColumn('attendance', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
                }
            });
        }

        // Add campus and school_id to student_attendance table
        if (Schema::hasTable('student_attendance')) {
            Schema::table('student_attendance', function (Blueprint $table) {
                if (!Schema::hasColumn('student_attendance', 'campus')) {
                    $table->string('campus')->nullable()->after('status');
                }
                if (!Schema::hasColumn('student_attendance', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
                }
            });
        }

        // Update existing records with campus and school_id from related models
        $this->updateExistingRecords();
    }

    /**
     * Update existing records with campus and school_id
     */
    private function updateExistingRecords(): void
    {
        // Update grades table
        if (Schema::hasTable('grades')) {
            DB::statement("
                UPDATE grades g
                JOIN classes c ON g.class_id = c.id
                SET g.campus = c.campus, g.school_id = c.school_id
                WHERE g.campus IS NULL AND c.campus IS NOT NULL
            ");
        }

        // Update grade_entries table
        if (Schema::hasTable('grade_entries')) {
            DB::statement("
                UPDATE grade_entries ge
                JOIN classes c ON ge.class_id = c.id
                SET ge.campus = c.campus, ge.school_id = c.school_id
                WHERE ge.campus IS NULL AND c.campus IS NOT NULL
            ");
        }

        // Update attendance table
        if (Schema::hasTable('attendance')) {
            DB::statement("
                UPDATE attendance a
                JOIN classes c ON a.class_id = c.id
                SET a.campus = c.campus, a.school_id = c.school_id
                WHERE a.campus IS NULL AND c.campus IS NOT NULL
            ");
        }

        // Update student_attendance table
        if (Schema::hasTable('student_attendance')) {
            DB::statement("
                UPDATE student_attendance sa
                JOIN students s ON sa.student_id = s.id
                SET sa.campus = s.campus, sa.school_id = s.school_id
                WHERE sa.campus IS NULL AND s.campus IS NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['grades', 'grade_entries', 'attendance', 'student_attendance'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($tableName, 'school_id')) {
                        $table->dropForeign(['school_id']);
                        $table->dropColumn('school_id');
                    }
                    if (Schema::hasColumn($tableName, 'campus')) {
                        $table->dropColumn('campus');
                    }
                });
            }
        }
    }
};