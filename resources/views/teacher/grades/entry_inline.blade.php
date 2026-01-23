@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <!-- Header with Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0">
                    <i class="fas fa-edit"></i> Grade Entry - {{ $class->class_name }}
                </h2>
                <small class="text-muted">{{ $class->subject->name ?? 'N/A' }} | {{ ucfirst($term) }} Term</small>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('teacher.assessment.configure', $class->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sliders-h"></i> Configure
                    </a>
                    <button type="button" class="btn btn-outline-info btn-sm" id="statsBtn">
                        <i class="fas fa-chart-bar"></i> Analytics
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" id="exportBtn">
                        <i class="fas fa-download"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats Bar -->
        <div class="row mb-3">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Total Students</h6>
                        <h4 class="mb-0">{{ $students->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Class Average</h6>
                        <h4 class="mb-0" id="classAvg">--</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Highest</h6>
                        <h4 class="mb-0" id="highest">--</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Lowest</h6>
                        <h4 class="mb-0" id="lowest">--</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Passed</h6>
                        <h4 class="mb-0" id="passed">--</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-1">Failed</h6>
                        <h4 class="mb-0" id="failed">--</h4>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.grades.store.enhanced', $class->id) }}" id="gradeForm">
            @csrf
            <input type="hidden" name="term" value="{{ $term }}">

            <!-- Grade Entry Table -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">
                                <i class="fas fa-table"></i> Student Grades (Inline Edit)
                            </h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <input type="text" class="form-control form-control-sm" id="searchStudent"
                                placeholder="Search student..." style="width: 200px; display: inline-block;">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                        <table class="table table-hover table-sm mb-0" id="gradeTable">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="width: 25%;">
                                        <i class="fas fa-user"></i> Student
                                    </th>
                                    @for ($q = 1; $q <= ($range->num_quizzes ?? 5); $q++)
                                        <th class="text-center" title="Quiz {{ $q }}">
                                            <small>Q{{ $q }}</small><br>
                                            <span
                                                class="badge bg-light text-dark">{{ (int) ($range->total_quiz_items / ($range->num_quizzes ?? 5)) }}</span>
                                        </th>
                                    @endfor
                                    <th class="text-center" title="Midterm Exam">
                                        <small>Exam</small><br>
                                        <span class="badge bg-light text-dark">{{ $range->midterm_exam_max ?? 60 }}</span>
                                    </th>
                                    <th class="text-center bg-light">
                                        <small><strong>K.Score</strong></small>
                                    </th>
                                    <th class="text-center">Output</th>
                                    <th class="text-center">CP</th>
                                    <th class="text-center">Act</th>
                                    <th class="text-center bg-light">
                                        <small><strong>S.Score</strong></small>
                                    </th>
                                    <th class="text-center">Behav</th>
                                    <th class="text-center">Aware</th>
                                    <th class="text-center bg-light">
                                        <small><strong>A.Score</strong></small>
                                    </th>
                                    <th class="text-center">Attend%</th>
                                    <th class="text-center bg-primary text-white">
                                        <small><strong>FINAL</strong></small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    @php
                                        $grade = $grades->get($student->id);
                                    @endphp
                                    <tr class="student-row"
                                        data-student="{{ strtolower($student->user->first_name . ' ' . $student->user->last_name) }}">
                                        <td class="sticky-col">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                    {{ substr($student->user->first_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <small><strong>{{ $student->user->first_name }}
                                                            {{ $student->user->last_name }}</strong></small><br>
                                                    <tiny class="text-muted">{{ $student->admission_no }}</tiny>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Dynamic Quizzes -->
                                        @php $numQuizzes = $range->num_quizzes ?? 5; @endphp
                                        @for ($q = 1; $q <= $numQuizzes; $q++)
                                            <td class="text-center editable-cell">
                                                <input type="number"
                                                    class="form-control form-control-sm text-center grade-input"
                                                    name="grades[{{ $index }}][q{{ $q }}]"
                                                    value="{{ old("grades.$index.q$q", $grade->{"q$q"} ?? '') }}"
                                                    data-quiz="{{ $q }}" min="0"
                                                    max="{{ (int) ($range->total_quiz_items / $numQuizzes) }}"
                                                    placeholder="0" step="0.5" title="Click to edit">
                                            </td>
                                        @endfor

                                        <!-- Exam -->
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][midterm_exam]"
                                                value="{{ old("grades.$index.midterm_exam", $grade->midterm_exam ?? '') }}"
                                                min="0" max="{{ $range->midterm_exam_max ?? 60 }}"
                                                placeholder="0" step="0.5">
                                        </td>

                                        <!-- Knowledge Score -->
                                        <td class="text-center bg-light">
                                            <span
                                                class="badge bg-info knowledge-score">{{ $grade->knowledge_score ?? '-' }}</span>
                                        </td>

                                        <!-- Skills -->
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][output_score]"
                                                value="{{ old("grades.$index.output_score", $grade->output_score ?? '') }}"
                                                min="0" max="{{ $range->output_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][class_participation_score]"
                                                value="{{ old("grades.$index.class_participation_score", $grade->class_participation_score ?? '') }}"
                                                min="0" max="{{ $range->class_participation_max ?? 100 }}"
                                                placeholder="0" step="0.5">
                                        </td>
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][activities_score]"
                                                value="{{ old("grades.$index.activities_score", $grade->activities_score ?? '') }}"
                                                min="0" max="{{ $range->activities_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>

                                        <!-- Skills Score -->
                                        <td class="text-center bg-light">
                                            <span
                                                class="badge bg-success skills-score">{{ $grade->skills_score ?? '-' }}</span>
                                        </td>

                                        <!-- Attitude -->
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][behavior_score]"
                                                value="{{ old("grades.$index.behavior_score", $grade->behavior_score ?? '') }}"
                                                min="0" max="{{ $range->behavior_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][awareness_score]"
                                                value="{{ old("grades.$index.awareness_score", $grade->awareness_score ?? '') }}"
                                                min="0" max="{{ $range->awareness_max ?? 100 }}" placeholder="0"
                                                step="0.5">
                                        </td>

                                        <!-- Attitude Score -->
                                        <td class="text-center bg-light">
                                            <span
                                                class="badge bg-warning attitude-score">{{ $grade->attitude_score ?? '-' }}</span>
                                        </td>

                                        <!-- Attendance -->
                                        <td class="text-center editable-cell">
                                            <input type="number"
                                                class="form-control form-control-sm text-center grade-input"
                                                name="grades[{{ $index }}][attendance_score]"
                                                value="{{ old("grades.$index.attendance_score", $grade->attendance_score ?? '') }}"
                                                min="0" max="100" placeholder="0" step="0.5">
                                        </td>

                                        <!-- Final Grade -->
                                        <td class="text-center bg-primary text-white">
                                            @if ($grade && $grade->final_grade)
                                                <strong>{{ number_format($grade->final_grade, 2) }}</strong><br>
                                                <span
                                                    class="badge bg-{{ \App\Models\Grade::getGradeColor($grade->final_grade) }}">
                                                    {{ $grade->grade_letter }}
                                                </span>
                                            @else
                                                <span class="text-white">-</span>
                                            @endif
                                        </td>

                                        <input type="hidden" name="grades[{{ $index }}][student_id]"
                                            value="{{ $student->id }}">
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="25" class="text-center py-4">
                                            <p class="text-muted mb-0"><i class="fas fa-inbox"></i> No students enrolled
                                                in this class</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex gap-2 justify-content-between">
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="undoBtn">
                            <i class="fas fa-undo"></i> Undo
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="historyBtn">
                            <i class="fas fa-history"></i> History
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Save All Grades
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .form-control-sm {
            font-size: 11px;
            padding: 0.25rem 0.5rem;
            height: auto;
            min-height: 24px;
        }

        .editable-cell {
            padding: 2px !important;
        }

        .table-sm td {
            padding: 4px 2px !important;
            font-size: 11px;
        }

        .table-sm th {
            padding: 4px 2px !important;
            font-size: 10px;
            font-weight: 600;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background: white;
            z-index: 9;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            font-weight: bold;
            border: 2px solid #fff;
        }

        .grade-input {
            border: 1px solid #dee2e6;
        }

        .grade-input:focus {
            background-color: #fffbea;
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.5rem;
        }

        small {
            display: block;
            font-size: 0.75rem;
        }

        tiny {
            font-size: 0.7rem;
        }

        @media (max-width: 1600px) {

            .table-sm th,
            .table-sm td {
                font-size: 10px;
                padding: 2px 1px !important;
            }

            .form-control-sm {
                font-size: 10px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gradeInputs = document.querySelectorAll('.grade-input');
            const statsBtn = document.getElementById('statsBtn');
            const exportBtn = document.getElementById('exportBtn');
            const searchInput = document.getElementById('searchStudent');
            const undoBtn = document.getElementById('undoBtn');
            const historyBtn = document.getElementById('historyBtn');
            let changeHistory = [];

            // Calculate and update stats
            function updateStats() {
                const grades = Array.from(document.querySelectorAll('td.text-center:last-child .badge.bg-primary'))
                    .map(b => {
                        const text = b.textContent.trim();
                        return parseFloat(text) || 0;
                    })
                    .filter(g => g > 0);

                if (grades.length === 0) {
                    document.getElementById('classAvg').textContent = '--';
                    document.getElementById('highest').textContent = '--';
                    document.getElementById('lowest').textContent = '--';
                    document.getElementById('passed').textContent = '--';
                    document.getElementById('failed').textContent = '--';
                    return;
                }

                const avg = (grades.reduce((a, b) => a + b, 0) / grades.length).toFixed(2);
                const highest = Math.max(...grades).toFixed(2);
                const lowest = Math.min(...grades).toFixed(2);
                const passed = grades.filter(g => g >= 3.0).length;
                const failed = grades.filter(g => g > 3.0).length;

                document.getElementById('classAvg').textContent = avg;
                document.getElementById('highest').textContent = highest;
                document.getElementById('lowest').textContent = lowest;
                document.getElementById('passed').textContent = passed;
                document.getElementById('failed').textContent = failed;
            }

            // Track changes
            gradeInputs.forEach(input => {
                const originalValue = input.value;
                input.addEventListener('change', function() {
                    changeHistory.push({
                        input: this,
                        oldValue: originalValue,
                        newValue: this.value,
                        timestamp: new Date()
                    });
                    updateStats();
                });
            });

            // Search students
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.student-row').forEach(row => {
                    const name = row.getAttribute('data-student');
                    row.style.display = name.includes(query) ? '' : 'none';
                });
            });

            // Undo
            undoBtn.addEventListener('click', function() {
                if (changeHistory.length > 0) {
                    const last = changeHistory.pop();
                    last.input.value = last.oldValue;
                    updateStats();
                }
            });

            // History
            historyBtn.addEventListener('click', function() {
                let msg = `Change History (${changeHistory.length} changes):\n\n`;
                changeHistory.forEach((change, i) => {
                    const time = change.timestamp.toLocaleTimeString();
                    msg += `${i+1}. ${time}: ${change.oldValue} → ${change.newValue}\n`;
                });
                alert(msg || 'No changes made yet');
            });

            // Export
            exportBtn.addEventListener('click', function() {
                alert('Excel export coming soon! This will export all grades to a formatted Excel file.');
            });

            // Stats
            statsBtn.addEventListener('click', function() {
                alert(
                    'Analytics dashboard coming soon! View grade distribution, trends, and performance metrics.');
            });

            // Initial stats calculation
            updateStats();
        });
    </script>
@endsection
