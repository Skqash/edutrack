@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-auto">

            </div>
        </div>
    </div>

    <style>
        /* CLASS CARD HEADER */
        .class-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .class-header:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .class-header-info {
            flex: 1;
        }

        .class-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .class-stats {
            font-size: 12px;
            opacity: 0.9;
        }

        .class-actions {
            margin: 0 15px;
        }

        .class-actions form {
            display: inline;
        }

        .class-actions button {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }

        .class-actions button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .toggle-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .toggle-icon.rotated {
            transform: rotate(180deg);
        }

        /* CLASS CONTENT */
        .class-content {
            background: white;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 20px;
            transition: max-height 0.3s ease;
            overflow: hidden;
        }

        .class-content.collapsed {
            display: none;
        }

        /* CARD CONTAINER */
        .class-card {
            background: white;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* STUDENT TABLE */
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 12px;
        }

        .students-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .students-table .student-name {
            font-weight: 500;
            color: #333;
        }

        .grade-value {
            text-align: center;
            font-weight: 600;
        }

        .grade-value.na {
            color: #999;
            font-style: italic;
        }

        .grade-value.excellent {
            color: #28a745;
        }

        .grade-value.verygood {
            color: #20c997;
        }

        .grade-value.good {
            color: #17a2b8;
        }

        .grade-value.satisfactory {
            color: #ffc107;
        }

        .grade-value.needsimprovement {
            color: #dc3545;
        }

        /* ACTION BUTTONS */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 11px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-print {
            background: #667eea;
            color: white;
        }

        .btn-print:hover {
            background: #5568d3;
            transform: scale(1.05);
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #999;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .class-header,
            .class-actions,
            .action-buttons {
                display: none !important;
            }

            .class-content {
                padding: 0;
                border: none;
            }

            .students-table {
                page-break-inside: avoid;
            }
        }
    </style>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- GRADES BY CLASS -->
    <div class="grades-container">
        @forelse($gradesByClass as $classId => $classData)
            <div class="class-card">
                <!-- CLASS HEADER -->
                <div class="class-header" onclick="toggleClassCard(this)">
                    <div class="class-header-info">
                        <div class="class-title">📚 {{ $classData['class']->class_name }}</div>
                        <div class="class-stats">
                            📊 {{ $classData['stats']['total_records'] }} Records |
                            👥 {{ $classData['class']->students->count() }} Students |
                            ⭐ Avg: {{ number_format($classData['stats']['average_grade'], 2) }}
                        </div>
                    </div>
                    <div class="class-actions">
                        <form action="{{ route('admin.grades.export-class', $classData['class']->id) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-small"
                                style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                <i class="fas fa-download"></i> Export Class
                            </button>
                        </form>
                    </div>
                    <div class="toggle-icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <!-- CLASS CONTENT (STUDENTS & GRADES) -->
                <div class="class-content">
                    @php
                        $classStudents = $classData['class']->students;
                    @endphp

                    @if ($classStudents->count() > 0)
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th style="width: 12%;"><i class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                    <th style="width: 25%;"><i class="fas fa-user me-1 text-primary"></i>Student Name</th>
                                    <th style="text-align: center;">Midterm</th>
                                    <th style="text-align: center;">Finals</th>
                                    <th style="text-align: center;">Total Average</th>
                                    <th style="width: 100px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classStudents as $student)
                                    @php
                                        // Get all grades for this student in this class
                                        $studentGrades = $classData['grades']->where('student_id', $student->id);
                                        $midtermAvg =
                                            $studentGrades->count() > 0
                                                ? round($studentGrades->avg('midterm_exam'), 2)
                                                : null;
                                        $finalAvg =
                                            $studentGrades->count() > 0
                                                ? round($studentGrades->avg('final_exam'), 2)
                                                : null;
                                        $totalAvg =
                                            $studentGrades->count() > 0
                                                ? round($studentGrades->avg('grade_point'), 2)
                                                : null;

                                        // Determine color class based on total average
                                        $colorClass = 'na';
                                        if ($totalAvg !== null) {
                                            if ($totalAvg >= 3.5) {
                                                $colorClass = 'excellent';
                                            } elseif ($totalAvg >= 3.0) {
                                                $colorClass = 'verygood';
                                            } elseif ($totalAvg >= 2.5) {
                                                $colorClass = 'good';
                                            } elseif ($totalAvg >= 2.0) {
                                                $colorClass = 'satisfactory';
                                            } else {
                                                $colorClass = 'needsimprovement';
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-info text-white fw-bold"
                                                style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                                {{ $student->student_id ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="student-name">{{ $student->user->name ?? 'N/A' }}</td>
                                        <td class="grade-value {{ $midtermAvg === null ? 'na' : '' }}">
                                            {{ $midtermAvg !== null ? number_format($midtermAvg, 2) : 'N/A' }}
                                        </td>
                                        <td class="grade-value {{ $finalAvg === null ? 'na' : '' }}">
                                            {{ $finalAvg !== null ? number_format($finalAvg, 2) : 'N/A' }}
                                        </td>
                                        <td class="grade-value {{ $colorClass }}">
                                            {{ $totalAvg !== null ? number_format($totalAvg, 2) : 'N/A' }}
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('admin.grades.print-student', [$classData['class']->id, $student->id]) }}"
                                                target="_blank" class="btn-small btn-print">
                                                <i class="fas fa-print"></i> Print
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                <i class="fas fa-info-circle me-2"></i>No classes found with grade records.
            </div>
        @endforelse
    </div>

    <script>
        // Toggle class card expansion/collapse
        function toggleClassCard(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.toggle-icon i');

            content.classList.toggle('collapsed');
            icon.classList.toggle('rotated');
        }
    </script>
@endsection
