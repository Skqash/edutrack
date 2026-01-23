@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Assignments</h1>
            <small class="text-muted">Create and manage assignments for your classes</small>
        </div>
    </div>

    @if ($classes->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You don't have any classes assigned yet.
        </div>
    @else
        <div class="row">
            @foreach ($classes as $class)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $class->name }}</h5>
                            <p class="card-text text-muted">
                                <small>{{ $class->subject->name ?? 'No Subject' }}</small>
                            </p>
                            <p class="mb-3">
                                <strong>Students:</strong> {{ $class->students->count() }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('teacher.assignments.list', $class->id) }}"
                                    class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-list me-1"></i> View
                                </a>
                                <a href="{{ route('teacher.assignments.create', $class->id) }}"
                                    class="btn btn-success btn-sm flex-grow-1">
                                    <i class="fas fa-plus me-1"></i> Create
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
