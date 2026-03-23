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
        $class = ClassModel::with('students')->findOrFail($classId);
        $term = $request->get('term', 'midterm');

        // Get or create KSA settings
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());

        // Get components grouped by category (no term filter since components are reusable)
        $components = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get()
            ->groupBy('category');

        return view('teacher.grades.settings', compact('class', 'term', 'ksaSettings', 'components'));
    }

    /**
     * Show grade settings page (alternative route with term in URL)
     */
    public function show($classId, $term = 'midterm')
    {
        $class = ClassModel::with('students')->findOrFail($classId);

        // Get or create KSA settings
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());

        // Get components grouped by category (no term filter since components are reusable)
        $components = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get()
            ->groupBy('category');

        return view('teacher.grades.settings', compact('class', 'term', 'ksaSettings', 'components'));
    }

    /**
     * Get settings as JSON (for AJAX requests)
     */
    public function getSettings($classId, $term)
    {
        $ksaSettings = KsaSetting::getOrCreateDefault($classId, $term, auth()->id());

        $components = GradeComponent::where('class_id', $classId)
            ->where('is_active', true)
            ->ordered()
            ->get()
            ->groupBy('category');

        return response()->json([
            'ksaSettings' => $ksaSettings,
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
            return back()->withErrors(['error' => 'Percentages must sum to 100%. Current sum: '.number_format($sum, 2).'%']);
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

        return back()->with('success', 'KSA percentages updated successfully!');
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
            return back()->withErrors(['error' => 'Percentages must sum to 100%. Current sum: '.number_format($sum, 2).'%']);
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

        return back()->with('success', 'KSA percentages updated successfully!');
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

        return back()->with('success', 'Component "'.$component->name.'" added successfully!');
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

        return back()->with('success', 'Component "'.$component->name.'" updated successfully!');
    }

    /**
     * Delete a component
     */
    public function deleteComponent($classId, $componentId)
    {
        $component = GradeComponent::where('class_id', $classId)->findOrFail($componentId);
        $name = $component->name;

        $component->delete();

        return back()->with('success', 'Component "'.$name.'" deleted successfully!');
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

        return back()->with('success', 'Settings '.$status.' successfully!');
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
            return back()->withErrors(['error' => 'Components already exist for this class. Delete them first if you want to reinitialize.']);
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

        return back()->with('success', 'Default components initialized successfully!');
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

        $ksaSettings = KsaSetting::updateOrCreate(
            ['class_id' => $classId, 'term' => $validated['term']],
            [
                'teacher_id' => auth()->id(),
                'total_meetings' => $validated['total_meetings'],
                'attendance_weight' => $validated['attendance_weight'],
                'attendance_category' => $validated['attendance_category'],
            ]
        );

        return back()->with('success', 'Attendance settings updated successfully! Attendance will now affect '
            .ucfirst($validated['attendance_category']).' category with '.$validated['attendance_weight'].'% weight.');
    }
}
