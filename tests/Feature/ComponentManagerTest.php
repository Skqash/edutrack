<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\AssessmentComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ComponentManagerTest - Test suite for component management CRUD operations
 */
class ComponentManagerTest extends TestCase
{
    use RefreshDatabase;

    private $teacher;
    private $classModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a teacher user
        $this->teacher = User::factory()->create([
            'role' => 'teacher'
        ]);

        // Create a test class
        $this->classModel = ClassModel::factory()->create([
            'teacher_id' => $this->teacher->id
        ]);
    }

    /** @test */
    public function teacher_can_view_component_manager_modal()
    {
        // Visit grade entry page
        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.grades.entry', ['classId' => $this->classModel->id]));

        // Assert page contains modal reference
        $response->assertStatus(200);
        $response->assertSeeInOrder([
            'Manage Components',
            'componentManagerModal'
        ]);
    }

    /** @test */
    public function teacher_can_add_component()
    {
        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.store', $this->classModel->id), [
                'name' => 'Quiz 1',
                'category' => 'Knowledge',
                'subcategory' => 'Quiz',
                'max_score' => 25,
                'weight' => 10,
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['id', 'name', 'category', 'max_score', 'weight']]);

        // Verify component in database
        $this->assertDatabaseHas('assessment_components', [
            'class_id' => $this->classModel->id,
            'name' => 'Quiz 1',
            'category' => 'Knowledge',
        ]);
    }

    /** @test */
    public function teacher_can_get_all_components()
    {
        // Create test components
        AssessmentComponent::factory(3)->create([
            'class_id' => $this->classModel->id,
            'category' => 'Knowledge',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->teacher)
            ->getJson(route('teacher.components.index', $this->classModel->id));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /** @test */
    public function teacher_can_delete_component()
    {
        $component = AssessmentComponent::factory()->create([
            'class_id' => $this->classModel->id,
        ]);

        $response = $this->actingAs($this->teacher)
            ->deleteJson(route('teacher.components.destroy', [
                'classId' => $this->classModel->id,
                'componentId' => $component->id
            ]));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('assessment_components', ['id' => $component->id]);
    }

    /** @test */
    public function teacher_can_duplicate_component()
    {
        $component = AssessmentComponent::factory()->create([
            'class_id' => $this->classModel->id,
            'name' => 'Original Component',
        ]);

        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.duplicate', [
                'classId' => $this->classModel->id,
                'componentId' => $component->id
            ]));

        $response->assertStatus(201);

        // Verify duplicated component exists
        $this->assertDatabaseHas('assessment_components', [
            'class_id' => $this->classModel->id,
            'name' => 'Original Component (Copy)',
        ]);
    }

    /** @test */
    public function teacher_can_get_subcategories_for_category()
    {
        $response = $this->actingAs($this->teacher)
            ->getJson(route('teacher.components.subcategories', [
                'classId' => $this->classModel->id,
                'category' => 'Knowledge'
            ]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
        // Verify it contains Knowledge subcategories
        $this->assertContains('Quiz', $response->json('data'));
    }

    /** @test */
    public function teacher_can_apply_template()
    {
        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.apply-template', $this->classModel->id), [
                'template' => 'Knowledge'
            ]);

        $response->assertStatus(200);

        // Verify components were created
        $components = AssessmentComponent::where('class_id', $this->classModel->id)
            ->where('category', 'Knowledge')
            ->count();

        $this->assertGreaterThan(0, $components);
    }

    /** @test */
    public function component_form_validates_required_fields()
    {
        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.store', $this->classModel->id), [
                // Missing required fields
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'category', 'max_score']);
    }

    /** @test */
    public function component_max_score_must_be_between_1_and_500()
    {
        // Test max_score too low
        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.store', $this->classModel->id), [
                'name' => 'Invalid Component',
                'category' => 'Knowledge',
                'max_score' => 0,
                'weight' => 10,
            ]);

        $response->assertStatus(422);

        // Test max_score too high
        $response = $this->actingAs($this->teacher)
            ->postJson(route('teacher.components.store', $this->classModel->id), [
                'name' => 'Invalid Component',
                'category' => 'Knowledge',
                'max_score' => 501,
                'weight' => 10,
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function non_teacher_cannot_manage_components()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)
            ->postJson(route('teacher.components.store', $this->classModel->id), [
                'name' => 'Unauthorized',
                'category' => 'Knowledge',
                'max_score' => 25,
            ]);

        $response->assertStatus(403);
    }
}
