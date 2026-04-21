<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "=== Checking Table Structures ===\n\n";

    $tables = ['admins', 'super_admins', 'teachers', 'students'];

    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            $columns = Schema::getColumnListing($table);
            echo "$table columns: " . implode(', ', $columns) . "\n";
            echo "  Row count: " . DB::table($table)->count() . "\n\n";
        } else {
            echo "$table: TABLE DOES NOT EXIST\n\n";
        }
    }

    echo "=== Users Table ===\n";
    $userColumns = Schema::getColumnListing('users');
    echo "users columns: " . implode(', ', $userColumns) . "\n";
    echo "  Row count: " . DB::table('users')->count() . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
