<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Link classes to programs based on class names
     * Parse class names to identify program (BSIT, BEED, BSHM) and update program_id, year_level, semester
     */
    public function up(): void
    {
        // Get all classes
        $classes = DB::table('classes')->get();

        foreach ($classes as $class) {
            $className = strtoupper($class->class_name);
            $programId = null;
            $yearLevel = $class->year_level ?? 1;  // Default to year 1
            $semester = $class->semester ?? 1;      // Default to semester 1

            // Detect program from class name
            if (strpos($className, 'BSIT') !== false || strpos($className, 'IT') !== false || strpos($className, 'INFORMATION TECHNOLOGY') !== false) {
                $programId = 1;  // BSIT
            } elseif (strpos($className, 'BEED') !== false || strpos($className, 'ELEMENTARY EDUCATION') !== false || strpos($className, 'EDUCATION') !== false) {
                $programId = 2;  // BEED
            } elseif (strpos($className, 'BSHM') !== false || strpos($className, 'HOTEL') !== false || strpos($className, 'HOSPITALITY') !== false) {
                $programId = 3;  // BSHM
            }

            // Detect year level from class name
            if (preg_match('/YEAR\s*(\d)/', $className, $matches)) {
                $yearLevel = (int)$matches[1];
            } elseif (preg_match('/(\d)(?:ST|ND|RD|TH)\s*YEAR/', $className, $matches)) {
                $yearLevel = (int)$matches[1];
            }

            // Detect semester from class name
            if (preg_match('/SEMESTER\s*(\d)/', $className, $matches)) {
                $semester = (int)$matches[1];
            } elseif (preg_match('/SEM\s*(\d)/', $className, $matches)) {
                $semester = (int)$matches[1];
            }

            // Update the class if we detected a program
            if ($programId !== null) {
                DB::table('classes')
                    ->where('id', $class->id)
                    ->update([
                        'program_id' => $programId,
                        'year_level' => $yearLevel,
                        'semester' => $semester,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset program_id, year_level, and semester
        DB::table('classes')->update([
            'program_id' => null,
            'year_level' => 1,
            'semester' => null,
        ]);
    }
};
