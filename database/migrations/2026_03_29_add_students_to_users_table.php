<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing students from students table to users table with role='student'
        if (Schema::hasTable('students') && Schema::hasTable('users')) {
            $students = DB::table('students')
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();

            foreach ($students as $student) {
                // Check if user already exists
                $existing = DB::table('users')->where('email', $student->email)->first();
                
                $userId = null;
                if ($existing) {
                    $userId = $existing->id;
                } else {
                    $name = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                    
                    $userId = DB::table('users')->insertGetId([
                        'name' => $name ?: $student->email,
                        'email' => $student->email,
                        'password' => $student->password ?? bcrypt('student123'),
                        'phone' => $student->phone ?? null,
                        'role' => 'student',
                        'status' => $student->status ?? 'Active',
                        'school_id' => $student->school_id ?? null,
                        'campus' => $student->campus ?? null,
                        'created_at' => $student->created_at ?? now(),
                        'updated_at' => $student->updated_at ?? now(),
                    ]);
                }

                // Link the student profile to the user
                if ($userId && !$student->user_id) {
                    DB::table('students')->where('id', $student->id)->update(['user_id' => $userId]);
                }
            }

            echo "Student migration completed: " . $students->count() . " students processed\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove students from users table (keep the students table intact)
        DB::table('users')->where('role', 'student')->delete();
    }
};
