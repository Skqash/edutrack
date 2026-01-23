@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-0"><i class="fas fa-pen-fancy me-2"></i>EduTrack Grade Management</h1>
                    <small class="text-muted">Enter and manage student grades using the EduTrack grading system</small>
                </div>
                <a href="{{ route('teacher.students.add') }}" class="btn fw-bold"
                    style="background-color: #00a86b; color: white; border: none;">
                    <i class="fas fa-user-plus me-2"></i>Add Students
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Classes Cards with Term Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                    <h5 class="mb-0" style="color: #1a1a1a;">
                        <i class="fas fa-door-open me-2"></i> Select Class & Term to Enter Grades
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($classes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Class Name</th>
                                        <th class="d-none d-md-table-cell">Level</th>
                                        <th class="d-none d-md-table-cell">Students</th>
                                        <th colspan="2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classes as $class)
                                        <tr>
                                            <td class="ps-3">
                                                <strong>{{ $class->name }}</strong>
                                                @if ($class->section)
                                                    <br><small class="text-muted">Section {{ $class->section }}</small>
                                                @endif
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="badge" style="background-color: #0066cc; color: white;">
                                                    {{ $class->level ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <strong>{{ $class->students()->count() }}</strong> students
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.grades.entry', ['classId' => $class->id, 'term' => 'midterm']) }}"
                                                    class="btn btn-sm fw-bold"
                                                    style="background-color: #0066cc; color: white; border: none;">
                                                    <i class="fas fa-edit me-1"></i> Midterm
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.grades.entry', ['classId' => $class->id, 'term' => 'final']) }}"
                                                    class="btn btn-sm fw-bold"
                                                    style="background-color: #ff8c00; color: white; border: none;">
                                                    <i class="fas fa-edit me-1"></i> Final
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                            <p>No classes assigned yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades Posted -->
    @if ($recentGrades->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #ffffff; border-bottom: 1px solid #e9ecef;">
                        <h5 class="mb-0" style="color: #1a1a1a;">
                            <i class="fas fa-history me-2"></i> Recent Grades Posted
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student</th>
                                        <th class="d-none d-md-table-cell">Subject</th>
                                        <th class="text-center d-none d-lg-table-cell">
                                            <small>Knowledge<br><span class="text-muted">40%</span></small>
                                        </th>
                                        <th class="text-center d-none d-lg-table-cell">
                                            <small>Skills<br><span class="text-muted">50%</span></small>
                                        </th>
                                        <th class="text-center d-none d-lg-table-cell">
                                            <small>Attitude<br><span class="text-muted">10%</span></small>
                                        </th>
                                        <th class="text-center d-none d-lg-table-cell">
                                            <small>Avg Score</small>
                                        </th>
                                        <th class="text-center">Final Grade</th>
                                        <th class="d-none d-md-table-cell">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentGrades as $grade)
                                        <tr>
                                            <td class="ps-3">
                                                <strong>{{ $grade->student->user->name ?? $grade->student->name }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $grade->student->admission_number ?? 'N/A' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                {{ $grade->subject->subject_name ?? 'N/A' }}
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="grade-badge"
                                                    style="background-color: #f0f7ff; color: #0066cc; padding: 6px 10px; border-radius: 5px; font-weight: 500;">
                                                    {{ round($grade->knowledge_score, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="grade-badge"
                                                    style="background-color: #f0fef7; color: #00a86b; padding: 6px 10px; border-radius: 5px; font-weight: 500;">
                                                    {{ round($grade->skills_score, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                <span class="grade-badge"
                                                    style="background-color: #fffaf0; color: #ff8c00; padding: 6px 10px; border-radius: 5px; font-weight: 500;">
                                                    {{ round($grade->attitude_score, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center d-none d-lg-table-cell">
                                                @php
                                                    $avgScore =
                                                        (($grade->knowledge_score ?? 0) +
                                                            ($grade->skills_score ?? 0) +
                                                            ($grade->attitude_score ?? 0)) /
                                                        3;
                                                @endphp
                                                <span class="grade-badge"
                                                    style="background-color: #f5f5f5; color: #666666; padding: 6px 10px; border-radius: 5px; font-weight: 500; border: 1px solid #ddd;">
                                                    {{ round($avgScore, 1) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="grade-badge fw-bold"
                                                    style="padding: 8px 12px; border-radius: 5px; 
                                                @if ($grade->final_grade >= 90) background-color: #d4edda; color: #155724;
                                                @elseif($grade->final_grade >= 80)
                                                    background-color: #cfe2ff; color: #084298;
                                                @elseif($grade->final_grade >= 70)
                                                    background-color: #fff3cd; color: #664d03;
                                                @elseif($grade->final_grade >= 60)
                                                    background-color: #f8d7da; color: #842029;
                                                @else
                                                    background-color: #f8d7da; color: #842029; @endif
                                            ">
                                                    {{ round($grade->final_grade, 1) }}
                                                    ({{ \App\Models\Grade::getGradePoint($grade->final_grade) }})
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small class="text-muted">{{ $grade->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
