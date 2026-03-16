@extends('layouts.teacher')

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Page header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <div>
            <h1 class="h5 fw-bold mb-1">Take Attendance</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.attendance') }}">Attendance</a></li>
                    <li class="breadcrumb-item active">{{ $class->class_name ?? $class->name ?? 'Class' }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
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
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('teacher.attendance.record', $class->id) }}" method="POST" id="attendanceForm">
            @csrf
            <input type="hidden" name="date" id="dateInput" value="{{ $today }}">

            {{-- Class info + date --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <div class="fw-bold text-primary">
                                <i class="fas fa-door-open me-1"></i>
                                {{ $class->class_name ?? $class->name ?? 'Class' }}
                            </div>
                            <small class="text-muted">
                                @if ($class->course)
                                    {{ $class->course->course_name ?? '' }}
                                    @if ($class->course->course_code)
                                        ({{ $class->course->course_code }})
                                    @endif
                                @else
                                    No course assigned
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <label class="form-label small text-muted mb-1 d-block">Date</label>
                            <input type="date" id="attendanceDate" class="form-control form-control-sm"
                                value="{{ $today }}" max="{{ $today }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-sm btn-success" onclick="markAll('Present')">
                    <i class="fas fa-check-double me-1"></i> All Present
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="markAll('Absent')">
                    <i class="fas fa-times me-1"></i> All Absent
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAll()">
                    <i class="fas fa-eraser me-1"></i> Clear
                </button>
                <span class="ms-auto align-self-center small text-muted">
                    {{ $students->count() }} student(s)
                </span>
            </div>

            {{-- Legend --}}
            <div class="d-flex flex-wrap gap-3 mb-3 small">
                <span><span class="status-dot present"></span> Present</span>
                <span><span class="status-dot absent"></span> Absent</span>
                <span><span class="status-dot late"></span> Late</span>
                <span><span class="status-dot excused"></span> Excused</span>
            </div>

            {{-- Student cards (mobile-first layout) --}}
            <div class="student-list">
                @foreach ($students as $index => $student)
                    @php $current = $attendances[$student->id] ?? null; @endphp
                    <div class="student-card card border-0 shadow-sm mb-2">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center gap-3">
                                {{-- Number + name --}}
                                <div class="student-num text-muted small fw-bold">{{ $index + 1 }}</div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $student->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $student->student_id ?? '–' }}</small>
                                </div>
                                {{-- Status buttons --}}
                                <div class="status-btns d-flex gap-1">
                                    <label class="status-btn present-btn" title="Present">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]"
                                            value="Present" class="sr-only"
                                            {{ $current && $current->status === 'Present' ? 'checked' : '' }}>
                                        <span><i class="fas fa-check"></i></span>
                                    </label>
                                    <label class="status-btn absent-btn" title="Absent">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]"
                                            value="Absent" class="sr-only"
                                            {{ $current && $current->status === 'Absent' ? 'checked' : '' }}>
                                        <span><i class="fas fa-times"></i></span>
                                    </label>
                                    <label class="status-btn late-btn" title="Late">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]"
                                            value="Late" class="sr-only"
                                            {{ $current && $current->status === 'Late' ? 'checked' : '' }}>
                                        <span><i class="fas fa-clock"></i></span>
                                    </label>
                                    <label class="status-btn excused-btn" title="Excused">
                                        <input type="radio" name="attendance[{{ $student->id }}][status]"
                                            value="Leave" class="sr-only"
                                            {{ $current && $current->status === 'Leave' ? 'checked' : '' }}>
                                        <span><i class="fas fa-umbrella-beach"></i></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Save footer --}}
            <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pb-3">
                <small class="text-muted">Tap a status for each student, then save.</small>
                <div class="d-flex gap-2">
                    <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </div>
            </div>

        </form>
    @endif
</div>

<style>
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
               overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

    /* Legend dots */
    .status-dot {
        display: inline-block; width: 10px; height: 10px;
        border-radius: 50%; margin-right: 4px; vertical-align: middle;
    }
    .status-dot.present  { background: #198754; }
    .status-dot.absent   { background: #dc3545; }
    .status-dot.late     { background: #fd7e14; }
    .status-dot.excused  { background: #0d6efd; }

    /* Student number */
    .student-num { min-width: 22px; text-align: center; }

    /* Status buttons */
    .status-btns { flex-shrink: 0; }

    .status-btn {
        cursor: pointer;
        margin: 0;
    }
    .status-btn span {
        display: inline-flex; align-items: center; justify-content: center;
        width: 38px; height: 38px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        color: #adb5bd;
        font-size: 14px;
        transition: all 0.15s ease;
    }
    .status-btn:hover span { border-color: #adb5bd; background: #f8f9fa; }

    /* Checked states */
    .present-btn  input:checked ~ span { background: #198754; border-color: #198754; color: #fff; }
    .absent-btn   input:checked ~ span { background: #dc3545; border-color: #dc3545; color: #fff; }
    .late-btn     input:checked ~ span { background: #fd7e14; border-color: #fd7e14; color: #fff; }
    .excused-btn  input:checked ~ span { background: #0d6efd; border-color: #0d6efd; color: #fff; }

    /* Highlight row when any status selected */
    .student-card:has(input:checked) {
        border-left: 3px solid #667eea !important;
    }

    /* Mobile tweaks */
    @media (max-width: 576px) {
        .status-btn span {
            width: 34px;
            height: 34px;
            font-size: 13px;
            border-radius: 6px;
        }
        .student-card .card-body {
            padding: 10px 12px;
        }
        .student-num { min-width: 18px; font-size: 12px; }
    }

    /* Larger screens: show as table-like layout */
    @media (min-width: 768px) {
        .status-btn span {
            width: 42px;
            height: 42px;
            font-size: 15px;
        }
    }
</style>

<script>
    document.getElementById('attendanceDate').addEventListener('change', function () {
        document.getElementById('dateInput').value = this.value;
    });

    function markAll(status) {
        document.querySelectorAll('input[value="' + status + '"]').forEach(i => i.checked = true);
    }

    function clearAll() {
        document.querySelectorAll('#attendanceForm input[type="radio"]').forEach(i => i.checked = false);
    }
</script>
@endsection
