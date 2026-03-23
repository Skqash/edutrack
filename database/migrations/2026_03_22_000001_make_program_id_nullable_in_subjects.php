<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make program_id nullable to allow independent teacher-created subjects
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Make program_id nullable
            $table->unsignedBigInteger('program_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Revert to not nullable (but this might fail if there are null values)
            $table->unsignedBigInteger('program_id')->nullable(false)->change();
        });
    }
};
