

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h3 class="mb-0">Your School Connection Requests</h3>
                <p class="text-muted">Review all connection requests you've submitted and their current status.</p>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('teacher.school-request.create')); ?>" class="btn btn-secondary">
                    <i class="fas fa-plus me-2"></i> New Request
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if($requests->isEmpty()): ?>
                    <div class="alert alert-info">You have not submitted any school connection requests yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Admin Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($request->school_name); ?></td>
                                        <td>
                                            <span
                                                class="badge bg-<?php echo e($request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'secondary')); ?>">
                                                <?php echo e(ucfirst($request->status)); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($request->created_at->diffForHumans()); ?></td>
                                        <td><?php echo e($request->admin_note ?? '—'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <?php echo e($requests->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\teacher\school_request\history.blade.php ENDPATH**/ ?>