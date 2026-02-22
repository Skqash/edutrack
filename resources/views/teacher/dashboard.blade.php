@extends('layouts.teacher')

@section('content')
    <style>
        /* Professional dashboard styling with minimal colors */
        body {
            background-color: #f8f9fa;
        }

        .stat-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .stat-card h2 {
            font-size: 2rem;
            letter-spacing: -1px;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: none;
            background: #ffffff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .feature-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .feature-card .card-body {
            padding: 1.75rem 1.5rem;
        }

        .feature-card .card-title {
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }

        /* Remove excessive border colors from components */
        .alert-light {
            border: none !important;
            background-color: #f8f9fa !important;
        }

        .card {
            border: none !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .card:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef !important;
            font-weight: 600;
            padding: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Clean table styling */
        .table {
            color: #333 !important;
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f8f9fa !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #212529 !important;
        }

        .btn-group-sm .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.875rem;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Header enhancement */
        .header-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        /* Quick banner enhancement */
        .quick-banner {
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-banner i {
            opacity: 0.3;
        }

        .quick-banner h6,
        .quick-banner small {
            color: white !important;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #0066cc, transparent);
            border-radius: 2px;
        }

        /* 5-column stat cards layout */
        .col-lg-2-4 {
            flex: 0 0 calc(20% - 0.6rem);
            max-width: calc(20% - 0.6rem);
        }

        @media (max-width: 1199.98px) {
            .col-lg-2-4 {
                flex: 0 0 calc(50% - 0.75rem);
                max-width: calc(50% - 0.75rem);
            }
        }

        @media (max-width: 767.98px) {
            .col-lg-2-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .stat-card .card-body {
            padding: 1rem;
        }

        .stat-card h2 {
            font-size: 1.75rem;
        }

        .stat-card i {
            font-size: 2.5rem !important;
        }
    </style>

    <!-- Header Section -->
    <div class="header-section">
        <div class="row mb-0">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="h2 fw-bold mb-1">Welcome, {{ auth()->user()->name ?? 'Teacher' }}! 👋</h1>
                        <small class="text-muted d-block">{{ now()->format('l, F j, Y') }}</small>
                    </div>
                    @if ($myClasses && $myClasses->count() > 0)
                        <div class="btn-group d-none d-md-inline-block">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-keyboard me-2"></i> Start Grading
                            </button>
                            <ul class="dropdown-menu">
                                @foreach($myClasses as $c)
                                    <li class="dropdown-header">{{ $c->class_name ?? 'Class' }}</li>
                                    <li><a class="dropdown-item" href="{{ route('teacher.grades.entry', $c->id) }}?term=midterm">Midterm — {{ $c->class_name }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('teacher.grades.entry', $c->id) }}?term=final">Final — {{ $c->class_name }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <button class="btn btn-primary d-none d-md-inline-block" disabled>
                            <i class="fas fa-keyboard me-2"></i> Start Grading
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="quick-banner">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <i class="fas fa-rocket fa-2x"></i>
                    </div>
                    <div class="col">
                        <h6 class="mb-1 fw-bold">Enhanced Grading System Ready</h6>
                        <small>Your dashboard is fully configured • All systems operational • Ready for grading</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Enhanced with gradients and improved spacing -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-sm-6 col-lg-2-4">
            <div class="card stat-card h-100" style="border-left: 4px solid #0066cc;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small fw-semibold">Classes</h6>
                            <h2 class="mb-0 text-primary fw-bold">{{ $myClasses ? $myClasses->count() : 0 }}</h2>
                        </div>
                        <i class="fas fa-door-open fa-3x text-primary opacity-15"></i>
                    </div>
                    <small class="text-muted d-block mt-2">Active classes assigned</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2-4">
            <div class="card stat-card h-100" style="border-left: 4px solid #6f42c1;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small fw-semibold">Courses</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6f42c1;">{{ $myCourses ? $myCourses->count() : 0 }}</h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-15" style="color: #6f42c1;"></i>
                    </div>
                    <small class="text-muted d-block mt-2">Total courses</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2-4">
            <div class="card stat-card h-100" style="border-left: 4px solid #00a86b;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small fw-semibold">Students</h6>
                            <h2 class="mb-0 fw-bold" style="color: #00a86b;">{{ $totalStudents ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-15" style="color: #00a86b;"></i>
                    </div>
                    <small class="text-muted d-block mt-2">Total enrolled</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2-4">
            <div class="card stat-card h-100" style="border-left: 4px solid #ff8c00;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small fw-semibold">Grades Posted</h6>
                            <h2 class="mb-0 fw-bold" style="color: #ff8c00;">{{ $gradesPosted ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-star fa-3x opacity-15" style="color: #ff8c00;"></i>
                    </div>
                    <small class="text-muted d-block mt-2">Recently added</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-2-4">
            <div class="card stat-card h-100" style="border-left: 4px solid #6c757d;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small fw-semibold">Attendance Rate</h6>
                            <h2 class="mb-0 fw-bold text-muted">
                                {{ round($totalStudents > 0 ? ($gradesPosted / $totalStudents) * 100 : 0, 0) }}%</h2>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-15" style="color: #6c757d;"></i>
                    </div>
                    <small class="text-muted d-block mt-2">Data completeness</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Cards - 4 Core Functions (Optimized Layout) -->
    <div class="row mb-4 g-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card feature-card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column">
                    <div class="mb-3" style="font-size: 2.5rem; color: #495057;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h5 class="card-title text-dark fw-bold mb-2">Create Class</h5>
                    <p class="card-text text-muted small mb-auto flex-grow-1">
                        Set up a new class and configure assessment parameters
                    </p>
                    <button class="btn btn-sm fw-bold text-white mt-3" style="background-color: #495057; border: none;"
                        data-bs-toggle="modal" data-bs-target="#createClassModal">
                        <i class="fas fa-plus me-1"></i> Create
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card feature-card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column">
                    <div class="mb-3" style="font-size: 2.5rem; color: #0066cc;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h5 class="card-title text-dark fw-bold mb-2">Add Students</h5>
                    <p class="card-text text-muted small mb-auto flex-grow-1">
                        Enroll students manually or via bulk import
                    </p>
                    <button class="btn btn-sm fw-bold text-white mt-3" style="background-color: #0066cc; border: none;"
                        data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-plus me-1"></i> Add
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card feature-card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column">
                    <div class="mb-3" style="font-size: 2.5rem; color: #28a745;">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h5 class="card-title text-dark fw-bold mb-2">Enter Grades</h5>
                    <p class="card-text text-muted small mb-auto flex-grow-1">
                        Professional grade entry with dynamic calculations
                    </p>
                    <a href="{{ route('teacher.grades') }}" class="btn btn-sm fw-bold text-white mt-3"
                        style="background-color: #28a745; border: none;">
                        <i class="fas fa-edit me-1"></i> Enter
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card feature-card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column">
                    <div class="mb-3" style="font-size: 2.5rem; color: #0066cc;">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h5 class="card-title text-dark fw-bold mb-2">Configure</h5>
                    <p class="card-text text-muted small mb-auto flex-grow-1">
                        Customize grading scales and components per class
                    </p>
                    @if ($myClasses && $myClasses->count() > 0)
                        <a href="{{ route('teacher.assessment.configure', $myClasses->first()->id) }}"
                            class="btn btn-sm fw-bold text-white mt-3" style="background-color: #0066cc; border: none;">
                            <i class="fas fa-gear me-1"></i> Setup
                        </a>
                    @else
                        <button class="btn btn-sm fw-bold text-white mt-3" disabled
                            style="background-color: #0066cc; border: none;">
                            <i class="fas fa-gear me-1"></i> No Classes
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
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">
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
                                                <small class="text-muted">{{ $class->course->course_name ?? 'Course' }}</small>
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
                                                    <a href="{{ route('teacher.grades.entry', $class->id) }}?term=midterm"
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
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-star me-2"></i> KSA Grading System
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Comprehensive grading based on three key components:</p>

                    <!-- Knowledge -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 text-dark small fw-bold">
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
                            <h6 class="mb-0 fw-bold small text-dark">
                                <i class="fas fa-tools me-2"></i> Skills
                            </h6>
                            <small class="text-muted"><strong>50%</strong></small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 50%; background-color: #28a745;"></div>
                        </div>
                        <small class="text-muted d-block mt-1">Output (40%) + Class Part (30%) + Activities (15%) +
                            Assignments (15%)</small>
                    </div>

                    <!-- Attitude -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold small text-dark">
                                <i class="fas fa-handshake me-2"></i> Attitude
                            </h6>
                            <small class="text-muted"><strong>10%</strong></small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 10%; background-color: #6c757d;"></div>
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

    <!-- Courses Section -->
    <div class="row mb-4 g-3">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-bold">
                            <i class="fas fa-book me-2"></i> My Courses
                        </h5>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-sm fw-bold text-white"
                            style="background-color: #0066cc; border: none;">
                            <i class="fas fa-plus me-1"></i> Add Course
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Course Name</th>
                                    <th class="d-none d-md-table-cell">Code</th>
                                    <th class="d-none d-lg-table-cell">Department</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($myCourses && $myCourses->count() > 0)
                                    @forelse($myCourses as $course)
                                        <tr>
                                            <td class="ps-3">
                                                <strong class="text-primary d-block">{{ $course->course_name ?? 'N/A' }}</strong>
                                                <small class="text-muted">{{ $course->description ?? 'No description' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="badge text-dark" style="background-color: #e9ecef;">
                                                    {{ $course->course_code ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <small class="text-muted">{{ $course->department ?? 'General' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                                                        class="btn fw-bold text-white"
                                                        style="background-color: #0066cc; border: none;"
                                                        title="Edit Course">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display: inline;" 
                                                        onsubmit="return confirm('Are you sure you want to delete this course?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm fw-bold text-white"
                                                            style="background-color: #dc3545; border: none;"
                                                            title="Delete Course">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                <small>No courses assigned yet</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            <small>No courses available</small>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades Posted Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="section-title mb-3">
                <i class="fas fa-history me-2"></i> Recent Classes Graded
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    @if ($recentGrades && $recentGrades->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 fw-bold">Class</th>
                                        <th class="fw-bold">Course</th>
                                        <th class="text-center fw-bold">Students</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Knowledge</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Skills</th>
                                        <th class="d-none d-md-table-cell text-center fw-bold">Avg Attitude</th>
                                        <th class="text-center fw-bold">Avg Grade</th>
                                        <th class="text-center fw-bold">Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentGrades as $classData)
                                        <tr>
                                            <td class="ps-4 fw-semibold">
                                                <a href="{{ route('teacher.classes.show', $classData->class_id) }}" class="text-decoration-none text-dark">
                                                    {{ $classData->class_name ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td><small class="text-muted">{{ $classData->course_name ?? 'N/A' }}</small></td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $classData->student_count }}</span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $kPoint = \App\Models\Grade::getGradePoint($classData->avg_knowledge);
                                                    $kColor = \App\Models\Grade::getGradeColor($classData->avg_knowledge);
                                                @endphp
                                                <span class="badge bg-{{ $kColor }}">
                                                    {{ round($classData->avg_knowledge, 1) }}
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $sPoint = \App\Models\Grade::getGradePoint($classData->avg_skills);
                                                    $sColor = \App\Models\Grade::getGradeColor($classData->avg_skills);
                                                @endphp
                                                <span class="badge bg-{{ $sColor }}">
                                                    {{ round($classData->avg_skills, 1) }}
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell text-center">
                                                @php
                                                    $aPoint = \App\Models\Grade::getGradePoint($classData->avg_attitude);
                                                    $aColor = \App\Models\Grade::getGradeColor($classData->avg_attitude);
                                                @endphp
                                                <span class="badge bg-{{ $aColor }}">
                                                    {{ round($classData->avg_attitude, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $fPoint = \App\Models\Grade::getGradePoint($classData->avg_final_grade);
                                                    $fColor = \App\Models\Grade::getGradeColor($classData->avg_final_grade);
                                                @endphp
                                                <span class="badge bg-{{ $fColor }} fw-bold">
                                                    {{ round($classData->avg_final_grade, 2) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted d-block">
                                                    @if($classData->updated_at)
                                                        {{ $classData->updated_at->diffForHumans() }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i><br>
                            <small>No grades posted yet • Start by creating a class and adding students</small>
                        </div>
                    @endif
                </div>
                @if ($recentGrades && $recentGrades->count() > 0)
                    <div class="card-footer bg-light text-center">
                        <a href="{{ route('teacher.grades') }}" class="btn btn-sm btn-outline-primary fw-bold">
                            <i class="fas fa-list me-1"></i> View All Grades
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    @include('teacher.components.add-student-modal')

    <!-- Create Class Modal -->
    <div class="modal fade" id="createClassModal" tabindex="-1" role="dialog" aria-labelledby="createClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createClassModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Create New Class
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="{{ route('teacher.classes.store') }}" method="POST" id="createClassForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Class Name -->
                            <div class="col-md-12">
                                <label for="className" class="form-label fw-bold">
                                    <i class="fas fa-book me-2"></i>Class Name
                                </label>
                                <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                                    id="className" name="class_name" placeholder="e.g., Object Oriented Programming"
                                    required>
                                @error('class_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Course Selection -->
                            <div class="col-md-6">
                                <label for="courseId" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2"></i>Course
                                </label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="courseId"
                                    name="course_id" required>
                                    <option value="">Select Course</option>
                                    @if (!empty($myClasses))
                                        @foreach ($myClasses as $class)
                                            <option value="{{ $class->course_id }}">{{ $class->course->course_name ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Year -->
                            <div class="col-md-6">
                                <label for="year" class="form-label fw-bold">
                                    <i class="fas fa-graduation-cap me-2"></i>Year
                                </label>
                                <select class="form-select @error('year') is-invalid @enderror" id="year"
                                    name="year" required>
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                                @error('year')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Section -->
                            <div class="col-md-6">
                                <label for="section" class="form-label fw-bold">
                                    <i class="fas fa-users me-2"></i>Section
                                </label>
                                <select class="form-select @error('section') is-invalid @enderror" id="section"
                                    name="section" required>
                                    <option value="">Select Section</option>
                                    <option value="A">Section A</option>
                                    <option value="B">Section B</option>
                                    <option value="C">Section C</option>
                                    <option value="D">Section D</option>
                                    <option value="E">Section E</option>
                                </select>
                                @error('section')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="col-md-6">
                                <label for="capacity" class="form-label fw-bold">
                                    <i class="fas fa-chair me-2"></i>Class Capacity
                                </label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                    id="capacity" name="capacity" placeholder="e.g., 50" min="1" max="100"
                                    required>
                                @error('capacity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-file-alt me-2"></i>Description (Optional)
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Add notes about this class..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" style="background-color: #667eea; border: none;">
                            <i class="fas fa-plus me-2"></i>Create Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
