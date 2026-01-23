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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->text('submission_content')->nullable();
            $table->string('file_path')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->float('score')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'submitted', 'graded', 'late'])->default('pending');
            $table->timestamps();
            
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
