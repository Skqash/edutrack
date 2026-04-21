<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->string('role')->default('student');
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->string('employee_id')->nullable()->unique();
                $table->string('qualification')->nullable();
                $table->string('specialization')->nullable();
                $table->string('department')->nullable();
                $table->string('campus')->nullable();
                $table->string('connected_school')->nullable();
                $table->text('bio')->nullable();
                $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('student')->after('email');
                }
                if (! Schema::hasColumn('users', 'status')) {
                    $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('role');
                }
                if (! Schema::hasColumn('users', 'employee_id')) {
                    $table->string('employee_id')->nullable()->unique()->after('status');
                }
                if (! Schema::hasColumn('users', 'qualification')) {
                    $table->string('qualification')->nullable()->after('employee_id');
                }
                if (! Schema::hasColumn('users', 'specialization')) {
                    $table->string('specialization')->nullable()->after('qualification');
                }
                if (! Schema::hasColumn('users', 'department')) {
                    $table->string('department')->nullable()->after('specialization');
                }
                if (! Schema::hasColumn('users', 'campus')) {
                    $table->string('campus')->nullable()->after('department');
                }
                if (! Schema::hasColumn('users', 'connected_school')) {
                    $table->string('connected_school')->nullable()->after('campus');
                }
                if (! Schema::hasColumn('users', 'bio')) {
                    $table->text('bio')->nullable()->after('connected_school');
                }
                if (! Schema::hasColumn('users', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete()->after('connected_school');
                }
            });
        }

        if (Schema::hasTable('super_admins')) {
            Schema::table('super_admins', function (Blueprint $table) {
                if (! Schema::hasColumn('super_admins', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
            });

            $superadmins = DB::table('super_admins')
                ->whereNull('user_id')
                ->get();

            foreach ($superadmins as $superadmin) {
                if (! empty($superadmin->email)) {
                    $userId = DB::table('users')->where('email', $superadmin->email)->value('id');
                    if ($userId) {
                        DB::table('super_admins')
                            ->where('id', $superadmin->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            }
        }

        if (Schema::hasTable('admins')) {
            Schema::table('admins', function (Blueprint $table) {
                if (! Schema::hasColumn('admins', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
            });

            $admins = DB::table('admins')
                ->whereNull('user_id')
                ->get();

            foreach ($admins as $admin) {
                if (! empty($admin->email)) {
                    $userId = DB::table('users')->where('email', $admin->email)->value('id');
                    if ($userId) {
                        DB::table('admins')
                            ->where('id', $admin->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            }
        }

        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (! Schema::hasColumn('teachers', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
            });

            $teachers = DB::table('teachers')
                ->whereNull('user_id')
                ->get();

            foreach ($teachers as $teacher) {
                if (! empty($teacher->email)) {
                    $userId = DB::table('users')->where('email', $teacher->email)->value('id');
                    if ($userId) {
                        DB::table('teachers')
                            ->where('id', $teacher->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            }
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (! Schema::hasColumn('students', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }
            });

            $students = DB::table('students')
                ->whereNull('user_id')
                ->get();

            foreach ($students as $student) {
                if (! empty($student->email)) {
                    $userId = DB::table('users')->where('email', $student->email)->value('id');
                    if ($userId) {
                        DB::table('students')
                            ->where('id', $student->id)
                            ->update(['user_id' => $userId]);
                    }
                }
            }
        }

        if (Schema::hasTable('users')) {
            DB::table('users')->whereNull('role')->update(['role' => 'student']);
            DB::table('users')->whereNull('status')->update(['status' => 'Active']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is additive and may touch existing tables. It is intentionally
        // non-destructive on rollback to avoid removing columns that may be shared
        // with other features or older migration histories.
    }
};
