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
        // Add attendance settings to classes table
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (!Schema::hasColumn('classes', 'total_meetings_midterm')) {
                    $table->integer('total_meetings_midterm')->default(18)->after('school_year');
                }
                if (!Schema::hasColumn('classes', 'total_meetings_final')) {
                    $table->integer('total_meetings_final')->default(18)->after('total_meetings_midterm');
                }
                if (!Schema::hasColumn('classes', 'attendance_weight')) {
                    $table->decimal('attendance_weight', 5, 2)->default(10)->after('total_meetings_final');
                }
                if (!Schema::hasColumn('classes', 'enable_e_signature')) {
                    $table->boolean('enable_e_signature')->default(true)->after('attendance_weight');
                }
                if (!Schema::hasColumn('classes', 'require_e_signature')) {
                    $table->boolean('require_e_signature')->default(true)->after('enable_e_signature');
                }
            });
        }

        // Add e-signature fields to students table
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'e_signature')) {
                    $table->longText('e_signature')->nullable()->after('password');
                }
                if (!Schema::hasColumn('students', 'signature_date')) {
                    $table->timestamp('signature_date')->nullable()->after('e_signature');
                }
            });
        }

        // Add e-signature fields to attendance table
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (!Schema::hasColumn('attendance', 'e_signature')) {
                    $table->longText('e_signature')->nullable();
                }
                if (!Schema::hasColumn('attendance', 'signature_type')) {
                    $table->enum('signature_type', ['manual', 'e-signature', 'biometric'])->default('manual');
                }
                if (!Schema::hasColumn('attendance', 'signature_timestamp')) {
                    $table->timestamp('signature_timestamp')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback classes table changes
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                $columns = ['total_meetings_midterm', 'total_meetings_final', 'attendance_weight', 'enable_e_signature', 'require_e_signature'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('classes', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        // Rollback students table changes
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $columns = ['e_signature', 'signature_date'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('students', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        // Rollback attendance table changes
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                $columns = ['e_signature', 'signature_type', 'signature_timestamp'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('attendance', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
