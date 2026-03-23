@extends('layouts.teacher')

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">Attendance History - {{ $class->name }}</h1>
                <small class="text-muted">Search and revisit previous attendance records</small>
            </div>
            <div>
                <a href="{{ route('teacher.attendance.manage', $class->id) }}" class="btn btn-outline-secondary me-2">Manage
                    Today's Attendance</a>
                <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.attendance.history', $class->id) }}"
                class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ $start ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ $end ?? '' }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Student</label>
                    <x-searchable-dropdown
                        name="student_id"
                        id="student_id"
                        placeholder="All Students"
                        api-url="{{ route('api.students') }}"
                        :selected="$studentId ?? ''"
                        :clearable="true"
                        class="form-select"
                    />
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->student->name ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $att->status === 'Present' ? 'success' : ($att->status === 'Absent' ? 'danger' : ($att->status === 'Late' ? 'warning' : 'secondary')) }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td>{{ $class->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
