@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">{{ $class->name }} - Attendance</h1>
                <small class="text-muted">
                    @if ($class->course)
                        {{ $class->course->course_name }} •
                    @endif
                    {{ $students->count() }} Students
                </small>
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
            <div class="card-header" style="background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0" style="color: #495057; font-weight: 600;">
                            <i class="fas fa-calendar-alt me-2" style="color: #667eea;"></i>
                            Attendance Date: <span id="dateDisplay"
                                style="color: #667eea; font-weight: 700;">{{ $today }}</span>
                        </h5>
                    </div>
                    <div class="col-auto">
                        <input type="date" id="attendanceDate" class="form-control form-control-sm"
                            value="{{ $today }}" max="{{ $today }}" style="max-width: 200px;">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.attendance.record', $class->id) }}" method="POST" id="attendanceForm">
                    @csrf
                    <input type="hidden" name="date" id="dateInput" value="{{ $today }}">

                    <!-- Quick Actions -->
                    <div class="mb-4 d-flex gap-2" style="flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm" onclick="markAllPresent()"
                            style="background-color: #f0f9ff; color: #0c63e4; border: 1px solid #b6d4fe; font-weight: 500;">
                            <i class="fas fa-check-circle me-1"></i> All Present
                        </button>
                        <button type="button" class="btn btn-sm" onclick="markAllAbsent()"
                            style="background-color: #fff5f5; color: #a71d2a; border: 1px solid #f8d7da; font-weight: 500;">
                            <i class="fas fa-times-circle me-1"></i> All Absent
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAll()"
                            style="font-weight: 500;">
                            <i class="fas fa-redo me-1"></i> Clear
                        </button>
                    </div>

                    <!-- Students with Checklist -->
                    <div class="students-attendance-list">
                        @forelse($students as $student)
                            <div class="attendance-item mb-2 p-3 border"
                                style="background: #ffffff; border-radius: 6px; border: 1px solid #e9ecef;">
                                <div class="row align-items-center">
                                    <!-- Student Info -->
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Student ID Badge -->
                                            <div style="flex-shrink: 0;">
                                                <span class="badge"
                                                    style="background-color: #667eea; font-size: 0.85rem; padding: 0.5rem 0.75rem; font-weight: 600;">
                                                    {{ $student->student_id ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <!-- Student Name & Avatar -->
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="badge rounded-circle"
                                                    style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; font-weight: bold; background-color: #e9ecef; color: #495057; flex-shrink: 0;">
                                                    {{ strtoupper(substr($student->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0" style="color: #212529; font-weight: 600;">
                                                        {{ $student->user->name ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attendance Checklist -->
                                    <div class="col-md-7">
                                        <div class="attendance-checklist d-flex gap-2" style="flex-wrap: wrap;">
                                            <!-- Present -->
                                            <label class="attendance-checkbox">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                    value="Present" class="form-check-input"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Present' ? 'checked' : '' }}>
                                                <span class="checkbox-label present">
                                                    <i class="fas fa-check"></i> Present
                                                </span>
                                            </label>

                                            <!-- Absent -->
                                            <label class="attendance-checkbox">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                    value="Absent" class="form-check-input"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Absent' ? 'checked' : '' }}>
                                                <span class="checkbox-label absent">
                                                    <i class="fas fa-times"></i> Absent
                                                </span>
                                            </label>

                                            <!-- Late -->
                                            <label class="attendance-checkbox">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                    value="Late" class="form-check-input"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Late' ? 'checked' : '' }}>
                                                <span class="checkbox-label late">
                                                    <i class="fas fa-clock"></i> Late
                                                </span>
                                            </label>

                                            <!-- Excuse -->
                                            <label class="attendance-checkbox">
                                                <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                    value="Leave" class="form-check-input"
                                                    {{ isset($attendances[$student->id]) && $attendances[$student->id]->status === 'Leave' ? 'checked' : '' }}>
                                                <span class="checkbox-label excuse">
                                                    <i class="fas fa-ban"></i> Excuse
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                <p style="color: #6c757d;">No students found</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($students->isNotEmpty())
                        <div class="d-flex gap-2 justify-content-end mt-5 pt-4 border-top">
                            <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary">
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

    <style>
        /* Checklist Styles - Minimalist with 60-30-10 rule */
        .attendance-checkbox {
            display: inline-block;
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .attendance-checkbox input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1.5px solid #e9ecef;
            background: white;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 12.5px;
            cursor: pointer;
            color: #6c757d;
        }

        .checkbox-label i {
            font-size: 12px;
        }

        /* Present - Primary color (60% of palette) */
        .checkbox-label.present {
            color: #0c63e4;
            border-color: #0c63e4;
        }

        .attendance-checkbox input[value="Present"]:checked+.checkbox-label.present {
            background: #0c63e4;
            color: white;
            box-shadow: 0 2px 6px rgba(12, 99, 228, 0.2);
        }

        /* Absent - Secondary accent (30% of palette) */
        .checkbox-label.absent {
            color: #a71d2a;
            border-color: #a71d2a;
        }

        .attendance-checkbox input[value="Absent"]:checked+.checkbox-label.absent {
            background: #a71d2a;
            color: white;
            box-shadow: 0 2px 6px rgba(167, 29, 42, 0.2);
        }

        /* Late - Tertiary accent (10% of palette) */
        .checkbox-label.late {
            color: #fd7e14;
            border-color: #fd7e14;
        }

        .attendance-checkbox input[value="Late"]:checked+.checkbox-label.late {
            background: #fd7e14;
            color: white;
            box-shadow: 0 2px 6px rgba(253, 126, 20, 0.2);
        }

        /* Excuse - Tertiary accent (10% of palette) */
        .checkbox-label.excuse {
            color: #6c757d;
            border-color: #6c757d;
        }

        .attendance-checkbox input[value="Leave"]:checked+.checkbox-label.excuse {
            background: #6c757d;
            color: white;
            box-shadow: 0 2px 6px rgba(108, 117, 125, 0.2);
        }

        /* Attendance Item */
        .attendance-item {
            transition: all 0.2s ease;
        }

        .attendance-item:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            border-color: #dee2e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .attendance-item {
                padding: 1rem !important;
            }

            .checkbox-label {
                font-size: 11px;
                padding: 5px 10px;
            }
        }
    </style>

    <script>
        // Update date display and form input when date picker changes
        document.getElementById('attendanceDate').addEventListener('change', function() {
            document.getElementById('dateInput').value = this.value;
            document.getElementById('dateDisplay').textContent = this.value;
        });

        // Mark all as present
        function markAllPresent() {
            document.querySelectorAll('input[value="Present"]').forEach(input => {
                input.checked = true;
            });
        }

        // Mark all as absent
        function markAllAbsent() {
            document.querySelectorAll('input[value="Absent"]').forEach(input => {
                input.checked = true;
            });
        }

        // Clear all selections
        function clearAll() {
            document.querySelectorAll('.attendance-checklist input[type="radio"]').forEach(input => {
                input.checked = false;
            });
        }
    </script>

@endsection
