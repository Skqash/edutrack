@extends('layouts.teacher')

@section('content')
    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
        }

        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 102, 204, 0.1);
            transform: translateY(-2px);
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-top: 4px solid #0066cc;
        }

        .feature-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h2 fw-bold mb-1">Welcome, {{ auth()->user()->name ?? 'Teacher' }}!</h1>
                    <small class="text-muted d-block">{{ now()->format('l, F j, Y') }}</small>
                </div>
                @if ($myClasses && $myClasses->count() > 0)
                    <a href="{{ route('teacher.grades.entry.enhanced', $myClasses->first()->id) }}"
                        class="btn btn-primary d-none d-md-inline-block">
                        <i class="fas fa-keyboard me-2"></i> Start Grading
                    </a>
                @else
                    <button class="btn btn-primary d-none d-md-inline-block" disabled>
                        <i class="fas fa-keyboard me-2"></i> Start Grading
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Info Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-light border-start border-primary border-4 mb-0">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <i class="fas fa-rocket fa-2x text-primary"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1 text-dark fw-bold">Welcome to Enhanced Grading System v2.0</h6>
                        <small class="text-muted">67% faster grading • Real-time analytics • Full customization</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Classes</h6>
                            <h3 class="mb-0 text-primary">{{ $myClasses ? $myClasses->count() : 0 }}</h3>
                        </div>
                        <i class="fas fa-door-open fa-2x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Students</h6>
                            <h3 class="mb-0" style="color: #00a86b;">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-25" style="color: #00a86b;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Grades Posted</h6>
                            <h3 class="mb-0 text-primary">{{ $gradesPosted ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-star fa-2x opacity-25" style="color: #ff8c00;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Pending Tasks</h6>
                            <h3 class="mb-0 text-muted">{{ $pendingTasks ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-tasks fa-2x text-muted opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Cards -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-md-4">
            <div class="card feature-card h-100 shadow-sm" style="border-top-color: #0066cc;">
                <div class="card-body text-center">
                    <div class="mb-3" style="font-size: 2.5rem; color: #0066cc;">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h5 class="card-title text-dark">Flexible Quiz Configuration</h5>
                    <p class="card-text text-muted small mb-3">
                        Set 1-10 quizzes, customize component weights, and adjust grading scales per class
                    </p>
                    @if ($myClasses && $myClasses->count() > 0)
                        <a href="{{ route('teacher.assessment.configure', $myClasses->first()->id) }}"
                            class="btn btn-sm fw-bold text-white" style="background-color: #0066cc; border: none;">
                            <i class="fas fa-gear me-1"></i> Configure Now
                        </a>
                    @else
                        <button class="btn btn-sm fw-bold text-white" disabled
                            style="background-color: #0066cc; border: none;">
                            <i class="fas fa-gear me-1"></i> No Classes
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card feature-card h-100 shadow-sm" style="border-top-color: #00a86b;">
                <div class="card-body text-center">
                    <div class="mb-3" style="font-size: 2.5rem; color: #00a86b;">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h5 class="card-title text-dark">Grade Entry Form</h5>
                    <p class="card-text text-muted small mb-3">
                        Professional grade entry with dynamic quiz columns, auto-calculations & Excel format
                    </p>
                    @if ($myClasses && $myClasses->count() > 0)
                        <a href="{{ route('teacher.grades.entry.enhanced', $myClasses->first()->id) }}"
                            class="btn btn-sm fw-bold text-white" style="background-color: #00a86b; border: none;">
                            <i class="fas fa-edit me-1"></i> Enter Grades
                        </a>
                    @else
                        <button class="btn btn-sm fw-bold text-white" disabled
                            style="background-color: #00a86b; border: none;">
                            <i class="fas fa-edit me-1"></i> No Classes
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card feature-card h-100 shadow-sm" style="border-top-color: #ff8c00;">
                <div class="card-body text-center">
                    <div class="mb-3" style="font-size: 2.5rem; color: #ff8c00;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h5 class="card-title text-dark">Analytics Dashboard</h5>
                    <p class="card-text text-muted small mb-3">
                        View grade distribution, KSA breakdown, performance metrics & trends
                    </p>
                    @if ($myClasses && $myClasses->count() > 0)
                        <a href="{{ route('teacher.grades.analytics', $myClasses->first()->id) }}"
                            class="btn btn-sm fw-bold text-white" style="background-color: #ff8c00; border: none;">
                            <i class="fas fa-chart-bar me-1"></i> View Analytics
                        </a>
                    @else
                        <button class="btn btn-sm fw-bold text-white" disabled
                            style="background-color: #ff8c00; border: none;">
                            <i class="fas fa-chart-bar me-1"></i> No Classes
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Section: Classes & KSA Info -->
    <div class="row mb-4 g-3">
        <!-- Left: My Classes -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary fw-bold">
                            <i class="fas fa-door-open me-2"></i> My Classes
                        </h5>
                        <a href="{{ route('teacher.classes') }}" class="btn btn-sm fw-bold text-white"
                            style="background-color: #0066cc; border: none;">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Class</th>
                                    <th>Level</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($myClasses && $myClasses->count() > 0)
                                    @forelse($myClasses as $class)
                                        <tr>
                                            <td class="ps-3">
                                                <strong
                                                    class="text-primary d-block">{{ $class->class_name ?? 'N/A' }}</strong>
                                                <small class="text-muted">{{ $class->subject->name ?? 'Subject' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge text-white" style="background-color: #0066cc;">
                                                    {{ $class->section ?? 'Year 1' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('teacher.assessment.configure', $class->id) }}"
                                                        class="btn fw-bold text-white"
                                                        style="background-color: #0066cc; border: none;"
                                                        title="Configure">
                                                        <i class="fas fa-sliders-h"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.grades.entry.enhanced', $class->id) }}"
                                                        class="btn fw-bold text-white"
                                                        style="background-color: #00a86b; border: none;" title="Entry">
                                                        <i class="fas fa-keyboard"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.grades.analytics', $class->id) }}"
                                                        class="btn fw-bold text-white"
                                                        style="background-color: #ff8c00; border: none;"
                                                        title="Analytics">
                                                        <i class="fas fa-chart-pie"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                <small>No classes assigned yet</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            <small>No classes available</small>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: KSA Grading System Info -->
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-star me-2"></i> KSA Grading System
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Comprehensive grading based on three key components:</p>

                    <!-- Knowledge -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 text-primary small fw-bold">
                                <i class="fas fa-brain me-2"></i> Knowledge
                            </h6>
                            <small class="text-muted"><strong>40%</strong></small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 40%; background-color: #0066cc;"></div>
                        </div>
                        <small class="text-muted d-block mt-1">Quizzes (40%) + Exams (60%)</small>
                    </div>

                    <!-- Skills -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold small" style="color: #00a86b;">
                                <i class="fas fa-tools me-2"></i> Skills
                            </h6>
                            <small class="text-muted"><strong>50%</strong></small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 50%; background-color: #00a86b;"></div>
                        </div>
                        <small class="text-muted d-block mt-1">Output (40%) + Class Part (30%) + Activities (15%) +
                            Assignments (15%)</small>
                    </div>

                    <!-- Attitude -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold small" style="color: #ff8c00;">
                                <i class="fas fa-handshake me-2"></i> Attitude
                            </h6>
                            <small class="text-muted"><strong>10%</strong></small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 10%; background-color: #ff8c00;"></div>
                        </div>
                        <small class="text-muted d-block mt-1">Behavior (50%) + Awareness (50%)</small>
                    </div>

                    <hr class="my-3">
                    <div class="alert alert-light mb-0 p-3 rounded">
                        <small class="fw-bold text-dark">Final Grade = (K × 0.40) + (S × 0.50) + (A × 0.10)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades Posted -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white fw-bold" style="background-color: #0066cc; border: none;">
                    <i class="fas fa-history me-2"></i> Recent Grades Posted
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Student</th>
                                    <th>Class</th>
                                    <th class="d-none d-md-table-cell text-center">Knowledge</th>
                                    <th class="d-none d-md-table-cell text-center">Skills</th>
                                    <th class="d-none d-md-table-cell text-center">Attitude</th>
                                    <th class="d-none d-lg-table-cell text-center">Average</th>
                                    <th class="text-center">Final Grade</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($recentGrades && $recentGrades->count() > 0)
                                    @foreach ($recentGrades as $grade)
                                        <tr>
                                            <td class="ps-3">{{ $grade->student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $grade->class->class_name ?? 'N/A' }}</td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $kPoint = \App\Models\Grade::getGradePoint($grade->knowledge_score);
                                                    $kColor = \App\Models\Grade::getGradeColor($grade->knowledge_score);
                                                @endphp
                                                <span class="badge bg-{{ $kColor }}">
                                                    {{ $kPoint }} ({{ round($grade->knowledge_score, 1) }})
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $sPoint = \App\Models\Grade::getGradePoint($grade->skills_score);
                                                    $sColor = \App\Models\Grade::getGradeColor($grade->skills_score);
                                                @endphp
                                                <span class="badge bg-{{ $sColor }}">
                                                    {{ $sPoint }} ({{ round($grade->skills_score, 1) }})
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $aPoint = \App\Models\Grade::getGradePoint($grade->attitude_score);
                                                    $aColor = \App\Models\Grade::getGradeColor($grade->attitude_score);
                                                @endphp
                                                <span class="badge bg-{{ $aColor }}">
                                                    {{ $aPoint }} ({{ round($grade->attitude_score, 1) }})
                                                </span>
                                            </td>
                                            <td class="d-none d-lg-table-cell text-center">
                                                @php
                                                    $avgScore =
                                                        (($grade->knowledge_score ?? 0) +
                                                            ($grade->skills_score ?? 0) +
                                                            ($grade->attitude_score ?? 0)) /
                                                        3;
                                                @endphp
                                                <span class="badge"
                                                    style="background-color: #f5f5f5; color: #666666; border: 1px solid #ddd;">
                                                    {{ round($avgScore, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $fPoint = \App\Models\Grade::getGradePoint($grade->final_grade);
                                                    $fColor = \App\Models\Grade::getGradeColor($grade->final_grade);
                                                @endphp
                                                <span class="badge bg-{{ $fColor }} fw-bold">
                                                    {{ $fPoint }} ({{ round($grade->final_grade, 1) }})
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small>{{ $grade->updated_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            <small>No grades posted yet</small>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('teacher.grades') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i> View All Grades
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
