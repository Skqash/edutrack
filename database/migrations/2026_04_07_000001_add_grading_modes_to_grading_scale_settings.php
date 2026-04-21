<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds support for multiple grading modes:
     * - standard: Traditional KSA model
     * - manual: Teachers manually enter all grades
     * - automated: System auto-calculates from components
     * - hybrid: Mix of manual and automated components
     */
    public function up(): void
    {
        Schema::table('grading_scale_settings', function (Blueprint $table) {
            // Grading Mode Selection
            $table->enum('grading_mode', ['standard', 'manual', 'automated', 'hybrid'])
                ->default('standard')
                ->comment('Grading mode: standard, manual, automated, or hybrid');
            
            // Quiz Entry Modes
            $table->enum('quiz_entry_mode', ['manual', 'automated', 'both'])
                ->default('both')
                ->comment('How quizzes are entered: manual, automated, or both');
            
            // Hybrid Mode Components (stored as JSON for flexibility)
            $table->json('hybrid_components_config')->nullable()
                ->comment('For hybrid mode: component-level settings (manual/automated)');
            
            // Output Format
            $table->enum('output_format', ['standard', 'detailed', 'summary'])
                ->default('standard')
                ->comment('Grading sheet output format');
            
            // Enable Features
            $table->boolean('enable_esignature')->default(true)
                ->comment('Allow e-signature uploads for attendance');
            $table->boolean('enable_auto_calculation')->default(true)
                ->comment('Enable automatic grade calculation');
            $table->boolean('enable_weighted_components')->default(true)
                ->comment('Enable component weighting');
            
            // Thresholds and Settings
            $table->decimal('passing_grade', 5, 2)->default(75.00)
                ->comment('Minimum passing grade');
            $table->integer('attendance_weight_percentage')->default(0)
                ->comment('Percentage weight for attendance in final grade');
            
            // Timestamp for last modified
            $table->timestamp('settings_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grading_scale_settings', function (Blueprint $table) {
            $table->dropColumn([
                'grading_mode',
                'quiz_entry_mode',
                'hybrid_components_config',
                'output_format',
                'enable_esignature',
                'enable_auto_calculation',
                'enable_weighted_components',
                'passing_grade',
                'attendance_weight_percentage',
                'settings_updated_at',
            ]);
        });
    }
};
