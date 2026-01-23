@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2><i class="fas fa-pen-fancy me-2"></i>EduTrack Grade Entry Form</h2>
                        <p class="text-muted mb-0"><strong>Class:</strong> {{ $class->name }} | <strong>Term:</strong>
                            {{ ucfirst($term) }}</p>
                    </div>
                    <a href="{{ route('teacher.grades') }}" class="btn fw-bold"
                        style="background-color: #0066cc; color: white; border: none;">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Grading Information Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                        <h6 class="mb-0" style="color: #1a1a1a;"><i class="fas fa-info-circle me-2"></i>EduTrack Grading
                            System - Philippines</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-graduation-cap me-2" style="color: #0066cc;"></i>Knowledge
                                    (40%)</strong>
                                <div class="small text-muted mt-1">
                                    Quizzes 40% + Exams 60%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-rocket me-2" style="color: #00a86b;"></i>Skills (50%)</strong>
                                <div class="small text-muted mt-1">
                                    Output 40%, Class Part 30%, Activities 15%, Assignments 15%
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-heart me-2" style="color: #ff8c00;"></i>Attitude (10%)</strong>
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
                            <th colspan="2"
                                style="background-color: #ffffff; border-left: 4px solid #0066cc; color: #1a1a1a; font-weight: 600;">
                                Student</th>

                            <!-- KNOWLEDGE Section -->
                            <th colspan="7"
                                style="background-color: #f8f9fa; color: #0066cc; text-align: center; font-weight: 600; border-top: 2px solid #0066cc;">
                                <small>KNOWLEDGE (40%)</small>
                            </th>

                            <!-- SKILLS Section - Updated for 3 entries -->
                            <th colspan="12"
                                style="background-color: #f8f9fa; color: #00a86b; text-align: center; font-weight: 600; border-top: 2px solid #00a86b;">
                                <small>SKILLS (50%) - 3 Entries per Component</small>
                            </th>

                            <!-- ATTITUDE Section - Updated for 3 entries -->
                            <th colspan="6"
                                style="background-color: #f8f9fa; color: #ff8c00; text-align: center; font-weight: 600; border-top: 2px solid #ff8c00;">
                                <small>ATTITUDE (10%) - 3 Entries per Component</small>
                            </th>

                            <!-- FINAL Grade -->
                            <th
                                style="background-color: #ffffff; color: #1a1a1a; font-weight: 600; border-left: 4px solid #0066cc;">
                                Final Grade</th>
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

                            <!-- Skills Sub-headers (3 entries each) -->
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Output (MT)</small></th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Class Part (MT)</small>
                            </th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Activities (MT)</small>
                            </th>
                            <th colspan="3" style="text-align: center; color: #00a86b;"><small>Assignments (MT)</small>
                            </th>

                            <!-- Attitude Sub-headers (3 entries each) -->
                            <th colspan="3" style="text-align: center; color: #ff8c00;"><small>Behavior (MT)</small></th>
                            <th colspan="3" style="text-align: center; color: #ff8c00;"><small>Awareness (MT)</small>
                            </th>

                            <!-- Final Grade -->
                            <th><small>Grade</small></th>
                        </tr>
                        <tr>
                            <th colspan="2"></th>
                            <th colspan="7"></th>

                            <!-- Entry numbers for Skills -->
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>

                            <!-- Entry numbers for Attitude -->
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>
                            <th><small>E1</small></th>
                            <th><small>E2</small></th>
                            <th><small>E3</small></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                            <tr>
                                <td class="ps-2">
                                    <small><strong>{{ $student->roll_number ?? '-' }}</strong></small>
                                </td>
                                <td class="ps-2">
                                    <small><strong>{{ $student->user->name }}</strong></small>
                                </td>

                                <!-- KNOWLEDGE INPUTS -->
                                @foreach (['q1', 'q2', 'q3', 'q4', 'q5'] as $quiz)
                                    @php $quizNum = substr($quiz, 1); @endphp
                                    <td>
                                        <input type="number" class="form-control form-control-sm quiz-input"
                                            name="grades[{{ $student->id }}][{{ $quiz }}]" min="0"
                                            max="{{ $range ? $range->{'quiz_' . $quizNum . '_max'} ?? 100 : 100 }}"
                                            step="0.5" data-student="{{ $student->id }}"
                                            placeholder="0-{{ $range ? $range->{'quiz_' . $quizNum . '_max'} ?? 100 : 100 }}">
                                    </td>
                                @endforeach

                                <!-- EXAM INPUTS -->
                                <td>
                                    <input type="number" class="form-control form-control-sm exam-input"
                                        name="grades[{{ $student->id }}][midterm_exam]" min="0"
                                        max="{{ $range ? $range->midterm_exam_max ?? 100 : 100 }}" step="0.5"
                                        data-student="{{ $student->id }}"
                                        placeholder="0-{{ $range ? $range->midterm_exam_max ?? 100 : 100 }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm exam-input"
                                        name="grades[{{ $student->id }}][final_exam]" min="0"
                                        max="{{ $range ? $range->final_exam_max ?? 100 : 100 }}" step="0.5"
                                        data-student="{{ $student->id }}"
                                        placeholder="0-{{ $range ? $range->final_exam_max ?? 100 : 100 }}">
                                </td>

                                <!-- SKILLS INPUTS - 3 entries each -->
                                <!-- Output (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][output_1_{{ $term }}]" min="0"
                                        max="100" step="0.5" data-student="{{ $student->id }}" placeholder="E1"
                                        title="Output Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][output_2_{{ $term }}]" min="0"
                                        max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Output Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][output_3_{{ $term }}]" min="0"
                                        max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Output Entry 3">
                                </td>

                                <!-- Class Participation (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][class_participation_1_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E1" title="Class Participation Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][class_participation_2_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Class Participation Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][class_participation_3_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Class Participation Entry 3">
                                </td>

                                <!-- Activities (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][activities_1_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E1" title="Activities Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][activities_2_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Activities Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][activities_3_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Activities Entry 3">
                                </td>

                                <!-- Assignments (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][assignments_1_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E1" title="Assignments Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][assignments_2_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Assignments Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm skills-input"
                                        name="grades[{{ $student->id }}][assignments_3_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Assignments Entry 3">
                                </td>

                                <!-- ATTITUDE INPUTS - 3 entries each -->
                                <!-- Behavior (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][behavior_1_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E1" title="Behavior Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][behavior_2_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Behavior Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][behavior_3_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Behavior Entry 3">
                                </td>

                                <!-- Awareness (E1, E2, E3) -->
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][awareness_1_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E1" title="Awareness Entry 1">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][awareness_2_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E2" title="Awareness Entry 2">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm attitude-input"
                                        name="grades[{{ $student->id }}][awareness_3_{{ $term }}]"
                                        min="0" max="100" step="0.5" data-student="{{ $student->id }}"
                                        placeholder="E3" title="Awareness Entry 3">
                                </td>

                                <!-- FINAL GRADE DISPLAY -->
                                <td>
                                    <input type="text" class="form-control form-control-sm final-grade"
                                        name="grades[{{ $student->id }}][final_grade]" readonly
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
                    <div class="card shadow-sm">
                        <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                            <h6 class="mb-0" style="color: #1a1a1a;">Additional Notes</h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="remarks" rows="3"
                                placeholder="Enter any remarks about this grading period..." style="border: 1px solid #ddd; border-radius: 5px;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('teacher.grades') }}" class="btn fw-bold"
                            style="background-color: #e9ecef; color: #1a1a1a; border: none;">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn fw-bold"
                            style="background-color: #00a86b; color: white; border: none;">
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
            font-weight: 600;
        }

        .table-bordered td {
            padding: 4px 2px !important;
        }

        .form-control-sm {
            font-size: 12px;
            height: 32px;
            padding: 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-control-sm:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate grades on input change
            const inputs = document.querySelectorAll('.quiz-input, .exam-input, .skills-input, .attitude-input');

            inputs.forEach(input => {
                input.addEventListener('input', calculateFinalGrade);
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
                const midterm = parseFloat(row.querySelector('input[name*="[midterm_exam]"]').value) || 0;
                const final = parseFloat(row.querySelector('input[name*="[final_exam]"]').value) || 0;

                const output = parseFloat(row.querySelector('input[name*="[output_score]"]').value) || 0;
                const classPart = parseFloat(row.querySelector('input[name*="[class_participation_score]"]')
                    .value) || 0;
                const activities = parseFloat(row.querySelector('input[name*="[activities_score]"]').value) || 0;
                const assignments = parseFloat(row.querySelector('input[name*="[assignments_score]"]').value) || 0;

                const behavior = parseFloat(row.querySelector('input[name*="[behavior_score]"]').value) || 0;
                const awareness = parseFloat(row.querySelector('input[name*="[awareness_score]"]').value) || 0;

                // Calculate Knowledge (40% of term)
                // Quizzes: 40% (each q out of 5, convert to 100)
                const quizTotal = (q1 + q2 + q3 + q4 + q5) / 25 * 100;
                const quizPart = quizTotal * 0.40;

                // Exams: 60%
                const examAverage = (midterm + final) / 2;
                const examPart = examAverage * 0.60;

                const knowledge = quizPart + examPart;

                // Calculate Skills (50% of term)
                const skills = (output * 0.40) + (classPart * 0.30) + (activities * 0.15) + (assignments * 0.15);

                // Calculate Attitude (10% of term)
                const attitude = (behavior * 0.50) + (awareness * 0.50);

                // Calculate Final Grade (EduTrack: K=40%, S=50%, A=10%)
                const finalGrade = (knowledge * 0.40) + (skills * 0.50) + (attitude * 0.10);

                // Display final grade
                const finalGradeInput = row.querySelector('.final-grade');
                if (finalGradeInput) {
                    finalGradeInput.value = finalGrade.toFixed(2);
                }

                // Update hidden input
                const hiddenInput = row.querySelector(`input[name="grades[${studentId}][final_grade]"]`);
                if (hiddenInput) {
                    hiddenInput.value = finalGrade.toFixed(2);
                }
            }
        });
    </script>
@endsection
