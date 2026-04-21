<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create super_admins table if it doesn't exist
        if (!Schema::hasTable('super_admins')) {
            Schema::create('super_admins', function (Blueprint $table) {
                $table->id();
                $table->string('super_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create admins table if it doesn't exist
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('admin_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Update teachers table with auth fields if needed
        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (!Schema::hasColumn('teachers', 'email')) {
                    $table->string('email')->unique()->nullable();
                }
                if (!Schema::hasColumn('teachers', 'password')) {
                    $table->string('password')->nullable();
                }
                if (!Schema::hasColumn('teachers', 'first_name')) {
                    $table->string('first_name')->nullable();
                }
                if (!Schema::hasColumn('teachers', 'last_name')) {
                    $table->string('last_name')->nullable();
                }
                if (!Schema::hasColumn('teachers', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('teachers', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active');
                }
                if (!Schema::hasColumn('teachers', 'remember_token')) {
                    $table->rememberToken();
                }
            });
        }

        // Update students table with auth fields if needed
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'email')) {
                    $table->string('email')->unique()->nullable();
                }
                if (!Schema::hasColumn('students', 'password')) {
                    $table->string('password')->nullable();
                }
                if (!Schema::hasColumn('students', 'first_name')) {
                    $table->string('first_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'last_name')) {
                    $table->string('last_name')->nullable();
                }
                if (!Schema::hasColumn('students', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (!Schema::hasColumn('students', 'e_signature')) {
                    $table->text('e_signature')->nullable();
                }
                if (!Schema::hasColumn('students', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active');
                }
                if (!Schema::hasColumn('students', 'remember_token')) {
                    $table->rememberToken();
                }
            });
        }

        // Update attendance table to store e-signatures
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (!Schema::hasColumn('attendance', 'e_signature')) {
                    $table->text('e_signature')->nullable();
                }
                if (!Schema::hasColumn('attendance', 'signature_type')) {
                    $table->string('signature_type')->default('manual');
                }
            });
        }

        // Migrate existing data (only if admins table is empty)
        if (DB::table('admins')->count() === 0) {
            $this->migrateExistingData();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop super_admins table if exists
        if (Schema::hasTable('super_admins')) {
            Schema::drop('super_admins');
        }
        // Drop admins table if exists
        if (Schema::hasTable('admins')) {
            Schema::drop('admins');
        }
        // Drop columns from teachers table if they exist
        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                $columns = ['email', 'password', 'first_name', 'last_name', 'phone', 'status', 'remember_token'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('teachers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
        // Drop columns from students table if they exist
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                $columns = ['email', 'password', 'first_name', 'last_name', 'phone', 'e_signature', 'status', 'remember_token'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('students', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
        // Drop columns from attendance table if they exist
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                $columns = ['e_signature', 'signature_type'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('attendance', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }



    /**
     * Migrate existing data from users table to separate tables
     */
    private function migrateExistingData()
    {
        // Get all existing users
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            switch ($user->role) {
                case 'super':
                case 'superadmin':
                    DB::table('super_admins')->insert([
                        'super_id' => 'SUPER_' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'password' => $user->password,
                        'phone' => $user->phone,
                        'status' => $user->status ?? 'active',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                    break;

                case 'admin':
                    DB::table('admins')->insert([
                        'admin_id' => 'ADMIN_' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'password' => $user->password,
                        'phone' => $user->phone,
                        'school_id' => $user->school_id,
                        'status' => $user->status ?? 'active',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                    break;

                case 'teacher':
                    DB::table('teachers')->insert([
                        'teacher_id' => $user->employee_id ?? 'TEACHER_' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'password' => $user->password,
                        'phone' => $user->phone,
                        'school_id' => $user->school_id,
                        'qualification' => $user->qualification,
                        'status' => $user->status ?? 'active',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                    break;

                case 'student':
                    DB::table('students')->insert([
                        'student_id' => $user->id, // Keep existing student_id
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'password' => $user->password,
                        'phone' => $user->phone,
                        'school_id' => $user->school_id,
                        'roll_number' => null, // Will be set later if needed
                        'gpa' => 0,
                        'status' => $user->status ?? 'active',
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]);
                    break;
            }
        }
    }
};
