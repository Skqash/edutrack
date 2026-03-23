<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add campus_code field to subjects table to separate campus code from subject code
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Add campus_code field that references school_code in schools table
            $table->string('campus_code')->nullable()->after('subject_code');
            
            // Add index for better performance
            $table->index('campus_code');
        });

        // Update existing subjects to extract campus code from subject_code
        // This assumes subject codes are in format like "CPSU-VIC-CS101" or "CS101"
        DB::statement("
            UPDATE subjects s 
            JOIN schools sc ON s.school_id = sc.id 
            SET s.campus_code = sc.school_code
            WHERE s.school_id IS NOT NULL
        ");

        // For subjects without school_id, try to extract from subject_code
        DB::statement("
            UPDATE subjects 
            SET campus_code = CASE 
                WHEN subject_code LIKE 'CPSU-MAIN-%' THEN 'CPSU-MAIN'
                WHEN subject_code LIKE 'CPSU-VIC-%' THEN 'CPSU-VIC'
                WHEN subject_code LIKE 'CPSU-SIP-%' THEN 'CPSU-SIP'
                WHEN subject_code LIKE 'CPSU-CAU-%' THEN 'CPSU-CAU'
                WHEN subject_code LIKE 'CPSU-CAN-%' THEN 'CPSU-CAN'
                WHEN subject_code LIKE 'CPSU-HIN-%' THEN 'CPSU-HIN'
                WHEN subject_code LIKE 'CPSU-ILO-%' THEN 'CPSU-ILO'
                WHEN subject_code LIKE 'CPSU-HIG-%' THEN 'CPSU-HIG'
                WHEN subject_code LIKE 'CPSU-MOP-%' THEN 'CPSU-MOP'
                WHEN subject_code LIKE 'CPSU-SCA-%' THEN 'CPSU-SCA'
                ELSE 'CPSU-MAIN'
            END
            WHERE campus_code IS NULL
        ");

        // Clean up subject_code to remove campus prefix
        DB::statement("
            UPDATE subjects 
            SET subject_code = CASE 
                WHEN subject_code LIKE 'CPSU-MAIN-%' THEN SUBSTRING(subject_code, 11)
                WHEN subject_code LIKE 'CPSU-VIC-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-SIP-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-CAU-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-CAN-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-HIN-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-ILO-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-HIG-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-MOP-%' THEN SUBSTRING(subject_code, 10)
                WHEN subject_code LIKE 'CPSU-SCA-%' THEN SUBSTRING(subject_code, 10)
                ELSE subject_code
            END
            WHERE campus_code IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex(['campus_code']);
            $table->dropColumn('campus_code');
        });
    }
};