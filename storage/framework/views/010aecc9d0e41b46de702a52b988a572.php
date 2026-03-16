<?php $__env->startSection('content'); ?>
    <style>
        .change-password-header {
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .password-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
        }

        .form-control:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.15);
        }

        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .password-requirements {
            background: #f8f9fa;
            border-left: 3px solid #0066cc;
            padding: 1.5rem;
            border-radius: 4px;
            margin-bottom: 2rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .requirement i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .requirement.met i {
            color: #28a745;
        }

        .requirement.unmet i {
            color: #6c757d;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .password-strength {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .password-strength.weak {
            background: #ffebee;
            color: #c62828;
        }

        .password-strength.medium {
            background: #fff3e0;
            color: #e65100;
        }

        .password-strength.strong {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>

    <!-- Header -->
    <div class="change-password-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h1 class="h2 fw-bold mb-0">
                        <i class="fas fa-lock me-2"></i> Change Password
                    </h1>
                </div>
                <div class="col-auto ms-auto">
                    <p class="mb-0 text-white-50 small">Keep your account secure with a strong password</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 mx-auto" style="max-width: 600px;">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Change Password Form -->
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card password-card">
                <div class="card-body p-4">
                    <form
                        action="<?php echo e(route(auth()->user()->role === 'teacher' ? 'teacher.profile.change-password.update' : 'admin.profile.change-password.update')); ?>"
                        method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key me-2 text-primary"></i> Current Password *
                            </label>
                            <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="current_password" name="current_password" required>
                            <?php $__errorArgs = ['current_password'];
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

                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i> Password Requirements
                            </h6>
                            <div class="requirement unmet" id="length-req">
                                <i class="fas fa-check-circle"></i>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="requirement unmet" id="uppercase-req">
                                <i class="fas fa-check-circle"></i>
                                <span>Uppercase letter (A-Z)</span>
                            </div>
                            <div class="requirement unmet" id="lowercase-req">
                                <i class="fas fa-check-circle"></i>
                                <span>Lowercase letter (a-z)</span>
                            </div>
                            <div class="requirement unmet" id="number-req">
                                <i class="fas fa-check-circle"></i>
                                <span>Number (0-9)</span>
                            </div>
                            <div class="requirement unmet" id="special-req">
                                <i class="fas fa-check-circle"></i>
                                <span>Special character (!@#$%^&*)</span>
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock-open me-2 text-primary"></i> New Password *
                            </label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="password" name="password" required oninput="checkPasswordStrength()">
                            <div id="strength" class="password-strength" style="display:none;"></div>
                            <?php $__errorArgs = ['password'];
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

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock me-2 text-primary"></i> Confirm Password *
                            </label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="password_confirmation" name="password_confirmation" required
                                oninput="checkPasswordMatch()">
                            <small class="text-muted d-block mt-1" id="match-status"></small>
                            <?php $__errorArgs = ['password_confirmation'];
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

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="<?php echo e(auth()->user()->role === 'teacher' ? route('teacher.profile.show') : route('admin.profile.show')); ?>"
                                class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card mt-4" style="border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shield-alt me-2"></i> Security Tips
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Use a unique password</strong> - Don't reuse passwords from other accounts
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Make it complex</strong> - Mix letters, numbers, and symbols
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Avoid personal info</strong> - Don't use birthdate, names, or common words
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Keep it secret</strong> - Never share your password with anyone
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Update regularly</strong> - Change your password every 90 days
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('strength');

            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*]/.test(password);

            // Update requirement indicators
            updateRequirement('length-req', hasLength);
            updateRequirement('uppercase-req', hasUppercase);
            updateRequirement('lowercase-req', hasLowercase);
            updateRequirement('number-req', hasNumber);
            updateRequirement('special-req', hasSpecial);

            // Calculate strength
            const strength = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;

            // Show strength indicator
            if (password.length === 0) {
                strengthDiv.style.display = 'none';
            } else {
                strengthDiv.style.display = 'block';
                if (strength < 3) {
                    strengthDiv.textContent = '⚠️ Weak Password';
                    strengthDiv.className = 'password-strength weak';
                } else if (strength < 5) {
                    strengthDiv.textContent = '⚠️ Medium Strength';
                    strengthDiv.className = 'password-strength medium';
                } else {
                    strengthDiv.textContent = '✓ Strong Password';
                    strengthDiv.className = 'password-strength strong';
                }
            }

            checkPasswordMatch();
        }

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            if (met) {
                element.classList.remove('unmet');
                element.classList.add('met');
                element.querySelector('i').className = 'fas fa-check-circle';
            } else {
                element.classList.remove('met');
                element.classList.add('unmet');
                element.querySelector('i').className = 'fas fa-check-circle';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchStatus = document.getElementById('match-status');

            if (confirmation.length === 0) {
                matchStatus.textContent = '';
            } else if (password === confirmation) {
                matchStatus.textContent = '✓ Passwords match';
                matchStatus.className = 'text-success fw-bold';
            } else {
                matchStatus.textContent = '✗ Passwords do not match';
                matchStatus.className = 'text-danger fw-bold';
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->user()->role === 'teacher' ? 'layouts.teacher' : 'layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\profile\change-password.blade.php ENDPATH**/ ?>