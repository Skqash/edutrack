@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <!-- Form Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; padding: 2rem; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2"><i class="fas fa-document-alt me-2"></i>Edutrack Grade Entry Form</h2>
                            <p class="mb-0">{{ $class->class_name }} - {{ $class->subject->name ?? 'N/A' }} | Term:
                                <strong>{{ ucfirst($term) }}</strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('teacher.assessment.configure', $class->id) }}"
                                class="btn btn-light btn-sm me-2">
                                <i class="fas fa-sliders-h"></i> Configure
                            </a>
                            <a href="{{ route('teacher.grades') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.grades.store.enhanced', $class->id) }}" id="gradeForm">
            @csrf
            <input type="hidden" name="term" value="{{ $term }}">

            <!-- Assessment Configuration Summary -->
            @if ($range)
                <div class="card mb-4 shadow-sm border-left-info">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i> Grading Configuration</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <small><strong>Knowledge (40%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Quizzes: {{ $range->num_quizzes ?? 5 }} ×
                                    {{ (int) ($range->total_quiz_items / ($range->num_quizzes ?? 5)) }} items
                                    <br>Exams: Prelim, Midterm, Final
                                </small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Skills (50%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Output • Class Participation
                                    <br>Activities • Assignments
                                </small>
                            </div>
                            <div class="col-md-3">
                                <small><strong>Attitude (10%):</strong></small>
                                <br>
                                <small class="text-muted">
                                    Behavior (50%)
                                    <br>Awareness (50%)
                                </small>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('teacher.assessment.configure', $class->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modify Configuration
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Grading Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-table me-2"></i>Student Grade Entry ({{ $students->count() }} students)
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                            <!-- Header Row 1: Main Components -->
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; width: 18%;">Student Name</th>
                                    <th colspan="{{ ($range->num_quizzes ?? 5) + 3 }}" class="text-center bg-primary"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <strong>KNOWLEDGE (40%)</strong>
                                    </th>
                                    <th colspan="5" class="text-center" style="background-color: #f8a623; color: white;">
                                        <strong>SKILLS (50%)</strong>
                                    </th>
                                    <th colspan="3" class="text-center" style="background-color: #17a2b8; color: white;">
                                        <strong>ATTITUDE (10%)</strong>
                                    </th>
                                    <th colspan="2" class="text-center text-white" style="background-color: #6c757d;">
                                        <strong>SCORE</strong>
                                    </th>
                                </tr>

                                <!-- Header Row 2: Sub-components -->
                                <tr style="background-color: #f8f9fa;">
                                    <!-- Knowledge Sub-Headers -->
                                    @for ($q = 1; $q <= ($range->num_quizzes ?? 5); $q++)
                                        <th class="text-center text-nowrap"
                                            style="background-color: #e3f2fd; font-weight: 600;">Q{{ $q }}</th>
                                    @endfor
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e3f2fd; font-weight: 600;">Mid</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e3f2fd; font-weight: 600;">Final</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #b3e5fc; font-weight: 600; border-left: 2px solid #667eea;">
                                        K%</th>

                                    <!-- Skills Sub-Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Output</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">C.Part</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Activ</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #fff3e0; font-weight: 600;">Assign</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #ffe0b2; font-weight: 600; border-left: 2px solid #f8a623;">
                                        S%</th>

                                    <!-- Attitude Sub-Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e0f2f1; font-weight: 600;">Behav</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e0f2f1; font-weight: 600;">Aware</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #b2dfdb; font-weight: 600; border-left: 2px solid #17a2b8;">
                                        A%</th>

                                    <!-- Score Headers -->
                                    <th class="text-center text-nowrap"
                                        style="background-color: #e8e8e8; font-weight: 600; border-left: 2px solid #6c757d;">
                                        Final</th>
                                    <th class="text-center text-nowrap"
                                        style="background-color: #d8d8d8; font-weight: 600;">Letter</th>
                                </tr>
                            </thead>

                            <!-- Data Rows -->
                            <tbody>
                                @foreach ($students as $index => $student)
                                    @php
                                        $grade = $grades->where('student_id', $student->id)->first();
                                        $studentName = $student->user->name ?? ($student->name ?? 'Unknown');
                                    @endphp
                                    <tr>
                                        <td style="font-weight: 600; vertical-align: middle;">
                                            {{ $index + 1 }}. {{ $studentName }}
                                            <br>
                                            <small class="text-muted">{{ $student->admission_number ?? 'N/A' }}</small>
                                            <input type="hidden" name="grades[{{ $student->id }}][student_id]"
                                                value="{{ $student->id }}">
                                        </td>

                                        <!-- Knowledge Quizzes -->
                                        @for ($q = 1; $q <= ($range->num_quizzes ?? 5); $q++)
                                            <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                                <input type="number"
                                                    name="grades[{{ $student->id }}][q{{ $q }}]"
                                                    class="form-control form-control-sm text-center quiz-input"
                                                    value="{{ old("grades.$student->id.q$q", $grade?->{'q' . $q} ?? '') }}"
                                                    min="0" max="{{ $range->{'quiz_' . $q . '_max'} ?? 100 }}"
                                                    placeholder="0-{{ $range->{'quiz_' . $q . '_max'} ?? 100 }}"
                                                    style="font-size: 0.75rem; padding: 2px;">
                                            </td>
                                        @endfor

                                        <!-- Exams -->
                                        <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][midterm_exam]"
                                                class="form-control form-control-sm text-center exam-input"
                                                value="{{ old("grades.$student->id.midterm_exam", $grade?->midterm_exam ?? '') }}"
                                                min="0" max="{{ $range->midterm_exam_max ?? 100 }}"
                                                placeholder="0-{{ $range->midterm_exam_max ?? 100 }}"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #f5f5f5; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][final_exam]"
                                                class="form-control form-control-sm text-center exam-input"
                                                value="{{ old("grades.$student->id.final_exam", $grade?->final_exam ?? '') }}"
                                                min="0" max="{{ $range->final_exam_max ?? 100 }}"
                                                placeholder="0-{{ $range->final_exam_max ?? 100 }}"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Knowledge Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #b3e5fc; font-weight: 600; border-left: 2px solid #667eea; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="{{ $grade?->knowledge_score ?? '0' }}" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Skills Components -->
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][output_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.output_score", $grade?->output_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number"
                                                name="grades[{{ $student->id }}][class_participation_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.class_participation_score", $grade?->class_participation_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][activities_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.activities_score", $grade?->activities_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #fff8f0; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][assignments_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.assignments_score", $grade?->assignments_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Skills Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #ffe0b2; font-weight: 600; border-left: 2px solid #f8a623; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="{{ $grade?->skills_score ?? '0' }}" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Attitude Components -->
                                        <td class="text-center" style="background-color: #f0fffe; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][behavior_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.behavior_score", $grade?->behavior_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>
                                        <td class="text-center" style="background-color: #f0fffe; padding: 4px;">
                                            <input type="number" name="grades[{{ $student->id }}][awareness_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$student->id.awareness_score", $grade?->awareness_score ?? '') }}"
                                                min="0" max="100" placeholder="0-100"
                                                style="font-size: 0.75rem; padding: 2px;">
                                        </td>

                                        <!-- Attitude Score (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #b2dfdb; font-weight: 600; border-left: 2px solid #17a2b8; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="{{ $grade?->attitude_score ?? '0' }}" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Final Grade (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #e8e8e8; font-weight: 600; border-left: 2px solid #6c757d; padding: 4px;">
                                            <input type="text"
                                                class="form-control form-control-sm text-center bg-light"
                                                value="{{ $grade?->final_grade ?? '0' }}" readonly
                                                style="font-size: 0.75rem; padding: 2px; font-weight: 600;">
                                        </td>

                                        <!-- Grade Point (Read-only) -->
                                        <td class="text-center"
                                            style="background-color: #d8d8d8; font-weight: 700; padding: 4px;">
                                            @php
                                                $gradePoint =
                                                    \App\Models\Grade::getGradePoint($grade?->final_grade ?? 0) ?? '-';
                                                $bgColor =
                                                    $gradePoint === 1.0
                                                        ? '#90EE90'
                                                        : ($gradePoint === 1.25 || $gradePoint === 1.5
                                                            ? '#87CEEB'
                                                            : ($gradePoint === 1.75 || $gradePoint === 2.0
                                                                ? '#FFD700'
                                                                : ($gradePoint === 2.25 || $gradePoint === 2.5
                                                                    ? '#FFA07A'
                                                                    : ($gradePoint === 'INC'
                                                                        ? '#FFB6C6'
                                                                        : '#F0F0F0'))));
                                            @endphp
                                            <div
                                                style="background-color: {{ $bgColor }}; border-radius: 4px; padding: 2px; font-size: 0.85rem;">
                                                {{ $gradePoint }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                All scores are on a 0-100 scale. Calculated fields update automatically.
                            </small>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save All Grades
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Grade Scale Reference -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-header">
                        <h6 class="mb-0">Grading Scale Reference</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Letter Grade Scale:</strong>
                                <ul class="list-unstyled small">
                                    <li><span
                                            style="background-color: #90EE90; padding: 2px 6px; border-radius: 3px;">A</span>
                                        = 90-100 (Excellent)</li>
                                    <li><span
                                            style="background-color: #87CEEB; padding: 2px 6px; border-radius: 3px;">B</span>
                                        = 80-89 (Very Good)</li>
                                    <li><span
                                            style="background-color: #FFD700; padding: 2px 6px; border-radius: 3px;">C</span>
                                        = 70-79 (Good)</li>
                                    <li><span
                                            style="background-color: #FFA07A; padding: 2px 6px; border-radius: 3px;">D</span>
                                        = 60-69 (Fair)</li>
                                    <li><span
                                            style="background-color: #FFB6C6; padding: 2px 6px; border-radius: 3px;">F</span>
                                        = 0-59 (Fail)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>KSA Weighting:</strong>
                                <ul class="list-unstyled small">
                                    <li><strong style="color: #667eea;">Knowledge:</strong> 40% (Quizzes 40% + Exams 60%)
                                    </li>
                                    <li><strong style="color: #f8a623;">Skills:</strong> 50% (Output 40% + C.Part 30% +
                                        Activ 15% + Assign 15%)</li>
                                    <li><strong style="color: #17a2b8;">Attitude:</strong> 10% (Behavior 50% + Awareness
                                        50%)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-sm td {
            vertical-align: middle;
        }

        .table-sm input[type="number"] {
            border: 1px solid #ddd;
        }

        .table-sm input[type="number"]:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-sm {
            height: 28px !important;
            padding: 0.25rem 0.5rem !important;
        }
    </style>
@endsection
