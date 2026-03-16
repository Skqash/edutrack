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
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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
                                <input type="text" id="class_name" name="class_name"
                                    class="form-control form-control-lg @error('class_name') is-invalid @enderror"
                                    value="{{ old('class_name', $class->class_name) }}" placeholder="e.g., Grade 10-A"
                                    required>
                                @error('class_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-book text-primary me-2"></i>Course
                                </label>
                                <select id="course_id" name="course_id"
                                    class="form-select form-select-lg @error('course_id') is-invalid @enderror" required>
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
                                    <select id="year" name="year"
                                        class="form-select form-select-lg @error('year') is-invalid @enderror" required>
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
                                    <select id="section" name="section"
                                        class="form-select form-select-lg @error('section') is-invalid @enderror" required>
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
                                    <input type="number" id="capacity" name="capacity"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $class->capacity) }}" min="1" max="100"
                                        placeholder="50" required>
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

                            <!-- Student Assignment Section -->
                            <div class="card border-info bg-light">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>Manage Students
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Add or remove students from this class. You can filter by year or search by student
                                        ID or name.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="student_year_filter" class="form-label">Year</label>
                                                <select id="student_year_filter" class="form-select form-select-sm">
                                                    <option value="">All Years</option>
                                                    <option value="1">1st Year</option>
                                                    <option value="2">2nd Year</option>
                                                    <option value="3">3rd Year</option>
                                                    <option value="4">4th Year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="student_search" class="form-label">Search by Student ID or
                                                    Name</label>
                                                <input type="text" id="student_search"
                                                    class="form-control form-control-sm" placeholder="Search students...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label mb-0">
                                                    <strong>Available Students</strong>
                                                    <span class="badge bg-secondary ms-2" id="availableCount">0</span>
                                                </label>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        id="selectAllAvailable">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        id="deselectAllAvailable">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label mb-0">
                                                    <strong>Selected Students</strong>
                                                    <span class="badge bg-success ms-2" id="selectedCount">0</span>
                                                </label>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        id="selectAllSelected">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        id="deselectAllSelected">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded p-2" style="height: 300px; overflow-y: auto;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div id="availableStudents" class="h-100">
                                                    <div class="text-center text-muted">
                                                        <i class="fas fa-spinner fa-spin"></i> Loading students...
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="selectedStudents" class="h-100">
                                                    <div class="text-center text-muted">
                                                        <i class="fas fa-spinner fa-spin"></i> Loading students...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input to store selected student IDs -->
                            <input type="hidden" name="assigned_students" id="assigned_students" value="">

                            <!-- Form Actions -->
                            <div class="d-flex gap-3 mt-5 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                    <i class="fas fa-save me-2"></i>Update Class
                                </button>
                                <a href="{{ route('teacher.classes.show', $class->id) }}"
                                    class="btn btn-secondary btn-lg">
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
                                        aria-valuenow="{{ $class->students->count() }}" aria-valuemin="0"
                                        aria-valuemax="{{ $class->capacity }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentYearFilter = document.getElementById('student_year_filter');
            const studentSearch = document.getElementById('student_search');
            const availableStudentsDiv = document.getElementById('availableStudents');
            const selectedStudentsDiv = document.getElementById('selectedStudents');
            const availableCount = document.getElementById('availableCount');
            const selectedCount = document.getElementById('selectedCount');
            const assignedStudentsInput = document.getElementById('assigned_students');

            const getStudentsUrl = '{{ route('teacher.classes.get-students') }}';
            const initialSelectedStudentIds = @json($class->students->pluck('id')->toArray());

            let allStudents = [];
            let selectedStudents = new Set(initialSelectedStudentIds);

            // Load students function
            function loadStudents() {
                fetch(getStudentsUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            year: studentYearFilter.value,
                            search: studentSearch.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        allStudents = data.students || [];
                        renderAvailableStudents();
                        renderSelectedStudents();
                    })
                    .catch(error => {
                        console.error('Error loading students:', error);
                        availableStudentsDiv.innerHTML =
                            '<div class="text-center text-danger">Error loading students</div>';
                    });
            }

            // Event listeners for filters
            studentYearFilter.addEventListener('change', loadStudents);
            studentSearch.addEventListener('input', debounce(loadStudents, 300));

            // Select/Clear buttons
            document.getElementById('selectAllAvailable').addEventListener('click', function() {
                const availableIds = Array.from(availableStudentsDiv.querySelectorAll(
                        'input[type="checkbox"]'))
                    .map(cb => Number(cb.value));
                availableIds.forEach(id => selectedStudents.add(id));
                renderAvailableStudents();
                renderSelectedStudents();
            });

            document.getElementById('deselectAllAvailable').addEventListener('click', function() {
                const availableIds = Array.from(availableStudentsDiv.querySelectorAll(
                        'input[type="checkbox"]'))
                    .map(cb => Number(cb.value));
                availableIds.forEach(id => selectedStudents.delete(id));
                renderAvailableStudents();
                renderSelectedStudents();
            });

            // Toggle student selection
            function toggleStudent(studentId) {
                if (selectedStudents.has(studentId)) {
                    selectedStudents.delete(studentId);
                } else {
                    selectedStudents.add(studentId);
                }

                renderAvailableStudents();
                renderSelectedStudents();
            }

            // Update selected count display
            function updateSelectedCount() {
                const selectedStudentsList = Array.from(selectedStudents);
                selectedCount.textContent = selectedStudentsList.length;
                assignedStudentsInput.value = selectedStudentsList.join(',');
            }

            // Render available students
            function renderAvailableStudents() {
                const filteredStudents = filterStudents();
                const availableStudents = filteredStudents.filter(student => !selectedStudents.has(student.id));

                availableCount.textContent = availableStudents.length;

                if (availableStudents.length === 0) {
                    availableStudentsDiv.innerHTML =
                        '<div class="text-center text-muted">No available students</div>';
                    return;
                }

                let html = '';
                availableStudents.forEach(student => {
                    html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom hover-bg-light">
                    <input type="checkbox" class="form-check-input me-2" value="${student.id}" 
                           onchange="toggleStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}
                        </small>
                    </div>
                </div>
            `;
                });

                availableStudentsDiv.innerHTML = html;
            }

            // Render selected students
            function renderSelectedStudents() {
                const selectedIds = Array.from(selectedStudents);

                if (selectedIds.length === 0) {
                    selectedStudentsDiv.innerHTML =
                    '<div class="text-center text-muted">No students selected</div>';
                    updateSelectedCount();
                    return;
                }

                let html = '';
                selectedIds.forEach(id => {
                    const student = allStudents.find(s => s.id === id) || {
                        id,
                        name: 'Unknown Student',
                        student_id: '',
                        course_name: '',
                        year: '',
                        section: '',
                    };

                    html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <input type="checkbox" class="form-check-input me-2" checked value="${student.id}" 
                           onchange="toggleStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id ? student.student_id + ' • ' : ''}${student.course_name ? student.course_name + ' • ' : ''}${student.year ? 'Year ' + student.year + ' • ' : ''}${student.section || ''}
                        </small>
                    </div>
                </div>
            `;
                });

                selectedStudentsDiv.innerHTML = html;
                updateSelectedCount();
            }

            // Filter students
            function filterStudents() {
                return allStudents.filter(student => {
                    if (studentYearFilter.value && student.year != studentYearFilter.value) return false;
                    if (studentSearch.value) {
                        const search = studentSearch.value.toLowerCase();
                        return student.name.toLowerCase().includes(search) ||
                            student.student_id.toLowerCase().includes(search);
                    }
                    return true;
                });
            }

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                    clearTimeout(timeout);
                    return later();
                };
            }

            // Initial load
            loadStudents();
        });
    </script>

@endsection
