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
        Schema::create('ksa_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('term', 20)->default('midterm'); // midterm or final
            
            // KSA Weight Distribution (must sum to 100%)
            $table->decimal('knowledge_weight', 5, 2)->default(40.00); // 40%
            $table->decimal('skills_weight', 5, 2)->default(50.00);    // 50%
            $table->decimal('attitude_weight', 5, 2)->default(10.00);  // 10%
            
            // Grading Scale Settings
            $table->string('grading_scale', 50)->default('percentage'); // percentage, points, letter
            $table->boolean('use_weighted_average')->default(true);
            $table->boolean('round_final_grade')->default(true);
            $table->integer('decimal_places')->default(2);
            
            // Passing Grade Settings
            $table->decimal('passing_grade', 5, 2)->default(75.00);
            $table->decimal('minimum_attendance', 5, 2)->default(75.00);
            
            // Additional Settings
            $table->boolean('include_attendance_in_attitude')->default(true);
            $table->boolean('auto_calculate')->default(true);
            $table->json('custom_settings')->nullable(); // For future extensibility
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            
            // Unique constraint: one setting per class per term
            $table->unique(['class_id', 'term']);
            
            // Indexes for performance
            $table->index('teacher_id');
            $table->index(['class_id', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ksa_settings');
    }
};
