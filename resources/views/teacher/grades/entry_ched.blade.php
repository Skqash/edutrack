@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2><i class="fas fa-pen-fancy me-2"></i>CHED Grade Entry Form</h2>
                    <p class="text-muted mb-0"><strong>Class:</strong> {{ $class->name }} | <strong>Term:</strong> {{ ucfirst($term) }}</p>
                </div>
                <a href="{{ route('teacher.grades') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Grading Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>CHED Grading System - Philippines</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-graduation-cap me-2" style="color: #667eea;"></i>Knowledge (40%)</strong>
                            <div class="small text-muted mt-1">
                                Quizzes 40% + Exams 60%
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-rocket me-2" style="color: #764ba2;"></i>Skills (50%)</strong>
                            <div class="small text-muted mt-1">
                                Output 40%, Class Part 30%, Activities 15%, Assignments 15%
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-heart me-2" style="color: #28a745;"></i>Attitude (10%)</strong>
                            <div class="small text-muted mt-1">
                                Behavior 50% + Awareness 50%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Form -->
    <form method="POST" action="{{ route('teacher.grades.store', $class->id) }}" id="gradingForm">
        @csrf

        <input type="hidden" name="term" value="{{ $term }}">

        <!-- Students Grid -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th colspan="2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">Student</th>
                        
                        <!-- KNOWLEDGE Section -->
                        <th colspan="7" style="background: #667eea; color: white; text-align: center;">
                            <small>KNOWLEDGE (40%)</small>
                        </th>
                        
                        <!-- SKILLS Section -->
                        <th colspan="4" style="background: #764ba2; color: white; text-align: center;">
                            <small>SKILLS (50%)</small>
                        </th>
                        
                        <!-- ATTITUDE Section -->
                        <th colspan="2" style="background: #28a745; color: white; text-align: center;">
                            <small>ATTITUDE (10%)</small>
                        </th>
                        
                        <!-- FINAL Grade -->
                        <th style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">Final Grade</th>
                    </tr>
                    <tr>
                        <th>Roll #</th>
                        <th>Name</th>
                        
                        <!-- Knowledge Sub-headers -->
                        <th><small>Q1</small></th>
                        <th><small>Q2</small></th>
                        <th><small>Q3</small></th>
                        <th><small>Q4</small></th>
                        <th><small>Q5</small></th>
                        <th><small>PR Exam</small></th>
                        <th><small>MD Exam</small></th>
                        
                        <!-- Skills Sub-headers -->
                        <th><small>Output</small></th>
                        <th><small>Class Part</small></th>
                        <th><small>Activities</small></th>
                        <th><small>Assignments</small></th>
                        
                        <!-- Attitude Sub-headers -->
                        <th><small>Behavior</small></th>
                        <th><small>Awareness</small></th>
                        
                        <!-- Final Grade -->
                        <th><small>Grade</small></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        <tr>
                            <td class="ps-2">
                                <small><strong>{{ $student->roll_number ?? '-' }}</strong></small>
                            </td>
                            <td class="ps-2">
                                <small><strong>{{ $student->user->name }}</strong></small>
                            </td>
                            
                            <!-- KNOWLEDGE INPUTS -->
                            @foreach(['q1', 'q2', 'q3', 'q4', 'q5'] as $quiz)
                                <td>
                                    <input type="number" 
                                           class="form-control form-control-sm quiz-input" 
                                           name="grades[{{ $student->id }}][{{ $quiz }}]" 
                                           min="0" max="5" step="0.5" 
                                           data-student="{{ $student->id }}"
                                           placeholder="0-5">
                                </td>
                            @endforeach
                            
                            <!-- EXAM INPUTS -->
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm exam-input" 
                                       name="grades[{{ $student->id }}][prelim_exam]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm exam-input" 
                                       name="grades[{{ $student->id }}][midterm_exam]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            
                            <!-- SKILLS INPUTS -->
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm skills-input" 
                                       name="grades[{{ $student->id }}][output_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm skills-input" 
                                       name="grades[{{ $student->id }}][class_participation_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm skills-input" 
                                       name="grades[{{ $student->id }}][activities_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm skills-input" 
                                       name="grades[{{ $student->id }}][assignments_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            
                            <!-- ATTITUDE INPUTS -->
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm attitude-input" 
                                       name="grades[{{ $student->id }}][behavior_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            <td>
                                <input type="number" 
                                       class="form-control form-control-sm attitude-input" 
                                       name="grades[{{ $student->id }}][awareness_score]" 
                                       min="0" max="100" step="0.5" 
                                       data-student="{{ $student->id }}"
                                       placeholder="0-100">
                            </td>
                            
                            <!-- FINAL GRADE DISPLAY -->
                            <td>
                                <input type="text" 
                                       class="form-control form-control-sm final-grade" 
                                       name="grades[{{ $student->id }}][final_grade]" 
                                       readonly
                                       style="background-color: #f0f4ff; text-align: center; font-weight: bold;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Remarks Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <h6 class="mb-0">Additional Notes</h6>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="remarks" rows="3" placeholder="Enter any remarks about this grading period..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('teacher.grades') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Save Grades
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.table-bordered th {
    padding: 8px 4px !important;
    font-size: 11px;
    text-align: center;
}

