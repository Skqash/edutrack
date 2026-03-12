

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-edit"></i>
            </span>
            Edit Degree Program
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('admin.courses.index')); ?>">Academic Programs</a></li>
                <li class="breadcrumb-item active">Edit Program</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-university me-2 text-primary"></i>Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.courses.update', $course)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="program_code" class="form-label fw-bold">
                                        <i class="fas fa-barcode text-primary"></i> Program Code
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
                                           value="<?php echo e(old('program_code', $course->program_code)); ?>" required>
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
                                    <label for="duration" class="form-label fw-bold">
                                        <i class="fas fa-clock text-primary"></i> Program Duration
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="duration" name="duration" required>
                                        <option value="">Select duration...</option>
                                        <option value="1 Year" <?php echo e(old('duration', $course->duration) == '1 Year' ? 'selected' : ''); ?>>1 Year</option>
                                        <option value="2 Years" <?php echo e(old('duration', $course->duration) == '2 Years' ? 'selected' : ''); ?>>2 Years</option>
                                        <option value="3 Years" <?php echo e(old('duration', $course->duration) == '3 Years' ? 'selected' : ''); ?>>3 Years</option>
                                        <option value="4 Years" <?php echo e(old('duration', $course->duration) == '4 Years' ? 'selected' : ''); ?>>4 Years</option>
                                        <option value="5 Years" <?php echo e(old('duration', $course->duration) == '5 Years' ? 'selected' : ''); ?>>5 Years</option>
                                    </select>
                                    <?php $__errorArgs = ['duration'];
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
                                <i class="fas fa-graduation-cap text-primary"></i> Program Name
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
                                   value="<?php echo e(old('program_name', $course->program_name)); ?>" required>
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
                            <label for="department" class="form-label fw-bold">
                                <i class="fas fa-sitemap text-primary"></i> Department
                            </label>
                            <select class="form-select <?php $__errorArgs = ['department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="department" name="department" required>
                                <option value="">Select department...</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($department); ?>" <?php echo e(old('department', $course->department) == $department ? 'selected' : ''); ?>>
                                        <?php echo e($department); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['department'];
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
                                <i class="fas fa-align-left text-primary"></i> Program Description
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
                                      placeholder="Describe the degree program, objectives, and career opportunities..."><?php echo e(old('description', $course->description)); ?></textarea>
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
                                        <i class="fas fa-users text-primary"></i> Maximum Students
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
                                           value="<?php echo e(old('max_students', $course->max_students) ?? 50); ?>">
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
                                        <i class="fas fa-toggle-on text-primary"></i> Status
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
                                        <option value="Active" <?php echo e(old('status', $course->status) == 'Active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="Inactive" <?php echo e(old('status', $course->status) == 'Inactive' ? 'selected' : ''); ?>>Inactive</option>
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
                                        <i class="fas fa-user-tie text-primary"></i> Program Head
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
                                            <option value="<?php echo e($head->id); ?>" <?php echo e(old('head_id', $course->head_id) == $head->id ? 'selected' : ''); ?>>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update Program
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
                        <i class="fas fa-info-circle text-info me-2"></i>Program Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Current Program Details</h6>
                        <div class="small">
                            <div class="mb-2"><strong>Code:</strong> <?php echo e($course->program_code); ?></div>
                            <div class="mb-2"><strong>Name:</strong> <?php echo e($course->program_name); ?></div>
                            <div class="mb-2"><strong>College:</strong> <?php echo e($course->college); ?></div>
                            <div class="mb-2"><strong>Students:</strong> <?php echo e($course->current_students ?? 0); ?> / <?php echo e($course->max_students ?? 50); ?></div>
                            <div class="mb-2"><strong>Status:</strong> <?php echo e($course->status); ?></div>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.courses.show', $course)); ?>" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-2"></i>View Program Details
                        </a>
                        <a href="<?php echo e(route('admin.courses.manageSubjects', $course)); ?>" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-book me-2"></i>Manage Subjects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
                                                                                                    

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/courses/edit.blade.php ENDPATH**/ ?>