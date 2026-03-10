@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="fas fa-chalkboard-teacher"></i>
            </span>
            Teacher Assignments
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.teacher-assignments.create') }}" class="btn btn-gradient-primary">
                <i class="fas fa-plus"></i> New Assignment
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.teacher-assignments.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="teacher_id" class="form-label">Teacher</label>
                                <select name="teacher_id" id="teacher_id" class="form-select">
                                    <option value="">All Teachers</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="department" class="form-label">Department</label>
                                <select name="department" id="department" class="form-select">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                                            {{ $department }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="academic_year" class="form-label">Academic Year</label>
                                <select name="academic_year" id="academic_year" class="form-select">
                                    <option value="">All Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="semester" class="form-label">Semester</label>
                                <select name="semester" id="semester" class="form-select">
                                    <option value="">All Semesters</option>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>
                                            {{ $semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-gradient-info w-100">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Assignments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Teacher</th>
                                    <th>Assignment</th>
                                    <th>Department</th>
                                    <th>Academic Year</th>
                                    <th>Semester</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center text-white me-2">
                                                    {{ strtoupper(substr($assignment->teacher->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $assignment->teacher->name }}</div>
                                                    <small class="text-muted">{{ $assignment->teacher->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="assignment-details">
                                                @if($assignment->class)
                                                    <div class="badge bg-info me-1">{{ $assignment->class->class_name }}</div>
                                                @endif
                                                @if($assignment->subject)
                                                    <div class="badge bg-success me-1">{{ $assignment->subject->subject_code }}</div>
                                                @endif
                                                @if($assignment->course)
                                                    <div class="badge bg-warning me-1">{{ $assignment->course->program_code }}</div>
                                                @endif
                                                @if($assignment->department && !$assignment->course)
                                                    <div class="badge bg-secondary me-1">{{ $assignment->department }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $assignment->department ?: 'N/A' }}</td>
                                        <td>{{ $assignment->academic_year }}</td>
                                        <td>{{ $assignment->semester }}</td>
                                        <td>
                                            <span class="badge bg-gradient-primary">{{ $assignment->student_count }} students</span>
                                        </td>
                                        <td>
                                            @if($assignment->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($assignment->status == 'inactive')
                                                <span class="badge bg-warning">Inactive</span>
                                            @else
                                                <span class="badge bg-secondary">Completed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.teacher-assignments.show', $assignment) }}" 
                                                   class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.teacher-assignments.edit', $assignment) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.teacher-assignments.destroy', $assignment) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No teacher assignments found.</p>
                                            <a href="{{ route('admin.teacher-assignments.create') }}" class="btn btn-gradient-primary">
                                                <i class="fas fa-plus"></i> Create First Assignment
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($assignments->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
                            </div>
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        color: white;
    }

    .btn-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #138496);
        color: white;
        border: none;
    }

    .btn-gradient-info:hover {
        background: linear-gradient(45deg, #138496, #117a8b);
        color: white;
    }

    .assignment-details {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    .assignment-details .badge {
        font-size: 0.75rem;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
</style>
@endsection
