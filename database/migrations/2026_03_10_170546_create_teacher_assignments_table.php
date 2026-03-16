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
        if (!Schema::hasTable('teacher_assignments')) {
            Schema::create('teacher_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('cascade');
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
                $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
                $table->string('department')->nullable();
                $table->string('academic_year')->default('2024-2025');
                $table->string('semester')->default('First');
                $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamp('assigned_at')->default(now());
                $table->timestamps();

                // Indexes for better performance
                $table->index(['teacher_id', 'status']);
                $table->index(['department', 'academic_year']);
                $table->index(['class_id', 'subject_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
