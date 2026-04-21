<?php

namespace App\Http\Controllers;

use App\Models\GradingScaleSetting;
use App\Models\AssessmentComponent;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingModeController extends Controller
{
    /**
     * Show grading mode selection and configuration page
     */
    public function show($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', request()->input('term', 'midterm'))
            ->first();

        if (!$settings) {
            $settings = GradingScaleSetting::getOrCreateDefault($classId, Auth::id());
        }

        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        $modes = [
            'standard' => [
                'name' => 'Standard KSA',
                'description' => 'Traditional Knowledge, Skills, Attitude model with manual component entry',
                'features' => [
                    'Knowledge (Exams, Quizzes)',
                    'Skills (Output, Participation, Activities)',
                    'Attitude (Behavior, Awareness)',
                    'Flexible KSA percentages',
                    'Manual grade entry per component',
                ],
                'best_for' => 'Traditional grading with KSA framework'
            ],
            'manual' => [
                'name' => 'Full Manual Entry',
                'description' => 'Teachers manually enter all grades without auto-calculation',
                'features' => [
                    'No automatic calculations',
                    'Teachers enter final grades directly',
                    'Component scores optional',
                    'Full control over grading',
                    'Flexible weighting',
                ],
                'best_for' => 'Teachers who prefer complete control'
            ],
            'automated' => [
                'name' => 'Fully Automated',
                'description' => 'System automatically calculates all grades from component scores',
                'features' => [
                    'Auto-calculation from components',
                    'Consistent formula application',
                    'No manual entry errors',
                    'Real-time grade updates',
                    'Audit trail of calculations',
                ],
                'best_for' => 'Objective, data-driven grading'
            ],
            'hybrid' => [
                'name' => 'Hybrid (Mixed Mode)',
                'description' => 'Mix manual and automated entry at component level',
                'features' => [
                    'Choose per-component mode',
                    'Mix manual and automated',
                    'Maximum flexibility',
                    'Per-student customization',
                    'Gradual automation transition',
                ],
                'best_for' => 'Flexible grading with mixed entry methods'
            ],
        ];

        $quizModes = [
            'manual' => 'Teachers enter quiz scores manually',
            'automated' => 'System auto-scores objective quizzes',
            'both' => 'Both manual and automated options available',
        ];

        return view('teacher.grades.grading-mode-selector', compact(
            'class',
            'settings',
            'modes',
            'quizModes',
            'components'
        ));
    }

    /**
     * Update grading mode settings
     */
    public function update(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'grading_mode' => 'required|in:standard,manual,automated,hybrid',
            'quiz_entry_mode' => 'required|in:manual,automated,both',
            'output_format' => 'required|in:standard,detailed,summary',
            'enable_esignature' => 'boolean',
            'enable_auto_calculation' => 'boolean',
            'enable_weighted_components' => 'boolean',
            'passing_grade' => 'numeric|min:0|max:100',
            'attendance_weight_percentage' => 'numeric|min:0|max:100',
            'knowledge_percentage' => 'numeric|min:0|max:100',
            'skills_percentage' => 'numeric|min:0|max:100',
            'attitude_percentage' => 'numeric|min:0|max:100',
        ]);

        // Validate percentages sum to 100 for KSA
        if (!GradingScaleSetting::validatePercentages(
            $validated['knowledge_percentage'],
            $validated['skills_percentage'],
            $validated['attitude_percentage']
        )) {
            return back()->withErrors(['percentages' => 'Knowledge + Skills + Attitude must equal 100']);
        }

        $settings = GradingScaleSetting::updateOrCreate(
            [
                'class_id' => $classId,
                'term' => $validated['term'],
            ],
            [
                'teacher_id' => Auth::id(),
                'grading_mode' => $validated['grading_mode'],
                'quiz_entry_mode' => $validated['quiz_entry_mode'],
                'output_format' => $validated['output_format'],
                'enable_esignature' => $validated['enable_esignature'] ?? false,
                'enable_auto_calculation' => $validated['enable_auto_calculation'] ?? true,
                'enable_weighted_components' => $validated['enable_weighted_components'] ?? true,
                'passing_grade' => $validated['passing_grade'] ?? 75.00,
                'attendance_weight_percentage' => $validated['attendance_weight_percentage'] ?? 0,
                'knowledge_percentage' => $validated['knowledge_percentage'],
                'skills_percentage' => $validated['skills_percentage'],
                'attitude_percentage' => $validated['attitude_percentage'],
                'settings_updated_at' => now(),
            ]
        );

        // Handle hybrid mode component configuration
        if ($validated['grading_mode'] === 'hybrid' && $request->has('component_modes')) {
            $hybridConfig = [];
            foreach ($request->input('component_modes', []) as $componentId => $mode) {
                if (in_array($mode, ['manual', 'automated'])) {
                    $hybridConfig[$componentId] = $mode;
                }
            }
            $settings->update(['hybrid_components_config' => $hybridConfig]);
        }

        return redirect()->route('teacher.grades.settings.index', $class->id)
            ->with('success', 'Grading mode settings updated successfully');
    }

    /**
     * Get component mode configuration for hybrid mode
     */
    public function getComponentModes($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', request()->input('term', 'midterm'))
            ->first();

        if (!$settings || !$settings->isHybridMode()) {
            return response()->json(['error' => 'Not in hybrid mode'], 400);
        }

        $components = AssessmentComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->get()
            ->map(function ($component) use ($settings) {
                return [
                    'id' => $component->id,
                    'name' => $component->name,
                    'category' => $component->category,
                    'current_mode' => $settings->getHybridComponentConfig($component->id),
                ];
            });

        return response()->json($components);
    }

    /**
     * Update component mode for hybrid grading
     */
    public function updateComponentMode(Request $request, $classId, $componentId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $component = AssessmentComponent::findOrFail($componentId);
        if ($component->class_id != $classId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'mode' => 'required|in:manual,automated',
        ]);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', $validated['term'])
            ->first();

        if (!$settings || !$settings->isHybridMode()) {
            return response()->json(['error' => 'Not in hybrid mode'], 400);
        }

        $settings->updateHybridComponentConfig($componentId, $validated['mode']);

        return response()->json([
            'success' => true,
            'message' => "Component mode updated to {$validated['mode']}"
        ]);
    }

    /**
     * Get mode configuration summary
     */
    public function getSummary($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', request()->input('term', 'midterm'))
            ->first();

        if (!$settings) {
            return response()->json(['error' => 'Settings not found'], 404);
        }

        return response()->json([
            'mode' => $settings->grading_mode,
            'quiz_mode' => $settings->quiz_entry_mode,
            'output_format' => $settings->output_format,
            'ksa' => [
                'knowledge' => $settings->knowledge_percentage,
                'skills' => $settings->skills_percentage,
                'attitude' => $settings->attitude_percentage,
            ],
            'features' => [
                'e_signature' => $settings->enable_esignature,
                'auto_calculation' => $settings->enable_auto_calculation,
                'weighted_components' => $settings->enable_weighted_components,
            ],
            'passing_grade' => $settings->passing_grade,
            'attendance_weight' => $settings->attendance_weight_percentage,
        ]);
    }
}
