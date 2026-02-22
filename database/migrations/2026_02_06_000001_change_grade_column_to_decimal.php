<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change grade column from enum (letter grade) to decimal (numeric grade)
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Drop the old enum column and recreate as decimal
            $table->dropColumn('grade');
        });

        Schema::table('grades', function (Blueprint $table) {
            // Add grade as decimal (0-100 numeric scale)
            $table->decimal('grade', 5, 2)->nullable()
                ->after('total_marks')
                ->comment('Numeric grade (0-100 scale) - Letter grades deprecated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Revert back to enum
            $table->dropColumn('grade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->enum('grade', ['A+', 'A', 'B', 'C', 'D', 'F'])
                ->nullable()
                ->after('total_marks');
        });
    }
};
