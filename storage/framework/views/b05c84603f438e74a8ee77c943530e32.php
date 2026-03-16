<?php $__env->startSection('title', 'Edit Class'); ?>

<?php $__env->startSection('content'); ?>
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
                    <a href="<?php echo e(route('teacher.classes.show', $class->id)); ?>" class="btn btn-outline-secondary">
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
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle me-2"></i>Validation Errors</strong>
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('teacher.classes.update', $class->id)); ?>" method="POST" novalidate>
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Class Name -->
                            <div class="mb-4">
                                <label for="class_name" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-bookmark text-primary me-2"></i>Class Name
                                </label>
                                <input type="text" id="class_name" name="class_name"
                                    class="form-control form-control-lg <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('class_name', $class->class_name)); ?>" placeholder="e.g., Grade 10-A"
                                    required>
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
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-book text-primary me-2"></i>Course
                                </label>
                                <select id="course_id" name="course_id"
                                    class="form-select form-select-lg <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">-- Select Course --</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($course->id); ?>" <?php if(old('course_id', $class->course_id) == $course->id): echo 'selected'; endif; ?>>
                                            <?php echo e($course->course_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option disabled>No courses available</option>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        class="form-select form-select-lg <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">-- Select Year --</option>
                                        <?php for($i = 1; $i <= 4; $i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php if(old('year', $class->year) == $i): echo 'selected'; endif; ?>>
                                                Year <?php echo e($i); ?>

                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="section" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-layer-group text-primary me-2"></i>Section
                                    </label>
                                    <select id="section" name="section"
                                        class="form-select form-select-lg <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">-- Select Section --</option>
                                        <?php $__currentLoopData = ['A', 'B', 'C', 'D', 'E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($sec); ?>" <?php if(old('section', $class->section) == $sec): echo 'selected'; endif; ?>>
                                                Section <?php echo e($sec); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Capacity -->
                            <div class="mb-4">
                                <label for="capacity" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-users text-primary me-2"></i>Class Capacity
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" id="capacity" name="capacity"
                                        class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('capacity', $class->capacity)); ?>" min="1" max="100"
                                        placeholder="50" required>
                                    <span class="input-group-text bg-light text-muted">students</span>
                                </div>
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
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Currently <?php echo e($class->students->count()); ?> student(s) enrolled. Maximum capacity: 100.
                                </small>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-align-left text-primary me-2"></i>Description (Optional)
                                </label>
                                <textarea id="description" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    rows="4" placeholder="Add any additional information about this class..."><?php echo e(old('description', $class->description)); ?></textarea>
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
                                <a href="<?php echo e(route('teacher.classes.show', $class->id)); ?>"
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
                            <strong class="text-dark">#<?php echo e($class->id); ?></strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Created</small>
                            <strong class="text-dark"><?php echo e($class->created_at->format('M d, Y')); ?></strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Subject</small>
                            <strong class="text-dark"><?php echo e($class->subject->name ?? 'Not assigned'); ?></strong>
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
                                <h4 class="mb-1 text-dark fw-bold"><?php echo e($class->students->count()); ?></h4>
                                <small class="text-muted">of <?php echo e($class->capacity); ?> capacity</small>
                            </div>
                            <div class="text-end">
                                <div class="progress" style="width: 80px; height: 40px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: <?php echo e(($class->students->count() / $class->capacity) * 100); ?>%"
                                        aria-valuenow="<?php echo e($class->students->count()); ?>" aria-valuemin="0"
                                        aria-valuemax="<?php echo e($class->capacity); ?>"></div>
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

            const getStudentsUrl = '<?php echo e(route('teacher.classes.get-students')); ?>';
            const initialSelectedStudentIds = <?php echo json_encode($class->students->pluck('id')->toArray(), 15, 512) ?>;

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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\classes\edit.blade.php ENDPATH**/ ?>