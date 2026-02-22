@extends('layouts.teacher')

@section('content')
    <div class="container-fluid">
        {{-- Page header --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">Take Attendance</h1>
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.attendance') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">{{ $class->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to classes
            </a>
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
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                {{-- Card header: Class, Course, Date --}}
                <div class="card-header bg-light py-3">
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-door-open text-primary me-2"></i>{{ $class->name }}
                            </h5>
                            <p class="mb-0 small text-muted mt-1">
                                <i class="fas fa-book me-1"></i>
                                @if ($class->course)
                                    <strong>{{ $class->course->course_name }}</strong>
                                    @if ($class->course->course_code)
                                        <span class="text-muted">({{ $class->course->course_code }})</span>
                                    @endif
                                @else
                                    <em>No course assigned</em>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-md-auto">
                            <label class="form-label small text-muted mb-0">Date</label>
                            <input type="date" id="attendanceDate" class="form-control form-control-sm" value="{{ $today }}" max="{{ $today }}" style="min-width: 140px;">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <form action="{{ route('teacher.attendance.record', $class->id) }}" method="POST" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="date" id="dateInput" value="{{ $today }}">

                        {{-- Quick actions --}}
                        <div class="px-3 pt-3 pb-2 bg-white border-bottom d-flex flex-wrap gap-2">
                            <span class="small text-muted align-self-center me-2">Quick:</span>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="markAllPresent()">
                                <i class="fas fa-check-double me-1"></i> All Present
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAllAbsent()">
                                <i class="fas fa-times me-1"></i> All Absent
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAll()">
                                <i class="fas fa-eraser me-1"></i> Clear
                            </button>
                        </div>

                        {{-- Checklist table: one column per status --}}
                        <div class="px-3 pt-2 pb-1 small text-muted border-bottom">
                            <strong>Checklist:</strong> Tick one box per student — <span class="text-success">Present</span> · <span class="text-danger">Absent</span> · <span class="text-warning">Late</span> · <span class="text-primary">Excused</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-checklist mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center col-num">#</th>
                                        <th class="col-id">Student ID</th>
                                        <th class="col-name">Name</th>
                                        <th class="text-center col-status"><i class="fas fa-check text-success me-1"></i><span class="d-none d-lg-inline">Present</span></th>
                                        <th class="text-center col-status"><i class="fas fa-times text-danger me-1"></i><span class="d-none d-lg-inline">Absent</span></th>
                                        <th class="text-center col-status"><i class="fas fa-clock text-warning me-1"></i><span class="d-none d-lg-inline">Late</span></th>
                                        <th class="text-center col-status"><i class="fas fa-umbrella-beach text-primary me-1"></i><span class="d-none d-lg-inline">Excused</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $index => $student)
                                        @php
                                            $current = $attendances[$student->id] ?? null;
                                        @endphp
                                        <tr class="checklist-row">
                                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                                            <td class="text-nowrap"><span class="badge bg-secondary">{{ $student->student_id ?? '–' }}</span></td>
                                            <td class="fw-medium">{{ $student->user->name ?? 'N/A' }}</td>
                                            <td class="text-center cell-status">
                                                <label class="checklist-cell present-cell mb-0">
                                                    <input type="radio" name="attendance[{{ $student->id }}][status]" value="Present" class="sr-only"
                                                        {{ $current && $current->status === 'Present' ? 'checked' : '' }}>
                                                    <span class="check-box"><i class="fas fa-check"></i></span>
                                                </label>
                                            </td>
                                            <td class="text-center cell-status">
                                                <label class="checklist-cell absent-cell mb-0">
                                                    <input type="radio" name="attendance[{{ $student->id }}][status]" value="Absent" class="sr-only"
                                                        {{ $current && $current->status === 'Absent' ? 'checked' : '' }}>
                                                    <span class="check-box"><i class="fas fa-times"></i></span>
                                                </label>
                                            </td>
                                            <td class="text-center cell-status">
                                                <label class="checklist-cell late-cell mb-0">
                                                    <input type="radio" name="attendance[{{ $student->id }}][status]" value="Late" class="sr-only"
                                                        {{ $current && $current->status === 'Late' ? 'checked' : '' }}>
                                                    <span class="check-box"><i class="fas fa-clock"></i></span>
                                                </label>
                                            </td>
                                            <td class="text-center cell-status">
                                                <label class="checklist-cell excuse-cell mb-0">
                                                    <input type="radio" name="attendance[{{ $student->id }}][status]" value="Leave" class="sr-only"
                                                        {{ $current && $current->status === 'Leave' ? 'checked' : '' }}>
                                                    <span class="check-box"><i class="fas fa-umbrella-beach"></i></span>
                                                </label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer actions --}}
                        <div class="card-footer bg-light d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
                            <small class="text-muted">Check one box per row (Present / Absent / Late / Excused). Then save.</small>
                            <div class="d-flex gap-2">
                                <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Attendance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <style>
        /* Checklist table: uniform, easy to scan */
        .table-checklist { border-collapse: collapse; }
        .table-checklist thead th { font-weight: 600; font-size: 0.8rem; white-space: nowrap; padding: 0.6rem 0.5rem; }
        .table-checklist tbody td { vertical-align: middle; padding: 0.5rem 0.5rem; }
        .table-checklist .col-num { width: 2.5rem; }
        .table-checklist .col-id { width: 10rem; min-width: 8rem; }
        .table-checklist .col-name { min-width: 10rem; }
        .table-checklist .col-status { width: 5rem; min-width: 4rem; }

        .checklist-cell { cursor: pointer; display: block; margin: 0; }
        .checklist-cell .check-box {
            display: inline-flex; align-items: center; justify-content: center;
            width: 2rem; height: 2rem; border: 2px solid #dee2e6; border-radius: 6px;
            background: #fff; color: transparent; transition: all 0.15s ease;
        }
        .checklist-cell:hover .check-box { border-color: #adb5bd; background: #f8f9fa; }
        .checklist-cell input:checked + .check-box { color: #fff; font-weight: bold; }
        .present-cell input:checked + .check-box { background: #198754; border-color: #198754; color: #fff; }
        .absent-cell input:checked + .check-box { background: #dc3545; border-color: #dc3545; color: #fff; }
        .late-cell input:checked + .check-box { background: #fd7e14; border-color: #fd7e14; color: #fff; }
        .excuse-cell input:checked + .check-box { background: #0d6efd; border-color: #0d6efd; color: #fff; }

        .checklist-row:hover { background-color: #f8f9fa; }
        .cell-status { background: #fafafa; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
    </style>

    <script>
        document.getElementById('attendanceDate').addEventListener('change', function() {
            document.getElementById('dateInput').value = this.value;
        });

        function markAllPresent() {
            document.querySelectorAll('input[value="Present"]').forEach(function(i) { i.checked = true; });
        }
        function markAllAbsent() {
            document.querySelectorAll('input[value="Absent"]').forEach(function(i) { i.checked = true; });
        }
        function clearAll() {
            document.querySelectorAll('.table-checklist input[type="radio"]').forEach(function(i) { i.checked = false; });
        }
    </script>
@endsection
