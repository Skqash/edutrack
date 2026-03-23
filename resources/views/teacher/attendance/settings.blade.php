@extends('layouts.teacher')

@section('content')
<div class="container-fluid px-2 px-md-3">

    {{-- Page header --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <div>
            <h1 class="h5 fw-bold mb-1">Attendance Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('teacher.attendance') }}">Attendance</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('teacher.attendance.manage', $class->id) }}">{{ $class->class_name }}</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('teacher.attendance.manage', $class->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Class Info --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-bold mb-2">{{ $class->class_name }}</h6>
            <small class="text-muted">
                {{ $class->course->program_name ?? 'N/A' }} • 
                {{ $class->section }} • 
                {{ $class->school_year }}
            </small>
        </div>
    </div>

    {{-- Settings Form --}}
    <form action="{{ route('teacher.attendance.settings.update', $class->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">Class Meeting Configuration</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">
                    Configure the total number of class meetings for each term. This will be used to calculate attendance scores.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Total Meetings - Midterm
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="total_meetings_midterm" 
                            class="form-control @error('total_meetings_midterm') is-invalid @enderror" 
                            value="{{ old('total_meetings_midterm', $class->total_meetings_midterm) }}" 
                            min="1" max="100" required>
                        <small class="text-muted">Number of class meetings in the midterm period</small>
                        @error('total_meetings_midterm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Total Meetings - Final
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="total_meetings_final" 
                            class="form-control @error('total_meetings_final') is-invalid @enderror" 
                            value="{{ old('total_meetings_final', $class->total_meetings_final) }}" 
                            min="1" max="100" required>
                        <small class="text-muted">Number of class meetings in the final period</small>
                        @error('total_meetings_final')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">Attendance Weight in Grade</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">
                    Set the percentage weight of attendance in the overall grade calculation.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Attendance Percentage
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="attendance_percentage" 
                                class="form-control @error('attendance_percentage') is-invalid @enderror" 
                                value="{{ old('attendance_percentage', $class->attendance_percentage) }}" 
                                min="0" max="100" step="0.01" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <small class="text-muted">Weight of attendance in final grade (e.g., 10%)</small>
                        @error('attendance_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Calculation Formula Info --}}
        <div class="card border-0 shadow-sm bg-light mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-calculator text-primary me-2"></i>
                    Attendance Score Calculation Formula
                </h6>
                <div class="formula-box p-3 bg-white rounded border">
                    <code class="text-dark">
                        Attendance Score = (Attendance Count / Total Meetings) × 50 + 50
                    </code>
                </div>
                <div class="mt-3">
                    <p class="mb-2 small"><strong>Example:</strong></p>
                    <ul class="small text-muted mb-0">
                        <li>Student attended 30 out of 34 meetings</li>
                        <li>Calculation: (30 / 34) × 50 + 50 = 94.12</li>
                        <li>Attendance Score: <strong>94.12</strong></li>
                        <li>If attendance weight is 10%, contribution to grade: 94.12 × 0.10 = <strong>9.41 points</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end gap-2 pb-3">
            <a href="{{ route('teacher.attendance.manage', $class->id) }}" class="btn btn-outline-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Save Settings
            </button>
        </div>
    </form>

</div>

<style>
    .formula-box {
        font-size: 1.1rem;
        font-weight: 500;
    }
</style>
@endsection
