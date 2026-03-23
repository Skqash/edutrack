<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill missing employee IDs for teacher/admin users
        $users = DB::table('users')
            ->whereIn('role', ['teacher', 'admin'])
            ->whereNull('employee_id')
            ->get(['id', 'role']);

        foreach ($users as $user) {
            $prefix = $user->role === 'admin' ? 'ADM' : 'EMP';
            $employeeId = $prefix . str_pad($user->id, 6, '0', STR_PAD_LEFT);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'employee_id' => $employeeId,
                    'status' => DB::raw("COALESCE(status, 'Active')"),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not reverse this migration.
    }
};
