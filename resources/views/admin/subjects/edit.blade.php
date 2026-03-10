@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-edit me-2" style="color: #6c757d;"></i>Edit Subject
                    </h2>
                    <p class="text-muted">Update subject information and program assignment</p>
                </div>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Subjects
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-book me-2" style="color: #6c757d;"></i>Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject_code" class="form-label fw-bold">
                                        <i class="fas fa-barcode me-1" style="color: #6c757d;"></i> Subject Code
                                    </label>
                                    <input type="text" class="form-control @error('subject_code') is-invalid @enderror" 
                                           id="subject_code" name="subject_code" 
                                           value="{{ $subject->subject_code }}" required>
                                    @error('subject_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="credit_hours" class="form-label fw-bold">
                                        <i class="fas fa-clock me-1" style="color: #6c757d;"></i> Credit Hours
                                    </label>
                                    <input type="number" class="form-control @error('credit_hours') is-invalid @enderror" 
                                           id="credit_hours" name="credit_hours" 
                                           value="{{ $subject->credit_hours }}" min="1" max="6" required>
                                    @error('credit_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject_name" class="form-label fw-bold">
                                <i class="fas fa-book me-1" style="color: #6c757d;"></i> Subject Name
                            </label>
                            <input type="text" class="form-control @error('subject_name') is-invalid @enderror" 
                                   id="subject_name" name="subject_name" 
                                   value="{{ $subject->subject_name }}" required>
                            @error('subject_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="course_id" class="form-label fw-bold">
                                        <i class="fas fa-graduation-cap me-1" style="color: #6c757d;"></i> Degree Program
                                    </label>
                                    <select class="form-select @error('course_id') is-invalid @enderror" 
                                            id="course_id" name="course_id" onchange="updateProgramField()">
                                        <option value="">Select a program (Optional)...</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                    data-program="{{ $course->program_name }}"
                                                    {{ $subject->course_id == $course->id ? 'selected' : '' }}>
                                                {{ $course->program_name }} ({{ $course->program_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Assign to a degree program or leave as General Education</small>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="program" class="form-label fw-bold">
                                        <i class="fas fa-tag me-1" style="color: #6c757d;"></i> Program Name
                                    </label>
                                    <input type="text" class="form-control @error('program') is-invalid @enderror" 
                                           id="program" name="program" 
                                           value="{{ old('program', $subject->program) }}">
                                    <small class="text-muted">Auto-filled when program is selected</small>
                                    @error('program')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label fw-bold">
                                        <i class="fas fa-folder me-1" style="color: #6c757d;"></i> Category
                                    </label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Select category...</option>
                                        @foreach($categories as $key => $value)
                                            <option value="{{ $key }}" {{ $subject->category == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label fw-bold">
                                        <i class="fas fa-layer-group me-1" style="color: #6c757d;"></i> Subject Type
                                    </label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select type...</option>
                                        @foreach($types as $key => $value)
                                            <option value="{{ $key }}" {{ $subject->type == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="instructor_id" class="form-label fw-bold">
                                <i class="fas fa-user-tie me-1" style="color: #6c757d;"></i> Assigned Instructor
                            </label>
                            <select class="form-select @error('instructor_id') is-invalid @enderror" 
                                    id="instructor_id" name="instructor_id">
                                <option value="">Select instructor (Optional)...</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" 
                                            {{ $subject->instructor_id == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->name }} ({{ $instructor->role }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Assign an instructor to this subject</small>
                            @error('instructor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1" style="color: #6c757d;"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter subject description and learning objectives">{{ $subject->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Subject
                            </button>
                            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold text-muted">
                        <i class="fas fa-info-circle me-2"></i>Subject Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark">📊 Current Details</h6>
                        <ul class="small text-muted">
                            <li><strong>Code:</strong> {{ $subject->subject_code }}</li>
                            <li><strong>Name:</strong> {{ $subject->subject_name }}</li>
                            <li><strong>Credits:</strong> {{ $subject->credit_hours }} units</li>
                            <li><strong>Category:</strong> {{ $subject->category }}</li>
                            <li><strong>Type:</strong> {{ $subject->type ?? 'General' }}</li>
                            <li><strong>Program:</strong> {{ $subject->program ?? 'General Education' }}</li>
                        </ul>
                    </div>

                    @if($subject->instructor)
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark">👨‍🏫 Current Instructor</h6>
                        <div class="alert alert-info small">
                            <i class="fas fa-user-tie me-1"></i>
                            <strong>{{ $subject->instructor->name }}</strong><br>
                            <span class="text-muted">{{ $subject->instructor->role }}</span>
                        </div>
                    </div>
                    @endif

                    @if($subject->course)
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark">🎓 Assigned Program</h6>
                        <div class="alert alert-success small">
                            <i class="fas fa-graduation-cap me-1"></i>
                            <strong>{{ $subject->course->program_name }}</strong><br>
                            <span class="text-muted">{{ $subject->course->program_code }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Note:</strong> Changing the degree program will automatically update the program field and sync the subject.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function updateProgramField() {
    const courseSelect = document.getElementById('course_id');
    const programInput = document.getElementById('program');
    const selectedOption = courseSelect.options[courseSelect.selectedIndex];
    
    if (selectedOption.value && selectedOption.dataset.program) {
        programInput.value = selectedOption.dataset.program;
        programInput.readOnly = true;
        programInput.classList.add('bg-light');
    } else {
        programInput.value = '';
        programInput.readOnly = false;
        programInput.classList.remove('bg-light');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateProgramField();
});
</script>
@endpush
