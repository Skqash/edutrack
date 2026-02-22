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
        Schema::table('grades', function (Blueprint $table) {
            // Skills Component Totals
            if (!Schema::hasColumn('grades', 'output_total')) {
                $table->decimal('output_total', 5, 2)->nullable()->comment('Sum of output_1 + output_2 + output_3');
            }
            if (!Schema::hasColumn('grades', 'class_participation_total')) {
                $table->decimal('class_participation_total', 5, 2)->nullable()->comment('Sum of class_participation_1 + 2 + 3');
            }
            if (!Schema::hasColumn('grades', 'activities_total')) {
                $table->decimal('activities_total', 5, 2)->nullable()->comment('Sum of activities_1 + activities_2 + activities_3');
            }
            if (!Schema::hasColumn('grades', 'assignments_total')) {
                $table->decimal('assignments_total', 5, 2)->nullable()->comment('Sum of assignments_1 + assignments_2 + assignments_3');
            }

            // Attitude Component Totals
            if (!Schema::hasColumn('grades', 'behavior_total')) {
                $table->decimal('behavior_total', 5, 2)->nullable()->comment('Sum of behavior_1 + behavior_2 + behavior_3');
            }
            if (!Schema::hasColumn('grades', 'awareness_total')) {
                $table->decimal('awareness_total', 5, 2)->nullable()->comment('Sum of awareness_1 + awareness_2 + awareness_3');
            }

            // Decimal Grade (for display and reporting)
            if (!Schema::hasColumn('grades', 'decimal_grade')) {
                $table->decimal('decimal_grade', 5, 2)->nullable()->comment('Decimal representation of final grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $columns = [
                'output_total',
                'class_participation_total',
                'activities_total',
                'assignments_total',
                'behavior_total',
                'awareness_total',
                'decimal_grade'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('grades', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
