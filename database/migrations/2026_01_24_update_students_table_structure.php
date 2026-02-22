<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Update students table structure for college system
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('students', 'admission_number')) {
                $table->dropColumn('admission_number');
            }
            if (Schema::hasColumn('students', 'roll_number')) {
                $table->dropColumn('roll_number');
            }
            
            // Add new columns for college system
            if (!Schema::hasColumn('students', 'year')) {
                $table->integer('year')->default(1)->after('student_id'); // 1st, 2nd, 3rd, 4th year
            }
            if (!Schema::hasColumn('students', 'section')) {
                $table->string('section')->nullable()->after('year'); // A, B, C, etc.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add back old columns
            if (!Schema::hasColumn('students', 'admission_number')) {
                $table->string('admission_number')->nullable();
            }
            if (!Schema::hasColumn('students', 'roll_number')) {
                $table->string('roll_number')->nullable();
            }
            
            // Remove new columns
            if (Schema::hasColumn('students', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('students', 'section')) {
                $table->dropColumn('section');
            }
        });
    }
};
