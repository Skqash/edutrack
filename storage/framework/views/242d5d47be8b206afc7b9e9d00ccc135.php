

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
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Manual Student Entry</h5>
                </div>
                <div class="card-body">
                    <form id="manualStudentForm" method="POST" action="<?php echo e(route('teacher.students.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class<span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_id" name="class_id" required>
                                <option value="">-- Select Class --</option>
                                <?php $__currentLoopData = $myClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
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
                            <label for="name" class="form-label">Student Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" name="name" placeholder="Enter full name" required>
                            <?php $__errorArgs = ['name'];
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
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="email" name="email" placeholder="student@example.com" required>
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

                        <div class="mb-3">
                            <label for="admission_number" class="form-label">Admission Number</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['admission_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="admission_number" name="admission_number" placeholder="e.g., ADM-2024-001">
                            <?php $__errorArgs = ['admission_number'];
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
                            <label for="roll_number" class="form-label">Roll Number</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['roll_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="roll_number" name="roll_number" placeholder="e.g., 01">
                            <?php $__errorArgs = ['roll_number'];
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

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Add Student
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Excel Import Form -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); border: none;">
                    <h5 class="mb-0"><i class="fas fa-file-excel me-2"></i>Bulk Import (Excel)</h5>
                </div>
                <div class="card-body">
                    <form id="excelImportForm" method="POST" action="<?php echo e(route('teacher.students.import')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="class_id_excel" class="form-label">Class<span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_id_excel" name="class_id" required>
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
                            <label for="excel_file" class="form-label">Excel File (.xlsx)<span class="text-danger">*</span></label>
                            <input type="file" class="form-control <?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                            <small class="text-muted d-block mt-2">
                                <strong>Required columns:</strong> Name, Email, Admission Number (optional), Roll Number (optional)
                            </small>
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

                        <div class="alert alert-info mb-3">
                            <h6><i class="fas fa-info-circle me-2"></i>Excel File Format</h6>
                            <small>
                                <p class="mb-1"><strong>Column A:</strong> Name (required)</p>
                                <p class="mb-1"><strong>Column B:</strong> Email (required)</p>
                                <p class="mb-1"><strong>Column C:</strong> Admission Number (optional)</p>
                                <p class="mb-0"><strong>Column D:</strong> Roll Number (optional)</p>
                            </small>
                        </div>

                        <a href="<?php echo e(asset('templates/students_import_template.xlsx')); ?>" class="btn btn-sm btn-secondary mb-3 w-100">
                            <i class="fas fa-download me-2"></i>Download Template
                        </a>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-upload me-2"></i>Import Students
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Added Students -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Class Students</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($students->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Name</th>
                                        <th>Email</th>
                                        <th>Admission #</th>
                                        <th>Roll #</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong><?php echo e($student->user->name ?? 'N/A'); ?></strong>
                                            </td>
                                            <td><?php echo e($student->user->email ?? 'N/A'); ?></td>
                                            <td><?php echo e($student->admission_number ?? '-'); ?></td>
                                            <td><?php echo e($student->roll_number ?? '-'); ?></td>
                                            <td>
                                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <?php echo e(ucfirst($student->status ?? 'Active')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="javascript:void(0)" class="btn btn-outline-secondary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger" title="Remove" onclick="confirmRemove(<?php echo e($student->id); ?>)">
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
function confirmRemove(studentId) {
    if (confirm('Are you sure you want to remove this student from the class?')) {
        // Implement removal logic
        console.log('Remove student:', studentId);
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/students/add.blade.php ENDPATH**/ ?>