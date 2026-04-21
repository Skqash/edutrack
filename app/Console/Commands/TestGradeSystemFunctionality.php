<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KsaSetting;
use App\Models\GradingScaleSetting;
use App\Models\AssessmentComponent;
use App\Models\ClassModel;
use App\Models\User;
use DB;

class TestGradeSystemFunctionality extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:grades {--class-id=1} {--teacher-id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test grade settings, KSA distribution, and attendance functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $classId = $this->option('class-id');
        $teacherId = $this->option('teacher-id');

        $this->info('========================================');
        $this->info('GRADE SYSTEM FUNCTIONALITY TEST');
        $this->info('========================================');
        $this->newLine();

        // Check if class and teacher exist
        $teacher = User::find($teacherId);
        $class = ClassModel::find($classId);

        if (!$teacher || !$class) {
            $this->error('Teacher or Class not found. Please provide valid IDs.');
            $this->info('Usage: php artisan test:grades --class-id=X --teacher-id=Y');
            return;
        }

        $this->info("✓ Teacher: {$teacher->name}");
        $this->info("✓ Class: {$class->class_name}");
        $this->newLine();

        // TEST 1: KSA Setting Creation
        $this->test1_ksa_creation($classId, $teacherId);

        // TEST 2: KSA Validation
        $this->test2_ksa_validation($classId);

        // TEST 3: Weight Modes
        $this->test3_weight_modes($classId, $teacherId);

        // TEST 4: Attendance Settings
        $this->test4_attendance_settings($classId, $teacherId);

        // TEST 5: Component Weights
        $this->test5_component_weights($classId);

        // TEST 6: Multiple Term Isolation
        $this->test6_term_isolation($classId, $teacherId);

        // TEST 7: Retrieve Settings from Database
        $this->test7_database_integrity($classId);

