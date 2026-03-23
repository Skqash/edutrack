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
        Schema::table('subjects', function (Blueprint $table) {
            // Add school year and semester columns if they don't exist
            if (!Schema::hasColumn('subjects', 'school_year')) {
                $table->string('school_year')->default('2025-2026')->after('category');
            }
            if (!Schema::hasColumn('subjects', 'semester')) {
                $table->enum('semester', ['1', '2'])->default('1')->after('school_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'school_year')) {
                $table->dropColumn('school_year');
            }
            if (Schema::hasColumn('subjects', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }
};
