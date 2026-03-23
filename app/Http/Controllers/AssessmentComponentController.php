<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentComponentController extends Controller
{
    // ========== SUBCATEGORY TEMPLATES ==========
    private const SUBCATEGORY_TEMPLATES = [
        'Knowledge' => ['Quiz', 'Exam', 'Test', 'Pre-test'],
        'Skills' => ['Output', 'Project', 'Assignment', 'Activity', 'Participation', 'Presentation'],
        'Attitude' => ['Behavior', 'Attendance', 'Awareness', 'Collaboration', 'Punctuality'],
    ];

    /**
     * Get all components for a class (categorized by KSA)
     */
    public function getComponents($classId)
    {
        try {
            Log::info('getComponents called', ['classId' => $classId, 'user' => Auth::id()]);
            
            $teacherId = Auth::id();
            
            if (!$teacherId) {
                Log::error('No authenticated user');
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated'
                ], 401);
            }
            
            $class = ClassModel::where('id', $classId)
                ->where('teacher_id', $teacherId)
                ->first();
            
            if (!$class) {
                Log::warning('Class not found or not owned by teacher', [
                    'classId' => $classId,
                    'teacherId' => $teacherId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or access denied'
                ], 404);
            }

            $components = AssessmentComponent::where('class_id', $classId)
                ->where('is_active', true)
                ->orderBy('category')
                ->orderBy('order')
                ->get();
            
            Log::info('Components found', ['count' => $components->count()]);

            // Group by lowercase category for frontend compatibility
            $grouped = [
                'knowledge' => $components->where('category', 'Knowledge')->values(),
                'skills' => $components->where('category', 'Skills')->values(),
                'attitude' => $components->where('category', 'Attitude')->values(),
            ];

            return response()->json([
                'success' => true,
                'components' => $grouped,
                'summary' => [
                    'knowledge' => $grouped['knowledge']->count(),
                    'skills' => $grouped['skills']->count(),
                    'attitude' => $grouped['attitude']->count(),
                    'total' => $components->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getComponents', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a new subcomponent to a class
     */
    public function addComponent(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-]+$/',
            'category' => 'required|in:Knowledge,Skills,Attitude',
            'subcategory' => 'required|string|max:50',
            'max_score' => 'required|integer|min:1|max:500',
            'weight' => 'required|numeric|min:0|max:100',
            'passing_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $validated['class_id'] = $classId;
        $validated['teacher_id'] = $teacherId;
        $validated['is_active'] = true;
        $validated['order'] = AssessmentComponent::where('class_id', $classId)
            ->where('category', $validated['category'])
            ->max('order') + 1;

        try {
            $component = AssessmentComponent::create($validated);

            return response()->json([
                'success' => true,
                'message' => "✅ {$component->name} added successfully!",
                'component' => $component->load('entries'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding component: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a subcomponent from a class
     */
    public function deleteComponent($classId, $componentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $component = AssessmentComponent::where('id', $componentId)
            ->where('class_id', $classId)
            ->firstOrFail();

        $componentName = $component->name;
        $category = $component->category;
        $order = $component->order;

        try {
            // Delete all associated entries
            ComponentEntry::where('component_id', $componentId)->delete();

            // Delete the component
            $component->delete();

            // Reorder remaining components in the same category
            AssessmentComponent::where('class_id', $classId)
                ->where('category', $category)
                ->where('order', '>', $order)
                ->where('is_active', true)
                ->decrement('order');

            return response()->json([
                'success' => true,
                'message' => "🗑️ {$componentName} deleted successfully!",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting component: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update component details
     */
    public function updateComponent(Request $request, $classId, $componentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $component = AssessmentComponent::where('id', $componentId)
            ->where('class_id', $classId)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100|regex:/^[a-zA-Z0-9\s\-]+$/',
            'max_score' => 'sometimes|integer|min:1|max:500',
            'weight' => 'sometimes|numeric|min:0|max:100',
            'passing_score' => 'sometimes|nullable|numeric|min:0|max:100',
            'subcategory' => 'sometimes|string|max:50',
        ]);

        try {
            $component->update($validated);

            return response()->json([
                'success' => true,
                'message' => '✏️ Component updated successfully!',
                'component' => $component,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating component: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reorder components within a category
     */
    public function reorderComponents(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|integer|exists:assessment_components,id',
            'components.*.order' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['components'] as $data) {
                AssessmentComponent::where('id', $data['id'])
                    ->where('class_id', $classId)
                    ->update(['order' => $data['order']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '↕️ Components reordered successfully!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error reordering components: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get subcategory options for a given KSA category
     */
    public function getSubcategories($category)
    {
        if (!in_array($category, ['Knowledge', 'Skills', 'Attitude'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'subcategories' => self::SUBCATEGORY_TEMPLATES[$category] ?? [],
        ]);
    }

    /**
     * Get templates for quick component setup
     */
    public function getTemplates()
    {
        return response()->json([
            'success' => true,
            'templates' => [
                'knowledge' => [
                    ['name' => 'Midterm Exam', 'subcategory' => 'Exam', 'weight' => 60, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Quiz 1', 'subcategory' => 'Quiz', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Quiz 2', 'subcategory' => 'Quiz', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Quiz 3', 'subcategory' => 'Quiz', 'weight' => 13.34, 'max_score' => 100, 'passing_score' => 75],
                ],
                'skills' => [
                    ['name' => 'Output 1', 'subcategory' => 'Output', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Output 2', 'subcategory' => 'Output', 'weight' => 13.33, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Output 3', 'subcategory' => 'Output', 'weight' => 13.34, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Class Participation 1', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Class Participation 2', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Class Participation 3', 'subcategory' => 'Participation', 'weight' => 10, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Activity 1', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Activity 2', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Activity 3', 'subcategory' => 'Activity', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Assignment 1', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Assignment 2', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Assignment 3', 'subcategory' => 'Assignment', 'weight' => 5, 'max_score' => 100, 'passing_score' => 75],
                ],
                'attitude' => [
                    ['name' => 'Behavior 1', 'subcategory' => 'Behavior', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Behavior 2', 'subcategory' => 'Behavior', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Behavior 3', 'subcategory' => 'Behavior', 'weight' => 16.66, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Awareness 1', 'subcategory' => 'Awareness', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Awareness 2', 'subcategory' => 'Awareness', 'weight' => 16.67, 'max_score' => 100, 'passing_score' => 75],
                    ['name' => 'Awareness 3', 'subcategory' => 'Awareness', 'weight' => 16.66, 'max_score' => 100, 'passing_score' => 75],
                ]
            ]
        ]);
    }

    /**
     * Apply template components to a class
     */
    public function applyTemplate(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'template' => 'required|in:knowledge,skills,attitude',
            'category' => 'required|in:Knowledge,Skills,Attitude',
        ]);

        try {
            DB::beginTransaction();

            $templates = $this->getTemplates()->getData()->templates;
            $components = $templates->{$validated['template']} ?? [];

            $created = [];
            $order = AssessmentComponent::where('class_id', $classId)
                ->where('category', $validated['category'])
                ->max('order') ?? 0;

            foreach ($components as $comp) {
                $order++;
                $created[] = AssessmentComponent::create([
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'category' => $validated['category'],
                    'subcategory' => $comp->subcategory,
                    'name' => $comp->name,
                    'max_score' => $comp->max_score,
                    'weight' => $comp->weight,
                    'passing_score' => $comp->passing_score ?? 75,
                    'order' => $order,
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '🎯 Template applied successfully!',
                'components' => $created,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error applying template: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get component statistics
     */
    public function getComponentStats($classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get();

        $stats = [];
        foreach (['Knowledge', 'Skills', 'Attitude'] as $category) {
            $categoryComps = $components->where('category', $category);
            $stats[$category] = [
                'count' => $categoryComps->count(),
                'total_weight' => $categoryComps->sum('weight'),
                'avg_max_score' => $categoryComps->avg('max_score'),
            ];
        }

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'total_components' => $components->count(),
        ]);
    }

    /**
     * Bulk delete components
     */
    public function bulkDelete(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'component_ids' => 'required|array|min:1',
            'component_ids.*' => 'integer|exists:assessment_components,id',
        ]);

        try {
            DB::beginTransaction();

            $deleted = 0;
            foreach ($validated['component_ids'] as $componentId) {
                $component = AssessmentComponent::where('id', $componentId)
                    ->where('class_id', $classId)
                    ->first();

                if ($component) {
                    ComponentEntry::where('component_id', $componentId)->delete();
                    $component->delete();
                    $deleted++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "🗑️ {$deleted} components deleted successfully!",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting components: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Duplicate a component
     */
    public function duplicateComponent($classId, $componentId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $original = AssessmentComponent::where('id', $componentId)
            ->where('class_id', $classId)
            ->firstOrFail();

        try {
            $duplicate = $original->replicate();
            $duplicate->name = $original->name . ' (Copy)';
            $duplicate->order = AssessmentComponent::where('class_id', $classId)
                ->where('category', $original->category)
                ->max('order') + 1;
            $duplicate->save();

            return response()->json([
                'success' => true,
                'message' => '📋 Component duplicated successfully!',
                'component' => $duplicate,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating component: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update weights for multiple components
     */
    public function updateWeights(Request $request, $classId)
    {
        $validated = $request->validate([
            'weights' => 'required|array',
            'weights.*.id' => 'required|integer|exists:assessment_components,id',
            'weights.*.weight' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['weights'] as $componentData) {
                AssessmentComponent::where('id', $componentData['id'])
                    ->where('class_id', $classId)
                    ->update(['weight' => $componentData['weight']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Component weights updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating component weights: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update component weights: ' . $e->getMessage()
            ], 500);
        }
    }
}
