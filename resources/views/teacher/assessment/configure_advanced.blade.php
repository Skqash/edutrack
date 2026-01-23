@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-sliders-h"></i> Advanced Grading Configuration
                </h2>
                <p class="text-muted">Customize quiz items, exams, weightings, and grading scales for
                    {{ $class->class_name }}</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success" id="previewBtn">
                    <i class="fas fa-eye"></i> Grade Preview
                </button>
                <button class="btn btn-info" id="copyBtn">
                    <i class="fas fa-copy"></i> Copy to Classes
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.assessment.store.advanced', $class->id) }}" id="configForm">
            @csrf

            <div class="row">
                <!-- Left: Configuration -->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-dice-d6"></i> Quiz Configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Total Quiz Items</label>
                                    <input type="number" class="form-control" name="total_quiz_items"
                                        value="{{ old('total_quiz_items', $range->total_quiz_items ?? 100) }}"
                                        min="10" max="500" id="totalQuizItems">
                                    <small class="text-muted">Pool of items to distribute</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Number of Quizzes</label>
                                    <input type="number" class="form-control" name="num_quizzes"
                                        value="{{ old('num_quizzes', $range->num_quizzes ?? 5) }}" min="1"
                                        max="10" id="numQuizzes">
                                    <small class="text-muted">Q1, Q2, Q3... Qn</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Per-Quiz Maximum</label>
                                    <input type="text" class="form-control bg-light" id="perQuizMax" readonly>
                                    <small class="text-muted">Auto-calculated</small>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="equal_quiz_distribution"
                                    id="equalDist" value="1"
                                    {{ old('equal_quiz_distribution', $range->equal_quiz_distribution ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="equalDist">
                                    <strong>Distribute Equally</strong> - All quizzes have equal maximum score
                                </label>
                            </div>

                            <!-- Quiz Weights -->
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="20%">Quiz</th>
                                            <th width="20%">Maximum Items</th>
                                            <th width="20%">% of Quizzes</th>
                                            <th width="20%">% of Knowledge</th>
                                            <th width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="quizTableBody">
                                        <!-- Generated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Exam Configuration -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Exam Configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Prelim Exam (if midterm term)</strong></td>
                                        <td width="30%">
                                            <input type="number" class="form-control form-control-sm"
                                                name="prelim_exam_max"
                                                value="{{ old('prelim_exam_max', $range->prelim_exam_max ?? 60) }}"
                                                min="10" max="200" id="prelimMax">
                                        </td>
                                        <td width="30%">
                                            <span class="badge bg-secondary">30% of Knowledge</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Midterm Exam</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm"
                                                name="midterm_exam_max"
                                                value="{{ old('midterm_exam_max', $range->midterm_exam_max ?? 60) }}"
                                                min="10" max="200" id="midtermMax">
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">30% of Knowledge</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Final Exam</strong></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm"
                                                name="final_exam_max"
                                                value="{{ old('final_exam_max', $range->final_exam_max ?? 60) }}"
                                                min="10" max="200" id="finalMax">
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">40% of Knowledge</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Skills & Attitude -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-toolbox"></i> Skills (50%)</h5>
                                </div>
                                <div class="card-body">
                                    <small class="form-text text-muted d-block mb-2">40% Quiz/Output, 60% Exams</small>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Output:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="output_max"
                                                    value="{{ old('output_max', $range->output_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                        <tr>
                                            <td>Class Participation:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="class_participation_max"
                                                    value="{{ old('class_participation_max', $range->class_participation_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                        <tr>
                                            <td>Activities:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="activities_max"
                                                    value="{{ old('activities_max', $range->activities_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                        <tr>
                                            <td>Assignments:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="assignments_max"
                                                    value="{{ old('assignments_max', $range->assignments_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-heart"></i> Attitude (10%)</h5>
                                </div>
                                <div class="card-body">
                                    <small class="form-text text-muted d-block mb-2">50% Behavior, 50% Awareness</small>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Behavior:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="behavior_max"
                                                    value="{{ old('behavior_max', $range->behavior_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                        <tr>
                                            <td>Awareness:</td>
                                            <td><input type="number" class="form-control form-control-sm"
                                                    name="awareness_max"
                                                    value="{{ old('awareness_max', $range->awareness_max ?? 100) }}"
                                                    min="10" max="200"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Preview & Summary -->
                <div class="col-md-4">
                    <!-- Grade Distribution Preview -->
                    <div class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="mb-0 text-white"><i class="fas fa-chart-pie"></i> Grade Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="distributionChart" height="200"></canvas>

                            <hr>

                            <div class="small">
                                <div class="mb-2">
                                    <strong>Knowledge (40%)</strong><br>
                                    <span class="badge bg-primary">Quizzes: <span id="quizPct">20%</span></span>
                                    <span class="badge bg-info">Exams: <span id="examPct">20%</span></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Skills (50%)</strong>
                                    <br><span class="text-muted small">Output, CP, Activities, Assignments</span>
                                </div>
                                <div>
                                    <strong>Attitude (10%)</strong>
                                    <br><span class="text-muted small">Behavior & Awareness</span>
                                </div>
                            </div>

                            <hr>

                            <div class="alert alert-info small">
                                <strong>Test Grade:</strong> Enter test score to see how it calculates
                                <input type="number" class="form-control form-control-sm mt-2" id="testScore"
                                    placeholder="Score (0-100)" min="0" max="100">
                                <div id="testResult" class="mt-2 text-center">
                                    <span class="badge bg-secondary">Test: --</span>
                                    <span class="badge bg-secondary">Grade: --</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex gap-2 justify-content-between">
                        <div>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="restoreDefaults">
                                <i class="fas fa-refresh"></i> Restore Defaults
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('teacher.grades') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save Configuration
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .sticky-top {
            margin-top: 0 !important;
        }

        .card {
            border: none;
        }

        .card-header {
            border-bottom: 2px solid rgba(0, 0, 0, 0.125);
        }

        .table-sm td,
        .table-sm th {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        input.form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalInput = document.getElementById('totalQuizItems');
            const numInput = document.getElementById('numQuizzes');
            const perQuizDisplay = document.getElementById('perQuizMax');
            const quizTableBody = document.getElementById('quizTableBody');
            const testScore = document.getElementById('testScore');
            const testResult = document.getElementById('testResult');
            let chart = null;

            function updateQuizTable() {
                const total = parseInt(totalInput.value) || 100;
                const num = parseInt(numInput.value) || 5;
                const perQuiz = Math.floor(total / num);
                const pctPerQuiz = (100 / num).toFixed(2);
                const knowledgeContribution = (40 / num).toFixed(2);

                perQuizDisplay.value = perQuiz + ' items';

                // Generate quiz rows
                let html = '';
                for (let i = 1; i <= num; i++) {
                    html += `
                <tr>
                    <td><strong>Quiz ${i}</strong></td>
                    <td><input type="hidden" name="quiz_${i}_max" value="${perQuiz}">${perQuiz} items</td>
                    <td><span class="badge bg-primary">${pctPerQuiz}%</span></td>
                    <td><span class="badge bg-info">${knowledgeContribution}%</span></td>
                    <td><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuiz(${i})">Remove</button></td>
                </tr>
            `;
                }
                quizTableBody.innerHTML = html;

                updateChart();
                updateTestResult();
            }

            function updateChart() {
                const num = parseInt(numInput.value) || 5;
                const ctx = document.getElementById('distributionChart').getContext('2d');

                if (chart) chart.destroy();

                chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Knowledge', 'Skills', 'Attitude'],
                        datasets: [{
                            data: [40, 50, 10],
                            backgroundColor: ['#667eea', '#17c88e', '#ffc107'],
                            borderColor: ['#fff', '#fff', '#fff'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            function updateTestResult() {
                const score = parseInt(testScore.value) || 0;
                if (score === 0) {
                    testResult.innerHTML =
                        '<span class="badge bg-secondary">Test: --</span><span class="badge bg-secondary">Grade: --</span>';
                    return;
                }

                // Assume test score is out of 100 as final grade
                const grade = score.toFixed(2);
                let letterGrade = 'F';
                if (score >= 90) letterGrade = '1.0';
                else if (score >= 85) letterGrade = '1.25';
                else if (score >= 80) letterGrade = '1.5';
                else if (score >= 75) letterGrade = '1.75';
                else if (score >= 70) letterGrade = '2.0';
                else if (score >= 65) letterGrade = '2.25';
                else if (score >= 60) letterGrade = '2.5';
                else if (score >= 55) letterGrade = '2.75';
                else if (score >= 50) letterGrade = '3.0';

                testResult.innerHTML = `
            <span class="badge bg-success">Test: ${grade}</span>
            <span class="badge bg-primary">Grade: ${letterGrade}</span>
        `;
            }

            // Event listeners
            totalInput.addEventListener('change', updateQuizTable);
            numInput.addEventListener('change', updateQuizTable);
            testScore.addEventListener('input', updateTestResult);

            document.getElementById('restoreDefaults').addEventListener('click', function() {
                if (confirm('Restore to default configuration?')) {
                    totalInput.value = 100;
                    numInput.value = 5;
                    updateQuizTable();
                }
            });

            document.getElementById('previewBtn').addEventListener('click', function() {
                alert('Grade preview coming soon! This will show sample grades with your configuration.');
            });

            document.getElementById('copyBtn').addEventListener('click', function() {
                alert('Copy to other classes feature coming soon!');
            });

            // Initial load
            updateQuizTable();
        });

        function removeQuiz(num) {
            alert('Cannot remove individual quizzes. Use "Number of Quizzes" field instead.');
        }
    </script>
@endsection
