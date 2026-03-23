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
        // Add campus field to courses table for data separation
        if (!Schema::hasColumn('courses', 'campus')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('status');
                $table->index('campus');
            });
        }

        // Add campus field to subjects table for data separation
        if (!Schema::hasColumn('subjects', 'campus')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('program_id');
                $table->index('campus');
            });
        }

        // Add campus field to students table for data separation
        if (!Schema::hasColumn('students', 'campus')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('status');
                $table->index('campus');
            });
        }

        // Add campus field to classes table for data separation
        if (!Schema::hasColumn('classes', 'campus')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->string('campus')->nullable()->after('status');
                $table->index('campus');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('campus');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('campus');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('campus');
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('campus');
        });
    }
};