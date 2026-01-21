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
        Schema::create('student_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            
            $table->enum('term', ['midterm', 'final'])->default('midterm');
            $table->float('attendance_score')->default(0); // Percentage
            $table->integer('total_classes')->default(0);
            $table->integer('present_classes')->default(0);
            $table->integer('absent_classes')->default(0);
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            
            // Composite unique key
            $table->unique(['student_id', 'class_id', 'subject_id', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance');
    }
};
