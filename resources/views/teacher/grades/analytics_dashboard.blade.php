@extends('layouts.teacher')

@section('content')
    <div class="container-fluid my-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-chart-line"></i> Grading Analytics Dashboard
                </h2>
                <p class="text-muted">{{ $class->class_name }} - {{ $class->course->course_name ?? 'N/A' }} | {{ ucfirst($term) }}
                    Term</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
                <button class="btn btn-success" id="exportPdf">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm" style="border-left: 4px solid #0066cc;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="text-muted mb-2">Class Average</p>
                                <h3 class="mb-0" style="color: #0066cc;">
                                    {{ number_format($analytics['avg_grade'] ?? 0, 2) }}</h3>
                                <small class="text-muted">out of 100.0</small>
                            </div>
                            <div class="ms-auto" style="font-size: 2rem; color: #0066cc;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm" style="border-left: 4px solid #00a86b;">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="text-muted mb-2">Passed (60+)</p>
                                <h3 class="mb-0" style="color: #00a86b;">
                                    {{ $analytics['passed_count'] ?? 0 }}/{{ $total_students }}
                                </h3>
                                <small class="text-muted">{{ number_format($analytics['pass_percentage'] ?? 0, 1) }}% pass
                                    rate</small>
                            </div>
                            <div class="ms-auto text-success" style="font-size: 2rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-left-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="text-muted mb-2">Failed (Below 60)</p>
                                <h3 class="mb-0 text-danger">{{ $analytics['failed_count'] ?? 0 }}/{{ $total_students }}
                                </h3>
                                <small class="text-muted">{{ number_format($analytics['fail_percentage'] ?? 0, 1) }}% fail
                                    rate</small>
                            </div>
                            <div class="ms-auto text-danger" style="font-size: 2rem;">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-left-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="text-muted mb-2">Score Range</p>
                                <h3 class="mb-0 text-warning">{{ number_format($analytics['highest_grade'] ?? 0, 1) }}</h3>
                                <small class="text-muted">Lowest:
                                    {{ number_format($analytics['lowest_grade'] ?? 0, 1) }}</small>
                            </div>
                            <div class="ms-auto text-warning" style="font-size: 2rem;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Grade Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="distributionChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Pass vs Fail</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="passfailChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Component Analysis -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Component Performance Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6><i class="fas fa-brain text-primary"></i> Knowledge (40%)</h6>
                                <div class="progress mb-2" style="height: 30px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $analytics['knowledge_avg'] ?? 0 }}%">
                                        {{ number_format($analytics['knowledge_avg'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Quizzes: {{ number_format($analytics['quiz_avg'] ?? 0, 1) }}/100 |
                                    Exams: {{ number_format($analytics['exam_avg'] ?? 0, 1) }}/100
                                </small>
                            </div>

                            <div class="col-md-4">
                                <h6><i class="fas fa-toolbox text-success"></i> Skills (50%)</h6>
                                <div class="progress mb-2" style="height: 30px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $analytics['skills_avg'] ?? 0 }}%">
                                        {{ number_format($analytics['skills_avg'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Output: {{ number_format($analytics['output_avg'] ?? 0, 1) }} |
                                    CP: {{ number_format($analytics['cp_avg'] ?? 0, 1) }}
                                </small>
                            </div>

                            <div class="col-md-4">
                                <h6><i class="fas fa-heart text-warning"></i> Attitude (10%)</h6>
                                <div class="progress mb-2" style="height: 30px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $analytics['attitude_avg'] ?? 0 }}%">
                                        {{ number_format($analytics['attitude_avg'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Behavior: {{ number_format($analytics['behavior_avg'] ?? 0, 1) }} |
                                    Awareness: {{ number_format($analytics['awareness_avg'] ?? 0, 1) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Breakdown Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="color: #1a1a1a;"><i class="fas fa-users me-2"></i>Student Grade
                            Breakdown</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th class="text-center">Knowledge</th>
                                        <th class="text-center">Skills</th>
                                        <th class="text-center">Attitude</th>
                                        <th class="text-center">Attendance</th>
                                        <th class="text-center">Final Grade</th>
                                        <th class="text-center">Letter</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($grades as $i => $grade)
                                        @php
                                            $status = $grade->final_grade <= 3.0 ? 'Passed' : 'Failed';
                                            $statusBadge = $grade->final_grade <= 3.0 ? 'bg-success' : 'bg-danger';
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>
                                                <strong>{{ $grade->student->user->first_name }}
                                                    {{ $grade->student->user->last_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $grade->student->admission_no }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-primary">{{ number_format($grade->knowledge_score, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-success">{{ number_format($grade->skills_score, 2) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-warning">{{ number_format($grade->attitude_score, 2) }}</span>
                                            </td>
                                            <td class="text-center">{{ $grade->attendance_score ?? '-' }}%</td>
                                            <td class="text-center">
                                                <strong
                                                    class="text-primary">{{ number_format($grade->final_grade, 2) }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-info">{{ \App\Models\Grade::getGradePoint($grade->final_grade) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $statusBadge }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-inbox text-muted"></i> No grades recorded yet
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 4px solid #667eea;
        }

        .border-left-success {
            border-left: 4px solid #17c88e;
        }

        .border-left-danger {
            border-left: 4px solid #dc3545;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107;
        }

        .progress {
            background-color: #e9ecef;
        }

        @media print {

            .btn,
            .navbar {
                display: none;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grade Distribution Chart
            const distCtx = document.getElementById('distributionChart').getContext('2d');
            const gradeBuckets = {
                '1.0-1.5': 0,
                '1.5-2.0': 0,
                '2.0-2.5': 0,
                '2.5-3.0': 0,
                '3.0+': 0
            };

            // Calculate distribution
            @foreach ($grades as $grade)
                @php
                    $finalGrade = $grade->final_grade;
                    if ($finalGrade <= 1.5) {
                        $bucket = '1.0-1.5';
                    } elseif ($finalGrade <= 2.0) {
                        $bucket = '1.5-2.0';
                    } elseif ($finalGrade <= 2.5) {
                        $bucket = '2.0-2.5';
                    } elseif ($finalGrade <= 3.0) {
                        $bucket = '2.5-3.0';
                    } else {
                        $bucket = '3.0+';
                    }
                @endphp
                gradeBuckets['{{ $bucket }}']++;
            @endforeach

            new Chart(distCtx, {
                type: 'bar',
                data: {
                    labels: ['1.0-1.5 (Excellent)', '1.5-2.0 (Very Good)', '2.0-2.5 (Good)',
                        '2.5-3.0 (Passing)',
                        '3.0+ (Failed)'
                    ],
                    datasets: [{
                        label: 'Number of Students',
                        data: [
                            gradeBuckets['1.0-1.5'],
                            gradeBuckets['1.5-2.0'],
                            gradeBuckets['2.0-2.5'],
                            gradeBuckets['2.5-3.0'],
                            gradeBuckets['3.0+']
                        ],
                        backgroundColor: [
                            '#28a745',
                            '#20c997',
                            '#17a2b8',
                            '#ffc107',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Pass vs Fail Chart
            const pfCtx = document.getElementById('passfailChart').getContext('2d');
            const passCount = {{ $analytics['passed_count'] ?? 0 }};
            const failCount = {{ $analytics['failed_count'] ?? 0 }};

            new Chart(pfCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [passCount, failCount],
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderColor: ['#fff', '#fff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Export PDF
            document.getElementById('exportPdf').addEventListener('click', function() {
                alert('PDF export coming soon!');
            });
        });
    </script>
@endsection
