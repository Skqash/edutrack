<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates table for storing grading sheet templates
     */
    public function up(): void
    {
        Schema::create('grading_sheet_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Template Info
            $table->string('name')->comment('Template name');
            $table->string('slug')->unique()->comment('URL slug');
            $table->text('description')->nullable();
            $table->enum('template_type', ['standard', 'detailed', 'summary', 'ksa_only', 'component_detail'])
                ->default('standard')->comment('Type of template');
            
            // Template Configuration (JSON)
            $table->json('header_config')->nullable()->comment('Header section configuration');
            $table->json('columns_config')->nullable()->comment('Visible columns configuration');
            $table->json('calculations_config')->nullable()->comment('Which calculations to show');
            $table->json('styling_config')->nullable()->comment('Font, colors, formatting');
            
            // Sections
            $table->json('sections')->nullable()->comment('Sections to include: [signature, grade_scale, legend, remarks]');
            
            // Features
            $table->boolean('include_components')->default(true)->comment('Show individual components');
            $table->boolean('include_ksa_breakdown')->default(true)->comment('Show KSA breakdown');
            $table->boolean('include_final_grade')->default(true)->comment('Show final grade');
            $table->boolean('include_decimal_grade')->default(true)->comment('Show decimal grade');
            $table->boolean('include_remarks')->default(true)->comment('Show remarks/comments');
            $table->boolean('include_signatures')->default(false)->comment('Include e-signatures');
            $table->boolean('include_grade_scale_legend')->default(true)->comment('Show grade scale legend');
            
            // Customization
            $table->integer('columns_per_page')->default(5)->comment('Columns to display per page');
            $table->string('page_orientation')->default('portrait')->comment('Portrait or landscape');
            $table->string('font_family')->default('Arial')->comment('PDF font');
            $table->integer('font_size')->default(10)->comment('Base font size');
            
            // Usage
            $table->boolean('is_default')->default(false)->comment('Default template for school');
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0)->comment('Times used');
            $table->timestamp('last_used_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['school_id', 'is_active']);
            $table->index(['template_type']);
            $table->index(['is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_sheet_templates');
    }
};
