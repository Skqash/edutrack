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
            // Add grade_point column (4.0 scale CHED Philippines)
            if (!Schema::hasColumn('grades', 'grade_point')) {
                $table->decimal('grade_point', 3, 2)->nullable()->after('final_grade')->comment('CHED Grade Point (4.0 scale)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'grade_point')) {
                $table->dropColumn('grade_point');
            }
        });
    }
};
