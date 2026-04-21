@extends('layouts.student')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">My Attendance</h1>
                <p class="text-muted mb-0">View your attendance records and history</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-primary fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h6 class="text-muted mt-2">Total Classes</h6>
                        <h3>{{ $total }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-success fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-check"></i>
                        </div>
                        <h6 class="text-muted mt-2">Present</h6>
                        <h3>{{ $present }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-danger fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-times"></i>
                        </div>
                        <h6 class="text-muted mt-2">Absent</h6>
                        <h3>{{ $absent }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <div class="text-warning fw-bold" style="font-size: 1.8rem;">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h6 class="text-muted mt-2">Attendance %</h6>
                        <h3>{{ number_format($percentage, 1) }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="card border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                    Attendance Records
                </h6>
            </div>
            <div class="card-body p-0">
                @if ($records->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Class</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>E-Signature</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td>
                                            <strong>{{ $record->date->format('M d, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $record->date->format('l') }}</small>
                                        </td>
                                        <td>{{ $record->class->name ?? 'N/A' }}</td>
                                        <td>{{ $record->teacher->first_name ?? 'N/A' }}
                                            {{ $record->teacher->last_name ?? '' }}</td>
                                        <td>
                                            @if ($record->status === 'present')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i> Present
                                                </span>
                                            @elseif($record->status === 'absent')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i> Absent
                                                </span>
                                            @elseif($record->status === 'late')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i> Late
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($record->status) }}</span>
                                            @endif
                                        </td>
                                        <td @if ($record->status === 'late') style="background-color: #fff3cd;" @endif>
                                            @if ($record->status === 'absent')
                                                <span class="text-muted small fw-bold">NONE</span>
                                            @elseif ($record->e_signature)
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#signatureModal{{ $record->id }}">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </button>

                                                <!-- Signature Modal -->
                                                <div class="modal fade" id="signatureModal{{ $record->id }}"
                                                    tabindex="-1">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content border-0">
                                                            <div class="modal-header bg-light">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-pen-fancy me-2"></i> E-Signature
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-center p-4">
                                                                <img src="{{ $record->e_signature }}" alt="E-Signature"
                                                                    style="max-width: 100%; border-radius: 0.25rem; border: 1px solid #dee2e6;">
                                                                <p class="text-muted small mt-2 mb-0">
                                                                    Taken on {{ $record->date->format('M d, Y H:i') }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                        <p class="text-muted mt-2 mb-0">No attendance records found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Attendance by Class -->
        <div class="card border-0">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-info"></i>
                    Attendance by Class
                </h6>
            </div>
            <div class="card-body">
                @if ($attendanceByClass && $attendanceByClass->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Class</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Total</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceByClass as $class)
                                    <tr>
                                        <td><strong>{{ $class->name ?? 'N/A' }}</strong></td>
                                        <td><span class="badge bg-success">{{ $class->present_count ?? 0 }}</span></td>
                                        <td><span class="badge bg-danger">{{ $class->absent_count ?? 0 }}</span></td>
                                        <td>{{ ($class->present_count ?? 0) + ($class->absent_count ?? 0) }}</td>
                                        <td>
                                            @php
                                                $total = ($class->present_count ?? 0) + ($class->absent_count ?? 0);
                                                $percent =
                                                    $total > 0 ? (($class->present_count ?? 0) / $total) * 100 : 0;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percent }}%">
                                                    {{ number_format($percent, 0) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No attendance data by class available</p>
                @endif
            </div>
        </div>
    </div>
@endsection
