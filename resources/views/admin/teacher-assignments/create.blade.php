@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-plus"></i>
            </span>
            Create Teacher Assignment
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Assignments
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.teacher-assignments.store') }}" method="POST" id="assignmentForm">
                        @csrf

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <h5 class="col-12"><i class="fas fa-info-circle"></i> Basic Information</h5>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="form-label">
                                        <i class="fas fa-chalkboard-teacher"></i> Teacher *
                                    </label>
                                    <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                        <option value="">Select a teacher...</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }} ({{ $teacher->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on"></i> Status *
                                    </label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="row mb-4">
                            <h5 class="col-12"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="academic_year" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Academic Year *
                                    </label>
                                    <select name="academic_year" id="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
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
                                    <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                        @foreach($semesters as $semester)
                                            <option value="{{ $semester }}" {{ old('semester') == $semester ? 'selected' : '' }}>
                                                {{ $semester }}
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
                                    <label for="department" class="form-label">
                                        <i class="fas fa-building"></i> Department
                                    </label>
                                    <select name="department" id="department" class="form-select @error('department') is-invalid @enderror">
                                        <option value="">Select department...</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department }}" {{ old('department') == $department ? 'selected' : '' }}>
                                                {{ $department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Details -->
                        <div class="row mb-4">
                            <h5 class="col-12"><i class="fas fa-tasks"></i> Assignment Details</h5>
                            <p class="col-12 text-muted small">Select at least one assignment type (class, subject, course, or department)</p>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="class_id" class="form-label">
                                        <i class="fas fa-door-open"></i> Class
                                    </label>
                                    <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror">
                                        <option value="">Select class...</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" 
                                                    data-course="{{ $class->course_id ?? '' }}"
                                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->class_name }} - {{ $class->section }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="subject_id" class="form-label">
                                        <i class="fas fa-book"></i> Subject
                                    </label>
                                    <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                                        <option value="">Select subject...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    data-course="{{ $subject->course_id ?? '' }}"
                                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->subject_code }} - {{ $subject->subject_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="course_id" class="form-label">
                                        <i class="fas fa-graduation-cap"></i> Course
                                    </label>
                                    <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror">
                                        <option value="">Select course...</option>
                                        @foreach($courses as $course)
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
                        </div>

                        <!-- Student Assignment -->
                        <div class="row mb-4">
                            <h5 class="col-12"><i class="fas fa-users"></i> Student Assignment</h5>
                            
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="student_filter" class="form-label">
                                        <i class="fas fa-filter"></i> Filter Students (Optional)
                                    </label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <select name="student_filter_class" id="student_filter_class" class="form-select">
                                                <option value="">Filter by Class</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="student_filter_course" id="student_filter_course" class="form-select">
                                                <option value="">Filter by Course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->program_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="student_filter_department" id="student_filter_department" class="form-select">
                                                <option value="">Filter by Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department }}">{{ $department }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="student_filter_year" id="student_filter_year" class="form-select">
                                                <option value="">Filter by Year</option>
                                                <option value="1">1st Year</option>
                                                <option value="2">2nd Year</option>
                                                <option value="3">3rd Year</option>
                                                <option value="4">4th Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <button type="button" id="loadStudentsBtn" class="btn btn-outline-info">
                                        <i class="fas fa-search"></i> Load Students
                                    </button>
                                    <button type="button" id="selectAllBtn" class="btn btn-outline-primary ms-2">
                                        <i class="fas fa-check-double"></i> Select All
                                    </button>
                                    <button type="button" id="clearAllBtn" class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-times"></i> Clear All
                                    </button>
                                </div>

                                <div class="form-group mb-3">
                                    <div id="studentsList" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                        <p class="text-muted text-center">Click "Load Students" to see available students</p>
                                    </div>
                                    <input type="hidden" name="student_ids" id="student_ids" value="">
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">
                                        <i class="fas fa-sticky-note"></i> Notes
                                    </label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                              rows="3" placeholder="Add any additional notes...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Create Assignment
                            </button>
                            <a href="{{ route('admin.teacher-assignments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>

                        @error('assignment_type')
                            <div class="alert alert-danger mt-3">
                                {{ $message }}
                            </div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .student-checkbox {
        margin-right: 8px;
    }

    .student-item {
        padding: 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    .student-item:hover {
        background-color: #f8f9fa;
    }

    .student-item.selected {
        background-color: #e3f2fd;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadStudentsBtn = document.getElementById('loadStudentsBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const studentsList = document.getElementById('studentsList');
    const studentIdsInput = document.getElementById('student_ids');

    // Auto-fill department when course is selected
    document.getElementById('course_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const department = selectedOption.dataset.department;
        if (department) {
            document.getElementById('department').value = department;
        }
    });

    // Load students
    loadStudentsBtn.addEventListener('click', function() {
        const filters = {
            class_id: document.getElementById('student_filter_class').value,
            course_id: document.getElementById('student_filter_course').value,
            department: document.getElementById('student_filter_department').value,
            year: document.getElementById('student_filter_year').value
        };

        const params = new URLSearchParams(filters);
        
        fetch(`{{ route('admin.teacher-assignments.get-students') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                displayStudents(data.students);
            })
            .catch(error => {
                console.error('Error loading students:', error);
                studentsList.innerHTML = '<p class="text-danger text-center">Error loading students</p>';
            });
    });

    function displayStudents(students) {
        if (students.length === 0) {
            studentsList.innerHTML = '<p class="text-muted text-center">No students found with the selected filters</p>';
            return;
        }

        let html = '<div class="row">';
        students.forEach((student, index) => {
            html += `
                <div class="col-md-6 mb-2">
                    <div class="student-item">
                        <label class="d-flex align-items-center cursor-pointer">
                            <input type="checkbox" name="selected_students[]" value="${student.id}" 
                                   class="student-checkbox" data-name="${student.name}">
                            <div class="ms-2">
                                <div class="fw-medium">${student.name}</div>
                                <small class="text-muted">
                                    ${student.student_id} • Year ${student.year} • ${student.section} • ${student.class_name}
                                </small>
                            </div>
                        </label>
                    </div>
                </div>
            `;
        });
        html += '</div>';

        studentsList.innerHTML = html;

        // Add event listeners to checkboxes
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedStudents);
        });
    }

    function updateSelectedStudents() {
        const checkboxes = document.querySelectorAll('.student-checkbox:checked');
        const studentIds = Array.from(checkboxes).map(cb => cb.value);
        studentIdsInput.value = JSON.stringify(studentIds);

        // Update visual selection
        document.querySelectorAll('.student-item').forEach(item => {
            const checkbox = item.querySelector('.student-checkbox');
            if (checkbox.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }

    selectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedStudents();
    });

    clearAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedStudents();
    });
});
</script>
@endsection
