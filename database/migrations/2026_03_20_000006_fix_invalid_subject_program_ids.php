<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix subjects with invalid program_id
     * Some BSHM subjects have program_id=4 which doesn't exist
     * Map them to program_id=3 (BSHM)
     */
    public function up(): void
    {
        // Fix subjects with program_id=4 (non-existent program)
        DB::table('subjects')
            ->where('program_id', '=', 4)
            ->update([
                'program_id' => 3,  // Map to BSHM
            ]);

        // Also fix any other invalid program_ids
        DB::statement(
            "UPDATE subjects SET program_id = CASE
                WHEN program_id > 3 THEN 3
                ELSE program_id
            END
            WHERE program_id > 3"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot easily reverse, would need to know original values
        // This is a data cleanup migration
    }
};
