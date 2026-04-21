<?php

namespace App\Services;

use App\Models\GradingScaleSetting;
use App\Models\GradingSheetTemplate;
use App\Models\ComponentAverage;
use App\Models\Student;
use App\Models\AttendanceSignature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class GradingSheetGenerator
{
    /**
     * Generate grading sheet in various formats
     */
    public function generate(int $classId, string $term = 'midterm', ?string $format = null, ?int $templateId = null)
    {
        $settings = GradingScaleSetting::where('class_id', $classId)
            ->where('term', $term)
            ->first();

        if (!$settings) {
            throw new \Exception('Grading settings not found');
        }

        $format = $format ?? $settings->output_format;

        // Get template
        $template = $templateId 
            ? GradingSheetTemplate::find($templateId)
            : GradingSheetTemplate::where('is_default', true)->first();

        // Get grades
        $grades = $this->getGrades($classId, $term);

        return match($format) {
            'html' => $this->generateHTML($classId, $term, $grades, $settings, $template),
            'pdf' => $this->generatePDF($classId, $term, $grades, $settings, $template),
            'csv' => $this->generateCSV($grades),
            default => $this->generateHTML($classId, $term, $grades, $settings, $template),
        };
    }

    /**
     * Get grades for class/term
     */
    private function getGrades(int $classId, string $term): Collection
    {
        return ComponentAverage::with('student')
            ->where('class_id', $classId)
            ->where('term', $term)
            ->orderBy('student_id')
            ->get();
    }

    /**
     * Generate HTML grading sheet
     */
    private function generateHTML(int $classId, string $term, Collection $grades, GradingScaleSetting $settings, ?GradingSheetTemplate $template): string
    {
        $students = Student::where('class_id', $classId)->orderBy('last_name')->orderBy('first_name')->get();
        
        $html = '<html><head><meta charset="UTF-8"><style>';
        $html .= $this->getStyles();
        $html .= '</style></head><body>';

        // Header
        $html .= $this->generateHeader($classId, $term);

        // Grading Scale Legend
        if ($template?->include_grade_scale_legend ?? true) {
            $html .= $this->generateScaleLegend();
        }

        // Grades Table
        $html .= '<table class="grades-table">';
        $html .= $this->generateTableHeader($settings, $template);
        $html .= $this->generateTableBody($students, $grades, $settings, $template);
        $html .= '</table>';

        // Signatures section (if enabled)
        if ($settings->enable_esignature) {
            $html .= $this->generateSignatureSection($classId, $term);
        }

        $html .= '</body></html>';

        return $html;
    }

    /**
     * Generate PDF grading sheet
     */
    private function generatePDF(int $classId, string $term, Collection $grades, GradingScaleSetting $settings, ?GradingSheetTemplate $template)
    {
        $html = $this->generateHTML($classId, $term, $grades, $settings, $template);
        
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download("grading-sheet-{$term}.pdf");
    }

    /**
     * Generate CSV grading sheet
     */
    private function generateCSV(Collection $grades): string
    {
        $csv = "Student Name,Student ID,Knowledge,Skills,Attitude,Final Grade,Decimal Grade,Status\n";

        foreach ($grades as $grade) {
            $status = $grade->final_grade >= 75 ? 'Passed' : 'Failed';
            $csv .= sprintf(
                "\"%s\",%s,%.2f,%.2f,%.2f,%.2f,%.2f,%s\n",
                $grade->student->full_name,
                $grade->student->student_id,
                $grade->knowledge_average,
                $grade->skills_average,
                $grade->attitude_average,
                $grade->final_grade,
                $this->getDecimalGrade($grade->final_grade),
                $status
            );
        }

        return $csv;
    }

    /**
     * Get CSS styles for grading sheet
     */
    private function getStyles(): string
    {
        return <<<CSS
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 9pt;
        }
        
        .scale-legend {
            margin: 15px 0;
            font-size: 9pt;
        }
        
        .scale-legend table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .scale-legend td {
            padding: 3px 5px;
            border: 1px solid #ccc;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .grades-table th {
            background-color: #f0f0f0;
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }
        
        .grades-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        
        .grades-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .grades-table .student-name {
            text-align: left;
            font-weight: 500;
        }
        
        .grades-table .student-id {
            text-align: center;
        }
        
        .passing {
            color: #28a745;
            font-weight: bold;
        }
        
        .failing {
            color: #dc3545;
            font-weight: bold;
        }
        
        .signature-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 200px;
        }
        
        .signature-label {
            font-size: 8pt;
            margin-top: 5px;
        }
        CSS;
    }

    /**
     * Generate table header
     */
    private function generateTableHeader(GradingScaleSetting $settings, ?GradingSheetTemplate $template): string
    {
        $html = '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Student Name</th>';
        $html .= '<th>Student ID</th>';
        
        if ($template?->include_components ?? true) {
            $html .= '<th colspan="3">Components</th>';
        }
        
        if ($template?->include_ksa_breakdown ?? true) {
            $html .= '<th>Knowledge<br>(' . $settings->knowledge_percentage . '%)</th>';
            $html .= '<th>Skills<br>(' . $settings->skills_percentage . '%)</th>';
            $html .= '<th>Attitude<br>(' . $settings->attitude_percentage . '%)</th>';
        }
        
        if ($template?->include_final_grade ?? true) {
            $html .= '<th>Final Grade</th>';
        }
        
        if ($template?->include_decimal_grade ?? true) {
            $html .= '<th>Decimal<br>Grade</th>';
        }
        
        if ($template?->include_remarks ?? false) {
            $html .= '<th>Remarks</th>';
        }
        
        $html .= '</tr>';
        return $html;
    }

    /**
     * Generate table body
     */
    private function generateTableBody(Collection $students, Collection $grades, GradingScaleSetting $settings, ?GradingSheetTemplate $template): string
    {
        $html = '';
        $count = 1;

        foreach ($students as $student) {
            $grade = $grades->where('student_id', $student->id)->first();
            
            $html .= '<tr>';
            $html .= '<td>' . $count++ . '</td>';
            $html .= '<td class="student-name">' . htmlspecialchars($student->full_name) . '</td>';
            $html .= '<td class="student-id">' . htmlspecialchars($student->student_id) . '</td>';
            
            if ($template?->include_components ?? true) {
                $html .= '<td>-</td><td>-</td><td>-</td>';
            }
            
            if ($template?->include_ksa_breakdown ?? true) {
                $html .= '<td>' . ($grade?->knowledge_average ?? '-') . '</td>';
                $html .= '<td>' . ($grade?->skills_average ?? '-') . '</td>';
                $html .= '<td>' . ($grade?->attitude_average ?? '-') . '</td>';
            }
            
            if ($template?->include_final_grade ?? true) {
                $finalGrade = $grade?->final_grade ?? '-';
                $class = $grade && $grade->final_grade >= 75 ? 'passing' : 'failing';
                $html .= '<td class="' . $class . '">' . $finalGrade . '</td>';
            }
            
            if ($template?->include_decimal_grade ?? true) {
                $decimal = $grade ? $this->getDecimalGrade($grade->final_grade) : '-';
                $html .= '<td>' . $decimal . '</td>';
            }
            
            if ($template?->include_remarks ?? false) {
                $html .= '<td>-</td>';
            }
            
            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * Generate grading scale legend
     */
    private function generateScaleLegend(): string
    {
        return <<<HTML
        <div class="scale-legend">
            <strong>Grading Scale:</strong>
            <table>
                <tr>
                    <td><strong>98+</strong></td>
                    <td>1.0 Excellent</td>
                    <td><strong>92-89</strong></td>
                    <td>1.50-1.75</td>
                    <td><strong>80-77</strong></td>
                    <td>2.50-2.75</td>
                </tr>
                <tr>
                    <td><strong>95-97</strong></td>
                    <td>1.25</td>
                    <td><strong>86-83</strong></td>
                    <td>2.0-2.25</td>
                    <td><strong>70-74</strong></td>
                    <td>3.0-3.50</td>
                </tr>
            </table>
        </div>
        HTML;
    }

    /**
     * Generate signature section
     */
    private function generateSignatureSection(int $classId, string $term): string
    {
        return <<<HTML
        <div class="signature-section">
            <p><strong>Teacher's Signature:</strong></p>
            <div class="signature-line"></div>
            <div class="signature-label">Teacher Name and Signature</div>
        </div>
        HTML;
    }

    /**
     * Generate header
     */
    private function generateHeader(int $classId, string $term): string
    {
        $class = \App\Models\ClassModel::find($classId);
        
        return <<<HTML
        <div class="header">
            <h2>Grading Sheet - {$class->class_name}</h2>
            <p><strong>Term:</strong> {$term}</p>
            <p><strong>Generated:</strong> {$this->getCurrentDate()}</p>
        </div>
        HTML;
    }

    /**
     * Get current date formatted
     */
    private function getCurrentDate(): string
    {
        return now()->format('F d, Y');
    }

    /**
     * Convert grade to decimal
     */
    private function getDecimalGrade(float $percentage): float
    {
        if ($percentage >= 98) return 1.0;
        if ($percentage >= 95) return 1.25;
        if ($percentage >= 92) return 1.50;
        if ($percentage >= 89) return 1.75;
        if ($percentage >= 86) return 2.0;
        if ($percentage >= 83) return 2.25;
        if ($percentage >= 80) return 2.50;
        if ($percentage >= 77) return 2.75;
        if ($percentage >= 74) return 3.0;
        if ($percentage >= 71) return 3.25;
        if ($percentage >= 70) return 3.50;
        return 5.0;
    }
}
