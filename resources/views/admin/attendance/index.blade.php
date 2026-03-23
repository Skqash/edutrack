@extends('layouts.admin')

@section('content')
    <style>
        /* Modern Page Header */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
            color: white;
        }

        .page-header-modern .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-modern .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header-modern .header-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
        }

        .page-header-modern .header-title {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .page-header-modern .header-subtitle {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.5rem;
            }

            .page-header-modern .header-title {
                font-size: 1.5rem;
            }

            .page-header-modern .header-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }
        }

        /* CARD STYLING */
        .attendance-card {
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .attendance-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* CARD HEADER - CLICKABLE */
        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            color: #333;
            padding: 18px 20px;
            cursor: pointer;
            user-select: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .card-header:hover {
            background: #f8f8f8;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .card-header-info {
            flex: 1;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .card-subtitle {
            font-size: 12px;
            color: #666;
            display: flex;
            gap: 15px;
        }

        .card-header-icon {
            font-size: 20px;
            color: #333;
            transition: transform 0.3s ease;
        }

        .card-header-icon.rotated {
            transform: rotate(180deg);
        }

        /* CARD CONTENT */
        .card-content {
            padding: 20px;
            border-top: 1px solid #e0e0e0;
            display: none;
            background: #f9f9f9;
        }

        .card-content.show {
            display: block;
        }

        /* FILTER SECTION */
        .filter-section {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }

        .filter-section.show {
            display: block;
        }

        .filter-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }

        /* ATTENDANCE TABLE */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            background: white;
            border-radius: 6px;
            overflow: hidden;
        }

        .attendance-table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }

        .attendance-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .attendance-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* STATUS BADGES */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 10px;
            text-align: center;
            min-width: 70px;
        }

        .status-present {
            background-color: #d4edda;
            color: #155724;
        }

        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-leave {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* WEEK SECTION */
        .week-section {
            background: white;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 4px;
            border-left: 4px solid #87CEEB;
        }

        .week-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 12px;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.5;
            margin-bottom: 15px;
        }

        /* TABS */
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 13px;
        }

        .tab-btn.active {
            border-color: #87CEEB;
            background: #87CEEB;
            color: white;
        }

        .tab-btn:hover {
            border-color: #87CEEB;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        @media print {

            .no-print,
            .tab-buttons,
            .filter-section {
                display: none !important;
            }

            .attendance-card {
                page-break-inside: avoid;
                margin-bottom: 30px;
            }

            .card-header {
                background: none;
                color: #000;
                border-bottom: 2px solid #000;
            }

            .card-header-icon {
                display: none;
            }
        }
    </style>

    <!-- Modern Page Header -->
    <div class="page-header-modern">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h1 class="header-title">Attendance Management</h1>
                    <p class="header-subtitle">Track and manage student attendance records</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TAB NAVIGATION -->
    <div class="tab-buttons no-print">
        <button class="tab-btn active" onclick="switchTab('classes', this)">
            <i class="fas fa-building"></i> Classes
        </button>
        <button class="tab-btn" onclick="switchTab('students', this)">
            <i class="fas fa-users"></i> Students
        </button>
        <button class="tab-btn" onclick="switchTab('teachers', this)">
            <i class="fas fa-chalkboard-user"></i> Teachers
        </button>
    </div>

    <!-- CLASSES TAB -->
    <div id="classes" class="tab-content active">
        <div class="attendance-container">
            @forelse($attendanceByClass as $classId => $classData)
                @php
                    $class = $classData['class'];
                    $attendance = $classData['attendance'];
                    $stats = $classData['stats'];
                @endphp
                <div class="attendance-card">
                    <div class="card-header" onclick="toggleCard(this)">
                        <div class="card-header-info">
                            <div class="card-title">{{ $class->class_name }}</div>
                            <div class="card-subtitle">
                                <span>{{ $stats['total'] }} Records</span>
                                <span>{{ $stats['present'] }} Present</span>
                                <span>{{ $stats['absent'] }} Absent</span>
                            </div>
                        </div>
                        <div class="card-header-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="card-content">
                        <!-- FILTER -->
                        <div class="filter-section show">
                            <input type="text" class="filter-input" placeholder="Filter by student name..."
                                onkeyup="filterClassStudents(this)">
                        </div>

                        @if ($class->students->count() > 0)
                            @php
                                // Group attendance by student
                                $studentAttendance = [];
                                foreach ($class->students as $student) {
                                    $studentRecords = $attendance->where('student_id', $student->id);
                                    $studentAttendance[$student->id] = [
                                        'student' => $student,
                                        'records' => $studentRecords,
                                    ];
                                }
                            @endphp

                            @foreach ($studentAttendance as $studentId => $data)
                                @php
                                    $student = $data['student'];
                                    $records = $data['records'];
                                    // Group by week
                                    $groupedByWeek = [];
                                    foreach ($records as $record) {
                                        $week = $record->date
                                            ? \Carbon\Carbon::parse($record->date)->format('Y-W')
                                            : 'Unknown';
                                        if (!isset($groupedByWeek[$week])) {
                                            $groupedByWeek[$week] = [];
                                        }
                                        $groupedByWeek[$week][] = $record;
                                    }
                                @endphp

                                <div class="week-section student-filter-item">
                                    <div class="week-title">
                                        <span class="badge bg-info text-white fw-bold me-2"
                                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            {{ $student->student_id ?? 'N/A' }}
                                        </span>
                                        {{ $student->user->name ?? 'N/A' }}
                                        @if ($records->count() == 0)
                                            <span style="color: #999; font-size: 11px;">(No attendance records)</span>
                                        @else
                                            <span style="color: #666; font-size: 11px;">- {{ $records->count() }}
                                                records</span>
                                        @endif
                                    </div>

                                    @if ($records->count() > 0)
                                        @foreach ($groupedByWeek as $week => $weekRecords)
                                            <table class="attendance-table" style="margin-bottom: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($weekRecords as $record)
                                                        <tr>
                                                            <td>{{ $record->date ? \Carbon\Carbon::parse($record->date)->format('M d, Y') : 'N/A' }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="status-badge status-{{ strtolower($record->status) }}">
                                                                    {{ $record->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endforeach
                                    @else
                                        <div class="empty-state"
                                            style="padding: 15px; text-align: center; color: #999; font-size: 12px;">
                                            No attendance records for this student
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No students in this class</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No classes found.
                </div>
            @endforelse
        </div>
    </div>

    <!-- STUDENTS TAB -->
    <div id="students" class="tab-content">
        <div class="attendance-container">
            @php
                // Build student list with all classes
                $allStudents = [];
                foreach ($attendanceByClass as $classData) {
                    foreach ($classData['class']->students as $student) {
                        $allStudents[$student->id] = $student;
                    }
                }
            @endphp

            <!-- FILTER -->
            <div class="filter-section show" style="margin-bottom: 20px;">
                <input type="text" class="filter-input" placeholder="Filter by student name..."
                    onkeyup="filterStudents(this)">
            </div>

            @forelse($allStudents as $studentId => $student)
                @php
                    // Get all attendance records for this student
                    $studentRecords = \App\Models\Attendance::where('student_id', $studentId)->get();
                    // Group by week
                    $groupedByWeek = [];
                    foreach ($studentRecords as $record) {
                        $week = $record->date ? \Carbon\Carbon::parse($record->date)->format('Y-W') : 'Unknown';
                        if (!isset($groupedByWeek[$week])) {
                            $groupedByWeek[$week] = [];
                        }
                        $groupedByWeek[$week][] = $record;
                    }
                    // Calculate stats
                    $totalRecords = $studentRecords->count();
                    $presentCount = $studentRecords->where('status', 'Present')->count();
                    $absentCount = $studentRecords->where('status', 'Absent')->count();
                @endphp

                <div class="attendance-card student-filter-item">
                    <div class="card-header" onclick="toggleCard(this)">
                        <div class="card-header-info">
                            <div class="card-title">
                                <span class="badge bg-info text-white fw-bold me-2"
                                    style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    {{ $student->student_id ?? 'N/A' }}
                                </span>
                                {{ $student->user->name ?? 'N/A' }}
                            </div>
                            <div class="card-subtitle">
                                <span>{{ $totalRecords }} Records</span>
                                <span>{{ $presentCount }} Present</span>
                                <span>{{ $absentCount }} Absent</span>
                            </div>
                        </div>
                        <div class="card-header-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="card-content">
                        @if ($totalRecords > 0)
                            @foreach ($groupedByWeek as $week => $weekRecords)
                                <div class="week-section">
                                    <div class="week-title">Week {{ substr($week, 6) }}, {{ substr($week, 0, 4) }}
                                    </div>
                                    <table class="attendance-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Class</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($weekRecords as $record)
                                                <tr>
                                                    <td>{{ $record->date ? \Carbon\Carbon::parse($record->date)->format('M d, Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ $record->class->class_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span
                                                            class="status-badge status-{{ strtolower($record->status) }}">
                                                            {{ $record->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No attendance records</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No students found.
                </div>
            @endforelse
        </div>
    </div>

    <!-- TEACHERS TAB -->
    <div id="teachers" class="tab-content">
        <div class="attendance-container">
            @php
                // Build teacher list from grades with attendance
                $allTeachers = [];
                foreach ($attendanceByClass as $classData) {
                    $grades = \App\Models\Grade::where('class_id', $classData['class']->id)->with('teacher')->get();
                    foreach ($grades as $grade) {
                        if ($grade->teacher) {
                            $allTeachers[$grade->teacher->id] = $grade->teacher;
                        }
                    }
                }
            @endphp

            <!-- FILTER -->
            <div class="filter-section show" style="margin-bottom: 20px;">
                <input type="text" class="filter-input" placeholder="Filter by teacher name..."
                    onkeyup="filterTeachers(this)">
            </div>

            @forelse($allTeachers as $teacherId => $teacher)
                @php
                    // Get teacher classes
                    $teacherClasses = \App\Models\Grade::where('teacher_id', $teacherId)
                        ->groupBy('class_id')
                        ->pluck('class_id')
                        ->toArray();
                @endphp

                <div class="attendance-card teacher-filter-item">
                    <div class="card-header" onclick="toggleCard(this)">
                        <div class="card-header-info">
                            <div class="card-title">{{ $teacher->name ?? 'N/A' }}</div>
                            <div class="card-subtitle">
                                <span>{{ count($teacherClasses) }} Classes</span>
                            </div>
                        </div>
                        <div class="card-header-icon">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="card-content">
                        @foreach ($teacherClasses as $classId)
                            @php
                                $class = \App\Models\ClassModel::find($classId);
                                $classStats = $attendanceByClass[$classId] ?? null;
                            @endphp
                            @if ($class)
                                <div class="week-section">
                                    <div class="week-title">📚 {{ $class->class_name }}</div>
                                    <table class="attendance-table">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Status Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $classAttendance = $classStats['attendance'] ?? collect();
                                                $students = $class->students;
                                            @endphp
                                            @foreach ($students as $student)
                                                @php
                                                    $studentRecords = $classAttendance->where(
                                                        'student_id',
                                                        $student->id,
                                                    );
                                                    $presentCount = $studentRecords
                                                        ->where('status', 'Present')
                                                        ->count();
                                                    $absentCount = $studentRecords->where('status', 'Absent')->count();
                                                    $lateCount = $studentRecords->where('status', 'Late')->count();
                                                    $leaveCount = $studentRecords->where('status', 'Leave')->count();
                                                @endphp
                                                <tr>
                                                    <td>{{ $student->user->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="status-badge status-present">✅
                                                            {{ $presentCount }}</span>
                                                        <span class="status-badge status-absent">❌
                                                            {{ $absentCount }}</span>
                                                        <span class="status-badge status-late">⏰
                                                            {{ $lateCount }}</span>
                                                        <span class="status-badge status-leave">📋
                                                            {{ $leaveCount }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No teachers found.
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Switch between tabs
        function switchTab(tabName, button) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            button.classList.add('active');
        }

        // Toggle card expansion
        function toggleCard(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.card-header-icon i');

            content.classList.toggle('show');
            icon.classList.toggle('rotated');
        }

        // Filter students in classes tab
        function filterClassStudents(input) {
            const searchTerm = input.value.toLowerCase();
            const items = document.querySelectorAll('#classes .student-filter-item');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        }

        // Filter students in students tab
        function filterStudents(input) {
            const searchTerm = input.value.toLowerCase();
            const items = document.querySelectorAll('#students .student-filter-item');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        }

        // Filter teachers in teachers tab
        function filterTeachers(input) {
            const searchTerm = input.value.toLowerCase();
            const items = document.querySelectorAll('#teachers .teacher-filter-item');

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        }
    </script>
@endsection
