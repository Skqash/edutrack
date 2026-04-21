<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds component weight automation mode:
     * - manual: Manual weight changes, no auto-distribution (total must = 100%)
     * - semi-auto: Change one component → others auto-adjust to fill remaining percentage
     * - auto: Change one component → all components get same weight (requires 2+ components)
     */
    public function up(): void
    {
        Schema::table('grading_scale_settings', function (Blueprint $table) {
            $table->enum('component_weight_mode', ['manual', 'semi-auto', 'auto'])
                ->default('semi-auto')
                ->comment('Component weight automation: manual, semi-auto, or auto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grading_scale_settings', function (Blueprint $table) {
            $table->dropColumn('component_weight_mode');
        });
    }
};
