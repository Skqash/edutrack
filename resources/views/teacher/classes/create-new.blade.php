@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create New Class</h2>
            <p class="text-muted mb-0">Set up a new class for your students</p>
        </div>
        <a href="{{ route('teacher.classes') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Classes
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chalkboard me-2"></i>Class Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('teacher.classes.store') }}" method="POST" id="createClassForm">
                @csrf
                
                <!-- Class Name -->
                <div class="mb-3">
                    <label for="class_name" class="form-label">Class Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('class_name') is-invalid @enderror" 
                           id="class_name" 
                           name="class_name" 
                           value="{{ old('class_name') }}" 
                           placeholder="e.g., BSIT 1-A, CS 101, Math 201" 
                           required>
                    @error('class_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Enter a descriptive name for your class</small>
                </div>

                <!-- Subject Selection -->
                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                    <x-searchable-dropdown
                        name="subject_id"
                        id="subject_id"
                        placeholder="Search and select subject..."
                        api-url="{{ route('api.subjects') }}"
                        :selected="old('subject_id', request('subject_id'))"
                        :create-new="true"
                        create-new-text="+ Create New Subject"
                        create-new-value="new-subject"
                        required="true"
                        class="form-control"
                    />
                    @error('subject_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Choose a subject assigned to you or create a new one</small>
                </div>

                <!-- New Subject Fields (Hidden by default) -->
                <div id="newSubjectFields" class="mb-3" style="display:none;">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-plus-circle me-2"></i>New Subject Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="new_subject_name" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" id="new_subject_name" name="new_subject_name" placeholder="Subject Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="new_subject_code" class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" id="new_subject_code" name="new_subject_code" placeholder="Subject Code">
                                </div>
                                <div class="col-md-4">
                                    <label for="credit_hours" class="form-label">Credit Hours</label>
                                    <x-searchable-dropdown
                                        name="credit_hours"
                                        id="credit_hours"
                                        placeholder="Select credit hours..."
                                        :options="[
                                            ['id' => '1', 'name' => '1 Unit', 'description' => '1 credit hour'],
                                            ['id' => '2', 'name' => '2 Units', 'description' => '2 credit hours'],
                                            ['id' => '3', 'name' => '3 Units', 'description' => '3 credit hours'],
                                            ['id' => '4', 'name' => '4 Units', 'description' => '4 credit hours'],
                                            ['id' => '5', 'name' => '5 Units', 'description' => '5 credit hours'],
                                            ['id' => '6', 'name' => '6 Units', 'description' => '6 credit hours']
                                        ]"
                                        selected="3"
                                        class="form-control"
                                    />
                                </div>
                                <div class="col-md-8">
                                    <label for="subject_description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="subject_description" name="description" placeholder="Subject Description (optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Selection -->
                <div class="mb-3">
                    <label for="course_id" class="form-label">Course/Program <span class="text-danger">*</span></label>
                    <x-searchable-dropdown
                        name="course_id"
                        id="course_id"
                        placeholder="Search and select course..."
                        api-url="{{ route('api.courses') }}"
                        :selected="old('course_id', request('course_id'))"
                        :create-new="true"
                        create-new-text="+ Create New Course"
                        create-new-value="new-course"
                        required="true"
                        class="form-control"
                    />
                    @error('course_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Select the course/program this class belongs to</small>
                </div>

                <!-- New Course Fields (Hidden by default) -->
                <div id="newCourseFields" class="mb-3" style="display:none;">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title"><i class="fas fa-plus-circle me-2"></i>New Course Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="new_course_name" class="form-label">Course Name</label>
                                    <input type="text" class="form-control" id="new_course_name" name="new_course_name" placeholder="Course Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="new_course_code" class="form-label">Course Code</label>
                                    <input type="text" class="form-control" id="new_course_code" name="new_course_code" placeholder="Course Code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Year and Section Row -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="year" class="form-label">Year Level</label>
                        <x-searchable-dropdown
                            name="year"
                            id="year"
                            placeholder="Select year level..."
                            :options="[
                                ['id' => '1', 'name' => '1st Year', 'description' => 'First year students'],
                                ['id' => '2', 'name' => '2nd Year', 'description' => 'Second year students'],
                                ['id' => '3', 'name' => '3rd Year', 'description' => 'Third year students'],
                                ['id' => '4', 'name' => '4th Year', 'description' => 'Fourth year students']
                            ]"
                            :selected="old('year')"
                            class="form-control"
                        />
                        @error('year')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="section" class="form-label">Section</label>
                        <x-searchable-dropdown
                            name="section"
                            id="section"
                            placeholder="Select section..."
                            :options="[
                                ['id' => 'A', 'name' => 'Section A', 'description' => 'Section A'],
                                ['id' => 'B', 'name' => 'Section B', 'description' => 'Section B'],
                                ['id' => 'C', 'name' => 'Section C', 'description' => 'Section C'],
                                ['id' => 'D', 'name' => 'Section D', 'description' => 'Section D'],
                                ['id' => 'E', 'name' => 'Section E', 'description' => 'Section E']
                            ]"
                            :selected="old('section')"
                            class="form-control"
                        />
                        @error('section')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Semester and Academic Year Row -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <x-searchable-dropdown
                            name="semester"
                            id="semester"
                            placeholder="Select semester..."
                            :options="[
                                ['id' => 'First', 'name' => 'First Semester', 'description' => 'First semester of academic year'],
                                ['id' => 'Second', 'name' => 'Second Semester', 'description' => 'Second semester of academic year'],
                                ['id' => 'Summer', 'name' => 'Summer', 'description' => 'Summer term']
                            ]"
                            :selected="old('semester')"
                            required="true"
                            class="form-control"
                        />
                        @error('semester')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('academic_year') is-invalid @enderror" 
                               id="academic_year" 
                               name="academic_year" 
                               value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" 
                               placeholder="e.g., 2024-2025" 
                               required>
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: YYYY-YYYY (e.g., 2024-2025)</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Add any additional information about this class...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Optional: Add notes or special instructions for this class</small>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('teacher.classes') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle new subject creation
    const subjectDropdown = document.querySelector('[data-dropdown-id="subject_id"]');
    const newSubjectFields = document.getElementById('newSubjectFields');
    
    if (subjectDropdown) {
        subjectDropdown.addEventListener('createNew', function(e) {
            if (e.detail.value === 'new-subject') {
                newSubjectFields.style.display = 'block';
                // Make new subject fields required
                newSubjectFields.querySelectorAll('input[required]').forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        });
        
        // Handle subject change
        const subjectHiddenInput = document.getElementById('subject_id_hidden');
        if (subjectHiddenInput) {
            subjectHiddenInput.addEventListener('change', function() {
                if (this.value !== 'new-subject') {
                    newSubjectFields.style.display = 'none';
                    // Remove required from new subject fields
                    newSubjectFields.querySelectorAll('input[required]').forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });
        }
    }

    // Handle new course creation
    const courseDropdown = document.querySelector('[data-dropdown-id="course_id"]');
    const newCourseFields = document.getElementById('newCourseFields');
    
    if (courseDropdown) {
        courseDropdown.addEventListener('createNew', function(e) {
            if (e.detail.value === 'new-course') {
                newCourseFields.style.display = 'block';
                // Make new course fields required
                newCourseFields.querySelectorAll('input[required]').forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        });
        
        // Handle course change
        const courseHiddenInput = document.getElementById('course_id_hidden');
        if (courseHiddenInput) {
            courseHiddenInput.addEventListener('change', function() {
                if (this.value !== 'new-course') {
                    newCourseFields.style.display = 'none';
                    // Remove required from new course fields
                    newCourseFields.querySelectorAll('input[required]').forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });
        }
    }

    // Form validation
    const form = document.getElementById('createClassForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endsection