.table-bordered td {
    padding: 4px 2px !important;
}

.form-control-sm {
    font-size: 12px;
    height: 32px;
    padding: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate grades on input change
    const inputs = document.querySelectorAll('.quiz-input, .exam-input, .skills-input, .attitude-input');
    
    inputs.forEach(input => {
        input.addEventListener('change', calculateFinalGrade);
    });

    function calculateFinalGrade(e) {
        const studentId = e.target.dataset.student;
        const row = document.querySelector(`input[data-student="${studentId}"]`).closest('tr');
        
        // Get all input values
        const q1 = parseFloat(row.querySelector('input[name*="[q1]"]').value) || 0;
        const q2 = parseFloat(row.querySelector('input[name*="[q2]"]').value) || 0;
        const q3 = parseFloat(row.querySelector('input[name*="[q3]"]').value) || 0;
        const q4 = parseFloat(row.querySelector('input[name*="[q4]"]').value) || 0;
        const q5 = parseFloat(row.querySelector('input[name*="[q5]"]').value) || 0;
        const prelim = parseFloat(row.querySelector('input[name*="[prelim_exam]"]').value) || 0;
        const midterm = parseFloat(row.querySelector('input[name*="[midterm_exam]"]').value) || 0;
        
        const output = parseFloat(row.querySelector('input[name*="[output_score]"]').value) || 0;
        const classPart = parseFloat(row.querySelector('input[name*="[class_participation_score]"]').value) || 0;
        const activities = parseFloat(row.querySelector('input[name*="[activities_score]"]').value) || 0;
        const assignments = parseFloat(row.querySelector('input[name*="[assignments_score]"]').value) || 0;
        
        const behavior = parseFloat(row.querySelector('input[name*="[behavior_score]"]').value) || 0;
        const awareness = parseFloat(row.querySelector('input[name*="[awareness_score]"]').value) || 0;

        // Calculate Knowledge (40% of term)
        // Quizzes: 40% (each q out of 5, convert to 100)
        const quizTotal = (q1 + q2 + q3 + q4 + q5) / 25 * 100;
        const quizPart = quizTotal * 0.40;
        
        // Exams: 60%
        const examAverage = (prelim + midterm) / 2;
        const examPart = examAverage * 0.60;
        
        const knowledge = quizPart + examPart;

        // Calculate Skills (50% of term)
        const skills = (output * 0.40) + (classPart * 0.30) + (activities * 0.15) + (assignments * 0.15);

        // Calculate Attitude (10% of term)
        const attitude = (behavior * 0.50) + (awareness * 0.50);

        // Calculate Final Grade (CHED: K=40%, S=50%, A=10%)
        const finalGrade = (knowledge * 0.40) + (skills * 0.50) + (attitude * 0.10);

        // Display final grade
        const finalGradeInput = row.querySelector('.final-grade');
        finalGradeInput.value = finalGrade.toFixed(2);

        // Update hidden input
        row.querySelector(`input[name="grades[${studentId}][final_grade]"]`).value = finalGrade.toFixed(2);
    }
});
</script>
@endsection
