<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\GradingScaleSetting;
use App\Models\GradingSheetTemplate;
use App\Services\GradingSheetGenerator;
use App\Services\GradingCalculationService;
use Illuminate\Http\Request;

class GradingSheetController extends Controller
{
    protected $generator;
    protected $calculator;

    public function __construct(GradingSheetGenerator $generator, GradingCalculationService $calculator)
    {
        $this->generator = $generator;
        $this->calculator = $calculator;
    }

    /**
     * Preview grading sheet
     */
    public function preview(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = $request->input('term', 'midterm');
        $templateId = $request->input('template_id', null);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', $term)
            ->first();

        if (!$settings) {
            return back()->with('error', 'Grading settings not configured');
        }

        try {
            $html = $this->generator->generate($classId, $term, 'html', $templateId);
            
            return view('teacher.grades.sheet-preview', [
                'class' => $class,
                'term' => $term,
                'settings' => $settings,
                'html' => $html,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating grading sheet: ' . $e->getMessage());
        }
    }

    /**
     * Download grading sheet
     */
    public function download(Request $request, $classId, $format = 'pdf')
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = $request->input('term', 'midterm');
        $templateId = $request->input('template_id', null);

        // Validate format
        if (!in_array($format, ['pdf', 'csv', 'html'])) {
            return back()->with('error', 'Invalid format');
        }

        try {
            $result = $this->generator->generate($classId, $term, $format, $templateId);

            if ($format === 'pdf') {
                return $result;
            } elseif ($format === 'csv') {
                return response($result)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', "attachment; filename=\"grading-sheet-{$term}.csv\"");
            } else {
                return response($result)
                    ->header('Content-Type', 'text/html; charset=utf-8')
                    ->header('Content-Disposition', "attachment; filename=\"grading-sheet-{$term}.html\"");
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error downloading grading sheet: ' . $e->getMessage());
        }
    }

    /**
     * Print grading sheet (optimized for printing)
     */
    public function print(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $term = $request->input('term', 'midterm');
        $templateId = $request->input('template_id', null);

        try {
            $html = $this->generator->generate($classId, $term, 'html', $templateId);
            
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8');
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating print view: ' . $e->getMessage());
        }
    }

    /**
     * List available templates
     */
    public function templates(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $templates = GradingSheetTemplate::where('is_active', true)
            ->where(function ($query) {
                $query->where('school_id', optional(auth()->user())->school_id)
                    ->orWhereNull('school_id');
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($templates);
    }

    /**
     * Update grading sheet template
     */
    public function updateTemplate(Request $request, $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $this->authorize('view', $class);

        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', $request->input('term', 'midterm'))
            ->first();

        if (!$settings) {
            return response()->json(['error' => 'Settings not found'], 404);
        }

        $validated = $request->validate([
            'output_format' => 'required|in:standard,detailed,summary',
        ]);

        $settings->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template updated successfully'
        ]);
    }
}
