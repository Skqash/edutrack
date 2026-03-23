<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure students table to be independent from users table
     * Students will have their own authentication and personal details
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove user_id foreign key if it exists
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Add personal information fields
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->after('student_id');
            }
            
            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->after('middle_name');
            }
            
            // Add email field for student communication
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->unique()->after('last_name');
            }
            
            // Add password field for student login (optional)
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            
            // Add contact information
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            
            // Add birth date
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('address');
            }
            
            // Add gender
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('birth_date');
            }
            
            // Add course_id if not exists (link to programs)
            if (!Schema::hasColumn('students', 'course_id')) {
                $table->foreignId('course_id')->nullable()->after('gender')->constrained('courses')->onDelete('set null');
            }
            
            // Ensure year_level exists
            if (!Schema::hasColumn('students', 'year_level')) {
                $table->integer('year_level')->default(1)->after('course_id');
            }
            
            // Add enrollment date
            if (!Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->default(now())->after('year_level');
            }
            
            // Add academic year
            if (!Schema::hasColumn('students', 'academic_year')) {
                $table->string('academic_year')->default('2024-2025')->after('enrollment_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add back user_id foreign key
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            
            // Remove the new fields
            $columns = [
                'first_name', 'middle_name', 'last_name', 'email', 'password',
                'phone', 'address', 'birth_date', 'gender', 'enrollment_date', 'academic_year'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};