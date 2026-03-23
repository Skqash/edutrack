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
            // Skills Individual Entries
            // Output Entries (O1, O2, O3)
            $table->decimal('output_1', 5, 2)->nullable()->after('final_exam');
            $table->decimal('output_2', 5, 2)->nullable()->after('output_1');
            $table->decimal('output_3', 5, 2)->nullable()->after('output_2');
            
            // Class Participation Entries (CP1, CP2, CP3)
            $table->decimal('class_participation_1', 5, 2)->nullable()->after('output_3');
            $table->decimal('class_participation_2', 5, 2)->nullable()->after('class_participation_1');
            $table->decimal('class_participation_3', 5, 2)->nullable()->after('class_participation_2');
            
            // Activities Entries (Act1, Act2, Act3)
            $table->decimal('activities_1', 5, 2)->nullable()->after('class_participation_3');
            $table->decimal('activities_2', 5, 2)->nullable()->after('activities_1');
            $table->decimal('activities_3', 5, 2)->nullable()->after('activities_2');
            
            // Assignments Entries (Asg1, Asg2, Asg3)
            $table->decimal('assignments_1', 5, 2)->nullable()->after('activities_3');
            $table->decimal('assignments_2', 5, 2)->nullable()->after('assignments_1');
            $table->decimal('assignments_3', 5, 2)->nullable()->after('assignments_2');
            
            // Attitude Individual Entries
            // Behavior Entries (B1, B2, B3)
            $table->decimal('behavior_1', 5, 2)->nullable()->after('assignments_3');
            $table->decimal('behavior_2', 5, 2)->nullable()->after('behavior_1');
            $table->decimal('behavior_3', 5, 2)->nullable()->after('behavior_2');
            
            // Awareness Entries (A1, A2, A3)
            $table->decimal('awareness_1', 5, 2)->nullable()->after('behavior_3');
            $table->decimal('awareness_2', 5, 2)->nullable()->after('awareness_1');
            $table->decimal('awareness_3', 5, 2)->nullable()->after('awareness_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $columns = [
                'output_1', 'output_2', 'output_3',
                'class_participation_1', 'class_participation_2', 'class_participation_3',
                'activities_1', 'activities_2', 'activities_3',
                'assignments_1', 'assignments_2', 'assignments_3',
                'behavior_1', 'behavior_2', 'behavior_3',
                'awareness_1', 'awareness_2', 'awareness_3',
            ];
            
            // Only drop columns that exist
            $existingColumns = [];
            foreach ($columns as $column) {
                if (Schema::hasColumn('grades', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
