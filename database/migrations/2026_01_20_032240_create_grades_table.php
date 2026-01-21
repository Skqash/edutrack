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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('set null');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Traditional grading columns
            $table->decimal('marks_obtained', 8, 2)->nullable();
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->enum('grade', ['A+', 'A', 'B', 'C', 'D', 'F'])->nullable();
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            
            // KSA Grading System columns
            $table->decimal('knowledge_score', 5, 2)->nullable()->comment('Knowledge (0-100)');
            $table->decimal('skills_score', 5, 2)->nullable()->comment('Skills (0-100)');
            $table->decimal('attitude_score', 5, 2)->nullable()->comment('Attitude (0-100)');
            $table->decimal('final_grade', 5, 2)->nullable()->comment('Final Grade (0-100) = (K*0.3 + S*0.4 + A*0.3)');
            
            $table->text('remarks')->nullable();
            $table->string('grading_period')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'subject_id', 'semester', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
