<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create schools table and reform campus structure for proper data isolation
     */
    public function up(): void
    {
        // Create schools table
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_code')->unique(); // e.g., 'CPSU-MAIN', 'CPSU-VIC', 'CPSU-SIP'
            $table->string('school_name'); // e.g., 'CPSU Main Campus - Kabankalan'
            $table->string('short_name'); // e.g., 'Kabankalan', 'Victorias', 'Sipalay'
            $table->enum('campus_type', ['main', 'satellite'])->default('satellite');
            $table->string('location'); // Full address
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

        // Update users table to use school_id instead of campus string
        Schema::table('users', function (Blueprint $table) {
            // Add school_id foreign key
            $table->foreignId('school_id')->nullable()->after('campus_status')->constrained('schools')->onDelete('set null');
            
            // Keep campus field for backward compatibility but make it nullable
            if (Schema::hasColumn('users', 'campus')) {
                $table->string('campus')->nullable()->change();
            }
        });

        // Update students table to use school_id
        Schema::table('students', function (Blueprint $table) {
            // Add school_id foreign key
            $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
            
            // Keep campus field for backward compatibility
            if (Schema::hasColumn('students', 'campus')) {
                $table->string('campus')->nullable()->change();
            }
        });

        // Update courses table to use school_id
        Schema::table('courses', function (Blueprint $table) {
            // Add school_id foreign key
            $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
            
            // Keep campus field for backward compatibility
            if (Schema::hasColumn('courses', 'campus')) {
                $table->string('campus')->nullable()->change();
            }
        });

        // Update subjects table to use school_id
        Schema::table('subjects', function (Blueprint $table) {
            // Add school_id foreign key
            $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
            
            // Keep campus field for backward compatibility
            if (Schema::hasColumn('subjects', 'campus')) {
                $table->string('campus')->nullable()->change();
            }
        });

        // Update classes table to use school_id
        Schema::table('classes', function (Blueprint $table) {
            // Add school_id foreign key
            $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
            
            // Keep campus field for backward compatibility
            if (Schema::hasColumn('classes', 'campus')) {
                $table->string('campus')->nullable()->change();
            }
        });

        // Update other tables that might have campus field
        $tablesWithCampus = [
            'grades', 'grade_entries', 'attendance', 'student_attendance',
            'assessment_components', 'assessment_ranges', 'component_averages',
            'component_entries', 'grading_scale_settings', 'ksa_settings',
            'teacher_assignments', 'assignment_students', 'teacher_subject',
            'notifications'
        ];

        foreach ($tablesWithCampus as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Add school_id foreign key
                    $table->foreignId('school_id')->nullable()->after('campus')->constrained('schools')->onDelete('set null');
                    
                    // Keep campus field for backward compatibility
                    if (Schema::hasColumn($tableName, 'campus')) {
                        $table->string('campus')->nullable()->change();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove school_id from all tables
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
                    $table->dropForeign(['school_id']);
                    $table->dropColumn('school_id');
                });
            }
        }

        // Drop schools table
        Schema::dropIfExists('schools');
    }
};