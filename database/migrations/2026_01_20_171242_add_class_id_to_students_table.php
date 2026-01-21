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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'class_id')) {
                $table->foreignId('class_id')->nullable()->after('user_id')->constrained('classes')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'admission_number')) {
                $table->string('admission_number')->nullable()->after('student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop foreign key first, then column
            if (Schema::hasColumn('students', 'class_id')) {
                try {
                    $table->dropForeign(['class_id']);
                } catch (\Exception $e) {
                    // Foreign key may not exist
                }
                $table->dropColumn('class_id');
            }
            if (Schema::hasColumn('students', 'admission_number')) {
                $table->dropColumn('admission_number');
            }
        });
    }
};
