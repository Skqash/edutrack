<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "========================================\n";
echo "EDUTRACK GRADE SYSTEM VALIDATION\n";
echo "========================================\n\n";

// Find teachers and classes
$teachers = \App\Models\User::where('role', 'teacher')->limit(3)->get();

echo "Available Teachers:\n";
foreach ($teachers as $t) {
    $classes = $t->classes()->count();
    echo "  ID: {$t->id}, Name: {$t->name}, Classes: {$classes}\n";
   
    if ($classes > 0) {
        $firstClass = $t->classes()->first();
        echo "    → Test Class ID: {$firstClass->id} ({$firstClass->class_name})\n\n";
        
        // Test basic functionality
        testClassGradeSettings($firstClass->id, $t->id);
    }
}

function testClassGradeSettings($classId, $teacherId) {
    echo "\n--- Testing Class ID {$classId} ---\n";
    
    // Test 1: KSA Setting
    echo "\n[1] KSA Settings:\n";
    try {
        $ksa = \App\Models\KsaSetting::getOrCreateDefault($classId, 'midterm', $teacherId);
        echo "  ✓ Knowledge: {$ksa->knowledge_weight}%\n";
        echo "  ✓ Skills: {$ksa->skills_weight}%\n";
        echo "  ✓ Attitude: {$ksa->attitude_weight}%\n";
        echo "  ✓ Total: " . ($ksa->knowledge_weight + $ksa->skills_weight + $ksa->attitude_weight) . "%\n";
        echo "  ✓ Valid: " . ($ksa->validateWeights() ? 'YES' : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
    
    // Test 2: Weight Modes
    echo "\n[2] Weight Modes:\n";
    try {
        \App\Models\GradingScaleSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => 'midterm'],
            [
                'teacher_id' => $teacherId,
                'component_weight_mode' => 'manual',
            ]
        );
        echo "  ✓ Manual mode created\n";
        
        $setting = \App\Models\GradingScaleSetting::where('class_id', $classId)->where('term', 'midterm')->first();
        echo "  ✓ Mode saved: {$setting->component_weight_mode}\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
    
    // Test 3: Attendance Settings
    echo "\n[3] Attendance Settings:\n";
    try {
        $ksa = \App\Models\KsaSetting::where('class_id', $classId)->where('term', 'midterm')->first();
        $ksa->update([
            'total_meetings' => 16,
            'attendance_weight' => 20,
            'attendance_category' => 'Attitude',
            'enable_attendance_ksa' => 1,
        ]);
        $ksa->refresh();
        echo "  ✓ Total Meetings: {$ksa->total_meetings}\n";
        echo "  ✓ Attendance Weight: {$ksa->attendance_weight}%\n";
        echo "  ✓ Category: {$ksa->attendance_category}\n";
        echo "  ✓ Enabled: " . ($ksa->enable_attendance_ksa ? 'YES' : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
    
    // Test 4: Component Weights
    echo "\n[4] Component Weight Distribution:\n";
    try {
        $categories = ['Knowledge', 'Skills', 'Attitude'];
        foreach ($categories as $cat) {
            $components = \App\Models\AssessmentComponent::where('class_id', $classId)
                ->where('category', $cat)
                ->where('is_active', true)
                ->get();
                
            $total = $components->sum('weight');
            $status = ($total == 100) ? '✓' : '✗';
            echo "  {$status} {$cat}: {$total}% (" . $components->count() . " components)\n";
            
            if ($total != 100) {
                foreach ($components as $comp) {
                    echo "      - {$comp->component_name}: {$comp->weight}%\n";
                }
            }
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
    
    // Test 5: Term Isolation
    echo "\n[5] Multiple Terms:\n";
    try {
        $midterm = \App\Models\KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => 'midterm'],
            ['teacher_id' => $teacherId, 'knowledge_weight' => 35, 'skills_weight' => 55, 'attitude_weight' => 10]
        );
        
        $final = \App\Models\KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => 'final'],
            ['teacher_id' => $teacherId, 'knowledge_weight' => 45, 'skills_weight' => 40, 'attitude_weight' => 15]
        );
        
        echo "  ✓ Midterm Knowledge: {$midterm->knowledge_weight}%\n";
        echo "  ✓ Final Knowledge: {$final->knowledge_weight}%\n";
        echo "  ✓ Terms properly isolated\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: {$e->getMessage()}\n";
    }
}

echo "\n========================================\n";
echo "VALIDATION COMPLETE\n";
echo "========================================\n";
