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
        Schema::table('ksa_settings', function (Blueprint $table) {
            $table->boolean('enable_attendance_ksa')
                ->default(true)
                ->comment('Whether attendance contributes to KSA calculation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ksa_settings', function (Blueprint $table) {
            $table->dropColumn('enable_attendance_ksa');
        });
    }
};
