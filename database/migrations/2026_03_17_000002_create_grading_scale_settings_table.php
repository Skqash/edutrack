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
        Schema::create('grading_scale_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id');
            $table->enum('term', ['midterm', 'final']);
            
            // Flexible KSA Percentages
            $table->decimal('knowledge_percentage', 5, 2)->default(40.00); // % of final grade
            $table->decimal('skills_percentage', 5, 2)->default(50.00);    // % of final grade
            $table->decimal('attitude_percentage', 5, 2)->default(10.00);  // % of final grade
            
            // Validation
            $table->boolean('is_locked')->default(false); // Lock during grading period
            $table->text('description')->nullable();
            
            $table->timestamps();
            
            // Indexes and constraints
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['class_id', 'term']);
            $table->index('teacher_id');
            $table->index(['class_id', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_scale_settings');
    }
};
