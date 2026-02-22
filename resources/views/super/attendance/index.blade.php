@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-calendar-check"></i> Attendance Management</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select id="classFilter" class="form-select form-select-sm">
                                    <option value="">All Classes</option>
                                    @foreach ($classes ?? [] as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="dateFilter" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="search" id="studentSearch" class="form-control form-control-sm"
                                    placeholder="Search student...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="attendanceTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendance as $record)
                            <tr class="attendance-row" data-class="{{ $record->class_id ?? '' }}"
                                data-date="{{ $record->date ?? '' }}" data-status="{{ $record->status ?? '' }}">
                                <td>{{ $record->student->user->name ?? $record->student->name }}</td>
                                <td>{{ $record->class->class_name ?? 'N/A' }}</td>
                                <td>{{ $record->date ? \Carbon\Carbon::parse($record->date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'absent' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td>{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $record->remarks ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editAttendance({{ $record->id }})">Edit</button>
                                    <form action="#" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No attendance records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $attendance->links() }}
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('super.attendance.delete-all') }}" method="POST"
                onsubmit="return confirm('Delete ALL attendance records? This cannot be undone!')">
                @csrf @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Attendance</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            filterAttendance();
        });

        document.getElementById('classFilter').addEventListener('change', function() {
            filterAttendance();
        });

        document.getElementById('dateFilter').addEventListener('change', function() {
            filterAttendance();
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            filterAttendance();
        });

        function filterAttendance() {
            let query = document.getElementById('studentSearch').value.toLowerCase();
            let classId = document.getElementById('classFilter').value;
            let date = document.getElementById('dateFilter').value;
            let status = document.getElementById('statusFilter').value;

            document.querySelectorAll('.attendance-row').forEach(row => {
                let matches = true;
                if (query && !row.textContent.toLowerCase().includes(query)) matches = false;
                if (classId && row.dataset.class !== classId) matches = false;
                if (date && row.dataset.date !== date) matches = false;
                if (status && row.dataset.status !== status) matches = false;
                row.style.display = matches ? '' : 'none';
            });
        }
    </script>
@endsection
