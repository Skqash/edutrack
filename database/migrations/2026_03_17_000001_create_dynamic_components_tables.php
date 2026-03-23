<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create assessment_components table for flexible component configuration
        Schema::create('assessment_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            
            // Component Definition
            $table->enum('category', ['Knowledge', 'Skills', 'Attitude'])->comment('KSA Category');
            $table->string('subcategory', 50)->comment('Type: Quiz, Exam, Output, Activity, etc');
            $table->string('name', 50)->comment('Display name for component');
            $table->integer('max_score')->default(100)->comment('Maximum possible score');
            $table->decimal('weight', 5, 2)->default(0)->comment('Weight % within category');
            $table->integer('order')->default(0)->comment('Display order');
            $table->boolean('is_active')->default(true)->comment('Soft delete flag');
            
            $table->timestamps();
            
            // Index for quick lookups
            $table->index(['class_id', 'category']);
            $table->index(['class_id', 'teacher_id']);
        });

        // Create component_entries table for individual student scores
        Schema::create('component_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('assessment_components')->onDelete('cascade');
            
            // Entry Data
            $table->enum('term', ['midterm', 'final']);
            $table->decimal('raw_score', 5, 2)->nullable()->comment('Raw input score');
            $table->decimal('normalized_score', 5, 2)->nullable()->comment('Auto-calculated 0-100');
            $table->text('remarks')->nullable();
            
            $table->timestamps();
            
            // Unique constraint per student per component per term
            $table->unique(['student_id', 'component_id', 'term']);
            
            // Indexes for queries
            $table->index(['class_id', 'term']);
            $table->index(['student_id', 'term']);
        });

        // Create component_averages table for caching calculated averages
        Schema::create('component_averages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            
            // Category Averages
            $table->enum('term', ['midterm', 'final']);
            $table->decimal('knowledge_average', 5, 2)->nullable()->comment('0-100 average for Knowledge');
            $table->decimal('skills_average', 5, 2)->nullable()->comment('0-100 average for Skills');
            $table->decimal('attitude_average', 5, 2)->nullable()->comment('0-100 average for Attitude');
            $table->decimal('final_grade', 5, 2)->nullable()->comment('Final computed grade');
            
            $table->timestamps();
            
            // Unique per student per class per term
            $table->unique(['student_id', 'class_id', 'term']);
            $table->index(['class_id', 'term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('component_averages');
        Schema::dropIfExists('component_entries');
        Schema::dropIfExists('assessment_components');
    }
};
