@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">My Grades</h1>
                <p class="text-muted mb-0">View your academic performance and grades</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Grade Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-success fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h6 class="text-muted mt-2">Average Grade</h6>
                        <h3>{{ number_format($averageGrade, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-primary fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-book"></i>
                        </div>
                        <h6 class="text-muted mt-2">Total Grades</h6>
                        <h3>{{ $totalGrades }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-success fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h6 class="text-muted mt-2">Passing</h6>
                        <h3>{{ $passing }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-danger fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h6 class="text-muted mt-2">Below Average</h6>
                        <h3>{{ $belowAverage }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="mb-0">
                            <i class="fas fa-file-alt me-2 text-primary"></i>
                            Grade Records
                        </h6>
                    </div>
                    <div class="col-auto">
                        <select class="form-select form-select-sm" id="classFilter" style="max-width: 200px;">
                            <option value="">All Classes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($records->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="gradesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Weight</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr class="grade-row" data-class="{{ $record->class_id ?? '' }}">
                                        <td>
                                            <strong>{{ $record->created_at->format('M d, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $record->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>{{ $record->class->name ?? 'N/A' }}</td>
                                        <td>{{ $record->subject ?? 'General' }}</td>
                                        <td>
                                            <strong
                                                style="font-size: 1.1rem;">{{ number_format($record->grade, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if ($record->weight)
                                                <span class="text-muted">{{ $record->weight }}%</span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->grade >= 90)
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($record->grade >= 80)
                                                <span class="badge bg-info">Very Good</span>
                                            @elseif($record->grade >= 75)
                                                <span class="badge bg-primary">Good</span>
                                            @elseif($record->grade >= 60)
                                                <span class="badge bg-warning">Satisfactory</span>
                                            @else
                                                <span class="badge bg-danger">Below Average</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->remarks)
                                                <small class="text-muted">{{ $record->remarks }}</small>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($records->hasPages())
                        <div class="card-footer bg-light">
                            {{ $records->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-4 text-center">
                        <i class="fas fa-inbox" style="font-size: 2rem; color: #cbd5e1;"></i>
                        <p class="text-muted mt-2 mb-0">No grades recorded yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grades by Class -->
        @if ($gradesByClass && $gradesByClass->count() > 0)
            <div class="card border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Average Grade by Class
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Class</th>
                                    <th>Average</th>
                                    <th>Total Grades</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gradesByClass as $class)
                                    <tr>
                                        <td><strong>{{ $class->name ?? 'N/A' }}</strong></td>
                                        <td>
                                            <strong>{{ number_format($class->avg_grade ?? 0, 2) }}</strong>
                                        </td>
                                        <td>{{ $class->grade_count ?? 0 }}</td>
                                        <td>
                                            @php
                                                $avg = $class->avg_grade ?? 0;
                                            @endphp
                                            @if ($avg >= 90)
                                                <span class="badge bg-success">Excellent</span>
                                            @elseif($avg >= 80)
                                                <span class="badge bg-info">Very Good</span>
                                            @elseif($avg >= 75)
                                                <span class="badge bg-primary">Good</span>
                                            @elseif($avg >= 60)
                                                <span class="badge bg-warning">Satisfactory</span>
                                            @else
                                                <span class="badge bg-danger">Below Average</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

@section('scripts')
    <script>
        // Class filter functionality
        document.getElementById('classFilter').addEventListener('change', function() {
            const selectedClass = this.value;
            const rows = document.querySelectorAll('.grade-row');

            rows.forEach(row => {
                if (selectedClass === '' || row.dataset.class === selectedClass) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
@endsection
