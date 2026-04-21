<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ComponentEntry;
use App\Models\GradeComponent;
use App\Models\KsaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeSettingsController extends Controller
{
    /**
     * Show grade settings page
     */
    public function index(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $term = $request->input('term', 'midterm');

        // Get or create KSA settings
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());

        // Get or create GradingScaleSetting to retrieve component_weight_mode
        $gradingScaleSettings = \App\Models\GradingScaleSetting::getOrCreateDefault($classId, auth()->id(), $term);

        // Get components grouped by lowercase category key for view compatibility
        $rawComponents = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get();
        $components = [
            'knowledge' => $rawComponents->where('category', 'Knowledge')->values(),
            'skills'    => $rawComponents->where('category', 'Skills')->values(),
            'attitude'  => $rawComponents->where('category', 'Attitude')->values(),
        ];

        return view('teacher.grades.settings', compact('class', 'term', 'ksaSettings', 'gradingScaleSettings', 'components'));
    }

    /**
     * Show grade settings page (alternative route with term in URL)
     */
    public function show($classId, $term = 'midterm')
    {
        $class = ClassModel::findOrFail($classId);

        // Get or create KSA settings (original, working model)
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());

        // Get or create GradingScaleSetting to retrieve component_weight_mode
        $gradingScaleSettings = GradingScaleSetting::getOrCreateDefault($classId, auth()->id(), $term);

        // Get components grouped by category key for view compatibility
        $rawComponents = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get();
        $components = [
            'knowledge' => $rawComponents->where('category', 'Knowledge')->values(),
            'skills'    => $rawComponents->where('category', 'Skills')->values(),
            'attitude'  => $rawComponents->where('category', 'Attitude')->values(),
        ];

        return view('teacher.grades.settings', compact('class', 'term', 'ksaSettings', 'gradingScaleSettings', 'components'));
    }

    /**
     * Get settings as JSON (for AJAX requests)
     */
    public function getSettings($classId, $term)
    {
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());
        
        // Get grading scale settings for component weight mode
        $gradingScaleSettings = \App\Models\GradingScaleSetting::getOrCreateDefault($classId, auth()->id(), $term);

        $rawComponents = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get();
        $components = [
            'knowledge' => $rawComponents->where('category', 'Knowledge')->values(),
            'skills'    => $rawComponents->where('category', 'Skills')->values(),
            'attitude'  => $rawComponents->where('category', 'Attitude')->values(),
        ];

        return response()->json([
            'ksaSettings' => $ksaSettings,
            'gradingScaleSettings' => $gradingScaleSettings,
            'components' => $components,
        ]);
    }

    /**
     * Update KSA percentages (alternative route with term in URL)
     */
    public function updatePercentages(Request $request, $classId, $term)
    {
        $validated = $request->validate([
            'knowledge_weight' => 'required|numeric|min:0|max:100',
            'skills_weight' => 'required|numeric|min:0|max:100',
            'attitude_weight' => 'required|numeric|min:0|max:100',
        ]);

        // Validate sum equals 100
        $sum = $validated['knowledge_weight'] + $validated['skills_weight'] + $validated['attitude_weight'];
        if (abs($sum - 100) > 0.01) {
            return $this->redirectToSettings($classId, $term, 'Percentages must sum to 100%. Current sum: '.number_format($sum, 2).'%', true);
        }

        $ksaSettings = KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => $term],
            [
                'teacher_id' => auth()->id(),
                'knowledge_weight' => $validated['knowledge_weight'],
                'skills_weight' => $validated['skills_weight'],
                'attitude_weight' => $validated['attitude_weight'],
            ]
        );

        return $this->redirectToSettings($classId, $term, 'KSA percentages updated successfully!');
    }

    /**
     * Update KSA percentages
     */
    public function updateKsaPercentages(Request $request, $classId)
    {
        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'knowledge_weight' => 'required|numeric|min:0|max:100',
            'skills_weight' => 'required|numeric|min:0|max:100',
            'attitude_weight' => 'required|numeric|min:0|max:100',
        ]);

        // Validate sum equals 100
        $sum = $validated['knowledge_weight'] + $validated['skills_weight'] + $validated['attitude_weight'];
        if (abs($sum - 100) > 0.01) {
            return $this->redirectToSettings($classId, $validated['term'], 'Percentages must sum to 100%. Current sum: '.number_format($sum, 2).'%', true);
        }

        $ksaSettings = KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => $validated['term']],
            [
                'teacher_id' => auth()->id(),
                'knowledge_weight' => $validated['knowledge_weight'],
                'skills_weight' => $validated['skills_weight'],
                'attitude_weight' => $validated['attitude_weight'],
            ]
        );

        return $this->redirectToSettings($classId, $validated['term'], 'KSA percentages updated successfully!');
    }

    /**
     * Resolve the correct redirect after a settings action.
     * If the request came from the grade_content page, go back there with ?tab=settings.
     * Otherwise fall back to the dedicated settings page.
     */
    private function redirectToSettings($classId, $term, $message, $isError = false)
    {
        $referer = request()->headers->get('referer', '');
        $contentUrl = route('teacher.grades.content', $classId).'?term='.$term.'&tab=settings';

        // If the referer contains the grade content URL, redirect there
        if (str_contains($referer, "/teacher/grades/content/{$classId}")) {
            $redirect = redirect($contentUrl);
        } else {
            $redirect = redirect()->route('teacher.grades.settings.index', ['classId' => $classId, 'term' => $term]);
        }

        return $isError
            ? $redirect->withErrors(['error' => $message])
            : $redirect->with('success', $message);
    }

    /**
     * Add a new component
     */
    public function addComponent(Request $request, $classId)
    {
        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'category' => 'required|in:Knowledge,Skills,Attitude',
            'component_type' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'max_score' => 'required|integer|min:1',
            'weight_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Get the next order number
        $maxOrder = GradeComponent::where('class_id', $classId)
            ->where('category', $validated['category'])
            ->max('order');

        $component = GradeComponent::create([
            'class_id' => $classId,
            'teacher_id' => auth()->id(),
            'category' => $validated['category'],
            'subcategory' => $validated['component_type'],
            'name' => $validated['name'],
            'max_score' => $validated['max_score'],
            'weight' => $validated['weight_percentage'],
            'order' => ($maxOrder ?? 0) + 1,
            'is_active' => true,
        ]);

        return $this->redirectToSettings($classId, $validated['term'], 'Component "'.$component->name.'" added successfully!');
    }

    /**
     * Update a component
     */
    public function updateComponent(Request $request, $classId, $componentId)
    {
        $component = GradeComponent::where('class_id', $classId)->findOrFail($componentId);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'max_score' => 'required|integer|min:1',
            'weight_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $component->update([
            'name' => $validated['name'],
            'max_score' => $validated['max_score'],
            'weight' => $validated['weight_percentage'],
        ]);

        // Recalculate normalized scores for all entries
        foreach ($component->entries as $entry) {
            if ($entry->raw_score !== null) {
                $entry->normalized_score = $component->normalizeScore($entry->raw_score);
                $entry->save();
            }
        }

        // Determine term from request or component
        $term = $request->input('term', 'midterm');

        return $this->redirectToSettings($classId, $term, 'Component "'.$component->name.'" updated successfully!');
    }

    /**
     * Delete a component
     */
    public function deleteComponent(Request $request, $classId, $componentId)
    {
        $component = GradeComponent::where('class_id', $classId)->findOrFail($componentId);
        $name = $component->name;
        $term = $request->input('term', 'midterm');

        $component->delete();

        return $this->redirectToSettings($classId, $term, 'Component "'.$name.'" deleted successfully!');
    }

    /**
     * Update component weight automation mode
     */
    public function updateWeightMode(Request $request, $classId, $term)
    {
        $validated = $request->validate([
            'component_weight_mode' => 'required|in:manual,semi-auto,auto',
        ]);

        try {
            // Find or create the settings for this class and term
            $settings = \App\Models\GradingScaleSetting::where('class_id', $classId)
                ->where('teacher_id', auth()->id())
                ->where('term', $term)
                ->first();

            if (!$settings) {
                // Create if doesn't exist
                $settings = \App\Models\GradingScaleSetting::create([
                    'class_id' => $classId,
                    'teacher_id' => auth()->id(),
                    'term' => $term,
                    'knowledge_percentage' => 40,
                    'skills_percentage' => 50,
                    'attitude_percentage' => 10,
                    'component_weight_mode' => $validated['component_weight_mode'],
                ]);
            } else {
                $settings->update(['component_weight_mode' => $validated['component_weight_mode']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Component weight automation mode updated successfully!',
                'mode' => $validated['component_weight_mode'],
                'term' => $term,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating automation mode: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reorder components
     */
    public function reorderComponents(Request $request, $classId)
    {
        $validated = $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|exists:grade_components,id',
            'components.*.order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $classId) {
            foreach ($validated['components'] as $componentData) {
                GradeComponent::where('class_id', $classId)
                    ->where('id', $componentData['id'])
                    ->update(['order' => $componentData['order']]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Components reordered successfully!']);
    }

    /**
     * Toggle lock status
     */
    public function toggleLock(Request $request, $classId, $term)
    {
        $ksaSettings = KsaSetting::where('class_id', $classId)
            ->where('term', $term)
            ->firstOrFail();

        $ksaSettings->is_locked = ! $ksaSettings->is_locked;
        $ksaSettings->save();

        $status = $ksaSettings->is_locked ? 'locked' : 'unlocked';

        return $this->redirectToSettings($classId, $term, 'Settings '.$status.' successfully!');
    }

    /**
     * Initialize default components for a class/term
     */
    public function initializeDefaults(Request $request, $classId)
    {
        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
        ]);

        $term = $validated['term'];

        // Check if components already exist
        $existingCount = GradeComponent::where('class_id', $classId)
            ->count();

        if ($existingCount > 0) {
            return $this->redirectToSettings($classId, $term, 'Components already exist for this class. Delete them first if you want to reinitialize.', true);
        }

        DB::transaction(function () use ($classId, $term) {
            $teacherId = auth()->id();

            // Knowledge components
            $knowledgeComponents = [
                ['type' => 'Exam', 'name' => $term === 'midterm' ? 'Midterm Exam' : 'Final Exam', 'max' => 100, 'weight' => 60],
                ['type' => 'Quiz', 'name' => 'Quiz 1', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 2', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 3', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 4', 'max' => 25, 'weight' => 8],
                ['type' => 'Quiz', 'name' => 'Quiz 5', 'max' => 25, 'weight' => 8],
            ];

            foreach ($knowledgeComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'category' => 'Knowledge',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                ]);
            }

            // Skills components
            $skillsComponents = [
                ['type' => 'Output', 'name' => 'Output 1', 'max' => 100, 'weight' => 13.33],
                ['type' => 'Output', 'name' => 'Output 2', 'max' => 100, 'weight' => 13.33],
                ['type' => 'Output', 'name' => 'Output 3', 'max' => 100, 'weight' => 13.34],
                ['type' => 'Participation', 'name' => 'Class Participation 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Participation', 'name' => 'Class Participation 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Participation', 'name' => 'Class Participation 3', 'max' => 100, 'weight' => 10],
                ['type' => 'Activity', 'name' => 'Activity 1', 'max' => 100, 'weight' => 5],
                ['type' => 'Activity', 'name' => 'Activity 2', 'max' => 100, 'weight' => 5],
                ['type' => 'Activity', 'name' => 'Activity 3', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 1', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 2', 'max' => 100, 'weight' => 5],
                ['type' => 'Assignment', 'name' => 'Assignment 3', 'max' => 100, 'weight' => 5],
            ];

            foreach ($skillsComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'category' => 'Skills',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                ]);
            }

            // Attitude components
            $attitudeComponents = [
                ['type' => 'Behavior', 'name' => 'Behavior 1', 'max' => 100, 'weight' => 16.67],
                ['type' => 'Behavior', 'name' => 'Behavior 2', 'max' => 100, 'weight' => 16.67],
                ['type' => 'Behavior', 'name' => 'Behavior 3', 'max' => 100, 'weight' => 16.66],
                ['type' => 'Attendance', 'name' => 'Attendance 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Attendance', 'name' => 'Attendance 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Attendance', 'name' => 'Attendance 3', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 1', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 2', 'max' => 100, 'weight' => 10],
                ['type' => 'Awareness', 'name' => 'Awareness 3', 'max' => 100, 'weight' => 10],
            ];

            foreach ($attitudeComponents as $index => $comp) {
                GradeComponent::create([
                    'class_id' => $classId,
                    'teacher_id' => $teacherId,
                    'category' => 'Attitude',
                    'subcategory' => $comp['type'],
                    'name' => $comp['name'],
                    'max_score' => $comp['max'],
                    'weight' => $comp['weight'],
                    'order' => $index + 1,
                ]);
            }
        });

        return $this->redirectToSettings($classId, $term, 'Default components initialized successfully!');
    }

    /**
     * Save dynamic grade entries
     */
    public function saveDynamicGrades(Request $request, $classId)
    {
        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'scores' => 'required|array',
            'scores.*' => 'array',
            'scores.*.*' => 'nullable|numeric|min:0',
        ]);

        $term = $validated['term'];
        $teacherId = auth()->id();

        // Verify class ownership
        $class = ClassModel::where('id', $classId)
            ->where('teacher_id', $teacherId)
            ->firstOrFail();

        DB::transaction(function () use ($classId, $term, $validated) {
            foreach ($validated['scores'] as $studentId => $componentScores) {
                foreach ($componentScores as $componentId => $rawScore) {
                    if ($rawScore !== null && $rawScore !== '') {
                        // Get component to calculate normalized score
                        $component = GradeComponent::findOrFail($componentId);

                        ComponentEntry::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'component_id' => $componentId,
                                'term' => $term,
                            ],
                            [
                                'class_id' => $classId,
                                'raw_score' => $rawScore,
                                'normalized_score' => $component->normalizeScore($rawScore),
                            ]
                        );
                    }
                }
            }
        });

        return back()->with('success', 'Grades saved successfully!');
    }

    /**
     * Update attendance settings
     */
    public function updateAttendanceSettings(Request $request, $classId)
    {
        $validated = $request->validate([
            'term' => 'required|in:midterm,final',
            'total_meetings' => 'required|integer|min:1|max:100',
            'attendance_weight' => 'required|numeric|min:0|max:100',
            'attendance_category' => 'required|in:knowledge,skills,attitude',
        ]);

        // Checkbox: if present and 'on', it's true; otherwise false
        $enableAttendance = $request->input('enable_attendance_ksa') === 'on';

        $ksaSettings = KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => $validated['term']],
            [
                'teacher_id' => auth()->id(),
                'total_meetings' => $validated['total_meetings'],
                'attendance_weight' => $validated['attendance_weight'],
                'attendance_category' => $validated['attendance_category'],
                'enable_attendance_ksa' => $enableAttendance,
            ]
        );

        return $this->redirectToSettings(
            $classId,
            $validated['term'],
            'Attendance settings updated successfully! Attendance is ' . ($enableAttendance ? 'ENABLED' : 'DISABLED') . 
            ' and will affect ' . ucfirst($validated['attendance_category']) . ' category with ' . $validated['attendance_weight'] . '% weight.'
        );
    }
}
