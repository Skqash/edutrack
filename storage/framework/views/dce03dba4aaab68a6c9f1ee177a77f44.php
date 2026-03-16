<?php $__env->startSection('content'); ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-book-open me-2" style="color: #6c757d;"></i>Subject Management
                    </h2>
                    <p class="text-muted">Manage subjects across all degree programs and departments</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger" onclick="syncAllSubjects()" title="Sync all subjects with their courses">
                        <i class="fas fa-sync me-2"></i>Sync Subjects
                    </button>
                    <a href="<?php echo e(route('admin.subjects.create')); ?>" class="btn btn-danger">
                        <i class="fas fa-plus me-2"></i> Add New Subject
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #6c757d;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Subjects</h6>
                            <h2 class="mb-0 fw-bold" style="color: #6c757d;"><?php echo e($subjects->count()); ?></h2>
                        </div>
                        <i class="fas fa-book" style="font-size: 35px; color: rgba(108, 117, 125, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #0d6efd;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Categories</h6>
                            <h2 class="mb-0 fw-bold" style="color: #0d6efd;"><?php echo e($subjects->pluck('category')->unique()->count()); ?></h2>
                        </div>
                        <i class="fas fa-tags" style="font-size: 35px; color: rgba(13, 110, 253, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #198754;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Credits</h6>
                            <h2 class="mb-0 fw-bold" style="color: #198754;"><?php echo e($subjects->sum('credit_hours')); ?></h2>
                        </div>
                        <i class="fas fa-clock" style="font-size: 35px; color: rgba(25, 135, 84, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #fd7e14;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Programs</h6>
                            <h2 class="mb-0 fw-bold" style="color: #fd7e14;"><?php echo e($subjects->pluck('program')->unique()->count()); ?></h2>
                        </div>
                        <i class="fas fa-graduation-cap" style="font-size: 35px; color: rgba(253, 126, 20, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Grouped by Courses -->
    <?php
        $groupedSubjects = $subjects->groupBy('program');
        // Sort programs alphabetically, but keep General Education first
        $sortedPrograms = $groupedSubjects->sortBy(function($subjects, $program) {
            return $program === 'General Education' ? '0' : $program;
        });
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $sortedPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program => $programSubjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-graduation-cap me-2" style="color: #6c757d;"></i><?php echo e($program); ?>

                    </h5>
                    <?php if($program !== 'General Education' && $programSubjects->first()->course): ?>
                        <small class="text-muted ms-3">(<?php echo e($programSubjects->first()->course->program_code ?? 'N/A'); ?>)</small>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex gap-2">
                        <span class="badge bg-secondary"><?php echo e($programSubjects->count()); ?> Subjects</span>
                        <span class="badge bg-success"><?php echo e($programSubjects->sum('credit_hours')); ?> Credits</span>
                        <span class="badge bg-primary"><?php echo e($programSubjects->where('type', 'Core')->count()); ?> Core</span>
                    </div>
                    <?php if($program !== 'General Education'): ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="syncProgramSubjects('<?php echo e($program); ?>')" title="Sync subjects in this program">
                            <i class="fas fa-sync"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="fw-bold" style="color: #495057;">Subject Code</th>
                        <th class="fw-bold" style="color: #495057;">Subject Name</th>
                        <th class="fw-bold" style="color: #495057;">Units</th>
                        <th class="fw-bold" style="color: #495057;">Category</th>
                        <th class="fw-bold" style="color: #495057;">Type</th>
                        <th class="fw-bold" style="color: #495057;">Instructor</th>
                        <th class="fw-bold" style="color: #495057;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $programSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong class="text-dark"><?php echo e($subject->subject_code); ?></strong></td>
                            <td>
                                <div>
                                    <strong class="text-dark"><?php echo e($subject->subject_name); ?></strong>
                                    <br><small class="text-muted"><?php echo e($subject->description ?? 'Learn fundamental concepts and principles'); ?></small>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo e($subject->credit_hours ?? 3); ?></span></td>
                            <td><span class="badge bg-light text-dark border"><?php echo e($subject->category); ?></span></td>
                            <td><span class="badge bg-<?php echo e($subject->type == 'Core' ? 'primary' : ($subject->type == 'Elective' ? 'warning' : 'info')); ?>"><?php echo e($subject->type ?? 'General'); ?></span></td>
                            <td>
                                <?php if($subject->instructor): ?>
                                    <span class="badge bg-light text-dark border"><?php echo e($subject->instructor->name); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted">Not Assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap actions-container">
                                    <!-- View Details -->
                                    <a href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>" 
                                       class="btn btn-sm btn-info d-flex align-items-center justify-content-center" 
                                       title="View Subject Details"
                                       style="min-width: 38px; height: 38px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Subject -->
                                    <a href="<?php echo e(route('admin.subjects.edit', $subject->id)); ?>" 
                                       class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                       title="Edit Subject"
                                       style="min-width: 38px; height: 38px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Delete Subject -->
                                    <form action="<?php echo e(route('admin.subjects.destroy', $subject->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" 
                                                onclick="return confirm('Are you sure you want to delete this subject? This action cannot be undone.')" 
                                                title="Delete Subject"
                                                style="min-width: 38px; height: 38px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No subjects found</h5>
            <p class="text-muted">Start by adding subjects to your degree programs.</p>
            <a href="<?php echo e(route('admin.subjects.create')); ?>" class="btn btn-danger">
                <i class="fas fa-plus me-2"></i>Add First Subject
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Subject Examples Reference -->
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 fw-bold text-muted">
                <i class="fas fa-info-circle me-2"></i>Subject Examples by Program
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-3">Information Technology</h6>
                    <ul class="list-unstyled small">
                        <li><strong>IT101</strong> - Introduction to Programming (Core, 3 units)</li>
                        <li><strong>IT102</strong> - Web Development (Core, 3 units)</li>
                        <li><strong>CS201</strong> - Data Structures (Core, 4 units)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-3">Education</h6>
                    <ul class="list-unstyled small">
                        <li><strong>ED101</strong> - Principles of Teaching (Core, 3 units)</li>
                        <li><strong>ED102</strong> - Child Development (Core, 3 units)</li>
                        <li><strong>ED201</strong> - Educational Psychology (Elective, 3 units)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-3">Agriculture</h6>
                    <ul class="list-unstyled small">
                        <li><strong>AGR101</strong> - Introduction to Agriculture (Core, 3 units)</li>
                        <li><strong>AGR201</strong> - Crop Science (Core, 4 units)</li>
                        <li><strong>AGR301</strong> - Sustainable Farming (Elective, 3 units)</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-3">General Education</h6>
                    <ul class="list-unstyled small">
                        <li><strong>GEN101</strong> - Mathematics in Modern World (General, 3 units)</li>
                        <li><strong>GEN102</strong> - Purposive Communication (General, 3 units)</li>
                        <li><strong>GEN103</strong> - Philippine History (General, 3 units)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Ensure FontAwesome icons are displayed properly */
    .fas, .far, .fab {
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }
    
    .btn-sm {
        transition: all 0.2s ease-in-out;
        font-weight: 500;
    }
    
    .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .btn-info:hover {
        background-color: #0c5460;
        border-color: #0c5460;
    }
    
    .btn-warning:hover {
        background-color: #d63384;
        border-color: #d63384;
    }
    
    .btn-danger:hover {
        background-color: #842029;
        border-color: #842029;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .actions-container {
        min-width: 130px;
    }
    
    @media (max-width: 768px) {
        .actions-container {
            min-width: auto;
            flex-direction: column;
        }
        
        .actions-container .btn {
            width: 38px;
            height: 38px;
            justify-content: center;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add tooltip functionality
    const buttons = document.querySelectorAll('[title]');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
    
    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('form button[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const subjectName = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
            if (!confirm(`⚠️ DELETE CONFIRMATION ⚠️\n\nAre you sure you want to delete "${subjectName}"?\n\nThis will permanently remove:\n• All subject data\n• Student enrollments\n• Grade records\n\nThis action CANNOT be undone!`)) {
                e.preventDefault();
            }
        });
    });
    
    // Add loading state to buttons
    const actionButtons = document.querySelectorAll('.btn-sm');
    actionButtons.forEach(button => {
        if (!button.form) { // Skip delete buttons
            button.addEventListener('click', function() {
                const originalContent = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Reset after 2 seconds (in case of slow navigation)
                setTimeout(() => {
                    this.innerHTML = originalContent;
                    this.disabled = false;
                }, 2000);
            });
        }
    });
});

// Sync all subjects with their courses
function syncAllSubjects() {
    if (!confirm('Sync all subjects with their respective courses?\n\nThis will update the program field for all subjects based on their assigned course.')) {
        return;
    }
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Syncing...';
    button.disabled = true;
    
    fetch('<?php echo e(route("admin.subjects.syncAll")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error syncing subjects: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error syncing subjects. Please try again.');
    })
    .finally(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
    });
}

// Sync subjects for a specific program
function syncProgramSubjects(program) {
    if (!confirm(`Sync all subjects in "${program}" with their course information?`)) {
        return;
    }
    
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // This would need a corresponding route and controller method
    // For now, just reload the page
    setTimeout(() => {
        location.reload();
    }, 1000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views\admin\subjects.blade.php ENDPATH**/ ?>