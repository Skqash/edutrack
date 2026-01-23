<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add support for quiz columns 6-10 to support flexible quiz configuration
     */
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            // Add additional quiz columns (Q6-Q10) for flexibility
            // These are nullable to maintain backward compatibility
            
            if (!Schema::hasColumn('grades', 'q6')) {
                $table->decimal('q6', 5, 2)->nullable()->after('q5');
            }
            if (!Schema::hasColumn('grades', 'q7')) {
                $table->decimal('q7', 5, 2)->nullable()->after('q6');
            }
            if (!Schema::hasColumn('grades', 'q8')) {
                $table->decimal('q8', 5, 2)->nullable()->after('q7');
            }
            if (!Schema::hasColumn('grades', 'q9')) {
                $table->decimal('q9', 5, 2)->nullable()->after('q8');
            }
            if (!Schema::hasColumn('grades', 'q10')) {
                $table->decimal('q10', 5, 2)->nullable()->after('q9');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn(['q6', 'q7', 'q8', 'q9', 'q10']);
        });
    }
};
