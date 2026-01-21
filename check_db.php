<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Check if classes table exists and what columns it has
    $columns = DB::getSchemaBuilder()->getColumnListing('classes');
    echo "Classes table columns:\n";
    print_r($columns);
    
    // Check if teacher_id column exists
    if (in_array('teacher_id', $columns)) {
        echo "\nteacher_id column EXISTS ✓\n";
    } else {
        echo "\nteacher_id column MISSING ✗\n";
    }
    
    // Check actual classes data
    $classCount = DB::table('classes')->count();
    echo "\nTotal classes in database: $classCount\n";
    
    $firstClass = DB::table('classes')->first();
    if ($firstClass) {
        echo "First class:\n";
        print_r($firstClass);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