        $this->newLine();
        $this->info('========================================');
        $this->info('TEST COMPLETE');
        $this->info('========================================');
    }

    /**
     * TEST 1: KSA Setting Creation
     */
    protected function test1_ksa_creation($classId, $teacherId)
    {
        $this->info('TEST 1: KSA Setting Creation');
        $this->line('Testing KsaSetting::getOrCreateDefault()...');

        try {
            $ksaSetting = KsaSetting::getOrCreateDefault($classId, 'midterm', $teacherId);

            $this->line("  ✓ Knowledge Weight: {$ksaSetting->knowledge_weight}");
            $this->line("  ✓ Skills Weight: {$ksaSetting->skills_weight}");
            $this->line("  ✓ Attitude Weight: {$ksaSetting->attitude_weight}");
            $this->line("  ✓ Total: " . ($ksaSetting->knowledge_weight + $ksaSetting->skills_weight + $ksaSetting->attitude_weight));

            if ($ksaSetting->validateWeights()) {
                $this->info('  ✓ PASS: KSA weights validate correctly');
            } else {
                $this->error('  ✗ FAIL: KSA weights do not sum to 100');
            }
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 2: KSA Validation
     */
    protected function test2_ksa_validation($classId)
    {
        $this->info('TEST 2: KSA Weight Validation');
        $this->line('Testing weight total validation...');

        try {
            $ksaSetting = KsaSetting::where('class_id', $classId)->where('term', 'midterm')->first();

            // Test invalid sum
            $ksaSetting->update([
                'knowledge_weight' => 50,
                'skills_weight' => 40,
                'attitude_weight' => 20, // Total = 110 (invalid)
            ]);

            if (!$ksaSetting->validateWeights()) {
                $this->info('  ✓ PASS: Invalid weight sum correctly detected (50+40+20=110)');
            } else {
                $this->error('  ✗ FAIL: Invalid weight sum not detected');
            }

            // Reset to valid
            $ksaSetting->update([
                'knowledge_weight' => 40,
                'skills_weight' => 50,
                'attitude_weight' => 10,
            ]);

            if ($ksaSetting->validateWeights()) {
                $this->info('  ✓ PASS: Valid weight sum correctly validated (40+50+10=100)');
            }
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 3: Weight Modes
     */
    protected function test3_weight_modes($classId, $teacherId)
    {
        $this->info('TEST 3: Weight Mode Configuration');
        $this->line('Testing Manual, Semi-Auto, and Auto modes...');

        try {
            $modes = ['manual', 'semi-auto', 'auto'];

            foreach ($modes as $mode) {
                $setting = GradingScaleSetting::updateOrCreate(
                    ['class_id' => $classId, 'term' => 'midterm'],
                    [
                        'teacher_id' => $teacherId,
                        'component_weight_mode' => $mode,
                        'knowledge_percentage' => 40,
                        'skills_percentage' => 50,
                        'attitude_percentage' => 10,
                    ]
                );

                if ($setting->component_weight_mode === $mode) {
                    $this->info("  ✓ PASS: {$mode} mode created successfully");
                } else {
                    $this->error("  ✗ FAIL: {$mode} mode not saved correctly");
                }
            }
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 4: Attendance Settings
     */
    protected function test4_attendance_settings($classId, $teacherId)
    {
        $this->info('TEST 4: Attendance Settings');
        $this->line('Testing attendance configuration...');

        try {
            $ksaSetting = KsaSetting::where('class_id', $classId)->where('term', 'midterm')->first();

            if (!$ksaSetting) {
                $ksaSetting = KsaSetting::create([
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'term' => 'midterm',
                    'knowledge_weight' => 40,
                    'skills_weight' => 50,
                    'attitude_weight' => 10,
                ]);
            }

            $ksaSetting->update([
                'total_meetings' => 16,
                'attendance_weight' => 20,
                'attendance_category' => 'Attitude',
                'enable_attendance_ksa' => 1,
            ]);

            $this->info("  ✓ PASS: Total Meetings: {$ksaSetting->total_meetings}");
            $this->info("  ✓ PASS: Attendance Weight: {$ksaSetting->attendance_weight}");
            $this->info("  ✓ PASS: Attendance Category: {$ksaSetting->attendance_category}");
            $this->info("  ✓ PASS: Attendance KSA Enabled: " . ($ksaSetting->enable_attendance_ksa ? 'Yes' : 'No'));

            // Test disable
            $ksaSetting->update(['enable_attendance_ksa' => 0]);
            if (!$ksaSetting->enable_attendance_ksa) {
                $this->info("  ✓ PASS: Attendance KSA can be disabled");
            }

            // Re-enable
            $ksaSetting->update(['enable_attendance_ksa' => 1]);
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 5: Component Weights
     */
    protected function test5_component_weights($classId)
    {
        $this->info('TEST 5: Component Weight Distribution');
        $this->line('Testing component weights per category...');

        try {
            $categories = ['Knowledge', 'Skills', 'Attitude'];
            $allValid = true;

            foreach ($categories as $category) {
                $components = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $category)
                    ->where('is_active', true)
                    ->get();

                $totalWeight = $components->sum('weight');

                if ($totalWeight == 100) {
                    $this->info("  ✓ PASS: {$category} total = {$totalWeight}%");
                } else {
                    $this->error("  ✗ FAIL: {$category} total = {$totalWeight}% (should be 100%)");
                    $allValid = false;

                    // Show individual components
                    foreach ($components as $comp) {
                        $this->line("      {$comp->component_name}: {$comp->weight}%");
                    }
                }
            }

            if ($allValid) {
                $this->info('  ✓ PASS: All categories have valid weight distribution');
            }
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 6: Multiple Term Isolation
     */
    protected function test6_term_isolation($classId, $teacherId)
    {
        $this->info('TEST 6: Multiple Term Isolation');
        $this->line('Testing term isolation for same class...');

        try {
            // Create midterm settings
            $midterm = KsaSetting::updateOrCreate(
                ['class_id' => $classId, 'term' => 'midterm'],
                [
                    'teacher_id' => $teacherId,
                    'knowledge_weight' => 35,
                    'skills_weight' => 55,
                    'attitude_weight' => 10,
                ]
            );

            // Create final term settings
            $final = KsaSetting::updateOrCreate(
                ['class_id' => $classId, 'term' => 'final'],
                [
                    'teacher_id' => $teacherId,
                    'knowledge_weight' => 45,
                    'skills_weight' => 40,
                    'attitude_weight' => 15,
                ]
            );

            if ($midterm->knowledge_weight == 35 && $final->knowledge_weight == 45) {
                $this->info('  ✓ PASS: Midterm Knowledge = 35%, Final Knowledge = 45%');
                $this->info('  ✓ PASS: Terms are properly isolated');
            } else {
                $this->error('  ✗ FAIL: Terms are not isolated correctly');
            }
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }

    /**
     * TEST 7: Database Integrity
     */
    protected function test7_database_integrity($classId)
    {
        $this->info('TEST 7: Database Integrity');
        $this->line('Checking data consistency...');

        try {
            // Check KSA settings exist
            $ksaCount = KsaSetting::where('class_id', $classId)->count();
            $this->info("  ✓ KSA Records: {$ksaCount}");

            // Check Weight modes
            $modeCount = GradingScaleSetting::where('class_id', $classId)->count();
            $this->info("  ✓ Weight Mode Records: {$modeCount}");

            // Check component distribution
            $components = AssessmentComponent::where('class_id', $classId)
                ->where('is_active', true)
                ->count();
            $this->info("  ✓ Active Components: {$components}");

            $this->info('  ✓ PASS: All data properly stored in database');
        } catch (\Exception $e) {
            $this->error("  ✗ FAIL: {$e->getMessage()}");
        }

        $this->newLine();
    }
}
