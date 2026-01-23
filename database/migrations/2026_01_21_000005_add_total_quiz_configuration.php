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
        Schema::table('assessment_ranges', function (Blueprint $table) {
            // Add quiz configuration fields
            $table->integer('total_quiz_items')->default(100)->after('attendance_required');
            $table->integer('num_quizzes')->default(5)->after('total_quiz_items');
            $table->boolean('equal_quiz_distribution')->default(true)->after('num_quizzes');
            
            // Optional: custom distribution per quiz (JSON for flexibility)
            $table->json('quiz_distribution')->nullable()->after('equal_quiz_distribution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_ranges', function (Blueprint $table) {
            $table->dropColumn('total_quiz_items');
            $table->dropColumn('num_quizzes');
            $table->dropColumn('equal_quiz_distribution');
            $table->dropColumn('quiz_distribution');
        });
    }
};
