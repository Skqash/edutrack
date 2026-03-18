

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow auth-card">
            <div class="card-body">

                <h4 class="text-center mb-4">Reset Your Password</h4>
                <p class="text-center text-muted mb-4">Enter your email address and we'll send you a password reset link.</p>

                <?php if(session('status')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?php echo e(session('status')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/forgot-password">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                            placeholder="Enter your email">
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

                    <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                </form>

                <div class="text-center mt-4 auth-footer">
                    <p class="mb-1">
                        <a href="/login" class="text-decoration-none">Back to Login</a>
                    </p>
                    <p class="mb-1">
                        <a href="mailto:support@edutrack.example.com" class="text-decoration-none">Contact Support</a>
                    </p>
                    <p class="mb-0 text-muted">Don't have an account? Contact support to request access.</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>