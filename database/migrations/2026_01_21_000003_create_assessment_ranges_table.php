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
        Schema::create('assessment_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            // Quiz Configuration (Knowledge)
            $table->integer('quiz_1_max')->default(20);
            $table->integer('quiz_2_max')->default(15);
            $table->integer('quiz_3_max')->default(25);
            $table->integer('quiz_4_max')->default(20);
            $table->integer('quiz_5_max')->default(20);
            
            // Exam Configuration (Knowledge)
            $table->integer('prelim_exam_max')->default(60);
            $table->integer('midterm_exam_max')->default(60);
            $table->integer('final_exam_max')->default(60);
            
            // Skills Components
            $table->integer('output_max')->default(100);
            $table->integer('class_participation_max')->default(100);
            $table->integer('activities_max')->default(100);
            $table->integer('assignments_max')->default(100);
            
            // Attitude Components
            $table->integer('behavior_max')->default(100);
            $table->integer('awareness_max')->default(100);
            
            // Attendance Configuration
            $table->integer('attendance_max')->default(100);
            $table->boolean('attendance_required')->default(true);
            
            // General Settings
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Composite unique key
            $table->unique(['class_id', 'subject_id', 'teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_ranges');
    }
};
