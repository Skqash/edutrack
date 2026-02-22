@extends('layouts.teacher')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-0">Create New Class</h1>
                    <p class="text-muted mb-0">Set up a new class and configure basic settings</p>
                </div>
                <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Classes
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>New Class Information</h5>
                </div>

                <form action="{{ route('teacher.classes.store') }}" method="POST" class="card-body">
                    @csrf

                    <div class="row g-3 mb-4">
                        <!-- Class Name -->
                        <div class="col-12">
                            <label for="className" class="form-label fw-bold">
                                <i class="fas fa-book me-2"></i>Class Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                                id="className" name="class_name" placeholder="e.g., Object Oriented Programming"
                                value="{{ old('class_name') }}" required>
                            @error('class_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Provide a meaningful name for this class</small>
                        </div>

                        <!-- Course Selection -->
                        <div class="col-md-6">
                            <label for="courseId" class="form-label fw-bold">
                                <i class="fas fa-tag me-2"></i>Course <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('course_id') is-invalid @enderror" id="courseId"
                                name="course_id" required>
                                <option value="">-- Select a Course --</option>
                                @if (!empty($courses))
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->course_name }} ({{ $course->course_code }})
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>No courses available. Contact admin.</option>
                                @endif
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Select the course for this class</small>
                        </div>

                        <!-- Year -->
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap me-2"></i>Year <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('year') is-invalid @enderror" id="year"
                                name="year" required>
                                <option value="">-- Select Year --</option>
                                <option value="1" {{ old('year') == 1 ? 'selected' : '' }}>1st Year</option>
                                <option value="2" {{ old('year') == 2 ? 'selected' : '' }}>2nd Year</option>
                                <option value="3" {{ old('year') == 3 ? 'selected' : '' }}>3rd Year</option>
                                <option value="4" {{ old('year') == 4 ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('year')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Section -->
                        <div class="col-md-6">
                            <label for="section" class="form-label fw-bold">
                                <i class="fas fa-users me-2"></i>Section <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('section') is-invalid @enderror" id="section"
                                name="section" required>
                                <option value="">-- Select Section --</option>
                                <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A</option>
                                <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B</option>
                                <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>Section C</option>
                                <option value="D" {{ old('section') == 'D' ? 'selected' : '' }}>Section D</option>
                                <option value="E" {{ old('section') == 'E' ? 'selected' : '' }}>Section E</option>
                            </select>
                            @error('section')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div class="col-md-6">
                            <label for="capacity" class="form-label fw-bold">
                                <i class="fas fa-chair me-2"></i>Class Capacity <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                id="capacity" name="capacity" placeholder="e.g., 50" min="1" max="100"
                                value="{{ old('capacity', 50) }}" required>
                            @error('capacity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Maximum number of students</small>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-file-alt me-2"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4" 
                                placeholder="Add notes about this class...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Optional: Add any relevant information about this class</small>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 justify-content-between pt-3 border-top">
                        <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Class
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-left border-primary">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2"></i>Next Steps</h6>
                    <ol class="small">
                        <li class="mb-2">Create your class</li>
                        <li class="mb-2">Add students to the class</li>
                        <li class="mb-2">Configure assessment ranges</li>
                        <li>Start entering grades</li>
                    </ol>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <ul class="small mb-0">
                        <li>Use descriptive class names</li>
                        <li>Set realistic capacity limits</li>
                        <li>You can edit class details later</li>
                        <li>Add students from your class page</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left {
        border-left: 4px solid;
    }
    .border-primary {
        border-left-color: #0066cc !important;
    }
</style>
@endsection
