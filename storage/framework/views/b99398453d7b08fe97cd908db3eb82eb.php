<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-0">Create New Class</h1>
                    <p class="text-muted mb-0">Set up a new class and configure basic settings</p>
                </div>
                <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-outline-secondary">
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
                <div class="card-body">
                    <form action="<?php echo e(route('teacher.classes.store')); ?>" method="POST" class="card-body">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row g-3 mb-4">
                                <!-- Subject Selection -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="subjectSelect" class="form-label fw-bold">
                                            <i class="fas fa-book me-2"></i>Select Subject <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control" id="subjectSearch" 
                                                   placeholder="Search subjects..." autocomplete="off">
                                        </div>
                                        <select class="form-select <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subjectSelect"
                                                name="subject_id" required style="margin-top: 8px;" onchange="showSubjectDetails(this.value)">
                                                    <option value="">-- Select a Subject --</option>
                                                    <?php if(!empty($assignedSubjects)): ?>
                                                        <?php $__currentLoopData = $assignedSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($subject->id); ?>" 
                                                                    data-code="<?php echo e($subject->subject_code); ?>"
                                                                    data-name="<?php echo e($subject->subject_name); ?>"
                                                                    data-units="<?php echo e($subject->credit_hours); ?>"
                                                                    data-course="<?php echo e($subject->course_name ?? 'N/A'); ?>"
                                                                    data-course-id="<?php echo e($subject->course_id ?? ''); ?>">
                                                                <?php echo e($subject->subject_code); ?> - <?php echo e($subject->subject_name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <!-- Fallback: Show all subjects if none assigned -->
                                                        <?php
                                                            $allSubjects = \App\Models\Subject::limit(10)->get();
                                                        ?>
                                                        <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($subject->id); ?>" 
                                                                    data-code="<?php echo e($subject->subject_code); ?>"
                                                                    data-name="<?php echo e($subject->subject_name); ?>"
                                                                    data-units="<?php echo e($subject->credit_hours); ?>"
                                                                    data-course="<?php echo e($subject->course_name ?? 'N/A'); ?>"
                                                                    data-course-id="<?php echo e($subject->course_id ?? ''); ?>">
                                                                <?php echo e($subject->subject_code); ?> - <?php echo e($subject->subject_name); ?>

                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                            <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <small class="text-muted d-block mt-1">Select from your assigned subjects</small>
                                        </div>
                                </div>
                                
                                <!-- Class Name -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="class_name" class="form-label fw-bold">
                                            <i class="fas fa-chalkboard me-2"></i>Class Name
                                        </label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_name" 
                                               name="class_name" required placeholder="e.g., BSIT - 1A">
                                    <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted d-block mt-1">Use descriptive class names</small>
                                    </div>
                                </div>
                                
                                <!-- Academic Year & Semester -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="year" class="form-label">Academic Year</label>
                                            <select id="year" class="form-select" name="year">
                                                <option value="">Select Year</option>
                                                <option value="1">1st Year</option>
                                                <option value="2">2nd Year</option>
                                                <option value="3">3rd Year</option>
                                                <option value="4">4th Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="semester" class="form-label">Semester</label>
                                            <select id="semester" class="form-select" name="semester">
                                                <option value="">Select Semester</option>
                                                <option value="First">First</option>
                                                <option value="Second">Second</option>
                                                <option value="Summer">Summer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Capacity -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="capacity" class="form-label">
                                            <i class="fas fa-users me-2"></i>Class Capacity
                                        </label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="capacity" 
                                               name="capacity" min="1" placeholder="Maximum number of students">
                                    <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted d-block mt-1">Maximum number of students</small>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label fw-bold">
                                            <i class="fas fa-file-alt me-2"></i>Description (Optional)
                                        </label>
                                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" 
                                                  rows="3" placeholder="Add notes about this class..."><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <small class="text-muted d-block mt-1">Optional: Add any relevant information about this class</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Student Assignment -->
                            <div class="card border-info bg-light">
                                <div class="card-header py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user-graduate me-2"></i>Add Students to Class
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> 
                                        Select students to enroll in this class. You can add students now or after creating the class.
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
                                                <label for="student_search" class="form-label">Search by Student ID or Name</label>
                                                <input type="text" id="student_search" class="form-control form-control-sm" placeholder="Search students...">
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
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllAvailable">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllAvailable">Clear</button>
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
                                                    <button type="button" class="btn btn-sm btn-outline-success" id="selectAllSelected">Select All</button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAllSelected">Clear</button>
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
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden input to store selected student IDs -->
                    <input type="hidden" name="assigned_students" id="assigned_students" value="">
                    
                    <!-- Submit Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Class
                        </button>
                        <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    color: white;
    border: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,.2);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
}

.student-item {
    transition: all 0.2s ease-in-out;
}

.student-item:hover {
    background-color: #f8f9fa;
}

.border-info {
    border-left: 4px solid #17a2b8 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentYearFilter = document.getElementById('student_year_filter');
    const studentSearch = document.getElementById('student_search');
    const availableStudentsDiv = document.getElementById('availableStudents');
    const selectedStudentsDiv = document.getElementById('selectedStudents');
    const availableCount = document.getElementById('availableCount');
    const selectedCount = document.getElementById('selectedCount');
    const assignedStudentsInput = document.getElementById('assigned_students');
    
    let allStudents = [];
    let selectedStudents = new Set();
    
    // Load students function
    function loadStudents() {
        fetch('/admin/classes/get-students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            availableStudentsDiv.innerHTML = '<div class="text-center text-danger">Error loading students</div>';
        });
    }
    
    // Event listeners for filters
    studentYearFilter.addEventListener('change', loadStudents);
    studentSearch.addEventListener('input', debounce(loadStudents, 300));
    
    // Select/Clear buttons
    document.getElementById('selectAllAvailable').addEventListener('click', function() {
        const checkboxes = availableStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    });
    
    document.getElementById('deselectAllAvailable').addEventListener('click', function() {
        const checkboxes = availableStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = false);
        updateSelectedCount();
    });
    
    // Toggle student selection
    function toggleStudent(studentId) {
        const checkbox = document.querySelector(`input[value="${studentId}"]`);
        if (checkbox) {
            if (selectedStudents.has(studentId)) {
                selectedStudents.delete(studentId);
                checkbox.checked = false;
            } else {
                selectedStudents.add(studentId);
                checkbox.checked = true;
            }
            updateSelectedCount();
        }
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
        const availableStudents = filteredStudents.filter(student => !selectedStudents.has(studentId));
        
        availableCount.textContent = availableStudents.length;
        
        if (availableStudents.length === 0) {
            availableStudentsDiv.innerHTML = '<div class="text-center text-muted">No available students</div>';
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
        const selectedStudentsList = Array.from(selectedStudents);
        
        if (selectedStudentsList.length === 0) {
            selectedStudentsDiv.innerHTML = '<div class="text-center text-muted">No students selected</div>';
            assignedStudentsInput.value = '';
            return;
        }
        
        let html = '';
        selectedStudentsList.forEach(student => {
            html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <input type="checkbox" class="form-check-input me-2" checked value="${student.id}" 
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
        
        selectedStudentsDiv.innerHTML = html;
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

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/create.blade.php ENDPATH**/ ?>