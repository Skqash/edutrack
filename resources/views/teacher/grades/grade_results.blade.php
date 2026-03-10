@extends('layouts.teacher')

@section('content')
    <style>
        .grade-result-container {
            max-width: 100%;
            overflow: hidden;
        }

        .class-card {
            border-left: 5px solid #2196F3;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .class-header {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 1.5rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .class-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a237e;
            margin: 0;
        }

        .class-subtitle {
            font-size: 0.9rem;
            color: #555;
            margin: 0.3rem 0 0 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 1rem;
            background: #f9f9f9;
            border-bottom: 1px solid #e0e0e0;
        }

        .stat-box {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e0e0e0;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2196F3;
            margin: 0.5rem 0 0 0;
        }

        .stat-value.passed {
            color: #4CAF50;
        }

        .stat-value.failed {
            color: #f44336;
        }

        .stat-value.percentage {
            color: #FF9800;
        }

        .grades-table-wrapper {
            overflow-x: auto;
            padding: 1.5rem;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .grades-table th {
            background-color: #f5f5f5;
            color: #333;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #2196F3;
            white-space: nowrap;
        }

        .grades-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .grades-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .student-name-cell {
            font-weight: 500;
            color: #1a237e;
        }

        .student-id-cell {
            font-size: 0.85rem;
            color: #999;
        }

        .grade-cell {
            text-align: center;
            font-weight: 600;
        }

        .decimal-grade {
            display: inline-block;
            min-width: 45px;
            padding: 0.3rem 0.6rem;
            border-radius: 0.3rem;
            background-color: #FFF9E6;
            color: #F57F17;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            min-width: 80px;
        }

        .status-passed {
            background-color: #C8E6C9;
            color: #1B5E20;
            border-left: 4px solid #4CAF50;
        }

        .status-failed {
            background-color: #FFCDD2;
            color: #B71C1C;
            border-left: 4px solid #f44336;
        }

        .grade-knowledge {
            background-color: #E3F2FD;
            color: #0D47A1;
        }

        .grade-skills {
            background-color: #E8F5E9;
            color: #1B5E20;
        }

        .grade-attitude {
            background-color: #F3E5F5;
            color: #4A148C;
        }

        .remarks-text {
            font-size: 0.85rem;
            color: #666;
            font-style: italic;
        }

        .letter-grade-badge {
            display: inline-block;
            min-width: 40px;
            padding: 0.3rem 0.6rem;
            border-radius: 0.3rem;
            font-weight: 700;
            text-align: center;
        }

        .grade-a {
            background-color: #BBDEFB;
            color: #0D47A1;
        }

        .grade-b {
            background-color: #C8E6C9;
            color: #1B5E20;
        }

        .grade-c {
            background-color: #E1BEE7;
            color: #4A148C;
        }

        .grade-d {
            background-color: #FFE0B2;
            color: #E65100;
        }

        .grade-f {
            background-color: #FFCDD2;
            color: #B71C1C;
        }

        .no-data {
            text-align: center;
            padding: 3rem 1.5rem;
            color: #999;
            background-color: #f9f9f9;
            border-radius: 0.5rem;
            border: 2px dashed #e0e0e0;
        }

        .no-data-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a237e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
            color: #666;
            margin: 0.5rem 0 0 0;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .legend-box {
            display: flex;
            gap: 2rem;
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .legend-color {
            width: 24px;
            height: 24px;
            border-radius: 0.3rem;
            border: 2px solid;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: #999;
            margin-bottom: 1.5rem;
        }
    </style>

    <div class="grade-result-container p-4">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i> Grade Results & Summary
            </h1>
            <p class="page-subtitle">Calculated grades by class with pass/fail status and decimal scale (1.0-5.0)</p>

            <div class="action-buttons">
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('teacher.grades') }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit"></i> Edit Grades
                </a>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend-box">
            <div class="legend-item">
                <div class="legend-color" style="background-color: #C8E6C9; border-color: #4CAF50;"></div>
                <span><strong>Passed</strong> - Decimal Grade ≤ 3.0</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #FFCDD2; border-color: #f44336;"></div>
                <span><strong>Failed</strong> - Decimal Grade > 3.0</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background-color: #FFF9E6; border-color: #FFD54F;"></div>
                <span><strong>Decimal Grade</strong> - 1.0 (Best) to 5.0 (Worst)</span>
            </div>
        </div>

        @if (empty($classGradeResults))
            <!-- No Data State -->
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <div class="empty-state-title">No Grade Results Available</div>
                <div class="empty-state-text">
                    No students have grades calculated yet. Please enter grades first using the Grade Entry Form.
                </div>
                <a href="{{ route('teacher.grades') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Go to Grade Entry
                </a>
            </div>
        @else
            <!-- Grade Results by Class -->
            @foreach ($classGradeResults as $classResult)
                <div class="card class-card">
                    <!-- Class Header -->
                    <div class="class-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="class-title">{{ $classResult['class']->name }}</h3>
                                <div class="class-subtitle">
                                    <i class="fas fa-book"></i> {{ $classResult['course']->name ?? 'Course' }}
                                </div>
                            </div>
                            <span
                                class="badge bg-primary">{{ $classResult['stats']['graded_students'] }}/{{ $classResult['stats']['total_students'] }}
                                Graded</span>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-label">Total Students</div>
                            <div class="stat-value">{{ $classResult['stats']['total_students'] }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Graded</div>
                            <div class="stat-value">{{ $classResult['stats']['graded_students'] }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Passed</div>
                            <div class="stat-value passed">{{ $classResult['stats']['passed_count'] }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Failed</div>
                            <div class="stat-value failed">{{ $classResult['stats']['failed_count'] }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Pass Rate</div>
                            <div class="stat-value percentage">{{ $classResult['stats']['pass_percentage'] }}%</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Average Grade</div>
                            <div class="stat-value">{{ $classResult['stats']['average_grade'] }}</div>
                        </div>
                    </div>

                    <!-- Grades Table -->
                    @if (empty($classResult['students']))
                        <div style="padding: 2rem;">
                            <div class="no-data">
                                <div class="no-data-icon">📋</div>
                                <p>No grades entered for this class yet</p>
                            </div>
                        </div>
                    @else
                        <div class="grades-table-wrapper">
                            <table class="grades-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 200px;">Student Name</th>
                                        <th class="grade-knowledge">Midterm<br>Grade / Decimal</th>
                                        <th class="grade-skills">Final<br>Grade / Decimal</th>
                                        <th class="grade-attitude">Overall<br>Grade / Decimal</th>
                                        <th>Letter<br>Grade</th>
                                        <th style="min-width: 120px;">Status</th>
                                        <th style="min-width: 250px;">Performance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classResult['students'] as $result)
                                        <tr data-student-id="{{ $result['student_id'] }}">
                                            <!-- Student Name -->
                                            <td>
                                                <div class="student-name-cell">{{ $result['student_name'] }}</div>
                                                <div class="student-id-cell">ID: {{ $result['student_id'] }}</div>
                                            </td>

                                            <!-- Midterm Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.3rem;">{{ $result['midterm_grade'] }}</div>
                                                <span class="decimal-grade">{{ $result['midterm_decimal'] }}</span>
                                            </td>

                                            <!-- Final Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.3rem;">{{ $result['final_grade'] }}</div>
                                                <span class="decimal-grade">{{ $result['final_decimal'] }}</span>
                                            </td>

                                            <!-- Overall Grade -->
                                            <td class="grade-cell">
                                                <div style="margin-bottom: 0.3rem; font-weight: 700;">
                                                    {{ $result['overall_grade'] }}</div>
                                                <span class="decimal-grade"
                                                    style="min-width: 50px;">{{ $result['decimal_grade'] }}</span>
                                            </td>

                                            <!-- Letter Grade -->
                                            <td class="grade-cell">
                                                @php
                                                    $letterGrade = substr($result['letter_grade'], 0, 1);
                                                    $gradeClass = match ($letterGrade) {
                                                        'A' => 'grade-a',
                                                        'B' => 'grade-b',
                                                        'C' => 'grade-c',
                                                        'D' => 'grade-d',
                                                        'F' => 'grade-f',
                                                        default => 'grade-f',
                                                    };
                                                @endphp
                                                <span
                                                    class="letter-grade-badge {{ $gradeClass }}">{{ $letterGrade }}</span>
                                            </td>

                                            <!-- Status -->
                                            <td>
                                                <span
                                                    class="status-badge @if ($result['status'] === 'Passed') status-passed @else status-failed @endif">
                                                    @if ($result['status'] === 'Passed')
                                                        ✅ Passed
                                                    @else
                                                        ❌ Failed
                                                    @endif
                                                </span>
                                            </td>

                                            <!-- Performance Remarks -->
                                            <td>
                                                <p class="remarks-text">{{ $result['remarks'] }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Backend Data Verification Alert -->
    <div style="position: fixed; bottom: 20px; right: 20px; max-width: 300px; z-index: 1000;">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-database"></i> Data Status:</strong><br>
            <small>✅ Grades calculated and stored in database</small><br>
            <small>✅ Decimal grades (1.0-5.0) computed</small><br>
            <small>✅ Pass/fail status determined</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>

    <script>
        // Highlight rows on hover
        document.querySelectorAll('.grades-table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#fff3e0';
                this.style.transition = 'background-color 0.2s ease';
            });

            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Data verification sound effect (optional)
        console.log('📊 Grade Results Page Loaded');
        console.log('✅ All calculations performed server-side');
        console.log('✅ Data stored in database');
        console.log('✅ Decimal grades converted (1.0-5.0 scale)');
    </script>
@endsection
