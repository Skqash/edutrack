<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-user-plus me-2"></i>Add Student to Class</h2>
                    <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Classes
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Manual Addition Form -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Manual Student Entry</h5>
                    </div>
                    <div class="card-body">
                        <form id="manualStudentForm" method="POST" action="<?php echo e(route('teacher.students.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="class_id" class="form-label fw-bold">Class<span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_id"
                                    name="class_id" required onchange="updateClassInfo()">
                                    <option value="">-- Select Class --</option>
                                    <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>"
                                                data-student-count="<?php echo e($class->students->count()); ?>"
                                                data-capacity="<?php echo e($class->capacity); ?>"
                                                data-year="<?php echo e($class->year); ?>"
                                                data-section="<?php echo e($class->section); ?>">
                                            <?php echo e($class->class_name); ?> (<?php echo e($class->year ?? 'Year 1'); ?>) - Section <?php echo e($class->section); ?> - <?php echo e($class->students->count()); ?>/<?php echo e($class->capacity); ?> students
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">Select the class to add this student to</small>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Student Name<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="name" name="name" placeholder="Enter full name (e.g., Juan Dela Cruz)" required>
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">Enter student's complete name as shown in official records</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="email" name="email" placeholder="student@example.com" required>
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">Must be a valid email address for student login</small>
                            </div>

                            <div class="mb-3">
                                <label for="admission_number" class="form-label fw-bold">Admission Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php $__errorArgs = ['admission_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="admission_number" name="admission_number" placeholder="e.g., ADM-2024-001" oninput="generateStudentId()">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                </div>
                                <?php $__errorArgs = ['admission_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">Official admission number from school records</small>
                            </div>

                            <div class="mb-3">
                                <label for="roll_number" class="form-label fw-bold">Roll Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php $__errorArgs = ['roll_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="roll_number" name="roll_number" placeholder="e.g., 01" oninput="generateStudentId()">
                                    <span class="input-group-text">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </span>
                                </div>
                                <?php $__errorArgs = ['roll_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted d-block mt-1">Class roll number (optional)</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Add Student
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i>Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Excel Import Form -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Bulk Import (Excel)</h5>
                    </div>
                    <div class="card-body">
                        <form id="excelImportForm" method="POST" action="<?php echo e(route('teacher.students.import')); ?>"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="class_id_excel" class="form-label">Class<span
                                        class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_id_excel"
                                    name="class_id" required>
                                    <option value="">-- Select Class --</option>
                                    <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>">
                                            <?php echo e($class->name); ?> (<?php echo e($class->level); ?>)
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
                                <label for="excel_file" class="form-label fw-bold">Excel File (.xlsx)<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="file" class="form-control <?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="excel_file" name="excel_file" accept=".xlsx,.xls" required onchange="validateExcelFile(this)">
                                    <span class="input-group-text">
                                        <i class="fas fa-file-excel"></i>
                                    </span>
                                </div>
                                <div id="fileInfo" class="mt-2 text-muted small"></div>
                                <?php $__errorArgs = ['excel_file'];
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
                                    <strong>Required columns:</strong> Name, Email, Admission Number (optional), Roll Number (optional)
                                    <br><strong>Format:</strong> .xlsx or .xls files only
                                </small>

                            <div class="alert alert-info mb-3">
                                <h6><i class="fas fa-info-circle me-2"></i>Excel File Format</h6>
                                <small>
                                    <p class="mb-1"><strong>Column A:</strong> Name (required)</p>
                                    <p class="mb-1"><strong>Column B:</strong> Email (required)</p>
                                    <p class="mb-1"><strong>Column C:</strong> Admission Number (optional)</p>
                                    <p class="mb-0"><strong>Column D:</strong> Roll Number (optional)</p>
                                </small>
                            </div>

                            <a href="<?php echo e(asset('templates/students_import_template.xlsx')); ?>"
                                class="btn btn-sm btn-secondary mb-3 w-100">
                                <i class="fas fa-download me-2"></i>Download Template
                            </a>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success" id="importBtn">
                                    <i class="fas fa-upload me-2"></i>Import Students
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="resetImportForm()">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Added Students -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Class Students</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if($students->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3" style="width: 15%;"><i
                                                    class="fas fa-id-card me-1 text-primary"></i>Student ID</th>
                                            <th style="width: 25%;"><i class="fas fa-user me-1 text-primary"></i>Name</th>
                                            <th style="width: 20%;"><i class="fas fa-envelope me-1 text-primary"></i>Email
                                            </th>
                                            <th style="width: 15%;"><i class="fas fa-file me-1 text-primary"></i>Admission
                                                #</th>
                                            <th style="width: 10%;"><i class="fas fa-list me-1 text-primary"></i>Roll #
                                            </th>
                                            <th style="width: 10%;"><i
                                                    class="fas fa-check-circle me-1 text-primary"></i>Status</th>
                                            <th style="width: 5%;" class="text-center"><i
                                                    class="fas fa-trash me-1 text-primary"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-3">
                                                    <span class="badge bg-info text-white fw-bold"
                                                        style="font-size: 0.85rem; letter-spacing: 0.5px;"><?php echo e($student->student_id ?? 'N/A'); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo e($student->user->name ?? 'N/A'); ?></strong>
                                                <td><?php echo e($student->user->email ?? 'N/A'); ?></td>
                                                <td><?php echo e($student->admission_number ?? '-'); ?></td>
                                                <td><?php echo e($student->roll_number ?? '-'); ?></td>
                                                <td>
                                                    <span class="badge"
                                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <?php echo e(ucfirst($student->status ?? 'Active')); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="javascript:void(0)" class="btn btn-outline-secondary"
                                                            title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger"
                                                            title="Remove" onclick="confirmRemove(<?php echo e($student->id); ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-2 opacity-50"></i>
                                <p>No students added yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_id');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const admissionInput = document.getElementById('admission_number');
        const rollInput = document.getElementById('roll_number');
        const submitBtn = document.getElementById('submitBtn');

        // Update class info when class is selected
        window.updateClassInfo = function() {
            const selectedOption = classSelect.options[classSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const studentCount = selectedOption.getAttribute('data-student-count');
                const capacity = selectedOption.getAttribute('data-capacity');
                const year = selectedOption.getAttribute('data-year');
                const section = selectedOption.getAttribute('data-section');
                
                // Show class info alert
                showClassInfo(studentCount, capacity, year, section);
            }
        };

        // Generate student ID based on class and name
        window.generateStudentId = function() {
            const selectedOption = classSelect.options[classSelect.selectedIndex];
            const name = nameInput.value.trim();
            
            if (selectedOption && name) {
                const year = selectedOption.getAttribute('data-year') || '2024';
                const section = selectedOption.getAttribute('data-section') || 'A';
                
                // Generate student ID: YEAR-SECTION-ROLL
                const rollNumber = rollInput.value.trim() || Math.floor(Math.random() * 99) + 1;
                const studentId = `${year}-${section}-${String(rollNumber).padStart(2, '0')}`;
                
                // Auto-fill admission number if empty
                if (!admissionInput.value.trim()) {
                    admissionInput.value = `ADM-${studentId}`;
                }
            }
        };

        // Show class information
        function showClassInfo(studentCount, capacity, year, section) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.class-info-alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create info alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-info alert-dismissible fade show class-info-alert';
            alert.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                <strong>Class Information:</strong> ${year} - Section ${section}<br>
                <small>Current students: ${studentCount}/${capacity}</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert after class select
            classSelect.parentNode.insertBefore(alert, classSelect.nextSibling);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }

        // Reset form
        window.resetForm = function() {
            document.getElementById('manualStudentForm').reset();
            
            // Remove class info alerts
            const existingAlerts = document.querySelectorAll('.class-info-alert');
            existingAlerts.forEach(alert => alert.remove());
        };

        // Form validation and submission
        document.getElementById('manualStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
            
            // Simulate form submission
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Add Student';
                
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i> Student added successfully!';
                
                // Insert at top of form
                const form = document.getElementById('manualStudentForm');
                form.parentNode.insertBefore(successAlert, form);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    if (successAlert.parentNode) {
                        successAlert.parentNode.removeChild(successAlert);
                    }
                }, 3000);
                
                // Reset form
                resetForm();
            }, 1000);
        });
        // Excel file validation
        window.validateExcelFile = function(input) {
            const file = input.files[0];
            const fileInfo = document.getElementById('fileInfo');
            
            if (file) {
                const fileName = file.name.toLowerCase();
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                const validTypes = ['.xlsx', '.xls'];
                const fileExt = fileName.substring(fileName.lastIndexOf('.'));
                
                if (validTypes.includes(fileExt)) {
                    fileInfo.innerHTML = `
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Selected:</strong> ${fileName} (${fileSize} MB)
                        <small class="d-block mt-1">File type: ${fileExt.toUpperCase()}</small>
                    `;
                    fileInfo.className = 'text-success';
                } else {
                    fileInfo.innerHTML = `
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        <strong>Invalid file type!</strong><br>
                        Please select .xlsx or .xls file only.
                    `;
                    fileInfo.className = 'text-danger';
                    input.value = ''; // Clear the input
                }
            } else {
                fileInfo.innerHTML = '<i class="fas fa-info-circle me-2"></i>No file selected';
                fileInfo.className = 'text-muted';
            }
        };

        // Reset import form
        window.resetImportForm = function() {
            document.getElementById('excelImportForm').reset();
            document.getElementById('fileInfo').innerHTML = '';
            document.getElementById('fileInfo').className = 'text-muted';
        };

        // Handle import form submission
        document.getElementById('excelImportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const importBtn = document.getElementById('importBtn');
            const fileInput = document.getElementById('excel_file');
            
            if (!fileInput.files[0]) {
                alert('Please select a file to import');
                return;
            }
            
            // Show loading state
            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importing...';
            
            // Simulate import process
            setTimeout(() => {
                importBtn.disabled = false;
                importBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Import Students';
                
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i> Students imported successfully!';
                
                // Insert at top of form
                const form = document.getElementById('excelImportForm');
                form.parentNode.insertBefore(successAlert, form);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    if (successAlert.parentNode) {
                        successAlert.parentNode.removeChild(successAlert);
                    }
                }, 3000);
                
                // Reset form
                resetImportForm();
            }, 2000);
        });
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\students\add.blade.php ENDPATH**/ ?>