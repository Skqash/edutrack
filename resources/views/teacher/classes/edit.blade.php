@extends('layouts.teacher')

@section('title', 'Edit Class')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 text-dark fw-bold">
                            <i class="fas fa-edit me-2 text-primary"></i>Edit Class
                        </h1>
                        <p class="text-muted mb-0">Update class information and settings</p>
                    </div>
                    <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 text-dark">Class Details</h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle me-2"></i>Validation Errors</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('teacher.classes.update', $class->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Class Name -->
                            <div class="mb-4">
                                <label for="class_name" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-bookmark text-primary me-2"></i>Class Name
                                </label>
                                <input type="text" id="class_name" name="class_name" class="form-control form-control-lg @error('class_name') is-invalid @enderror"
                                    value="{{ old('class_name', $class->class_name) }}" placeholder="e.g., Grade 10-A" required>
                                @error('class_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-book text-primary me-2"></i>Course
                                </label>
                                <select id="course_id" name="course_id" class="form-select form-select-lg @error('course_id') is-invalid @enderror" required>
                                    <option value="">-- Select Course --</option>
                                    @forelse ($courses as $course)
                                        <option value="{{ $course->id }}" @selected(old('course_id', $class->course_id) == $course->id)>
                                            {{ $course->course_name }}
                                        </option>
                                    @empty
                                        <option disabled>No courses available</option>
                                    @endforelse
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contact your administrator to create new courses
                                </small>
                            </div>

                            <!-- Year and Section Row -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="year" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>Grade Year
                                    </label>
                                    <select id="year" name="year" class="form-select form-select-lg @error('year') is-invalid @enderror" required>
                                        <option value="">-- Select Year --</option>
                                        @for ($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" @selected(old('year', $class->year) == $i)>
                                                Year {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="section" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-layer-group text-primary me-2"></i>Section
                                    </label>
                                    <select id="section" name="section" class="form-select form-select-lg @error('section') is-invalid @enderror" required>
                                        <option value="">-- Select Section --</option>
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $sec)
                                            <option value="{{ $sec }}" @selected(old('section', $class->section) == $sec)>
                                                Section {{ $sec }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-4">
                                <label for="capacity" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-users text-primary me-2"></i>Class Capacity
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" id="capacity" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $class->capacity) }}" min="1" max="100" placeholder="50" required>
                                    <span class="input-group-text bg-light text-muted">students</span>
                                </div>
                                @error('capacity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Currently {{ $class->students->count() }} student(s) enrolled. Maximum capacity: 100.
                                </small>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-align-left text-primary me-2"></i>Description (Optional)
                                </label>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                                    rows="4" placeholder="Add any additional information about this class...">{{ old('description', $class->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-3 mt-5 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                    <i class="fas fa-save me-2"></i>Update Class
                                </button>
                                <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-secondary btn-lg">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-info-circle text-info me-2"></i>Class Info
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Class Code</small>
                            <strong class="text-dark">#{{ $class->id }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Created</small>
                            <strong class="text-dark">{{ $class->created_at->format('M d, Y') }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Subject</small>
                            <strong class="text-dark">{{ $class->subject->name ?? 'Not assigned' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 border-warning">
                    <div class="card-header bg-warning bg-opacity-10 border-warning">
                        <h6 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-graduation-cap text-warning me-2"></i>Enrolled Students
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-1 text-dark fw-bold">{{ $class->students->count() }}</h4>
                                <small class="text-muted">of {{ $class->capacity }} capacity</small>
                            </div>
                            <div class="text-end">
                                <div class="progress" style="width: 80px; height: 40px;">
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                        style="width: {{ ($class->students->count() / $class->capacity) * 100 }}%"
                                        aria-valuenow="{{ $class->students->count() }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{ $class->capacity }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
