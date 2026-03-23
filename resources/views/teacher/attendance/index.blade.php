@extends('layouts.teacher')

@section('content')

    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\ClassModel[] $classes */

        // Defensive: Ensure $classes is always a Collection
        if (!$classes instanceof \Illuminate\Support\Collection) {
            \Illuminate\Support\Facades\Log::error('Attendance index view: $classes is not a Collection', [
                'type' => gettype($classes),
                'value' => $classes,
                'teacher_id' => Auth::id() ?? 'unknown',
            ]);
            $classes = collect();
        }
    @endphp

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Attendance Management</h1>
            <small class="text-muted">Select a class to manage student attendance</small>
        </div>
    </div>

    @if ($classes->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You don't have any classes assigned yet.
        </div>
    @else
        <?php
        // Determine which term and date to display
        $today = $today ?? request()->query('date', now()->format('Y-m-d'));
        $currentTerm = $currentTerm ?? request()->query('term', 'Midterm');
        if (!in_array($currentTerm, ['Midterm', 'Final'])) {
            $currentTerm = 'Midterm';
        }
        
        // Get today's attendance for all classes and the selected term
        $classIds = $classes->pluck('id')->toArray();
        
        $todayAttendances = \App\Models\Attendance::where('date', $today)
            ->where('term', $currentTerm)
            ->whereIn('class_id', $classIds)
            ->get()
            ->keyBy(function ($attendance) {
                return $attendance->class_id . '_' . $attendance->student_id;
            });
        ?>

        <div class="row mb-3">
            <div class="col-auto">
                <label class="form-label small mb-1">Term</label>
                <select id="attendanceTermSelect" class="form-select form-select-sm">
                    <option value="Midterm" {{ $currentTerm === 'Midterm' ? 'selected' : '' }}>Midterm</option>
                    <option value="Final" {{ $currentTerm === 'Final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Date</label>
                <input type="date" id="attendanceDateFilter" class="form-control form-control-sm"
                    value="{{ $today }}" max="{{ now()->format('Y-m-d') }}">
            </div>
        </div>

        <div class="attendance-classes-container">
            @foreach ($classes as $class)
                <div class="class-card mb-3 border rounded-lg overflow-hidden"
                    style="background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Class Header (Clickable) -->
                    <div class="class-header p-4 cursor-pointer d-flex justify-content-between align-items-center"
                        onclick="toggleClassStudents(this)"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; user-select: none;">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <i class="fas fa-door-open fa-lg"></i>
                            <div>
                                <h5 class="mb-0">{{ $class->class_name ?? $class->name }}</h5>
                                <small>
                                    @if ($class->course && $class->course->course_name)
                                        {{ $class->course->course_name }}
                                    @else
                                        <em>No course assigned</em>
                                    @endif
                                    • {{ $class->students->count() }}
                                    student{{ $class->students->count() !== 1 ? 's' : '' }}
                                </small>
                            </div>
                        </div>
                        <div class="class-toggle-icon">
                            <i class="fas fa-chevron-down fa-lg" style="transition: transform 0.3s;"></i>
                        </div>
                    </div>

                    <!-- Class Content (Expandable) -->
                    <div class="class-content" style="display: none;">
                        <div class="p-4 border-top">
                            <!-- Student List -->
                            <div class="students-list mb-4">
                                @if ($class->students->isEmpty())
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-circle me-2"></i>No students enrolled in this class.
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-muted fw-bold">{{ $class->students->count() }} Enrolled
                                            Students</h6>
                                        @php
                                            $classAttendanceStats = [
                                                'Present' => 0,
                                                'Absent' => 0,
                                                'Late' => 0,
                                                'Leave' => 0,
                                                'Unmarked' => 0,
                                            ];
                                            foreach ($class->students as $student) {
                                                $attendanceKey = $class->id . '_' . $student->id;
                                                $currentAttendance = $todayAttendances->get($attendanceKey);
                                                $status = $currentAttendance ? $currentAttendance->status : 'Unmarked';
                                                $classAttendanceStats[$status]++;
                                            }
                                        @endphp
                                        <div class="d-flex gap-2 align-items-center">
                                            <small class="text-muted">Today's Status:</small>
                                            <span class="badge bg-success">{{ $classAttendanceStats['Present'] }}
                                                Present</span>
                                            <span class="badge bg-danger">{{ $classAttendanceStats['Absent'] }}
                                                Absent</span>
                                            <span class="badge bg-warning">{{ $classAttendanceStats['Late'] }} Late</span>
                                            <span class="badge bg-primary">{{ $classAttendanceStats['Leave'] }}
                                                Excused</span>
                                            <span class="badge bg-secondary">{{ $classAttendanceStats['Unmarked'] }}
                                                Unmarked</span>
                                        </div>
                                    </div>
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr style="font-weight: 600;">
                                                    <th style="width: 20%;">Student ID</th>
                                                    <th style="width: 50%;">Student Name</th>
                                                    <th style="width: 30%; text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($class->students as $student)
                                                    <tr style="align-items: center;">
                                                        <td>
                                                            <span class="badge bg-secondary"
                                                                style="font-size: 0.8rem; font-weight: 600;">
                                                                {{ $student->student_id ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="badge rounded-circle"
                                                                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold; background-color: #e9ecef; color: #495057;">
                                                                    {{ strtoupper(substr($student->name ?? 'U', 0, 1)) }}
                                                                </div>
                                                                <span>{{ $student->name ?? 'N/A' }}</span>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @php
                                                                $attendanceKey = $class->id . '_' . $student->id;
                                                                $currentAttendance = $todayAttendances->get(
                                                                    $attendanceKey,
                                                                );
                                                                $status = $currentAttendance
                                                                    ? $currentAttendance->status
                                                                    : 'Unmarked';
                                                            @endphp
                                                            <a href="{{ route('teacher.attendance.manage', $class->id) }}"
                                                                class="btn btn-sm @if ($status === 'Present') btn-success @elseif($status === 'Absent') btn-danger @elseif($status === 'Late') btn-warning @elseif($status === 'Leave') btn-primary @else btn-secondary @endif"
                                                                title="Take Attendance - Current: {{ $status }}"
                                                                style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                                                @if ($status === 'Present')
                                                                    <i class="fas fa-check"></i> Present
                                                                @elseif($status === 'Absent')
                                                                    <i class="fas fa-times"></i> Absent
                                                                @elseif($status === 'Late')
                                                                    <i class="fas fa-clock"></i> Late
                                                                @elseif($status === 'Leave')
                                                                    <i class="fas fa-umbrella-beach"></i> Excused
                                                                @else
                                                                    <i class="fas fa-question-circle"></i> Unmarked
                                                                @endif
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-between border-top pt-3">
                                <div>
                                    <a href="{{ route('teacher.attendance.history', $class->id) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-history me-1"></i> History
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('teacher.attendance.manage', $class->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-clipboard-check me-1"></i> Take Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .class-card {
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .class-header:hover {
            opacity: 0.95 !important;
        }

        .class-content {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }

            to {
                opacity: 1;
                max-height: 600px;
            }
        }

        /* Attendance status buttons */
        .btn-sm {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.65rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Status badges */
        .badge {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
            font-weight: 500 !important;
        }

        /* Responsive badge container */
        .d-flex.gap-2 {
            flex-wrap: wrap;
            gap: 0.5rem !important;
        }

        /* Table improvements */
        .table-sm td {
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        /* Status button specific styles */
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: #212529;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
        }
    </style>

    <script>
        function toggleClassStudents(headerElement) {
            const card = headerElement.closest('.class-card');
            const content = card.querySelector('.class-content');
            const icon = headerElement.querySelector('.class-toggle-icon i');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>

@endsection
