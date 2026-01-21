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
        Schema::table('classes', function (Blueprint $table) {
            // Add year field for 4-year course tracking (1st to 4th year)
            if (!Schema::hasColumn('classes', 'year')) {
                $table->enum('year', ['1st', '2nd', '3rd', '4th'])->default('1st')->after('section');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'year')) {
                $table->dropColumn('year');
            }
        });
    }
};
