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
        Schema::table('grade_entries', function (Blueprint $table) {
            // Add simplified grade columns for easier grade entry
            $table->decimal('exam', 5, 2)->nullable()->after('quiz_5');
            $table->decimal('output', 5, 2)->nullable()->after('assignment_3');
            $table->decimal('class_participation', 5, 2)->nullable()->after('output');
            $table->decimal('activities', 5, 2)->nullable()->after('class_participation');
            $table->decimal('behavior', 5, 2)->nullable()->after('awareness_3');
            $table->decimal('awareness', 5, 2)->nullable()->after('behavior');
            $table->decimal('final_grade', 5, 2)->nullable()->after('term_grade');
            $table->timestamp('graded_at')->nullable()->after('final_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_entries', function (Blueprint $table) {
            $table->dropColumn([
                'exam',
                'output',
                'class_participation',
                'activities',
                'behavior',
                'awareness',
                'final_grade',
                'graded_at'
            ]);
        });
    }
};
