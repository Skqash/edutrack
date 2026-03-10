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
        Schema::create('assignment_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('teacher_assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['assigned', 'completed', 'dropped'])->default('assigned');
            $table->timestamp('assigned_at')->default(now());
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['assignment_id', 'student_id']);

            // Indexes
            $table->index(['student_id', 'status']);
            $table->index(['assignment_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_students');
    }
};
