<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if attendance table exists, if not create it
        if (!Schema::hasTable('attendance')) {
            Schema::create('attendance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
                $table->date('date');
                $table->enum('status', ['Present', 'Absent', 'Late', 'Leave'])->default('Present');
                $table->enum('term', ['Midterm', 'Final'])->default('Midterm');
                $table->text('remarks')->nullable();
                $table->timestamps();
                
                $table->unique(['student_id', 'class_id', 'date', 'term']);
            });
        } else {
            // Add term column if it doesn't exist
            if (!Schema::hasColumn('attendance', 'term')) {
                Schema::table('attendance', function (Blueprint $table) {
                    $table->enum('term', ['Midterm', 'Final'])->default('Midterm')->after('status');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('attendance', 'term')) {
            Schema::table('attendance', function (Blueprint $table) {
                $table->dropColumn('term');
            });
        }
    }
};
