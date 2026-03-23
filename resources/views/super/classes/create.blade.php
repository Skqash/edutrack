@extends('layouts.super')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-success text-white me-2">
                    <i class="fas fa-plus-circle"></i>
                </span>
                Create New Class
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('super.classes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Classes
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <form action="{{ route('super.classes.store') }}" method="POST" id="classForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="class_name" class="form-label">
                                            <i class="fas fa-door-open"></i> Class Name *
                                        </label>
                                        <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                                            id="class_name" name="class_name" placeholder="e.g., BSIT-1A, BSCS-2B"
                                            value="{{ old('class_name') }}" required>
                                        @error('class_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="year" class="form-label">
                                            <i class="fas fa-layer-group"></i> Year Level *
                                        </label>
                                        <select name="year" id="year"
                                            class="form-select @error('year') is-invalid @enderror" required>
                                            <option value="">Select year level...</option>
                                            <option value="1" {{ old('year') == '1' ? 'selected' : '' }}>1st Year
                                            </option>
                                            <option value="2" {{ old('year') == '2' ? 'selected' : '' }}>2nd Year
                                            </option>
                                            <option value="3" {{ old('year') == '3' ? 'selected' : '' }}>3rd Year
                                            </option>
                                            <option value="4" {{ old('year') == '4' ? 'selected' : '' }}>4th Year
                                            </option>
                                        </select>
                                        @error('year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="section" class="form-label">
                                            <i class="fas fa-tag"></i> Section *
                                        </label>
                                        <input type="text" class="form-control @error('section') is-invalid @enderror"
                                            id="section" name="section" placeholder="e.g., A, B, C"
                                            value="{{ old('section') }}" required>
                                        @error('section')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="capacity" class="form-label">
                                            <i class="fas fa-users"></i> Capacity *
                                        </label>
                                        <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                            id="capacity" name="capacity" placeholder="e.g., 60"
                                            value="{{ old('capacity') }}" min="10" required>
                                        @error('capacity')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-toggle-on"></i> Status *
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-graduation-cap"></i> Academic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="course_id" class="form-label">
                                            <i class="fas fa-book"></i> Course/Program *
                                        </label>
                                        <select name="course_id" id="course_id"
                                            class="form-select @error('course_id') is-invalid @enderror" required>
                                            <option value="">Select course...</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    data-department="{{ $course->department ?? '' }}"
                                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                    {{ $course->program_code }} - {{ $course->program_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="teacher_id" class="form-label">
                                            <i class="fas fa-chalkboard-user"></i> Class Teacher *
                                        </label>
                                        <select class="form-select @error('teacher_id') is-invalid @enderror"
                                            id="teacher_id" name="teacher_id" required>
                                            <option value="">Select a teacher...</option>
                                            @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->id }}"
                                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="academic_year" class="form-label">
                                            <i class="fas fa-calendar-alt"></i> Academic Year *
                                        </label>
                                        <select name="academic_year" id="academic_year"
                                            class="form-select @error('academic_year') is-invalid @enderror" required>
                                            @foreach ($academicYears as $year)
                                                <option value="{{ $year }}"
                                                    {{ old('academic_year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('academic_year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="semester" class="form-label">
                                            <i class="fas fa-book-open"></i> Semester *
                                        </label>
                                        <select name="semester" id="semester"
                                            class="form-select @error('semester') is-invalid @enderror" required>
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester }}"
                                                    {{ old('semester') == $semester ? 'selected' : '' }}>
                                                    Semester {{ $semester }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('semester')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="school_year" class="form-label">
                                            <i class="fas fa-school"></i> School Year
                                        </label>
                                        <input type="text"
                                            class="form-control @error('school_year') is-invalid @enderror"
                                            id="school_year" name="school_year" value="{{ old('school_year') }}"
                                            placeholder="e.g., 2026-2027">
                                        @error('school_year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Create Class
                                </button>
                                <a href="{{ route('super.classes.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
