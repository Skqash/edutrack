<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any NULL semester values to '1'
        DB::table('subjects')->whereNull('semester')->update(['semester' => '1']);
        
        // Then modify the column to ensure it has a proper default and is not nullable
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('semester')->default('1')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('semester')->nullable()->change();
        });
    }
};