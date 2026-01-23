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
            // Make subject_id nullable to allow classes without subjects
            if (Schema::hasColumn('assessment_ranges', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_ranges', function (Blueprint $table) {
            if (Schema::hasColumn('assessment_ranges', 'subject_id')) {
                $table->foreignId('subject_id')->nullable(false)->change();
            }
        });
    }
};
