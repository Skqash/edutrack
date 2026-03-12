@extends('layouts.admin')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Classes Management</h2>
                    <p class="text-muted">Manage all classes and organize students by class sections</p>
                </div>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add New Class
                </a>
            </div>
        </div>
    </div>

    <!-- Class Capacity Chart -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar me-2" style="color: #3498db;"></i> Class Capacity Utilization
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="classCapacityChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-layer-group me-2" style="color: #27ae60;"></i> Class Levels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="category-list" style="font-size: 14px;">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 10</span>
                            <span class="badge bg-primary">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Class 11</span>
                            <span class="badge bg-success">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Class 12</span>
                            <span class="badge bg-warning">8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0" placeholder="Search classes...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="fw-bold">Class Name</th>
                            <th class="fw-bold">Class Level</th>
                            <th class="fw-bold">Section</th>
                            <th class="fw-bold">Students</th>
                            <th class="fw-bold">Class Teacher</th>
                            <th class="fw-bold">Capacity</th>
                            <th class="fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td><strong>{{ $class->class_name }}</strong></td>
                                <td><span class="badge bg-light text-dark">{{ $class->class_level }}</span></td>
                                <td>{{ $class->section }}</td>
                                <td><span class="badge bg-info">{{ $class->students()->count() }}</span></td>
                                <td>{{ $class->teacher->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar {{ $class->utilizationPercentage() > 85 ? 'bg-danger' : ($class->utilizationPercentage() > 70 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ $class->utilizationPercentage() }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.classes.edit', $class->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.classes.show', $class->id) }}"
                                            class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="openStudentModal({{ $class->id }}, '{{ $class->class_name }}')">
                                            <i class="fas fa-user-plus"></i> Add Students
                                        </button>
                                        <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">No classes found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Student Assignment Modal -->
<div class="modal fade" id="studentAssignmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-graduate me-2"></i>
                    Add Students to <span id="modalClassName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Select students to enroll in <strong id="modalClassName"></strong>. You can filter by course, year, department, or search by name/student ID.
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="modal_course_filter" class="form-label">Course</label>
                            <select id="modal_course_filter" class="form-select form-select-sm">
                                <option value="">All Courses</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-department="{{ $course->department }}">{{ $course->program_code }} - {{ $course->program_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="modal_year_filter" class="form-label">Year</label>
                            <select id="modal_year_filter" class="form-select form-select-sm">
                                <option value="">All Years</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="modal_department_filter" class="form-label">Department</label>
                            <select id="modal_department_filter" class="form-select form-select-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}">{{ $department }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="modal_student_search" class="form-label">Search</label>
                            <input type="text" id="modal_student_search" class="form-control form-control-sm" placeholder="Search students...">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">
                                <strong>Available Students</strong>
                                <span class="badge bg-secondary ms-2" id="modalAvailableCount">0</span>
                            </label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="modalSelectAllAvailable">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="modalDeselectAllAvailable">Clear</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">
                                <strong>Selected Students</strong>
                                <span class="badge bg-success ms-2" id="modalSelectedCount">0</span>
                            </label>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-success" id="modalSelectAllSelected">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="modalDeselectAllSelected">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="border rounded p-2" style="height: 200px; overflow-y: auto;">
                    <div class="text-center text-muted">
                        <i class="fas fa-spinner fa-spin"></i> Loading students...
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStudentAssignments">
                    <i class="fas fa-save me-2"></i> Save Assignments
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentClassId = null;
    let allStudents = [];
    let selectedStudents = new Set();
    let studentAssignments = {};
    
    // Modal elements
    const studentModal = document.getElementById('studentAssignmentModal');
    const modalClassName = document.getElementById('modalClassName');
    const modalCourseFilter = document.getElementById('modal_course_filter');
    const modalYearFilter = document.getElementById('modal_year_filter');
    const modalDepartmentFilter = document.getElementById('modal_department_filter');
    const modalStudentSearch = document.getElementById('modal_student_search');
    const modalAvailableDiv = document.getElementById('availableStudents');
    const modalSelectedDiv = document.getElementById('selectedStudents');
    const modalAvailableCount = document.getElementById('modalAvailableCount');
    const modalSelectedCount = document.getElementById('modalSelectedCount');
    const saveButton = document.getElementById('saveStudentAssignments');
    
    // Open student modal function
    window.openStudentModal = function(classId, className) {
        currentClassId = classId;
        modalClassName.textContent = className;
        studentModal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Load students for this class
        loadModalStudents(classId);
    };
    
    // Load students for modal
    function loadModalStudents(classId) {
        fetch('/admin/classes/get-students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                class_id: classId
            })
        })
        .then(response => response.json())
        .then(data => {
            allStudents = data.students || [];
            renderModalAvailableStudents();
            renderModalSelectedStudents();
        })
        .catch(error => {
            console.error('Error loading students:', error);
            modalAvailableDiv.innerHTML = '<div class="text-center text-danger">Error loading students</div>';
        });
    }
    
    // Render available students in modal
    function renderModalAvailableStudents() {
        const filteredStudents = filterModalStudents();
        const availableStudents = filteredStudents.filter(student => !selectedStudents.has(student.id));
        
        modalAvailableCount.textContent = availableStudents.length;
        
        if (availableStudents.length === 0) {
            modalAvailableDiv.innerHTML = '<div class="text-center text-muted">No available students</div>';
            return;
        }
        
        let html = '';
        availableStudents.forEach(student => {
            html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom hover-bg-light">
                    <input type="checkbox" class="form-check-input me-2" value="${student.id}" 
                           onchange="toggleModalStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}
                        </small>
                    </div>
                </div>
            `;
        });
        
        modalAvailableDiv.innerHTML = html;
    }
    
    // Render selected students in modal
    function renderModalSelectedStudents() {
        const selectedStudentsList = Array.from(selectedStudents);
        
        if (selectedStudentsList.length === 0) {
            modalSelectedDiv.innerHTML = '<div class="text-center text-muted">No students selected</div>';
            return;
        }
        
        let html = '';
        selectedStudentsList.forEach(student => {
            html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <input type="checkbox" class="form-check-input me-2" checked value="${student.id}" 
                           onchange="toggleModalStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}
                        </small>
                    </div>
                </div>
            `;
        });
        
        modalSelectedDiv.innerHTML = html;
    }
    
    // Toggle student selection in modal
    function toggleModalStudent(studentId) {
        if (selectedStudents.has(studentId)) {
            selectedStudents.delete(studentId);
        } else {
            selectedStudents.add(studentId);
        }
        renderModalAvailableStudents();
        renderModalSelectedStudents();
    }
    
    // Filter students for modal
    function filterModalStudents() {
        return allStudents.filter(student => {
            if (modalCourseFilter.value && student.course_id != modalCourseFilter.value) return false;
            if (modalYearFilter.value && student.year != modalYearFilter.value) return false;
            if (modalDepartmentFilter.value && student.department != modalDepartmentFilter.value) return false;
            if (modalStudentSearch.value) {
                const search = modalStudentSearch.value.toLowerCase();
                return student.name.toLowerCase().includes(search) || 
                       student.student_id.toLowerCase().includes(search);
            }
            return true;
        });
    }
    
    // Modal event listeners
    modalCourseFilter.addEventListener('change', loadModalStudents);
    modalYearFilter.addEventListener('change', loadModalStudents);
    modalDepartmentFilter.addEventListener('change', loadModalStudents);
    modalStudentSearch.addEventListener('input', debounce(loadModalStudents, 300));
    
    // Select/Clear buttons for modal
    document.getElementById('modalSelectAllAvailable').addEventListener('click', function() {
        const checkboxes = modalAvailableDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = true);
        renderModalSelectedStudents();
    });
    
    document.getElementById('modalDeselectAllAvailable').addEventListener('click', function() {
        const checkboxes = modalAvailableDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = false);
        renderModalSelectedStudents();
    });
    
    // Save student assignments
    saveButton.addEventListener('click', function() {
        const selectedStudentIds = Array.from(selectedStudents);
        
        if (selectedStudentIds.length === 0) {
            alert('Please select at least one student to assign.');
            return;
        }
        
        // Save assignments to database
        fetch('/admin/classes/assign-students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                class_id: currentClassId,
                student_ids: selectedStudentIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update student assignments count in table
                updateStudentCount(currentClassId, selectedStudentIds.length);
                
                // Close modal
                studentModal.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                // Show success message
                alert(`Successfully assigned ${selectedStudentIds.length} students to ${modalClassName.textContent}`);
            } else {
                alert('Error assigning students: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error assigning students:', error);
            alert('Error assigning students. Please try again.');
        });
    });
    
    // Update student count in table
    function updateStudentCount(classId, count) {
        const countElements = document.querySelectorAll(`[data-class-id="${classId}"] .badge-info`);
        if (countElements.length > 0) {
            countElements.forEach(el => el.textContent = count);
        }
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
});
</script>

@endsection
