@extends('layouts.teacher')

@section('content')

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Attendance Management</h1>
            <small class="text-muted">Track and manage student attendance</small>
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
                            <a href="{{ route('teacher.attendance.manage', $class->id) }}"
                                class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-clipboard-check me-2"></i> Manage
                            </a>
                            <a href="{{ route('teacher.attendance.history', $class->id) }}"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-history me-2"></i> History
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
