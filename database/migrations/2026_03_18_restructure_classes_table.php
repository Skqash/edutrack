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
            // Add new columns
            if (!Schema::hasColumn('classes', 'semester')) {
                $table->string('semester')->nullable()->after('academic_year');
            }
            
            if (!Schema::hasColumn('classes', 'school_year')) {
                $table->string('school_year')->nullable()->after('semester');
            }
            
            // Rename capacity to total_students
            if (Schema::hasColumn('classes', 'capacity') && !Schema::hasColumn('classes', 'total_students')) {
                $table->renameColumn('capacity', 'total_students');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // Rename back
            if (Schema::hasColumn('classes', 'total_students') && !Schema::hasColumn('classes', 'capacity')) {
                $table->renameColumn('total_students', 'capacity');
            }
            
            // Drop new columns
            if (Schema::hasColumn('classes', 'school_year')) {
                $table->dropColumn('school_year');
            }
            
            if (Schema::hasColumn('classes', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }
};
