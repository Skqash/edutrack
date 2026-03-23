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
            <input type="hidden" name="term" id="termInput" value="{{ $currentTerm }}">

            {{-- Class info + date + term --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center g-2">
                        <div class="col-12 col-md">
                            <div class="fw-bold text-primary">
                                <i class="fas fa-door-open me-1"></i>
                                {{ $class->class_name ?? $class->name ?? 'Class' }}
                            </div>
                            <small class="text-muted">
                                @if ($class->program)
                                    {{ $class->program->program_name ?? '' }}
                                    @if ($class->program->program_code)
                                        ({{ $class->program->program_code }})
                                    @endif
                                @else
                                    No program assigned
                                @endif
                            </small>
                        </div>
                        <div class="col-auto">
                            <label class="form-label small text-muted mb-1 d-block">
                                <i class="fas fa-calendar-alt me-1"></i>Academic Term
                            </label>
                            <select id="termSelect" class="form-select form-select-sm fw-bold" style="min-width: 120px;">
                                <option value="Midterm" {{ $currentTerm === 'Midterm' ? 'selected' : '' }}>
                                    📚 Midterm Term
                                </option>
                                <option value="Final" {{ $currentTerm === 'Final' ? 'selected' : '' }}>
                                    🎓 Final Term
                                </option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label small text-muted mb-1 d-block">
                                <i class="fas fa-calendar-day me-1"></i>Attendance Date
                            </label>
                            <input type="date" id="attendanceDate" class="form-control form-control-sm"
                                value="{{ $today }}" max="{{ $today }}">
                        </div>
                    </div>
                    
                    {{-- Term Indicator --}}
                    <div class="mt-2">
                        <div class="alert alert-info py-2 px-3 mb-0" id="termIndicator">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Currently taking attendance for:</strong>
                            <span class="badge bg-primary ms-1" id="currentTermBadge">{{ $currentTerm }} Term</span>
                            <small class="text-muted ms-2">All records will be saved under this term</small>
                        </div>
                    </div>
                    
                    {{-- Attendance Settings Info --}}
                    <div class="mt-3 pt-3 border-top">
                        <div class="row g-2 small">
                            <div class="col-auto">
                                <span class="text-muted">Total Meetings ({{ $currentTerm }}):</span>
                                <strong class="text-primary ms-1">
                                    {{ $currentTerm === 'Midterm' ? $class->total_meetings_midterm : $class->total_meetings_final }}
                                </strong>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted">Attendance Weight:</span>
                                <strong class="text-success ms-1">{{ $class->attendance_percentage }}%</strong>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('teacher.attendance.settings', $class->id) }}" class="text-decoration-none">
                                    <i class="fas fa-cog me-1"></i>Settings
                                </a>
                            </div>
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
                                    <div class="fw-semibold text-truncate">{{ $student->name ?? 'N/A' }}</div>
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
        const newDate = this.value;
        if (!newDate) return;
        // Reload page with new date so server returns correct existing attendance
        const url = new URL(window.location.href);
        url.searchParams.set('date', newDate);
        url.searchParams.set('term', document.getElementById('termSelect').value);
        window.location.href = url.toString();
    });
    
    document.getElementById('termSelect').addEventListener('change', function () {
        const selectedTerm = this.value;
        document.getElementById('termInput').value = selectedTerm;
        
        // Update term indicator
        const badge = document.getElementById('currentTermBadge');
        const indicator = document.getElementById('termIndicator');
        
        if (badge) {
            badge.textContent = selectedTerm + ' Term';
            badge.className = selectedTerm === 'Midterm' ? 'badge bg-warning ms-1' : 'badge bg-success ms-1';
        }
        
        // Update indicator message
        if (indicator) {
            const termIcon = selectedTerm === 'Midterm' ? '📚' : '🎓';
            indicator.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                <strong>Currently taking attendance for:</strong>
                <span class="badge ${selectedTerm === 'Midterm' ? 'bg-warning' : 'bg-success'} ms-1" id="currentTermBadge">${termIcon} ${selectedTerm} Term</span>
                <small class="text-muted ms-2">All records will be saved under this term</small>
            `;
        }
        
        // Show confirmation before reloading
        if (confirm(`Switch to ${selectedTerm} term? Any unsaved attendance will be lost.`)) {
            // Reload page to get attendance for selected term
            const url = new URL(window.location.href);
            url.searchParams.set('term', selectedTerm);
            url.searchParams.set('date', document.getElementById('attendanceDate').value);
            window.location.href = url.toString();
        } else {
            // Revert selection if cancelled
            this.value = '{{ $currentTerm }}';
        }
    });

    // Initialize term indicator on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentTerm = '{{ $currentTerm }}';
        const badge = document.getElementById('currentTermBadge');
        if (badge) {
            badge.className = currentTerm === 'Midterm' ? 'badge bg-warning ms-1' : 'badge bg-success ms-1';
        }
    });

    function markAll(status) {
        document.querySelectorAll('input[value="' + status + '"]').forEach(i => i.checked = true);
    }

    function clearAll() {
        document.querySelectorAll('#attendanceForm input[type="radio"]').forEach(i => i.checked = false);
    }
</script>
@endsection
