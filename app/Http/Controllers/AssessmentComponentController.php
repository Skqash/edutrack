<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\AssessmentComponent;
use App\Models\ComponentEntry;
use App\Models\GradingScaleSetting;
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
     * Redistribute weights among components in a category OR subcategory
     * 
     * @param int $classId
     * @param string $category (Knowledge, Skills, Attitude)
     * @param string|null $subcategory (Quiz, Exam, Output, etc.) - if provided, only redistribute within subcategory
     * @param int|null $excludeComponentId - component to exclude from redistribution
     */
    private function redistributeWeights($classId, $category, $subcategory = null, $excludeComponentId = null)
    {
        if ($subcategory !== null) {
            // SUBCATEGORY-LEVEL REDISTRIBUTION
            // Get all components in this subcategory
            $subcategoryComponents = AssessmentComponent::where('class_id', $classId)
                ->where('category', $category)
                ->where('subcategory', $subcategory)
                ->where('is_active', true)
                ->when($excludeComponentId, function ($query) use ($excludeComponentId) {
                    return $query->where('id', '!=', $excludeComponentId);
                })
                ->get();

            if ($subcategoryComponents->isEmpty()) {
                return;
            }

            // Calculate the total weight allocated to this subcategory
            // This is the sum of current weights of components in this subcategory
            $subcategoryTotalWeight = $subcategoryComponents->sum('weight');
            
            // If subcategory total is 0, we need to allocate some weight
            // Get total weight used by OTHER subcategories in this category
            $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $classId)
                ->where('category', $category)
                ->where('subcategory', '!=', $subcategory)
                ->where('is_active', true)
                ->sum('weight');
            
            // Available weight for this subcategory
            $availableWeight = 100 - $otherSubcategoriesWeight;
            
            // If no weight available or negative, set all to 0
            if ($availableWeight <= 0) {
                foreach ($subcategoryComponents as $component) {
                    $component->update(['weight' => 0]);
                }
                return;
            }
            
            // Distribute available weight equally among components in this subcategory
            $componentCount = $subcategoryComponents->count();
            $equalWeight = round($availableWeight / $componentCount, 2);
            $totalWeight = $equalWeight * $componentCount;

            // Handle rounding differences
            $remainder = round(($availableWeight - $totalWeight) * 100) / 100;

            foreach ($subcategoryComponents as $index => $component) {
                $weight = $equalWeight;
                if ($index < $remainder * 100) {
                    $weight += 0.01;
                }
                $component->update(['weight' => $weight]);
            }
        } else {
            // CATEGORY-LEVEL REDISTRIBUTION (original behavior)
            $components = AssessmentComponent::where('class_id', $classId)
                ->where('category', $category)
                ->where('is_active', true)
                ->when($excludeComponentId, function ($query) use ($excludeComponentId) {
                    return $query->where('id', '!=', $excludeComponentId);
                })
                ->get();

            if ($components->isEmpty()) {
                return;
            }

            $totalComponents = $components->count();
            $equalWeight = round(100 / $totalComponents, 2);
            $totalWeight = $equalWeight * $totalComponents;

            // Handle rounding differences
            $remainder = round((100 - $totalWeight) * 100) / 100;

            foreach ($components as $index => $component) {
                $weight = $equalWeight;
                if ($index < $remainder * 100) {
                    $weight += 0.01;
                }
                $component->update(['weight' => $weight]);
            }
        }
    }

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

        // Get the grading settings to check weight mode
        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->first();

        $weightMode = $settings->component_weight_mode ?? 'semi-auto';

        // AUTO MODE: Redistribute only within the same subcategory
        if ($weightMode === 'auto') {
            $validated['class_id'] = $classId;
            $validated['teacher_id'] = $teacherId;
            $validated['is_active'] = true;
            $validated['order'] = AssessmentComponent::where('class_id', $classId)
                ->where('category', $validated['category'])
                ->max('order') + 1;

            try {
                $component = AssessmentComponent::create($validated);

                // Redistribute weights ONLY among components with the same subcategory
                $this->redistributeWeights($classId, $validated['category'], $validated['subcategory']);

                // Fetch updated component
                $updatedComponent = AssessmentComponent::find($component->id);
                
                // Get count of components in this subcategory
                $subcategoryCount = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $validated['category'])
                    ->where('subcategory', $validated['subcategory'])
                    ->where('is_active', true)
                    ->count();

                return response()->json([
                    'success' => true,
                    'message' => "✅ Auto Mode: {$component->name} added! All {$subcategoryCount} {$validated['subcategory']} components now have equal weights.",
                    'component' => $updatedComponent->load('entries'),
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding component: ' . $e->getMessage(),
                ], 400);
            }
        }

        // MANUAL & SEMI-AUTO MODES: Validate weight doesn't exceed 100% at category level
        $existingWeights = AssessmentComponent::where('class_id', $classId)
            ->where('category', $validated['category'])
            ->where('is_active', true)
            ->sum('weight');

        $totalWeight = $existingWeights + $validated['weight'];

        // Check if weight would exceed 100%
        if ($totalWeight > 100) {
            return response()->json([
                'success' => false,
                'message' => "❌ Cannot add component. Weight validation failed:\n\n" .
                           "Existing weight: {$existingWeights}%\n" .
                           "New weight: {$validated['weight']}%\n" .
                           "Total would be: {$totalWeight}%\n\n" .
                           "Maximum weight available: " . (100 - $existingWeights) . "%\n\n" .
                           "Please adjust the weight to be ≤ " . (100 - $existingWeights) . "%",
            ], 400);
        }

        // If there are existing components, prevent setting weight to 100%
        if ($existingWeights > 0 && $validated['weight'] >= 100) {
            return response()->json([
                'success' => false,
                'message' => "❌ Cannot set component weight to 100% when other components exist.\n\n" .
                           "Please set weight to maximum: " . (100 - $existingWeights) . "%\n\n" .
                           "Other components in this category need space for their weights.",
            ], 400);
        }

        $validated['class_id'] = $classId;
        $validated['teacher_id'] = $teacherId;
        $validated['is_active'] = true;
        $validated['order'] = AssessmentComponent::where('class_id', $classId)
            ->where('category', $validated['category'])
            ->max('order') + 1;

        try {
            $component = AssessmentComponent::create($validated);

            // SEMI-AUTO MODE: Redistribute only within the same subcategory
            if ($weightMode === 'semi-auto') {
                $this->redistributeWeights($classId, $validated['category'], $validated['subcategory']);
                
                // Get count of components in this subcategory
                $subcategoryCount = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $validated['category'])
                    ->where('subcategory', $validated['subcategory'])
                    ->where('is_active', true)
                    ->count();
                
                return response()->json([
                    'success' => true,
                    'message' => "✅ Semi-Auto Mode: {$component->name} added! All {$subcategoryCount} {$validated['subcategory']} components redistributed.",
                    'component' => $updatedComponent->load('entries'),
                ], 201);
            }

            // Fetch updated components to return accurate weights
            $updatedComponent = AssessmentComponent::find($component->id);

            // MANUAL MODE: No redistribution
            return response()->json([
                'success' => true,
                'message' => "✅ Manual Mode: {$component->name} added with {$validated['weight']}% weight. No automatic redistribution.",
                'component' => $updatedComponent->load('entries'),
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
        $subcategory = $component->subcategory;
        $order = $component->order;

        try {
            // Get the grading settings to check weight mode
            $settings = GradingScaleSetting::where('class_id', $classId)
                ->where('teacher_id', $teacherId)
                ->first();

            $weightMode = $settings->component_weight_mode ?? 'semi-auto';

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

            // AUTO MODE: Redistribute weights ONLY within the same subcategory
            if ($weightMode === 'auto') {
                $this->redistributeWeights($classId, $category, $subcategory);
                
                $remainingCount = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $category)
                    ->where('subcategory', $subcategory)
                    ->where('is_active', true)
                    ->count();

                return response()->json([
                    'success' => true,
                    'message' => "🗑️ Auto Mode: {$componentName} deleted! Remaining {$remainingCount} {$subcategory} components redistributed equally.",
                ], 200);
            }

            // SEMI-AUTO MODE: Redistribute weights ONLY within the same subcategory
            if ($weightMode === 'semi-auto') {
                $this->redistributeWeights($classId, $category, $subcategory);
                
                $remainingCount = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $category)
                    ->where('subcategory', $subcategory)
                    ->where('is_active', true)
                    ->count();

                return response()->json([
                    'success' => true,
                    'message' => "🗑️ Semi-Auto Mode: {$componentName} deleted! Remaining {$remainingCount} {$subcategory} components redistributed.",
                ], 200);
            }

            // MANUAL MODE: No redistribution - teacher has full control
            return response()->json([
                'success' => true,
                'message' => "🗑️ Manual Mode: {$componentName} deleted. No automatic weight redistribution.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting component: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update component details with mode-based weight distribution
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
            // First, update non-weight fields (name, max_score, passing_score, subcategory)
            $nonWeightFields = array_diff_key($validated, ['weight' => null]);
            if (!empty($nonWeightFields)) {
                $component->update($nonWeightFields);
            }

            // Get the grading settings to check weight mode
            $settings = GradingScaleSetting::where('class_id', $classId)
                ->where('teacher_id', $teacherId)
                ->first();

            $weightMode = $settings->component_weight_mode ?? 'semi-auto';

            // If weight is being updated, apply mode-specific logic
            if (isset($validated['weight'])) {
                $category = $component->category;
                $newWeight = $validated['weight'];

                // Get all other components in the same category
                $otherComponents = AssessmentComponent::where('class_id', $classId)
                    ->where('category', $category)
                    ->where('is_active', true)
                    ->where('id', '!=', $componentId)
                    ->get();

                $componentCount = $otherComponents->count() + 1; // +1 for current component

                // MANUAL MODE: No auto-distribution, just validate total <= 100%
                if ($weightMode === 'manual') {
                    $otherTotalWeight = $otherComponents->sum('weight');
                    $totalWeight = $otherTotalWeight + $newWeight;

                    if ($totalWeight > 100) {
                        return response()->json([
                            'success' => false,
                            'message' => "❌ Manual Mode: Total weight exceeds 100%.\n\n" .
                                       "Other components: {$otherTotalWeight}%\n" .
                                       "This component: {$newWeight}%\n" .
                                       "Total would be: {$totalWeight}%\n\n" .
                                       "Please adjust weights so total = 100%.",
                        ], 400);
                    }
                    // Update weight
                    $component->update(['weight' => $newWeight]);
                    return response()->json([
                        'success' => true,
                        'message' => "✅ Component updated. Manual mode - no auto-distribution applied.",
                        'component' => $component->fresh()->load('entries'),
                    ], 200);
                }

                // AUTO MODE: All components in the same SUBCATEGORY get the same weight
                if ($weightMode === 'auto') {
                    // Get components in the same subcategory
                    $subcategoryComponents = AssessmentComponent::where('class_id', $classId)
                        ->where('category', $category)
                        ->where('subcategory', $component->subcategory)
                        ->where('is_active', true)
                        ->get();
                    
                    $subcategoryCount = $subcategoryComponents->count();
                    
                    if ($subcategoryCount < 2) {
                        return response()->json([
                            'success' => false,
                            'message' => "❌ Auto Mode requires at least 2 components in the same subcategory.\n\n" .
                                       "Current {$component->subcategory} components: {$subcategoryCount}\n" .
                                       "Auto mode distributes weights equally within subcategories.\n\n" .
                                       "Switch to Manual or Semi-Auto mode to configure with 1 component.",
                        ], 400);
                    }

                    // Redistribute weights ONLY within the same subcategory
                    // This respects the available weight for this subcategory
                    $this->redistributeWeights($classId, $category, $component->subcategory);
                    
                    // Get the actual weight after redistribution
                    $actualWeight = $component->fresh()->weight;

                    return response()->json([
                        'success' => true,
                        'message' => "✅ Auto Mode: All {$subcategoryCount} {$component->subcategory} components now have {$actualWeight}% weight each.",
                        'component' => $component->fresh()->load('entries'),
                    ], 200);
                }

                // SEMI-AUTO MODE: Change one within subcategory → others in same subcategory auto-adjust
                if ($weightMode === 'semi-auto') {
                    // Get components in the same SUBCATEGORY (not entire category)
                    $subcategoryComponents = AssessmentComponent::where('class_id', $classId)
                        ->where('category', $category)
                        ->where('subcategory', $component->subcategory)
                        ->where('is_active', true)
                        ->where('id', '!=', $componentId)
                        ->get();
                    
                    $subcategoryCount = $subcategoryComponents->count() + 1; // +1 for current component
                    
                    // Get total weight used by OTHER subcategories
                    $otherSubcategoriesWeight = AssessmentComponent::where('class_id', $classId)
                        ->where('category', $category)
                        ->where('subcategory', '!=', $component->subcategory)
                        ->where('is_active', true)
                        ->sum('weight');
                    
                    // Available weight for this subcategory
                    $availableWeight = 100 - $otherSubcategoriesWeight;
                    
                    // Validate that new weight doesn't exceed available weight for this subcategory
                    if ($newWeight > $availableWeight) {
                        return response()->json([
                            'success' => false,
                            'message' => "❌ Semi-Auto Mode: Cannot set {$component->name} to {$newWeight}%.\n\n" .
                                       "Other subcategories use: {$otherSubcategoriesWeight}%\n" .
                                       "Available for {$component->subcategory}: {$availableWeight}%\n\n" .
                                       "Please set weight to ≤ {$availableWeight}%",
                        ], 400);
                    }
                    
                    if ($subcategoryCount < 2) {
                        // Single component in subcategory - allow any weight up to available
                        if ($newWeight > $availableWeight) {
                            return response()->json([
                                'success' => false,
                                'message' => "❌ Component weight cannot exceed {$availableWeight}% (available for this subcategory).",
                            ], 400);
                        }

                        $component->update(['weight' => $newWeight]);
                        return response()->json([
                            'success' => true,
                            'message' => "✅ Semi-Auto Mode: Component updated (single {$component->subcategory} component).",
                            'component' => $component->fresh()->load('entries'),
                        ], 200);
                    }

                    // Multiple components in subcategory: distribute remaining weight proportionally WITHIN SUBCATEGORY
                    $otherSubcategoryTotalWeight = $subcategoryComponents->sum('weight');
                    
                    // Update current component weight
                    $component->update(['weight' => $newWeight]);

                    // Redistribute remaining weight among other components in SAME SUBCATEGORY proportionally
                    $remainingWeight = $availableWeight - $newWeight;
                    if ($subcategoryComponents->count() > 0 && $remainingWeight > 0) {
                        // Calculate proportional distribution based on current weights
                        foreach ($subcategoryComponents as $otherComponent) {
                            $proportion = $otherSubcategoryTotalWeight > 0 ? ($otherComponent->weight / $otherSubcategoryTotalWeight) : (1 / $subcategoryComponents->count());
                            $newOtherWeight = round($remainingWeight * $proportion, 2);
                            $otherComponent->update(['weight' => $newOtherWeight]);
                        }
                        
                        // Ensure total is exactly equal to available weight (fix rounding errors)
                        $actualTotal = $newWeight + $subcategoryComponents->sum('weight');
                        if (abs($actualTotal - $availableWeight) > 0.01) {
                            $diff = $availableWeight - $actualTotal;
                            $lastComponent = $subcategoryComponents->last();
                            $lastComponent->update(['weight' => $lastComponent->weight + $diff]);
                        }
                    } elseif ($remainingWeight <= 0) {
                        // If no remaining weight, set all others in subcategory to 0
                        foreach ($subcategoryComponents as $otherComponent) {
                            $otherComponent->update(['weight' => 0]);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => "✅ Semi-Auto Mode: {$component->name} updated to {$newWeight}%. Other {$component->subcategory} components adjusted proportionally.",
                        'component' => $component->fresh()->load('entries'),
                        'adjustedComponents' => $subcategoryComponents->fresh()->pluck('weight', 'name'),
                    ], 200);
                }
            }

            // If no weight update, just return success
            return response()->json([
                'success' => true,
                'message' => '✅ Component updated successfully!',
                'component' => $component->fresh()->load('entries'),
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating component', ['error' => $e->getMessage()]);
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

    /**
     * Toggle attendance affects grade setting
     */
    public function toggleAttendanceAffectsGrade(Request $request, $classId)
    {
        $teacherId = Auth::id();
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        $validated = $request->validate([
            'attendance_affects_grade' => 'required|boolean',
            'term' => 'sometimes|string|in:midterm,final',
        ]);

        $term = $validated['term'] ?? 'midterm';

        try {
            $settings = GradingScaleSetting::getOrCreateDefault($classId, $teacherId, $term);
            $settings->update(['attendance_affects_grade' => $validated['attendance_affects_grade']]);

            $status = $validated['attendance_affects_grade'] ? 'enabled' : 'disabled';

            return response()->json([
                'success' => true,
                'message' => "📊 Attendance grade inclusion {$status} successfully!",
                'attendance_affects_grade' => $validated['attendance_affects_grade'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating attendance setting: ' . $e->getMessage(),
            ], 400);
        }
    }
}
