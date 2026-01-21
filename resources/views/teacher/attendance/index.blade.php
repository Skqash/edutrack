@extends('layouts.teacher')

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 fw-bold mb-0">Attendance Management</h1>
        <small class="text-muted">Track and manage student attendance</small>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-square fa-3x text-primary mb-3 opacity-50"></i>
                <h5>Attendance Module Coming Soon</h5>
                <p class="text-muted">This feature is being developed to help you track and manage student attendance.</p>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
