@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">Welcome, {{ $student->full_name }}!</h1>
                <p class="text-muted mb-0">Student ID: {{ $student->student_id }} • {{ $student->department ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                    <a href="{{ route('student.signature.form') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-pen me-1"></i> E-Signature
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary fw-bold" style="font-size: 2rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h6 class="text-muted mt-2 mb-0">Attendance</h6>
                        <h2 class="mb-0">{{ $attendancePercentage }}%</h2>
                        <small class="text-success">{{ $attendance->count() }} sessions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-success fw-bold" style="font-size: 2rem;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h6 class="text-muted mt-2 mb-0">Average Grade</h6>
                        <h2 class="mb-0">{{ number_format($averageGrade, 2) }}</h2>
                        <small class="text-success">Academic performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-info fw-bold" style="font-size: 2rem;">
                            <i class="fas fa-book"></i>
                        </div>
                        <h6 class="text-muted mt-2 mb-0">Subjects</h6>
                        <h2 class="mb-0">{{ $grades->pluck('subject_id')->unique()->count() }}</h2>
                        <small class="text-info">Active courses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        @if ($student && $student->hasESignature())
                            <div style="margin-bottom: 1rem;">
                                <img src="{{ $student->e_signature }}" alt="E-Signature"
                                    style="max-height: 80px; max-width: 100%; border: 1px solid #dee2e6; border-radius: 0.25rem; padding: 0.25rem;">
                            </div>
                            <h6 class="text-muted mb-1">E-Signature</h6>
                            <span class="badge bg-success" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">✓ Set</span>
                            <a href="{{ route('student.signature.form') }}"
                                class="btn btn-sm btn-link text-decoration-none mt-2 d-block" style="font-size: 0.75rem;">
                                Update
                            </a>
                        @else
                            <div class="text-warning fw-bold" style="font-size: 2rem;">
                                <i class="fas fa-pen"></i>
                            </div>
                            <h6 class="text-muted mt-2 mb-0">E-Signature</h6>
                            <a href="{{ route('student.signature.form') }}" class="badge bg-warning text-dark"
                                style="text-decoration: none; font-size: 0.85rem; padding: 0.4rem 0.8rem;">Setup</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Info Bar -->
        @if ($student)
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">Academic Year</small>
                                    <strong>{{ $student->academic_year ?? 'Not Set' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    <span class="badge"
                                        style="background-color: {{ $student->status === 'active' ? '#28a745' : '#dc3545' }};">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-1">Campus</small>
                                    <strong>{{ $student->campus ?? 'Not Assigned' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="row">
            <!-- Recent Attendance -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-check me-2 text-primary"></i>
                            Recent Attendance
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($attendance->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Class</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendance as $record)
                                            <tr>
                                                <td>{{ $record->date->format('M d, Y') }}</td>
                                                <td>{{ $record->class->name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($record->status === 'present')
                                                        <span class="badge bg-success">Present</span>
                                                    @elseif($record->status === 'absent')
                                                        <span class="badge bg-danger">Absent</span>
                                                    @else
                                                        <span
                                                            class="badge bg-warning">{{ ucfirst($record->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">
                                                    No attendance records yet
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light">
                                <a href="{{ route('student.attendance') }}" class="btn btn-sm btn-outline-primary">
                                    View All <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">No attendance records yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-book me-2 text-success"></i>
                            Recent Grades
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($grades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Class</th>
                                            <th>Grade</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grades->take(10) as $grade)
                                            <tr>
                                                <td>{{ $grade->class->name ?? 'N/A' }}</td>
                                                <td><strong>{{ number_format($grade->grade, 2) }}</strong></td>
                                                <td>
                                                    @if ($grade->grade >= 75)
                                                        <span class="badge bg-success">Passing</span>
                                                    @else
                                                        <span class="badge bg-danger">Below Avg</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-3 text-muted">
                                                    No grades recorded yet
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-light">
                                <a href="{{ route('student.grades') }}" class="btn btn-sm btn-outline-primary">
                                    View All <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">No grades recorded yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
