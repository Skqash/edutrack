<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds support for component-level grading mode configuration
     */
    public function up(): void
    {
        Schema::table('assessment_components', function (Blueprint $table) {
            // Component-level grading mode
            $table->enum('entry_mode', ['manual', 'automated', 'hybrid'])
                ->default('manual')
                ->after('weight')
                ->comment('Entry mode for this component');
            
            // For automated components
            $table->string('calculation_formula')->nullable()
                ->after('entry_mode')
                ->comment('Formula for automated calculation');
            
            // For quiz components specifically
            $table->boolean('is_quiz_component')->default(false)
                ->after('calculation_formula')
                ->comment('Is this a quiz component?');
            
            $table->enum('quiz_type', ['objective', 'subjective', 'mixed'])
                ->nullable()
                ->after('is_quiz_component')
                ->comment('Type of quiz if applicable');
            
            // Component visibility and rules
            $table->boolean('show_in_report')->default(true)
                ->comment('Display in grading sheet');
            
            $table->integer('min_attempts')->default(1)
                ->comment('Minimum number of attempts');
            
            $table->boolean('use_best_attempt')->default(false)
                ->comment('Use best attempt score for automated');
            
            $table->boolean('use_average_attempt')->default(false)
                ->comment('Use average of all attempts for automated');
            
            $table->json('component_metadata')->nullable()
                ->comment('Additional metadata for component');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_components', function (Blueprint $table) {
            $table->dropColumn([
                'entry_mode',
                'calculation_formula',
                'is_quiz_component',
                'quiz_type',
                'show_in_report',
                'min_attempts',
                'use_best_attempt',
                'use_average_attempt',
                'component_metadata',
            ]);
        });
    }
};
