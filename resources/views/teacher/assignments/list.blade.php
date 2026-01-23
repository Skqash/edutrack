@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">{{ $class->name }} - Assignments</h1>
                <small class="text-muted">{{ $class->subject->name ?? 'No Subject' }}</small>
            </div>
            <div class="gap-2 d-flex">
                <a href="{{ route('teacher.assignments') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                <a href="{{ route('teacher.assignments.create', $class->id) }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Create Assignment
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($assignments->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                <h5>No Assignments Yet</h5>
                <p class="text-muted">Create your first assignment to get started.</p>
                <a href="{{ route('teacher.assignments.create', $class->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create Assignment
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach ($assignments as $assignment)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">{{ $assignment->title }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">
                                <i class="fas fa-calendar me-2"></i>
                                Due: {{ $assignment->due_date->format('M d, Y h:i A') }}
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-star me-2"></i>
                                Max Score: {{ $assignment->max_score }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-tag me-2"></i>
                                {{ ucfirst($assignment->term) }}
                            </p>
                            @if ($assignment->description)
                                <p class="small mb-3">{{ Str::limit($assignment->description, 100) }}</p>
                            @endif
                            <div class="d-flex gap-2">
                                <a href="{{ route('teacher.assignments.grade', [$class->id, $assignment->id]) }}"
                                    class="btn btn-info btn-sm flex-grow-1">
                                    <i class="fas fa-check-circle me-1"></i> Grade
                                </a>
                                <a href="{{ route('teacher.assignments.edit', [$class->id, $assignment->id]) }}"
                                    class="btn btn-warning btn-sm flex-grow-1">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('teacher.assignments.delete', [$class->id, $assignment->id]) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12">
                {{ $assignments->links() }}
            </div>
        </div>
    @endif

@endsection
