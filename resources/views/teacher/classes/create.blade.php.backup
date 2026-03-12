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
                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <!-- Subject Selection -->
                            <div class="col-md-12">
                                <label for="subjectSelect" class="form-label fw-bold">
                                    <i class="fas fa-book me-2"></i>Select Subject <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="subjectSearch" 
                                           placeholder="Search subjects..." autocomplete="off">
                                </div>
                                <select class="form-select @error('subject_id') is-invalid @enderror" id="subjectSelect"
                                    name="subject_id" required style="margin-top: 8px;" onchange="showSubjectDetails(this.value)">
                                    <option value="">-- Select a Subject --</option>
                                    @if (!empty($assignedSubjects))
                                        @foreach ($assignedSubjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    data-code="{{ $subject->subject_code }}"
                                                    data-name="{{ $subject->subject_name }}"
                                                    data-units="{{ $subject->credit_hours }}"
                                                    data-course="{{ $subject->course_name ?? 'N/A' }}"
                                                    data-course-id="{{ $subject->course_id ?? '' }}">
                                                {{ $subject->subject_name }} ({{ $subject->subject_code }}) - {{ $subject->credit_hours }} units
                                            </option>
                                        @endforeach
                                    @else
                                        <!-- Fallback: Show all subjects if none assigned -->
                                        @php
                                            $allSubjects = \App\Models\Subject::limit(10)->get();
                                        @endphp
                                        @if($allSubjects->count() > 0)
                                            @foreach ($allSubjects as $subject)
                                                <option value="{{ $subject->id }}" 
                                                        data-code="{{ $subject->subject_code }}"
                                                        data-name="{{ $subject->subject_name }}"
                                                        data-units="{{ $subject->credit_hours }}"
                                                        data-course="{{ $subject->course_name ?? 'N/A' }}"
                                                        data-course-id="{{ $subject->course_id ?? '' }}">
                                                    {{ $subject->subject_name }} ({{ $subject->subject_code }}) - {{ $subject->credit_hours }} units
                                                </option>
                                            @endforeach
                                        @else
                                            <option disabled>No subjects available. Contact admin to add subjects.</option>
                                        @endif
                                    @endif
                                </select>
                                
                                <!-- Subject Details Display -->
                                <div id="subjectDetails" class="alert alert-info border-0 mt-2" style="display: none;">
                                    <h6 class="alert-heading fw-bold mb-2">
                                        <i class="fas fa-info-circle me-2"></i>Subject Details
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Subject:</strong> <span id="detailSubjectName">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Course:</strong> <span id="detailCourseName">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Code:</strong> <span id="detailSubjectCode">-</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Units:</strong> <span id="detailUnits">-</span>
                                        </div>
                                    </div>
                                </div>
                                @error('subject_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Select from your assigned subjects</small>
                            </div>

                            <!-- Class Name -->
                            <div class="col-md-12">
                                <label for="className" class="form-label fw-bold">
                                    <i class="fas fa-book me-2"></i>Class Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('class_name') is-invalid @enderror"
                                        id="className" name="class_name" placeholder="e.g., Object Oriented Programming - Section A"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button" id="autoNameBtn" 
                                            title="Auto-generate from subject">
                                        <i class="fas fa-magic"></i>
                                    </button>
                                </div>
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

                            <!-- Section -->
                            <div class="col-md-6">
                                <label for="section" class="form-label fw-bold">
                                    <i class="fas fa-users me-2"></i>Section <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('section') is-invalid @enderror" id="section"
                                    name="section" required>
                                    <option value="">Select Section</option>
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

                            <!-- Academic Year & Semester -->
                            <div class="col-md-6">
                                <label for="academic_year" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-2"></i>Academic Year <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('academic_year') is-invalid @enderror" id="academic_year"
                                    name="academic_year" required>
                                    <option value="">-- Select Year --</option>
                                    <option value="2024-2025" {{ old('academic_year') == '2024-2025' ? 'selected' : '' }}>2024-2025</option>
                                    <option value="2025-2026" {{ old('academic_year') == '2025-2026' ? 'selected' : '' }}>2025-2026</option>
                                    <option value="2026-2027" {{ old('academic_year') == '2026-2027' ? 'selected' : '' }}>2026-2027</option>
                                </select>
                                @error('academic_year')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="semester" class="form-label fw-bold">
                                    <i class="fas fa-book-open me-2"></i>Semester <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('semester') is-invalid @enderror" id="semester"
                                    name="semester" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First Semester</option>
                                    <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second Semester</option>
                                    <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                                </select>
                                @error('semester')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Auto-Assignment Options -->
                            <div class="col-md-12">
                                <div class="card border-info bg-light">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-plus me-2"></i>Teacher Assignment Options
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="autoAssign" name="auto_assign" value="1" checked>
                                            <label class="form-check-label" for="autoAssign">
                                                <strong>Auto-assign me as teacher</strong> - Automatically create teacher assignment for this class
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="createAssignment" name="create_assignment" value="1">
                                            <label class="form-check-label" for="createAssignment">
                                                <strong>Create additional assignments</strong> - Assign other teachers to this class
                                            </label>
                                        </div>

                                        <div id="additionalTeachers" style="display: none;">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-users me-2"></i>Additional Teachers
                                            </label>
                                            <select class="form-select" name="additional_teachers[]" multiple size="3">
                                                @php
                                                    $allTeachers = \App\Models\User::where('role', 'teacher')
                                                        ->where('id', '!=', auth()->id())
                                                        ->orderBy('name')
                                                        ->get();
                                                @endphp
                                                @foreach($allTeachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Hold Ctrl/Cmd to select multiple teachers</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-file-alt me-2"></i>Description (Optional)
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Add notes about this class...">{{ old('description') }}</textarea>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Subject search functionality
        const subjectSearch = document.getElementById('subjectSearch');
        const subjectSelect = document.getElementById('subjectSelect');
        const classNameInput = document.getElementById('className');
        const autoNameBtn = document.getElementById('autoNameBtn');
        const yearSelect = document.getElementById('year');
        const sectionSelect = document.getElementById('section');
        
        // Assignment checkboxes
        const createAssignmentCheckbox = document.getElementById('createAssignment');
        const additionalTeachersDiv = document.getElementById('additionalTeachers');

        // Toggle additional teachers section
        createAssignmentCheckbox.addEventListener('change', function() {
            additionalTeachersDiv.style.display = this.checked ? 'block' : 'none';
        });

        // Filter subjects based on search
        subjectSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const options = subjectSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') return; // Skip placeholder
                
                const text = option.textContent.toLowerCase();
                const code = option.getAttribute('data-code')?.toLowerCase() || '';
                const name = option.getAttribute('data-name')?.toLowerCase() || '';
                
                const matchesSearch = text.includes(searchTerm) || 
                                     code.includes(searchTerm) || 
                                     name.includes(searchTerm);
                
                option.style.display = matchesSearch ? '' : 'none';
            });
        });

        // Auto-generate class name when subject is selected
        subjectSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Auto-fill course if subject has course
                const courseId = selectedOption.getAttribute('data-course-id');
                if (courseId) {
                    const courseSelect = document.getElementById('courseId');
                    if (courseSelect) {
                        courseSelect.value = courseId;
                    }
                }
                
                if (yearSelect.value && sectionSelect.value) {
                    generateClassName();
                }
            }
        });

        // Auto-generate class name when year or section changes
        yearSelect.addEventListener('change', function() {
            if (subjectSelect.value && sectionSelect.value) {
                generateClassName();
            }
        });

        sectionSelect.addEventListener('change', function() {
            if (subjectSelect.value && yearSelect.value) {
                generateClassName();
            }
        });

        // Auto-name button click
        autoNameBtn.addEventListener('click', function() {
            if (subjectSelect.value && yearSelect.value && sectionSelect.value) {
                generateClassName();
            } else {
                // Show a subtle alert
                this.classList.add('btn-warning');
                this.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                setTimeout(() => {
                    this.classList.remove('btn-warning');
                    this.innerHTML = '<i class="fas fa-magic"></i>';
                }, 1000);
            }
        });

        function generateClassName() {
            const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
            const subjectName = selectedOption.getAttribute('data-name');
            const subjectCode = selectedOption.getAttribute('data-code');
            const yearText = yearSelect.options[yearSelect.selectedIndex].text;
            const section = sectionSelect.value;
            
            let className = '';
            
            // Use subject name or code
            if (subjectName) {
                className = `${subjectName} - ${yearText} - Section ${section}`;
            } else if (subjectCode) {
                className = `${subjectCode} - ${yearText} - Section ${section}`;
            }
            
            classNameInput.value = className;
            
            // Visual feedback
            classNameInput.classList.add('border-success');
            setTimeout(() => {
                classNameInput.classList.remove('border-success');
            }, 1500);
        }

        // Function to show subject details
        window.showSubjectDetails = function(subjectId) {
            const selectedOption = subjectSelect.querySelector(`option[value="${subjectId}"]`);
            const detailsDiv = document.getElementById('subjectDetails');
            
            if (selectedOption && subjectId) {
                const subjectName = selectedOption.getAttribute('data-name');
                const subjectCode = selectedOption.getAttribute('data-code');
                const units = selectedOption.getAttribute('data-units');
                const courseName = selectedOption.getAttribute('data-course');
                
                document.getElementById('detailSubjectName').textContent = subjectName || '-';
                document.getElementById('detailSubjectCode').textContent = subjectCode || '-';
                document.getElementById('detailUnits').textContent = units ? `${units} units` : '-';
                document.getElementById('detailCourseName').textContent = courseName || '-';
                
                detailsDiv.style.display = 'block';
            } else {
                detailsDiv.style.display = 'none';
            }
        };
    });
    </script>

@endsection
