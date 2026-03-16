@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold mb-0">{{ $class->class_name }}</h1>
                    <small class="text-muted">{{ $class->class_level }} · Section: {{ $class->section ?? 'N/A' }}</small>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Students</h6>
                            <h3 class="mb-0">{{ $class->students->count() }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Capacity</h6>
                            <h3 class="mb-0">{{ $class->capacity }}</h3>
                        </div>
                        <i class="fas fa-door-open fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Course</h6>
                            <h5 class="mb-0">{{ $class->course->course_name ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-book fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
            <div class="card stat-card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-white-50 mb-1">Status</h6>
                            <h5 class="mb-0">
                                <span class="badge bg-light text-dark">
                                    {{ $class->status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </h5>
                        </div>
                        <i class="fas fa-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i> Students in This Class</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-primary">{{ $class->students->count() }} students</span>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#studentAssignmentModal">
                            <i class="fas fa-user-plus me-1"></i> Add / Remove Students
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($class->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width: 15%;"><i
                                                class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                        <th style="width: 30%;"><i class="fas fa-user me-1 text-primary"></i>Name</th>
                                        <th class="d-none d-md-table-cell" style="width: 20%;"><i
                                                class="fas fa-building me-1 text-primary"></i>Year / Section</th>
                                        <th class="d-none d-md-table-cell" style="width: 20%;"><i
                                                class="fas fa-envelope me-1 text-primary"></i>Email</th>
                                        <th class="d-none d-lg-table-cell" style="width: 15%;"><i
                                                class="fas fa-phone me-1 text-primary"></i>Phone</th>
                                        <th class="text-center" style="width: 12%;"><i
                                                class="fas fa-cog me-1 text-primary"></i>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $student)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge bg-info text-white fw-bold"
                                                    style="font-size: 0.85rem; letter-spacing: 0.5px;">{{ $student->student_id ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $student->user->name ?? $student->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $student->status ?? 'Active' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small><strong>Year {{ $student->year }}</strong>, Section
                                                    <strong>{{ $student->section }}</strong></small>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small>{{ $student->user->email ?? 'N/A' }}</small>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                <small>{{ $student->user->phone ?? 'N/A' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info"
                                                        title="View Student">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Remove from class"
                                                        onclick="removeStudent({{ $student->id }})">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i>
                            <p>No students enrolled in this class yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.classes.partials.student-assignment-modal', [
        'classId' => $class->id,
        'className' => $class->class_name,
        'courses' => $courses,
        'departments' => $departments,
    ])

@endsection
