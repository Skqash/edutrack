<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Populate subjects for Years 2, 3, and 4
     * Currently subjects only exist for year_level 1
     * This migration duplicates them for additional year levels
     */
    public function up(): void
    {
        // Get all current subjects (all are year_level 1)
        $year1Subjects = DB::table('subjects')
            ->where('year_level', 1)
            ->get();

        // For each year level 2, 3, 4
        foreach ([2, 3, 4] as $yearLevel) {
            foreach ($year1Subjects as $subject) {
                DB::table('subjects')->insertOrIgnore([
                    'subject_code' => $subject->subject_code . $yearLevel,  // e.g., BSIT101 becomes BSIT201, BSIT301, BSIT401
                    'subject_name' => $subject->subject_name . ' (Year ' . $yearLevel . ')',
                    'program_id' => $subject->program_id,
                    'year_level' => $yearLevel,
                    'semester' => $subject->semester,
                    'category' => $subject->category,
                    'credit_hours' => $subject->credit_hours,
                    'description' => $subject->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove subjects for years 2, 3, 4 (keep only year 1)
        DB::table('subjects')->whereIn('year_level', [2, 3, 4])->delete();
    }
};
