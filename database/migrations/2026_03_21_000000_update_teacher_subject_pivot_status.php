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
        Schema::table('teacher_subject', function (Blueprint $table) {
            // if the column exists and does not include pending
            if (Schema::hasColumn('teacher_subject', 'status')) {
                $table->enum('status', ['active', 'inactive', 'pending'])
                    ->default('pending')
                    ->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subject', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_subject', 'status')) {
                $table->enum('status', ['active', 'inactive'])
                    ->default('active')
                    ->change();
            }
        });
    }
};