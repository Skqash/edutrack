

<?php $__env->startSection('content'); ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-university me-2" style="color: #27ae60;"></i>Academic Programs
                    </h2>
                    <p class="text-muted">Manage degree programs and their academic departments</p>
                </div>
                <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Add New Program
                </a>
            </div>
        </div>
    </div>

    <!-- Academic Programs Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #27ae60;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Programs</h6>
                            <h2 class="mb-0 fw-bold" style="color: #27ae60;"><?php echo e($courses->count()); ?></h2>
                        </div>
                        <i class="fas fa-university" style="font-size: 35px; color: rgba(39, 174, 96, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #3498db;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Active Programs</h6>
                            <h2 class="mb-0 fw-bold" style="color: #3498db;"><?php echo e($courses->where('status', 'Active')->count()); ?></h2>
                        </div>
                        <i class="fas fa-check-circle" style="font-size: 35px; color: rgba(52, 152, 219, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #f39c12;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Total Students</h6>
                            <h2 class="mb-0 fw-bold" style="color: #f39c12;"><?php echo e($courses->sum(function($course) { return $course->students ?? 0; })); ?></h2>
                        </div>
                        <i class="fas fa-user-graduate" style="font-size: 35px; color: rgba(243, 156, 18, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #9b59b6;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div style="flex: 1;">
                            <h6 class="text-muted text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                Departments</h6>
                            <h2 class="mb-0 fw-bold" style="color: #9b59b6;"><?php echo e($courses->pluck('department')->unique()->count()); ?></h2>
                        </div>
                        <i class="fas fa-building" style="font-size: 35px; color: rgba(155, 89, 182, 0.1);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Programs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-university me-2" style="color: #27ae60;"></i>All Degree Programs
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: 180px;" id="departmentFilter">
                        <option value="">All Departments</option>
                        <?php $__currentLoopData = $courses->pluck('department')->unique(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department); ?>"><?php echo e($department); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="programSearch" placeholder="Search programs...">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color: #f0f9f4;">
                        <tr>
                            <th class="fw-bold" style="color: #27ae60;">Program Code</th>
                            <th class="fw-bold" style="color: #27ae60;">Degree Program</th>
                            <th class="fw-bold" style="color: #27ae60;">Department</th>
                            <th class="fw-bold" style="color: #27ae60;">Students</th>
                            <th class="fw-bold" style="color: #27ae60;">Years</th>
                            <th class="fw-bold" style="color: #27ae60;">Status</th>
                            <th class="fw-bold" style="color: #27ae60;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong class="text-success"><?php echo e($course->course_code); ?></strong></td>
                            <td>
                                <div>
                                    <strong><?php echo e($course->course_name); ?></strong>
                                    <br><small class="text-muted"><?php echo e($course->description ?? 'Bachelor of Science in ' . $course->course_name); ?></small>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border border-success"><?php echo e($course->department ?? 'General Education'); ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-graduate me-2 text-muted"></i>
                                    <span class="badge bg-info"><?php echo e($course->students ?? 0); ?></span>
                                </div>
                            </td>
                            <td><span class="badge bg-success"><?php echo e($course->duration ?? '4 Years'); ?></span></td>
                            <td><span class="badge bg-<?php echo e($course->status == 'Active' ? 'success' : 'danger'); ?>"><?php echo e($course->status); ?></span></td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap actions-container">
                                    <!-- View Details -->
                                    <a href="<?php echo e(route('admin.courses.show', $course->id)); ?>" 
                                       class="btn btn-sm btn-info d-flex align-items-center justify-content-center" 
                                       title="View Program Details"
                                       style="min-width: 38px; height: 38px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Program -->
                                    <a href="<?php echo e(route('admin.courses.edit', $course->id)); ?>" 
                                       class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                       title="Edit Program"
                                       style="min-width: 38px; height: 38px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Manage Subjects -->
                                    <a href="<?php echo e(route('admin.courses.manageSubjects', $course->id)); ?>" 
                                       class="btn btn-sm btn-success d-flex align-items-center justify-content-center" 
                                       title="Manage Subjects"
                                       style="min-width: 38px; height: 38px;">
                                        <i class="fas fa-book"></i>
                                    </a>
                                    
                                    <!-- Delete Program -->
                                    <form action="<?php echo e(route('admin.courses.destroy', $course->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" 
                                                onclick="return confirm('Are you sure you want to delete this degree program? This action cannot be undone.')" 
                                                title="Delete Program"
                                                style="min-width: 38px; height: 38px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No degree programs found</p>
                                <a href="<?php echo e(route('admin.courses.create')); ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-2"></i>Add First Program
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Example Programs Reference -->
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 fw-bold text-muted">
                <i class="fas fa-info-circle me-2"></i>Common Degree Program Examples
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="fw-bold text-success mb-3">College of Information Technology</h6>
                    <ul class="list-unstyled small">
                        <li><strong>BSIT</strong> - Bachelor of Science in Information Technology</li>
                        <li><strong>BSCS</strong> - Bachelor of Science in Computer Science</li>
                        <li><strong>BSIS</strong> - Bachelor of Science in Information Systems</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-success mb-3">College of Education</h6>
                    <ul class="list-unstyled small">
                        <li><strong>BEED</strong> - Bachelor of Elementary Education</li>
                        <li><strong>BSED</strong> - Bachelor of Secondary Education</li>
                        <li><strong>BSAB</strong> - Bachelor of Special Needs Education</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-success mb-3">College of Agriculture</h6>
                    <ul class="list-unstyled small">
                        <li><strong>BS-Agri</strong> - Bachelor of Science in Agriculture</li>
                        <li><strong>BSA</strong> - Bachelor of Science in Agribusiness</li>
                        <li><strong>BSF</strong> - Bachelor of Science in Forestry</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-success mb-3">College of Business</h6>
                    <ul class="list-unstyled small">
                        <li><strong>BSBA</strong> - Bachelor of Science in Business Administration</li>
                        <li><strong>BSA</strong> - Bachelor of Science in Accountancy</li>
                        <li><strong>BSTM</strong> - Bachelor of Science in Tourism Management</li>
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
    
    .btn-success:hover {
        background-color: #0f5132;
        border-color: #0f5132;
    }
    
    .btn-danger:hover {
        background-color: #842029;
        border-color: #842029;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .actions-container {
        min-width: 170px;
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
    // Add filtering functionality
    const departmentFilter = document.getElementById('departmentFilter');
    const programSearch = document.getElementById('programSearch');
    const tableRows = document.querySelectorAll('tbody tr');
    
    function filterTable() {
        const selectedDepartment = departmentFilter.value.toLowerCase();
        const searchTerm = programSearch.value.toLowerCase();
        
        tableRows.forEach(row => {
            if (row.querySelector('td[colspan]')) return; // Skip empty row
            
            const department = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const programName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const programCode = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
            
            const matchesDepartment = !selectedDepartment || department.includes(selectedDepartment);
            const matchesSearch = !searchTerm || 
                programName.includes(searchTerm) || 
                programCode.includes(searchTerm);
            
            row.style.display = matchesDepartment && matchesSearch ? '' : 'none';
        });
    }
    
    departmentFilter.addEventListener('change', filterTable);
    programSearch.addEventListener('input', filterTable);
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
            const programName = this.closest('tr').querySelector('td:nth-child(2) strong').textContent;
            if (!confirm(`⚠️ DELETE CONFIRMATION ⚠️\n\nAre you sure you want to delete "${programName}"?\n\nThis will permanently remove:\n• All program data\n• Associated subjects\n• Student enrollments\n\nThis action CANNOT be undone!`)) {
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/courses.blade.php ENDPATH**/ ?>