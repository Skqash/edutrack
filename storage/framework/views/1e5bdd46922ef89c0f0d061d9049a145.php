<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Add New Degree Program
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.courses.index')); ?>">Academic Programs</a></li>
                <li class="breadcrumb-item active">Add Program</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-university me-2 text-success"></i>Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.courses.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="program_code" class="form-label fw-bold">
                                        <i class="fas fa-barcode text-success"></i> Program Code
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['program_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="program_code" name="program_code" 
                                           placeholder="e.g., BSIT, BEED, BS-Agri" 
                                           value="<?php echo e(old('program_code')); ?>" required>
                                    <div class="form-text">Standard university program code (e.g., BSIT, BSCS, BEED)</div>
                                    <?php $__errorArgs = ['program_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_semesters" class="form-label fw-bold">
                                        <i class="fas fa-layers text-success"></i> Total Semesters
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['total_semesters'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="total_semesters" name="total_semesters" required>
                                        <option value="">Select semesters...</option>
                                        <option value="2" <?php echo e(old('total_semesters') == '2' ? 'selected' : ''); ?>>2 Semesters (1 Year)</option>
                                        <option value="4" <?php echo e(old('total_semesters') == '4' ? 'selected' : ''); ?>>4 Semesters (2 Years)</option>
                                        <option value="6" <?php echo e(old('total_semesters') == '6' ? 'selected' : ''); ?>>6 Semesters (3 Years)</option>
                                        <option value="8" <?php echo e(old('total_semesters') == '8' ? 'selected' : ''); ?> selected>8 Semesters (4 Years)</option>
                                        <option value="10" <?php echo e(old('total_semesters') == '10' ? 'selected' : ''); ?>>10 Semesters (5 Years)</option>
                                    </select>
                                    <?php $__errorArgs = ['total_semesters'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="program_name" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-success"></i> Program Name
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['program_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="program_name" name="program_name" 
                                   placeholder="e.g., Bachelor of Science in Information Technology" 
                                   value="<?php echo e(old('program_name')); ?>" required>
                            <div class="form-text">Full degree program name</div>
                            <?php $__errorArgs = ['program_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="department_id" class="form-label fw-bold">
                                <i class="fas fa-sitemap text-success"></i> Department
                            </label>
                            <select class="form-select <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="department_id" name="department_id" required>
                                <option value="">Select department...</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id') == $department->id ? 'selected' : ''); ?>>
                                        <?php echo e($department->department_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="form-text">Department under which this program is offered</div>
                            <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label fw-bold">
                                <i class="fas fa-align-left text-success"></i> Program Description
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Describe the degree program, objectives, and career opportunities..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="max_students" class="form-label fw-bold">
                                        <i class="fas fa-users text-success"></i> Maximum Students
                                    </label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['max_students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="max_students" name="max_students" 
                                           placeholder="50" min="1" max="500" 
                                           value="<?php echo e(old('max_students') ?? 50); ?>">
                                    <?php $__errorArgs = ['max_students'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on text-success"></i> Status
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="status" name="status" required>
                                        <option value="Active" <?php echo e(old('status') == 'Active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="Inactive" <?php echo e(old('status') == 'Inactive' ? 'selected' : ''); ?>>Inactive</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="head_id" class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-success"></i> Program Head
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="head_id" name="head_id">
                                        <option value="">Select program head...</option>
                                        <?php $__currentLoopData = $heads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $head): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($head->id); ?>" <?php echo e(old('head_id') == $head->id ? 'selected' : ''); ?>>
                                                <?php echo e($head->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="form-text">Optional: Assigned program head</div>
                                    <?php $__errorArgs = ['head_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('admin.courses.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Programs
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i> Create Program
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Program Guidelines
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <ul class="mb-0 small">
                            <li>Use standard program codes (BSIT, BEED, BS-Agri)</li>
                            <li>Provide clear, descriptive program names</li>
                            <li>Assign appropriate college for organization</li>
                            <li>Set realistic student capacity limits</li>
                        </ul>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Common Program Codes</h6>
                    <div class="small">
                        <div class="mb-2"><strong>BSIT</strong> - Information Technology</div>
                        <div class="mb-2"><strong>BSCS</strong> - Computer Science</div>
                        <div class="mb-2"><strong>BEED</strong> - Elementary Education</div>
                        <div class="mb-2"><strong>BSED</strong> - Secondary Education</div>
                        <div class="mb-2"><strong>BS-Agri</strong> - Agriculture</div>
                        <div class="mb-2"><strong>BSBA</strong> - Business Administration</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
                                
                                            

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/courses/create.blade.php ENDPATH**/ ?>