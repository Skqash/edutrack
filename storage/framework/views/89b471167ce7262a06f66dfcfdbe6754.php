<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="fas fa-book"></i>
                </span>
                Manage Subjects - <?php echo e($teacher->name); ?>

            </h3>
            <div class="page-breadcrumb">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn btn-sm btn-primary ms-2">
                    <i class="fas fa-chalkboard-user"></i> Teachers
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Assigned Subjects -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-book me-2"></i> Assigned Subjects
                            <span class="badge bg-success ms-2"><?php echo e($assignedSubjects->count()); ?></span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if($assignedSubjects->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Credits</th>
                                            <th>Course</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $assignedSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-info"><?php echo e($subject->subject_code); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo e($subject->subject_name); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($subject->category); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo e($subject->credit_hours); ?> units</span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?php echo e($subject->course->course_name ?? 'N/A'); ?></small>
                                                </td>
                                                <td>
                                                    <form action="<?php echo e(route('admin.teachers.remove-subject', [$teacher->id, $subject->id])); ?>" 
                                                          method="POST" style="display:inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Remove this subject assignment?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No subjects assigned yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Available Subjects -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i> Available Subjects
                            <span class="badge bg-primary ms-2"><?php echo e($availableSubjects->count()); ?></span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if($availableSubjects->count() > 0): ?>
                            <form action="<?php echo e(route('admin.teachers.assign-subjects', $teacher->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                                </th>
                                                <th>Subject Code</th>
                                                <th>Subject Name</th>
                                                <th>Credits</th>
                                                <th>Course</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $availableSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="subject_ids[]" value="<?php echo e($subject->id); ?>" 
                                                               class="form-check-input subject-checkbox">
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info"><?php echo e($subject->subject_code); ?></span>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo e($subject->subject_name); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo e($subject->category); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo e($subject->credit_hours); ?> units</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted"><?php echo e($subject->course->course_name ?? 'N/A'); ?></small>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if($availableSubjects->count() > 0): ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <small class="text-muted">
                                                <span id="selectedCount">0</span> subject(s) selected
                                            </small>
                                        </div>
                                        <button type="submit" class="btn btn-gradient-primary" id="assignBtn" disabled>
                                            <i class="fas fa-plus me-2"></i>Assign Selected Subjects
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p class="text-muted">All available subjects are already assigned</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Info Card -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="badge badge-gradient-warning rounded-circle me-3"
                                 style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <?php echo e(strtoupper(substr($teacher->name, 0, 1))); ?>

                            </div>
                            <div>
                                <h5 class="mb-1"><?php echo e($teacher->name); ?></h5>
                                <p class="text-muted mb-0"><?php echo e($teacher->email); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Joined <?php echo e($teacher->created_at->format('d M Y')); ?>

                                </small>
                            </div>
                            <div class="ms-auto">
                                <span class="badge bg-gradient-success">Active Teacher</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
            const selectedCountSpan = document.getElementById('selectedCount');
            const assignBtn = document.getElementById('assignBtn');

            function updateSelectedCount() {
                const checkedCount = document.querySelectorAll('.subject-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                assignBtn.disabled = checkedCount === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                subjectCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });

            subjectCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedCount();
                    
                    // Update select all checkbox state
                    const totalCheckboxes = subjectCheckboxes.length;
                    const checkedCheckboxes = document.querySelectorAll('.subject-checkbox:checked').length;
                    
                    selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes;
                    selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
                });
            });

            // Initialize
            updateSelectedCount();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\teachers\subjects.blade.php ENDPATH**/ ?>