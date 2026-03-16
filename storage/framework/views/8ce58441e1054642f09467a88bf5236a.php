<div class="modal fade" id="studentAssignmentModal" tabindex="-1" aria-labelledby="studentAssignmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="studentAssignmentModalLabel">
                    <i class="fas fa-user-graduate me-2"></i>
                    Assign Students to <span id="modalClassName"><?php echo e($className ?? 'Class'); ?></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Select students to enroll in <strong id="modalClassName"><!-- overwritten by JS --></strong>.
                    Use the filters below to narrow results.
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="modal_course_filter" class="form-label">Course</label>
                        <select id="modal_course_filter" class="form-select form-select-sm">
                            <option value="">All Courses</option>
                            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($course->id); ?>" data-department="<?php echo e($course->department); ?>">
                                    <?php echo e($course->program_code); ?> - <?php echo e($course->program_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="modal_year_filter" class="form-label">Year</label>
                        <select id="modal_year_filter" class="form-select form-select-sm">
                            <option value="">All Years</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="modal_department_filter" class="form-label">Department</label>
                        <select id="modal_department_filter" class="form-select form-select-sm">
                            <option value="">All Departments</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department); ?>"><?php echo e($department); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="modal_student_search" class="form-label">Search</label>
                        <input type="text" id="modal_student_search" class="form-control form-control-sm"
                            placeholder="Search students...">
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
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    id="modalSelectAllAvailable">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    id="modalDeselectAllAvailable">Clear</button>
                            </div>
                        </div>
                        <div class="border rounded p-2" style="height: 260px; overflow-y: auto;" id="availableStudents">
                            <div class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Loading students...
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
                                <button type="button" class="btn btn-sm btn-outline-success"
                                    id="modalSelectAllSelected">Select All</button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    id="modalDeselectAllSelected">Clear</button>
                            </div>
                        </div>
                        <div class="border rounded p-2" style="height: 260px; overflow-y: auto;" id="selectedStudents">
                            <div class="text-center text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Loading selected students...
                            </div>
                        </div>
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
        const modal = document.getElementById('studentAssignmentModal');
        const modalInstance = new bootstrap.Modal(modal);

        const modalClassName = modal.querySelector('#modalClassName');
        const modalCourseFilter = modal.querySelector('#modal_course_filter');
        const modalYearFilter = modal.querySelector('#modal_year_filter');
        const modalDepartmentFilter = modal.querySelector('#modal_department_filter');
        const modalStudentSearch = modal.querySelector('#modal_student_search');
        const modalAvailableDiv = modal.querySelector('#availableStudents');
        const modalSelectedDiv = modal.querySelector('#selectedStudents');
        const modalAvailableCount = modal.querySelector('#modalAvailableCount');
        const modalSelectedCount = modal.querySelector('#modalSelectedCount');
        const saveButton = modal.querySelector('#saveStudentAssignments');

        let currentClassId = null;
        let allStudents = [];
        let selectedStudents = new Set();

        window.openStudentModal = (classId, className) => {
            currentClassId = classId;
            modalClassName.textContent = className;
            selectedStudents = new Set();
            modalInstance.show();
            loadStudents();
        };

        const loadStudents = () => {
            const body = {
                class_id: currentClassId,
                course_id: modalCourseFilter.value,
                year: modalYearFilter.value,
                department: modalDepartmentFilter.value,
                search: modalStudentSearch.value,
            };

            fetch('<?php echo e(route('admin.classes.get-students')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    allStudents = data.students || [];

                    if (selectedStudents.size === 0) {
                        allStudents
                            .filter(student => student.class_id === currentClassId)
                            .forEach(student => selectedStudents.add(student.id));
                    }

                    renderAvailableStudents();
                    renderSelectedStudents();
                })
                .catch(() => {
                    modalAvailableDiv.innerHTML =
                        '<div class="text-center text-danger">Error loading students</div>';
                });
        };

        const renderAvailableStudents = () => {
            const filtered = filterStudents();
            const available = filtered.filter(s => !selectedStudents.has(s.id));

            modalAvailableCount.textContent = available.length;

            if (!available.length) {
                modalAvailableDiv.innerHTML =
                    '<div class="text-center text-muted">No available students</div>';
                return;
            }

            modalAvailableDiv.innerHTML = available.map(student => `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <div class="form-check me-2">
                        <input class="form-check-input" type="checkbox" value="${student.id}" id="student-available-${student.id}" />
                        <label class="form-check-label" for="student-available-${student.id}"></label>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary ms-3" onclick="selectStudent(${student.id})">Add</button>
                </div>
            `).join('');
        };

        const renderSelectedStudents = () => {
            const selected = allStudents.filter(s => selectedStudents.has(s.id));
            modalSelectedCount.textContent = selected.length;

            if (!selected.length) {
                modalSelectedDiv.innerHTML =
                    '<div class="text-center text-muted">No students selected</div>';
                return;
            }

            modalSelectedDiv.innerHTML = selected.map(student => `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStudent(${student.id})">Remove</button>
                </div>
            `).join('');
        };

        const filterStudents = () => {
            return allStudents.filter(student => {
                if (modalCourseFilter.value && String(student.course_id) !== modalCourseFilter
                    .value) return false;
                if (modalYearFilter.value && String(student.year) !== modalYearFilter.value)
                    return false;
                if (modalDepartmentFilter.value && student.department !== modalDepartmentFilter
                    .value) return false;

                if (modalStudentSearch.value) {
                    const q = modalStudentSearch.value.toLowerCase();
                    return student.name.toLowerCase().includes(q) || student.student_id
                        .toLowerCase().includes(q);
                }

                return true;
            });
        };

        window.selectStudent = (id) => {
            selectedStudents.add(id);
            renderAvailableStudents();
            renderSelectedStudents();
        };

        window.removeStudent = (id) => {
            selectedStudents.delete(id);
            renderAvailableStudents();
            renderSelectedStudents();
        };

        const debounce = (fn, wait) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), wait);
            };
        };

        modalCourseFilter.addEventListener('change', () => loadStudents());
        modalYearFilter.addEventListener('change', () => loadStudents());
        modalDepartmentFilter.addEventListener('change', () => loadStudents());
        modalStudentSearch.addEventListener('input', debounce(() => loadStudents(), 250));

        modal.addEventListener('show.bs.modal', () => {
            // No-op; openStudentModal handles class selection.
        });

        saveButton.addEventListener('click', () => {
            const payload = {
                class_id: currentClassId,
                student_ids: Array.from(selectedStudents)
            };

            fetch('<?php echo e(route('admin.classes.assign-students')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        modalInstance.hide();
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to save assignments');
                    }
                })
                .catch(() => {
                    alert('Failed to save assignments');
                });
        });
    });
</script>
<?php /**PATH C:\laragon\www\edutrack\resources\views/admin/classes/partials/student-assignment-modal.blade.php ENDPATH**/ ?>