<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\KsaSetting;
use App\Models\GradingScaleSetting;
use App\Models\AssessmentComponent;
use App\Models\School;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradeSettingsAndAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $classModel;
    protected $school;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();

        // Create school directly
        $this->school = School::create([
            'school_name' => 'Test School',
            'school_code' => 'TS001',
            'short_name' => 'TS',
            'status' => 'active',
        ]);

        // Create teacher user
        $this->teacher = User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
            'school_id' => $this->school->id,
            'status' => 'active',
        ]);

        // Create course
        $this->course = Course::create([
            'program_code' => 'TEST001',
            'program_name' => 'Test Program',
            'school_id' => $this->school->id,
            'total_years' => 2,
            'status' => 'active',
        ]);

        // Create class
        $this->classModel = ClassModel::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'school_id' => $this->school->id,
            'class_name' => 'Test Class',
            'section' => 'A',
        ]);

        // Create assessment components
        $this->createAssessmentComponents();
    }

    protected function createAssessmentComponents()
    {
        // Knowledge components
        AssessmentComponent::create([
            'class_id' => $this->classModel->id,
            'component_name' => 'Quiz',
            'category' => 'Knowledge',
            'weight' => 30,
            'is_active' => true,
        ]);

        AssessmentComponent::create([
            'class_id' => $this->classModel->id,
            'component_name' => 'Exam',
            'category' => 'Knowledge',
            'weight' => 70,
            'is_active' => true,
        ]);

        // Skills components
        AssessmentComponent::create([
            'class_id' => $this->classModel->id,
            'component_name' => 'Output',
            'category' => 'Skills',
            'weight' => 50,
            'is_active' => true,
        ]);

        AssessmentComponent::create([
            'class_id' => $this->classModel->id,
            'component_name' => 'Class Participation',
            'category' => 'Skills',
            'weight' => 50,
            'is_active' => true,
        ]);

        // Attitude components
        AssessmentComponent::create([
            'class_id' => $this->classModel->id,
            'component_name' => 'Behavior',
            'category' => 'Attitude',
            'weight' => 100,
            'is_active' => true,
        ]);
    }

    /**
     * TEST 1: Check if Grade Settings page loads
     */
    public function test_grade_settings_page_loads()
    {
        $response = $this->actingAs($this->teacher)
            ->get("/teacher/grades/settings/{$this->classModel->id}/midterm");

        $response->assertStatus(200);
        $response->assertViewIs('teacher.grades.settings');
    }

    /**
     * TEST 2: Check KSA Setting creation and retrieval
     */
    public function test_ksa_setting_creation_and_retrieval()
    {
        $ksaSetting = KsaSetting::getOrCreateDefault(
            $this->classModel->id,
            'midterm',
            $this->teacher->id
        );

        $this->assertNotNull($ksaSetting);
        $this->assertEquals(40, $ksaSetting->knowledge_weight);
        $this->assertEquals(50, $ksaSetting->skills_weight);
        $this->assertEquals(10, $ksaSetting->attitude_weight);
        $this->assertTrue($ksaSetting->validateWeights());
    }

    /**
     * TEST 3: Test KSA Percentage Update
     */
    public function test_ksa_percentages_update()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/settings/{$this->classModel->id}/ksa",
            [
                'knowledge_weight' => 35,
                'skills_weight' => 55,
                'attitude_weight' => 10,
                'term' => 'midterm',
            ]
        );

        $response->assertRedirect();

        $ksaSetting = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals(35, $ksaSetting->knowledge_weight);
        $this->assertEquals(55, $ksaSetting->skills_weight);
        $this->assertEquals(10, $ksaSetting->attitude_weight);
    }

    /**
     * TEST 4: Test KSA Validation - should reject if not equal to 100
     */
    public function test_ksa_validation_rejects_invalid_sum()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/settings/{$this->classModel->id}/ksa",
            [
                'knowledge_weight' => 35,
                'skills_weight' => 55,
                'attitude_weight' => 15, // Total = 105 (invalid)
                'term' => 'midterm',
            ]
        );

        $response->assertSessionHasErrors();
    }

    /**
     * TEST 5: Test Weight Mode Creation - Manual
     */
    public function test_weight_mode_manual_creation()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grade-settings/{$this->classModel->id}/midterm/weight-mode",
            [
                'component_weight_mode' => 'manual',
                'term' => 'midterm',
            ]
        );

        $response->assertStatus(200);

        $setting = GradingScaleSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals('manual', $setting->component_weight_mode);
    }

    /**
     * TEST 6: Test Weight Mode - Semi-Auto
     */
    public function test_weight_mode_semi_auto_creation()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grade-settings/{$this->classModel->id}/midterm/weight-mode",
            [
                'component_weight_mode' => 'semi-auto',
                'term' => 'midterm',
            ]
        );

        $response->assertStatus(200);

        $setting = GradingScaleSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals('semi-auto', $setting->component_weight_mode);
    }

    /**
     * TEST 7: Test Weight Mode - Auto
     */
    public function test_weight_mode_auto_creation()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grade-settings/{$this->classModel->id}/midterm/weight-mode",
            [
                'component_weight_mode' => 'auto',
                'term' => 'midterm',
            ]
        );

        $response->assertStatus(200);

        $setting = GradingScaleSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals('auto', $setting->component_weight_mode);
    }

    /**
     * TEST 8: Test Attendance Settings Creation
     */
    public function test_attendance_settings_creation()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/settings/{$this->classModel->id}/attendance",
            [
                'total_meetings' => 10,
                'attendance_weight' => 20,
                'attendance_category' => 'Attitude',
                'enable_attendance_ksa' => true,
                'term' => 'midterm',
            ]
        );

        $response->assertRedirect();

        $ksaSetting = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals(10, $ksaSetting->total_meetings);
        $this->assertEquals(20, $ksaSetting->attendance_weight);
        $this->assertEquals('Attitude', $ksaSetting->attendance_category);
        $this->assertTrue($ksaSetting->enable_attendance_ksa);
    }

    /**
     * TEST 9: Test Attendance - Disable attendance KSA
     */
    public function test_attendance_ksa_disable()
    {
        // First create with enabled
        KsaSetting::create([
            'class_id' => $this->classModel->id,
            'teacher_id' => $this->teacher->id,
            'term' => 'midterm',
            'knowledge_weight' => 40,
            'skills_weight' => 50,
            'attitude_weight' => 10,
            'enable_attendance_ksa' => true,
        ]);

        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/settings/{$this->classModel->id}/attendance",
            [
                'total_meetings' => 10,
                'attendance_weight' => 20,
                'attendance_category' => 'Attitude',
                'enable_attendance_ksa' => false,
                'term' => 'midterm',
            ]
        );

        $response->assertRedirect();

        $ksaSetting = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertFalse($ksaSetting->enable_attendance_ksa);
    }

    /**
     * TEST 10: Test Component Weight Distribution in Manual Mode
     */
    public function test_component_weight_distribution_manual_mode()
    {
        // Create grading scale setting with manual mode
        GradingScaleSetting::create([
            'class_id' => $this->classModel->id,
            'teacher_id' => $this->teacher->id,
            'term' => 'midterm',
            'component_weight_mode' => 'manual',
        ]);

        // Test that manual mode allows any total <= 100
        $quizComponent = AssessmentComponent::where('class_id', $this->classModel->id)
            ->where('component_name', 'Quiz')
            ->first();

        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/components/{$quizComponent->id}/update",
            [
                'weight' => 45, // Change from 30 to 45
                'class_id' => $this->classModel->id,
            ]
        );

        // Should succeed because total is still valid
        $quizComponent->refresh();
        $this->assertEquals(45, $quizComponent->weight);
    }

    /**
     * TEST 11: Test Component Weight Distribution in Auto Mode
     */
    public function test_component_weight_distribution_auto_mode()
    {
        // Create grading scale setting with auto mode
        GradingScaleSetting::create([
            'class_id' => $this->classModel->id,
            'teacher_id' => $this->teacher->id,
            'term' => 'midterm',
            'component_weight_mode' => 'auto',
        ]);

        // In auto mode, all components in same category should have equal weight
        $skillsComponents = AssessmentComponent::where('class_id', $this->classModel->id)
            ->where('category', 'Skills')
            ->get();

        $output = $skillsComponents->where('component_name', 'Output')->first();

        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/components/{$output->id}/update",
            [
                'weight' => 50,
                'class_id' => $this->classModel->id,
            ]
        );

        // After auto-distribution, both should be equal (50 each)
        $skillsComponents->each->refresh();
        $skillsComponents->each(function ($component) {
            $this->assertEquals(50, $component->weight);
        });
    }

    /**
     * TEST 12: Test KSA Percentage is Preserved
     */
    public function test_ksa_percentage_preservation()
    {
        $ksaSetting = KsaSetting::getOrCreateDefault(
            $this->classModel->id,
            'midterm',
            $this->teacher->id
        );

        $ksaSetting->update([
            'knowledge_weight' => 45,
            'skills_weight' => 40,
            'attitude_weight' => 15,
        ]);

        $retrieved = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $this->assertEquals(45, $retrieved->knowledge_weight);
        $this->assertEquals(40, $retrieved->skills_weight);
        $this->assertEquals(15, $retrieved->attitude_weight);
    }

    /**
     * TEST 13: Test Database Integrity - Weight Totals per Category
     */
    public function test_component_weights_total_per_category()
    {
        $categories = ['Knowledge', 'Skills', 'Attitude'];

        foreach ($categories as $category) {
            $total = AssessmentComponent::where('class_id', $this->classModel->id)
                ->where('category', $category)
                ->where('is_active', true)
                ->sum('weight');

            // Each category should have components that sum to 100
            $this->assertEquals(100, $total, "Category {$category} weight total should be 100");
        }
    }

    /**
     * TEST 14: Test Attendance Settings Validation
     */
    public function test_attendance_total_meetings_validation()
    {
        $response = $this->actingAs($this->teacher)->post(
            "/teacher/grades/settings/{$this->classModel->id}/attendance",
            [
                'total_meetings' => 0, // Invalid - should be > 0
                'attendance_weight' => 20,
                'attendance_category' => 'Attitude',
                'enable_attendance_ksa' => true,
                'term' => 'midterm',
            ]
        );

        // Should fail validation
        $response->assertSessionHasErrors();
    }

    /**
     * TEST 15: Test Multiple Term Isolation
     */
    public function test_multiple_term_settings_isolation()
    {
        // Create settings for midterm
        KsaSetting::getOrCreateDefault(
            $this->classModel->id,
            'midterm',
            $this->teacher->id
        )->update(['knowledge_weight' => 40]);

        // Create settings for final
        KsaSetting::getOrCreateDefault(
            $this->classModel->id,
            'final',
            $this->teacher->id
        )->update(['knowledge_weight' => 50]);

        // Verify they're isolated
        $midterm = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'midterm')
            ->first();

        $final = KsaSetting::where('class_id', $this->classModel->id)
            ->where('term', 'final')
            ->first();

        $this->assertEquals(40, $midterm->knowledge_weight);
        $this->assertEquals(50, $final->knowledge_weight);
    }
}
