<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white border-0"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title fw-bold" id="addStudentModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Add Student to Class (Updated)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs border-bottom" id="addStudentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="manual-tab" data-bs-toggle="tab"
                            data-bs-target="#manual" type="button" role="tab" aria-controls="manual"
                            aria-selected="true">
                            <i class="fas fa-keyboard me-2"></i> Manual Entry
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing"
                            type="button" role="tab" aria-controls="existing" aria-selected="false">
                            <i class="fas fa-search me-2"></i> Existing Students
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="excel-tab" data-bs-toggle="tab" data-bs-target="#excel"
                            type="button" role="tab" aria-controls="excel" aria-selected="false">
                            <i class="fas fa-file-excel me-2"></i> Excel Import
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="addStudentTabContent">
                    <!-- Manual Entry Tab -->
                    <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                        <form id="manualStudentForm" method="POST" action="<?php echo e(route('teacher.students.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="manual_class_id" class="form-label fw-bold">Class <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="manual_class_id" name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>">
                                            <?php echo e($class->class_name); ?> (<?php echo e($class->class_level); ?>) -
                                            <?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="manual_firstname" class="form-label fw-bold">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control form-control-lg <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="manual_firstname" name="firstname" placeholder="e.g., John" required>
                                <?php $__errorArgs = ['firstname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="manual_surname" class="form-label fw-bold">Surname <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control form-control-lg <?php $__errorArgs = ['surname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="manual_surname" name="surname" placeholder="e.g., Doe" required>
                                <?php $__errorArgs = ['surname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="manual_email" class="form-label fw-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="manual_email" name="email" placeholder="e.g., john@example.com" required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="manual_year" class="form-label fw-bold">Year <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="manual_year" name="year" required>
                                        <option value="">-- Select Year --</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                    <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="manual_section" class="form-label fw-bold">Section <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="manual_section" name="section" required>
                                        <option value="">-- Select Section --</option>
                                        <option value="A">Section A</option>
                                        <option value="B">Section B</option>
                                        <option value="C">Section C</option>
                                        <option value="D">Section D</option>
                                        <option value="E">Section E</option>
                                    </select>
                                    <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Password:</strong> A temporary password will be generated automatically. Student
                                can reset it on first login.
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg fw-bold text-white"
                                    style="background-color: #667eea; border: none;">
                                    <i class="fas fa-save me-2"></i> Add Student
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Students Tab -->
                    <div class="tab-pane fade" id="existing" role="tabpanel" aria-labelledby="existing-tab">
                        <div class="alert alert-info border-0 mb-4">
                            <h6 class="alert-heading fw-bold">
                                <i class="fas fa-search me-2"></i> Search & Add Existing Students
                            </h6>
                            <p class="mb-0 small">Search for students already in the system and add them to your class. If no students are found, switch to Manual Entry to create new students.</p>
                        </div>

                        <div class="mb-3">
                            <label for="existing_class_id" class="form-label fw-bold">Select Class <span
                                    class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="existing_class_id" required>
                                <option value="">-- Select Class --</option>
                                <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>">
                                        <?php echo e($class->class_name); ?> (<?php echo e($class->section ?? 'Year 1'); ?>) -
                                        <?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?> students
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="student_search" class="form-label fw-bold">
                                <i class="fas fa-search me-2"></i>Search Students
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="student_search"
                                    placeholder="Type name, email, or student ID to search...">
                                <button class="btn btn-outline-secondary" type="button" id="clear_search_btn">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">Search across all students in the database</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="show_unassigned_only">
                                    <label class="form-check-label" for="show_unassigned_only">
                                        <i class="fas fa-user-clock me-1"></i>Unassigned students only
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="show_same_year_only">
                                    <label class="form-check-label" for="show_same_year_only">
                                        <i class="fas fa-calendar me-1"></i>Same year level only
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Search Results Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 fw-bold">Search Results</h6>
                            <span class="badge bg-primary" id="results_count">0 students found</span>
                        </div>

                        <!-- Students Results -->
                        <div id="students_results" class="border rounded" style="max-height: 350px; overflow-y: auto;">
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-search fa-3x mb-3"></i>
                                <h6>Start Searching</h6>
                                <p class="mb-0">Select a class and type to search for students</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" id="add_selected_students" class="btn btn-lg fw-bold text-white flex-grow-1"
                                style="background-color: #667eea; border: none;" disabled>
                                <i class="fas fa-user-plus me-2"></i> Add Selected Students
                                <span class="badge bg-light text-dark ms-2" id="selected_count">0</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="switch_to_manual_btn">
                                <i class="fas fa-plus me-2"></i> Create New
                            </button>
                        </div>

                        <!-- Help Text -->
                        <div class="alert alert-light border mt-3 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <div class="small">
                                    <strong>Can't find the student?</strong> Use the "Create New" button to switch to manual entry and add a new student to the system.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Excel Import Tab -->
                    <div class="tab-pane fade" id="excel" role="tabpanel" aria-labelledby="excel-tab">
                        <form id="excelImportForm" method="POST" action="<?php echo e(route('teacher.students.import')); ?>"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="excel_class_id" class="form-label fw-bold">Class <span
                                        class="text-danger">*</span></label>
                                <select class="form-select form-select-lg <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="excel_class_id" name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>">
                                            <?php echo e($class->class_name); ?> (<?php echo e($class->class_level); ?>) -
                                            <?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="mb-3">
                                <label for="excel_file" class="form-label fw-bold">Excel File (.xlsx) <span
                                        class="text-danger">*</span></label>
                                <input type="file"
                                    class="form-control form-control-lg <?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                <?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- File Format Instructions -->
                            <div class="alert alert-info border-0 mb-3">
                                <h6 class="alert-heading fw-bold">
                                    <i class="fas fa-file-excel me-2"></i> Excel File Format
                                </h6>
                                <hr class="my-2">
                                <p class="mb-2 small"><strong>Required Columns:</strong></p>
                                <ul class="small mb-0">
                                    <li><strong>Column A - First Name:</strong> Student first name (required)</li>
                                    <li><strong>Column B - Surname:</strong> Student surname (required)</li>
                                    <li><strong>Column C - Email:</strong> Valid email address (required)</li>
                                    <li><strong>Column D - Year:</strong> Academic year 1-4 (required)</li>
                                    <li><strong>Column E - Section:</strong> Section A-E (required)</li>
                                </ul>
                            </div>

                            <!-- Example Data -->
                            <div class="alert alert-light border mb-3">
                                <h6 class="fw-bold mb-2">Example:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>First Name</th>
                                                <th>Surname</th>
                                                <th>Email</th>
                                                <th>Year</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>John</td>
                                                <td>Doe</td>
                                                <td>john@example.com</td>
                                                <td>1</td>
                                                <td>A</td>
                                            </tr>
                                            <tr>
                                                <td>Jane</td>
                                                <td>Smith</td>
                                                <td>jane@example.com</td>
                                                <td>1</td>
                                                <td>B</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?php echo e(asset('templates/students_import_template.xlsx')); ?>"
                                    class="btn btn-outline-secondary" download>
                                    <i class="fas fa-download me-2"></i> Download Template
                                </a>
                                <button type="submit" class="btn btn-lg fw-bold text-white"
                                    style="background-color: #667eea; border: none;">
                                    <i class="fas fa-upload me-2"></i> Import Students
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sync class selection between tabs
        const manualClassSelect = document.getElementById('manual_class_id');
        const excelClassSelect = document.getElementById('excel_class_id');
        const existingClassSelect = document.getElementById('existing_class_id');

        function syncClassSelects(source, targets) {
            source.addEventListener('change', function() {
                targets.forEach(target => {
                    if (target) target.value = this.value;
                });
                // Trigger search if existing tab is active
                if (document.getElementById('existing-tab').classList.contains('active')) {
                    searchStudents();
                }
            });
        }

        if (manualClassSelect) {
            syncClassSelects(manualClassSelect, [excelClassSelect, existingClassSelect]);
        }
        if (excelClassSelect) {
            syncClassSelects(excelClassSelect, [manualClassSelect, existingClassSelect]);
        }
        if (existingClassSelect) {
            syncClassSelects(existingClassSelect, [manualClassSelect, excelClassSelect]);
        }

        // Existing students functionality
        let selectedStudents = new Set();
        const studentSearch = document.getElementById('student_search');
        const showUnassignedOnly = document.getElementById('show_unassigned_only');
        const showSameYearOnly = document.getElementById('show_same_year_only');
        const clearSearchBtn = document.getElementById('clear_search_btn');
        const switchToManualBtn = document.getElementById('switch_to_manual_btn');
        const studentsResults = document.getElementById('students_results');
        const addSelectedBtn = document.getElementById('add_selected_students');
        const resultsCount = document.getElementById('results_count');
        const selectedCount = document.getElementById('selected_count');

        // Search students
        function searchStudents() {
            const classId = existingClassSelect?.value;
            const searchTerm = studentSearch?.value || '';
            const unassignedOnly = showUnassignedOnly?.checked || false;
            const sameYearOnly = showSameYearOnly?.checked || false;

            if (!classId) {
                studentsResults.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h6>Select a Class First</h6>
                        <p class="mb-0">Please select a class to search for students</p>
                    </div>`;
                resultsCount.textContent = '0 students found';
                return;
            }

            // Show loading
            studentsResults.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Searching students...</p>
                </div>`;

            // Build query parameters
            const params = new URLSearchParams({
                class_id: classId,
                search: searchTerm,
                unassigned_only: unassignedOnly,
                same_year_only: sameYearOnly
            });

            // Make AJAX request to search students
            fetch(`/teacher/students/search?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    displayStudents(data.students || []);
                    resultsCount.textContent = `${data.students?.length || 0} students found`;
                })
                .catch(error => {
                    console.error('Error searching students:', error);
                    studentsResults.innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h6>Search Error</h6>
                            <p class="mb-0">Error loading students. Please try again.</p>
                        </div>`;
                    resultsCount.textContent = 'Error';
                });
        }

        // Display students in results
        function displayStudents(students) {
            if (students.length === 0) {
                studentsResults.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-user-slash fa-3x mb-3"></i>
                        <h6>No Students Found</h6>
                        <p class="mb-0">Try adjusting your search or filters</p>
                    </div>`;
                addSelectedBtn.disabled = true;
                selectedCount.textContent = '0';
                return;
            }

            let html = '<div class="list-group list-group-flush">';
            students.forEach(student => {
                const isSelected = selectedStudents.has(student.id);
                html += `
                    <div class="list-group-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${student.id}" 
                                id="student_${student.id}" ${isSelected ? 'checked' : ''} 
                                onchange="toggleStudent(${student.id})">
                            <label class="form-check-label w-100" for="student_${student.id}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">${student.name}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>${student.email}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card me-1"></i>ID: ${student.student_id || 'N/A'}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-info">${student.year || 'N/A'} Year</span>
                                        <span class="badge bg-secondary">${student.section || 'N/A'}</span>
                                        ${student.current_class ? 
                                            `<span class="badge bg-warning">
                                                <i class="fas fa-users me-1"></i>${student.current_class}
                                            </span>` : 
                                            '<span class="badge bg-success">
                                                <i class="fas fa-user-clock me-1"></i>Unassigned
                                            </span>'
                                        }
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>`;
            });
            html += '</div>';
            studentsResults.innerHTML = html;
            addSelectedBtn.disabled = selectedStudents.size === 0;
        }

        // Toggle student selection
        window.toggleStudent = function(studentId) {
            if (selectedStudents.has(studentId)) {
                selectedStudents.delete(studentId);
            } else {
                selectedStudents.add(studentId);
            }
            addSelectedBtn.disabled = selectedStudents.size === 0;
            selectedCount.textContent = selectedStudents.size;
        };

        // Event listeners
        if (studentSearch) {
            let searchTimeout;
            studentSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchStudents, 300);
            });
        }

        if (showUnassignedOnly) {
            showUnassignedOnly.addEventListener('change', searchStudents);
        }

        if (showSameYearOnly) {
            showSameYearOnly.addEventListener('change', searchStudents);
        }

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                studentSearch.value = '';
                searchStudents();
            });
        }

        if (switchToManualBtn) {
            switchToManualBtn.addEventListener('click', function() {
                // Switch to manual entry tab
                const manualTab = document.getElementById('manual-tab');
                const manualPane = document.getElementById('manual');
                
                // Remove active from existing tab
                document.getElementById('existing-tab').classList.remove('active');
                document.getElementById('existing').classList.remove('show', 'active');
                
                // Add active to manual tab
                manualTab.classList.add('active');
                manualPane.classList.add('show', 'active');
                
                // Copy class selection
                if (existingClassSelect?.value && manualClassSelect) {
                    manualClassSelect.value = existingClassSelect.value;
                }
            });
        }

        // Add selected students
        if (addSelectedBtn) {
            addSelectedBtn.addEventListener('click', function() {
                const classId = existingClassSelect?.value;
                if (!classId || selectedStudents.size === 0) return;

                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';

                fetch('/teacher/students/add-existing', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        class_id: classId,
                        student_ids: Array.from(selectedStudents)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `
                            <i class="fas fa-check-circle me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        studentsResults.insertBefore(alert, studentsResults.firstChild);
                        
                        // Reset selection
                        selectedStudents.clear();
                        searchStudents(); // Refresh list
                        
                        // Close modal after delay
                        setTimeout(() => {
                            bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
                            // Reload page to show updated class
                            location.reload();
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Failed to add students');
                    }
                })
                .catch(error => {
                    console.error('Error adding students:', error);
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    studentsResults.insertBefore(alert, studentsResults.firstChild);
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-user-plus me-2"></i> Add Selected Students';
                });
            });
        }

        // Form validation feedback
        const forms = document.querySelectorAll('#manualStudentForm, #excelImportForm');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            }, false);
        });
    });
</script>
<?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/components/add-student-modal.blade.php ENDPATH**/ ?>