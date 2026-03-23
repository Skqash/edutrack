<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create schools table
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_code')->unique();
            $table->string('school_name');
            $table->string('short_name');
            $table->enum('campus_type', ['main', 'satellite'])->default('satellite');
            $table->string('location');
            $table->string('city');
            $table->string('province');
            $table->string('region')->default('Region VI - Western Visayas');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->date('established_date')->nullable();
            $table->timestamps();
        });

        // Add school_id to users
        if (!Schema::hasColumn('users', 'school_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
                if (Schema::hasColumn('users', 'campus')) {
                    $table->string('campus')->nullable()->change();
                }
            });
        }

        // Add school_id to students
        if (!Schema::hasColumn('students', 'school_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
                if (Schema::hasColumn('students', 'campus')) {
                    $table->string('campus')->nullable()->change();
                }
            });
        }

        // Add school_id to courses
        if (!Schema::hasColumn('courses', 'school_id')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
                if (Schema::hasColumn('courses', 'campus')) {
                    $table->string('campus')->nullable()->change();
                }
            });
        }

        // Add school_id to subjects
        if (!Schema::hasColumn('subjects', 'school_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
                if (Schema::hasColumn('subjects', 'campus')) {
                    $table->string('campus')->nullable()->change();
                }
            });
        }

        // Add school_id to classes
        if (!Schema::hasColumn('classes', 'school_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')->nullable();
                if (Schema::hasColumn('classes', 'campus')) {
                    $table->string('campus')->nullable()->change();
                }
            });
        }

        // Add school_id to other tables
        $tablesWithCampus = [
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tablesWithCampus as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->unsignedBigInteger('school_id')->nullable();
                    if (Schema::hasColumn($tableName, 'campus')) {
                        $table->string('campus')->nullable()->change();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        $tablesWithSchoolId = [
            'users', 'students', 'courses', 'subjects', 'classes',
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tablesWithSchoolId as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'school_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('school_id');
                });
            }
        }

        Schema::dropIfExists('schools');
    }
};
