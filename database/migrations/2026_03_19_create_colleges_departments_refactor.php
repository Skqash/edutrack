<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Refactors courses to use proper foreign keys for colleges and departments
     * instead of plain text columns.
     */
    public function up(): void
    {
        // Create colleges table
        if (!Schema::hasTable('colleges')) {
            Schema::create('colleges', function (Blueprint $table) {
                $table->id();
                $table->string('college_name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create departments table
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('department_name')->unique();
                $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Update courses table to use FK instead of plain text
        Schema::table('courses', function (Blueprint $table) {
            // Add new FK columns
            if (!Schema::hasColumn('courses', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('program_name')->constrained('departments')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('courses', 'program_head_id')) {
                $table->foreignId('program_head_id')->nullable()->after('department_id')->constrained('users')->onDelete('set null');
            }

            // Add total_years column
            if (!Schema::hasColumn('courses', 'total_years')) {
                $table->unsignedTinyInteger('total_years')->default(4)->after('program_name')->comment('Total years in program (typically 4)');
            }

            // Keep old columns for now (will drop in future migration once data is migrated)
            // They'll be deprecated but won't break existing code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'program_head_id')) {
                $table->dropForeign(['program_head_id']);
                $table->dropColumn('program_head_id');
            }
            
            if (Schema::hasColumn('courses', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            if (Schema::hasColumn('courses', 'total_years')) {
                $table->dropColumn('total_years');
            }
        });

        Schema::dropIfExists('departments');
        Schema::dropIfExists('colleges');
    }
};
