<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$count = \App\Models\Student::count();
echo "Total Students: {$count}\n";

$vicCount = \App\Models\Student::where('campus','CPSU Victorias Campus')->count();
echo "Victorias Students: {$vicCount}\n";

$mainCount = \App\Models\Student::where('campus','CPSU Main Campus - Kabankalan City')->count();
echo "Main Campus Students: {$mainCount}\n";

$sipCount = \App\Models\Student::where('campus','CPSU Sipalay Campus - Brgy. Gil Montilla')->count();
echo "Sipalay Students: {$sipCount}\n";

$withUser = \App\Models\Student::whereNotNull('user_id')->count();
echo "Students with user_id: {$withUser}\n";

echo "\nFirst 10 students:\n";
\App\Models\Student::select('student_id','first_name','email','user_id')->limit(10)->get()->each(function($s) {
    echo "- {$s->student_id}: {$s->first_name} (user_id: {$s->user_id})\n";
});
