<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->nullable()->unique();
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable();
            }
            if (!Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            }
            if (!Schema::hasColumn('students', 'course_id')) {
                $table->unsignedBigInteger('course_id')->nullable();
            }
            if (!Schema::hasColumn('students', 'enrollment_date')) {
                $table->date('enrollment_date')->nullable()->default(now());
            }
            if (!Schema::hasColumn('students', 'academic_year')) {
                $table->string('academic_year')->default('2024-2025');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

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
