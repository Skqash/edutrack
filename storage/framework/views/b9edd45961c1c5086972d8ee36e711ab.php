

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h3 class="mb-0">School Connection Request</h3>
                <p class="text-muted">Review and update the request status.</p>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('admin.school-requests.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Requests
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <h5>Teacher Information</h5>
                        <p class="mb-1"><strong>Name:</strong> <?php echo e($schoolRequest->user->name); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo e($schoolRequest->user->email); ?></p>
                        <p class="mb-1"><strong>Requested:</strong>
                            <?php echo e($schoolRequest->created_at->format('F j, Y \a\t g:i A')); ?></p>
                    </div>
                    <div class="col-lg-6">
                        <h5>Request Details</h5>
                        <p class="mb-1"><strong>School:</strong> <?php echo e($schoolRequest->school_name); ?></p>
                        <?php if($schoolRequest->school_email): ?>
                            <p class="mb-1"><strong>School Email:</strong> <?php echo e($schoolRequest->school_email); ?></p>
                        <?php endif; ?>
                        <?php if($schoolRequest->school_phone): ?>
                            <p class="mb-1"><strong>School Phone:</strong> <?php echo e($schoolRequest->school_phone); ?></p>
                        <?php endif; ?>
                        <?php if($schoolRequest->school_address): ?>
                            <p class="mb-1"><strong>School Address:</strong> <?php echo e($schoolRequest->school_address); ?></p>
                        <?php endif; ?>
                        <?php if($schoolRequest->note): ?>
                            <p class="mb-1"><strong>Teacher Message:</strong> <?php echo e($schoolRequest->note); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>

                <form action="<?php echo e(route('admin.school-requests.update', $schoolRequest)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" <?php echo e($schoolRequest->status === 'pending' ? 'selected' : ''); ?>>
                                    Pending</option>
                                <option value="approved" <?php echo e($schoolRequest->status === 'approved' ? 'selected' : ''); ?>>
                                    Approved</option>
                                <option value="rejected" <?php echo e($schoolRequest->status === 'rejected' ? 'selected' : ''); ?>>
                                    Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Assign Connected School</label>
                            <?php
                                $campuses = [
                                    'Victorias Campus',
                                    'Main Campus',
                                    'Candoni Campus',
                                    'Cauayan Campus',
                                    'Hinigaran Campus',
                                    'Hinoba-an Campus',
                                    'Ilog Campus',
                                    'Moises Padilla Campus',
                                    'San Carlos Campus',
                                    'Sipalay Campus',
                                ];

                                $currentConnected =
                                    old('connected_school') ??
                                    ($schoolRequest->user->teacher->connected_school ??
                                        null ??
                                        $schoolRequest->school_name);
                            ?>
                            <select name="connected_school" class="form-select">
                                <option value="">(Select campus or use requested school)</option>
                                <?php $__currentLoopData = $campuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($campus); ?>"
                                        <?php echo e($currentConnected === $campus ? 'selected' : ''); ?>>
                                        <?php echo e($campus); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Set the connected school when approving the request.</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Admin Note (optional)</label>
                            <textarea name="admin_note" class="form-control" rows="2"><?php echo e(old('admin_note', $schoolRequest->admin_note)); ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </form>

                <?php if(session('success')): ?>
                    <div class="alert alert-success mt-3">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\school_requests\show.blade.php ENDPATH**/ ?>