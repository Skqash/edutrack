@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">{{ $class->name }} - Attendance</h1>
                <small class="text-muted">Manage attendance for: {{ $class->subject->name ?? 'No Subject' }}</small>
            </div>
            <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>

    @if ($students->isEmpty())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> No students enrolled in this class.
        </div>
    @else
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Attendance Record - {{ $today }}</h5>
                    </div>
                    <div class="col-auto">
                        <input type="date" id="attendanceDate" class="form-control" value="{{ $today }}"
                            max="{{ $today }}">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.attendance.record', $class->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" id="dateInput" value="{{ $today }}">

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40%">Student Name</th>
                                    <th width="30%">Roll Number</th>
                                    <th width="30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->user->name ?? 'N/A' }}</td>
                                        <td>{{ $student->roll_number ?? '-' }}</td>
                                        <td>
                                            <select name="attendance[{{ $student->id }}][status]"
                                                class="form-select form-select-sm" required>
                                                <option value="">-- Select --</option>
                                                <option value="Present"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Present' ? 'selected' : '' }}>
                                                    <i class="fas fa-check text-success"></i> Present
                                                </option>
                                                <option value="Absent"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Absent' ? 'selected' : '' }}>
                                                    <i class="fas fa-times text-danger"></i> Absent
                                                </option>
                                                <option value="Late"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Late' ? 'selected' : '' }}>
                                                    <i class="fas fa-clock text-warning"></i> Late
                                                </option>
                                                <option value="Leave"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Leave' ? 'selected' : '' }}>
                                                    <i class="fas fa-ban text-info"></i> Leave
                                                </option>
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-5">
                                            No students found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($students->isNotEmpty())
                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('teacher.attendance') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Attendance
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif

    <script>
        document.getElementById('attendanceDate').addEventListener('change', function() {
            document.getElementById('dateInput').value = this.value;
        });
    </script>

@endsection
