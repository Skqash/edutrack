@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-edit"></i> Grade Entry - {{ $class->class_name }}
                </h2>
                <p class="text-muted">Subject: {{ $class->subject->name ?? 'N/A' }} | Term:
                    <strong>{{ ucfirst($term) }}</strong></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('teacher.assessment.configure', $class->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-sliders-h"></i> Configure Ranges
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.grades.store.enhanced', $class->id) }}" id="gradeForm">
            @csrf

            <input type="hidden" name="term" value="{{ $term }}">

            <!-- Assessment Range Info Card -->
            @if ($range)
                <div class="alert alert-info">
                    <h6><i class="fas fa-cog"></i> Active Assessment Ranges</h6>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <small><strong>Quizzes:</strong>
                                @if ($range->equal_quiz_distribution && $range->total_quiz_items)
                                    {{ $range->num_quizzes }} quizzes ×
                                    {{ (int) ($range->total_quiz_items / $range->num_quizzes) }} items
                                    ({{ $range->total_quiz_items }} total)
                                @else
                                    Q1-{{ $range->quiz_1_max }}, Q2-{{ $range->quiz_2_max }},
                                    Q3-{{ $range->quiz_3_max }}, Q4-{{ $range->quiz_4_max }}, Q5-{{ $range->quiz_5_max }}
                                @endif
                            </small>
                        </div>
                        <div class="col-md-3">
                            <small><strong>Attitude Max:</strong> Behavior-{{ $range->behavior_max }},
                                Awareness-{{ $range->awareness_max }}</small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Students Grade Entry Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-list"></i> Student Grades ({{ $students->count() }} students)
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">Student Name</th>
                                    <th colspan="{{ ($range->num_quizzes ?? 5) + 2 }}" class="text-center"><small>Knowledge
                                            (40%)</small></th>
                                    <th colspan="5" class="text-center"><small>Skills (50%)</small></th>
                                    <th colspan="3" class="text-center"><small>Attitude (10%)</small></th>
                                    <th colspan="2" class="text-center"><small>Attendance</small></th>
                                    <th class="text-center"><small>Final</small></th>
                                </tr>
                                <tr>
                                    <th>Student</th>
                                    <!-- Knowledge - Dynamic Quizzes -->
                                    @for ($q = 1; $q <= ($range->num_quizzes ?? 5); $q++)
                                        <th class="text-center"><small>Q{{ $q }}</small></th>
                                    @endfor
                                    <th class="text-center"><small>Exam(M)</small></th>
                                    <th class="text-center" style="background-color: #e8f4f8;"><small>K.Score</small></th>
                                    <!-- Skills -->
                                    <th class="text-center"><small>Output</small></th>
                                    <th class="text-center"><small>CP</small></th>
                                    <th class="text-center"><small>Activ</small></th>
                                    <th class="text-center"><small>Assign</small></th>
                                    <th class="text-center" style="background-color: #e8f8f4;"><small>S.Score</small></th>
                                    <!-- Attitude -->
                                    <th class="text-center"><small>Behav</small></th>
                                    <th class="text-center"><small>Aware</small></th>
                                    <th class="text-center" style="background-color: #f8f8e8;"><small>A.Score</small></th>
                                    <!-- Attendance -->
                                    <th class="text-center"><small>Attend%</small></th>
                                    <th class="text-center"><small>Remarks</small></th>
                                    <!-- Final -->
                                    <th class="text-center" style="background-color: #f0e8f8;">
                                        <small><strong>Grade</strong></small></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    @php
                                        $grade = $grades->get($student->id);
                                        $attend = $attendance->get($student->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                    {{ substr($student->user->first_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <small><strong>{{ $student->user->first_name }}
                                                            {{ $student->user->last_name }}</strong></small><br>
                                                    <small class="text-muted">{{ $student->admission_no }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Knowledge Inputs - Dynamic Quizzes -->
                                        @php
                                            $numQuizzes = $range->num_quizzes ?? 5;
                                            $quizMaxScores = $range->getQuizMaxScores();
                                        @endphp
                                        @for ($q = 1; $q <= $numQuizzes; $q++)
                                            <td class="text-center">
                                                <input type="number"
                                                    name="grades[{{ $index }}][q{{ $q }}]"
                                                    class="form-control form-control-sm text-center"
                                                    value="{{ old("grades.$index.q$q", $grade->{"q$q"} ?? '') }}"
                                                    min="0" max="{{ $quizMaxScores['q' . $q] ?? 20 }}"
                                                    placeholder="0" step="0.5"
                                                    title="Max: {{ $quizMaxScores['q' . $q] ?? 20 }}">
                                            </td>
                                        @endfor
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][midterm_exam]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.midterm_exam", $grade->midterm_exam ?? '') }}"
                                                min="0" max="{{ $range->midterm_exam_max ?? 60 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center" style="background-color: #e8f4f8;">
                                            <span class="badge bg-info">{{ $grade->knowledge_score ?? '-' }}</span>
                                        </td>

                                        <!-- Skills Inputs -->
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][output_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.output_score", $grade->output_score ?? '') }}"
                                                min="0" max="{{ $range->output_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center">
                                            <input type="number"
                                                name="grades[{{ $index }}][class_participation_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.class_participation_score", $grade->class_participation_score ?? '') }}"
                                                min="0" max="{{ $range->class_participation_max ?? 100 }}"
                                                placeholder="0" step="0.5">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][activities_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.activities_score", $grade->activities_score ?? '') }}"
                                                min="0" max="{{ $range->activities_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][assignments_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.assignments_score", $grade->assignments_score ?? '') }}"
                                                min="0" max="{{ $range->assignments_max ?? 100 }}"
                                                placeholder="0" step="0.5">
                                        </td>
                                        <td class="text-center" style="background-color: #e8f8f4;">
                                            <span class="badge bg-success">{{ $grade->skills_score ?? '-' }}</span>
                                        </td>

                                        <!-- Attitude Inputs -->
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][behavior_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.behavior_score", $grade->behavior_score ?? '') }}"
                                                min="0" max="{{ $range->behavior_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][awareness_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.awareness_score", $grade->awareness_score ?? '') }}"
                                                min="0" max="{{ $range->awareness_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center" style="background-color: #f8f8e8;">
                                            <span class="badge bg-warning">{{ $grade->attitude_score ?? '-' }}</span>
                                        </td>

                                        <!-- Attendance -->
                                        <td class="text-center">
                                            <input type="number" name="grades[{{ $index }}][attendance_score]"
                                                class="form-control form-control-sm text-center"
                                                value="{{ old("grades.$index.attendance_score", $attend->attendance_score ?? ($grade->attendance_score ?? '')) }}"
                                                min="0" max="100" placeholder="0" step="0.5">
                                        </td>
                                        <td class="text-center">
                                            <textarea name="grades[{{ $index }}][remarks]" class="form-control form-control-sm" placeholder="Remarks"
                                                rows="1" style="font-size: 11px;">{{ old("grades.$index.remarks", $grade->remarks ?? '') }}</textarea>
                                        </td>

                                        <!-- Final Grade Display -->
                                        <td class="text-center" style="background-color: #f0e8f8;">
                                            @if ($grade && $grade->final_grade)
                                                <div>
                                                    <strong
                                                        class="text-primary">{{ number_format($grade->final_grade, 2) }}</strong><br>
                                                    <span
                                                        class="badge bg-{{ \App\Models\Grade::getGradeColor($grade->final_grade) }}">{{ \App\Models\Grade::getGradePoint($grade->final_grade) }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <input type="hidden" name="grades[{{ $index }}][student_id]"
                                            value="{{ $student->id }}">
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="text-center py-4">
                                            <p class="text-muted mb-0"><i class="fas fa-inbox"></i> No students enrolled
                                                in this class</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save All Grades
                        </button>
                        <a href="{{ route('teacher.grades') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Back to Grades
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .form-control-sm {
            font-size: 12px;
            padding: 0.25rem 0.5rem;
            height: auto;
        }

        .table-responsive {
            font-size: 12px;
        }

        .table td {
            vertical-align: middle;
            padding: 8px 4px !important;
        }

        .table th {
            font-weight: 600;
            padding: 8px 4px !important;
        }

        @media (max-width: 1200px) {
            .table {
                font-size: 11px;
            }

            .form-control-sm {
                font-size: 10px;
            }
        }
    </style>
@endsection
