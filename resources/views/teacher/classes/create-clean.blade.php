@extends('layouts.teacher')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-1 fw-bold">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Create New Class
                            </h1>
                            <p class="mb-0 opacity-90">Set up a new class for your students</p>
                        </div>
                        <a href="{{ route('teacher.classes') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('teacher.classes.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Class Name -->
                        <div class="mb-4">
                            <label for="class_name" class="form-label fw-semibold">
                                Class Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('class_name') is-invalid @enderror" 
                                   id="class_name" 
                                   name="class_name" 
                                   value="{{ old('class_name') }}" 
                                   placeholder="e.g., BSIT 1-A" 
                                   required>
                            @error('class_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject Selection -->
                        <div class="mb-4">
                            <label for="subject_id" class="form-label fw-semibold">
                                Subject <span class="text-danger">*</span>
                                @if(isset($assignedSubjects))
                                    <small class="text-muted">({{ $assignedSubjects->count() }} available)</small>
                                @endif
                            </label>
                            <select class="form-select form-select-lg select2 @error('subject_id') is-invalid @enderror" 
                                    id="subject_id" 
                                    name="subject_id" 
                                    required>
                                <option value="">-- Select Subject --</option>
                                @if(isset($assignedSubjects) && $assignedSubjects->count() > 0)
                                    @foreach($assignedSubjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                        </option>
                                    @endforeach
                                @endif
                                <option value="new-subject">+ Create New Subject</option>
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Subject Fields -->
                        <div id="newSubjectFields" class="mb-4" style="display:none;">
                            <div class="alert alert-info">
                                <strong>Creating New Subject</strong>
                                <p class="mb-0 small">Fill in the details below.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" name="new_subject_name" placeholder="Subject Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" name="new_subject_code" placeholder="e.g., CS101">
                                </div>
                            </div>
                        </div>

                        <!-- Course Selection -->
                        <div class="mb-4">
                            <label for="course_id" class="form-label fw-semibold">
                                Course/Program <span class="text-danger">*</span>
                                @if(isset($courses))
                                    <small class="text-muted">({{ $courses->count() }} available)</small>
                                @endif
                            </label>
                            <select class="form-select form-select-lg select2 @error('course_id') is-invalid @enderror" 
                                    id="course_id" 
                                    name="course_id" 
                                    required>
                                <option value="">-- Select Course --</option>
                                @if(isset($courses) && $courses->count() > 0)
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->program_code }} - {{ $course->program_name }}
                                        </option>
                                    @endforeach
                                @endif
                                <option value="new-course">+ Create New Course</option>
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Course Fields -->
                        <div id="newCourseFields" class="mb-4" style="display:none;">
                            <div class="alert alert-info">
                                <strong>Creating New Course</strong>
                                <p class="mb-0 small">Fill in the details below.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Course Name</label>
                                    <input type="text" class="form-control" name="new_course_name" placeholder="Course Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course Code</label>
                                    <input type="text" class="form-control" name="new_course_code" placeholder="e.g., BSIT">
                                </div>
                            </div>
                        </div>

                        <!-- Year and Section -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="year_level" class="form-label fw-semibold">Year Level <span class="text-danger">*</span></label>
                                <select class="form-select @error('year_level') is-invalid @enderror" name="year_level" required>
                                    <option value="">-- Select Year --</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="section" class="form-label fw-semibold">Section <span class="text-danger">*</span></label>
                                <select class="form-select @error('section') is-invalid @enderror" name="section" required>
                                    <option value="">-- Select Section --</option>
                                    <option value="A">Section A</option>
                                    <option value="B">Section B</option>
                                    <option value="C">Section C</option>
                                    <option value="D">Section D</option>
                                    <option value="E">Section E</option>
                                </select>
                            </div>
                        </div>

                        <!-- Semester and Academic Year -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="semester" class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                                <select class="form-select @error('semester') is-invalid @enderror" name="semester" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="First">First Semester</option>
                                    <option value="Second">Second Semester</option>
                                    <option value="Summer">Summer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="academic_year" class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('academic_year') is-invalid @enderror" 
                                       name="academic_year" 
                                       value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" 
                                       placeholder="2024-2025" 
                                       required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-0">
                            <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Add any additional information...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('teacher.classes') }}" class="btn btn-lg btn-light">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fas fa-save me-2"></i>Create Class
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Quick Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Use a clear, descriptive name that includes the course code and section.</p>
                        <hr>
                        <p class="small text-muted">Select from your assigned subjects or create a new one.</p>
                        <hr>
                        <p class="small text-muted">Contact your admin if you need additional subjects or courses.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle subject change
    $('#subject_id').on('change', function() {
        if ($(this).val() === 'new-subject') {
            $('#newSubjectFields').slideDown();
        } else {
            $('#newSubjectFields').slideUp();
        }
    });

    // Handle course change
    $('#course_id').on('change', function() {
        if ($(this).val() === 'new-course') {
            $('#newCourseFields').slideDown();
        } else {
            $('#newCourseFields').slideUp();
        }
    });
});
</script>
@endsection
