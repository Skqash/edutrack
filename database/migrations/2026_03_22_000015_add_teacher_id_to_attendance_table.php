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
        // Add teacher_id to attendance table for easier querying
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (!Schema::hasColumn('attendance', 'teacher_id')) {
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
                }
            });

            // Update existing attendance records with teacher_id from classes
            DB::statement("
                UPDATE attendance a
                JOIN classes c ON a.class_id = c.id
                SET a.teacher_id = c.teacher_id
                WHERE a.teacher_id IS NULL AND c.teacher_id IS NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (Schema::hasColumn('attendance', 'teacher_id')) {
                    $table->dropForeign(['teacher_id']);
                    $table->dropColumn('teacher_id');
                }
            });
        }
    }
};