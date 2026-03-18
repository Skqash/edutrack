

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="fas fa-plus-circle"></i>
            </span>
            Create New Class
        </h3>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Classes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="<?php echo e(route('admin.classes.store')); ?>" method="POST" id="classForm">
                <?php echo csrf_field(); ?>

                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle"></i> Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="class_name" class="form-label">
                                        <i class="fas fa-door-open"></i> Class Name *
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_name" 
                                           name="class_name" placeholder="e.g., BSIT-1A, BSCS-2B" value="<?php echo e(old('class_name')); ?>" required>
                                    <?php $__errorArgs = ['class_name'];
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
                                    <label for="class_level" class="form-label">
                                        <i class="fas fa-layer-group"></i> Year Level *
                                    </label>
                                    <select name="class_level" id="class_level" class="form-select <?php $__errorArgs = ['class_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Select year level...</option>
                                        <option value="1" <?php echo e(old('class_level') == '1' ? 'selected' : ''); ?>>1st Year</option>
                                        <option value="2" <?php echo e(old('class_level') == '2' ? 'selected' : ''); ?>>2nd Year</option>
                                        <option value="3" <?php echo e(old('class_level') == '3' ? 'selected' : ''); ?>>3rd Year</option>
                                        <option value="4" <?php echo e(old('class_level') == '4' ? 'selected' : ''); ?>>4th Year</option>
                                    </select>
                                    <?php $__errorArgs = ['class_level'];
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

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="section" class="form-label">
                                        <i class="fas fa-tag"></i> Section *
                                    </label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="section" 
                                           name="section" placeholder="e.g., A, B, C" value="<?php echo e(old('section')); ?>" required>
                                    <?php $__errorArgs = ['section'];
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
                                    <label for="capacity" class="form-label">
                                        <i class="fas fa-users"></i> Capacity *
                                    </label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="capacity" 
                                           name="capacity" placeholder="e.g., 60" value="<?php echo e(old('capacity')); ?>" min="10" required>
                                    <?php $__errorArgs = ['capacity'];
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
                                    <label for="status" class="form-label">
                                        <i class="fas fa-toggle-on"></i> Status *
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
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
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" 
                                      name="description" rows="3" placeholder="Enter class description"><?php echo e(old('description')); ?></textarea>
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
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-graduation-cap"></i> Academic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_id" class="form-label">
                                        <i class="fas fa-book"></i> Course/Program
                                    </label>
                                    <select name="course_id" id="course_id" class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select course...</option>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>" 
                                                    data-department="<?php echo e($course->department ?? ''); ?>"
                                                    <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                                                <?php echo e($course->program_code); ?> - <?php echo e($course->program_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['course_id'];
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
                                    <label for="teacher_id" class="form-label">
                                        <i class="fas fa-chalkboard-user"></i> Class Teacher *
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['teacher_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="teacher_id" name="teacher_id" required>
                                        <option value="">Select a teacher...</option>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>" <?php echo e(old('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                                <?php echo e($teacher->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['teacher_id'];
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="academic_year" class="form-label">
                                        <i class="fas fa-calendar-alt"></i> Academic Year *
                                    </label>
                                    <select name="academic_year" id="academic_year" class="form-select <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($year); ?>" <?php echo e(old('academic_year') == $year ? 'selected' : ''); ?>>
                                                <?php echo e($year); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['academic_year'];
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
                                    <label for="semester" class="form-label">
                                        <i class="fas fa-book-open"></i> Semester *
                                    </label>
                                    <select name="semester" id="semester" class="form-select <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($semester); ?>" <?php echo e(old('semester') == $semester ? 'selected' : ''); ?>>
                                                <?php echo e($semester); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['semester'];
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
                    </div>
                </div>

                <!-- Teacher Assignments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chalkboard-teacher"></i> Teacher Assignments
                        </h5>
                        <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" id="createAssignment" name="create_assignment" value="1">
                            <label class="form-check-label" for="createAssignment">
                                Enable assignments
                            </label>
                        </div>
                    </div>
                    <div class="card-body" id="assignmentSection" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Create teacher assignments for this class. You can assign multiple teachers to different subjects and manage student assignments.
                        </div>

                        <!-- Teacher and Subject Assignment -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assignment_teachers" class="form-label">
                                        <i class="fas fa-users"></i> Assign Teachers *
                                    </label>
                                    <select name="assignment_teachers[]" id="assignment_teachers" class="form-select" multiple size="4">
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple teachers</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assignment_subjects" class="form-label">
                                        <i class="fas fa-book"></i> Assign Subjects
                                    </label>
                                    <select name="assignment_subjects[]" id="assignment_subjects" class="form-select" multiple size="4">
                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->subject_code); ?> - <?php echo e($subject->subject_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple subjects</small>
                                </div>
                            </div>
                        </div>

                        <!-- Department and Notes -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assignment_department" class="form-label">
                                        <i class="fas fa-building"></i> Department
                                    </label>
                                    <select name="assignment_department" id="assignment_department" class="form-select">
                                        <option value="">Select department...</option>
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($department); ?>"><?php echo e($department); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assignment_notes" class="form-label">
                                        <i class="fas fa-sticky-note"></i> Assignment Notes
                                    </label>
                                    <textarea name="assignment_notes" id="assignment_notes" class="form-control" 
                                              rows="2" placeholder="Add notes about this assignment..."><?php echo e(old('assignment_notes')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Student Assignment Section -->
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user-graduate"></i> Student Assignments
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="student_year_filter" class="form-label">Year</label>
                                            <select id="student_year_filter" class="form-select form-select-sm">
                                                <option value="">All Years</option>
                                                <option value="1">1st Year</option>
                                                <option value="2">2nd Year</option>
                                                <option value="3">3rd Year</option>
                                                <option value="4">4th Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="student_search" class="form-label">Search by Student ID or Name</label>
                                            <input type="text" id="student_search" class="form-control form-control-sm" placeholder="Search students...">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">
                                                <strong>Available Students</strong>
                                                <span class="badge bg-secondary ms-2" id="availableCount">0</span>
                                            </label>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllAvailable">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllAvailable">Clear</button>
                                            </div>
                                        </div>
                                        <div id="availableStudents" class="border rounded p-2" style="height: 200px; overflow-y: auto;">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-spinner fa-spin"></i> Loading students...
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">
                                                <strong>Selected Students</strong>
                                                <span class="badge bg-success ms-2" id="selectedCount">0</span>
                                            </label>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-success" id="selectAllSelected">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAllSelected">Clear</button>
                                            </div>
                                        </div>
                                        <div id="selectedStudents" class="border rounded p-2" style="height: 200px; overflow-y: auto;">
                                            <div class="text-center text-muted">No students selected</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden input to store selected student IDs -->
                                <input type="hidden" name="assigned_students" id="assigned_students" value="">
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Note:</strong> When enabled, this will create assignments for selected teachers to this class and subjects. 
                            Each teacher will be assigned to the class, and if subjects are selected, each teacher will be assigned to all selected subjects.
                            Selected students will be assigned to these teacher assignments.
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group">
                    <button type="submit" class="btn btn-gradient-success">
                        <i class="fas fa-save"></i> Create Class
                    </button>
                    <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-gradient-success {
        background: linear-gradient(45deg, #27ae60, #2ecc71);
        color: white;
        border: none;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(45deg, #229954, #27ae60);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #27ae60;
        box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .form-check-input:checked {
        background-color: #27ae60;
        border-color: #27ae60;
    }

    select[multiple] {
        min-height: 100px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const createAssignmentCheckbox = document.getElementById('createAssignment');
    const assignmentSection = document.getElementById('assignmentSection');
    const courseIdSelect = document.getElementById('course_id');
    const departmentSelect = document.getElementById('assignment_department');

    // Student assignment variables
    let allStudents = [];
    let selectedStudents = new Set();
    
    const studentYearFilter = document.getElementById('student_year_filter');
    const studentSearch = document.getElementById('student_search');
    const availableStudentsDiv = document.getElementById('availableStudents');
    const selectedStudentsDiv = document.getElementById('selectedStudents');
    const availableCount = document.getElementById('availableCount');
    const selectedCount = document.getElementById('selectedCount');
    const assignedStudentsInput = document.getElementById('assigned_students');

    // Toggle assignment section
    createAssignmentCheckbox.addEventListener('change', function() {
        assignmentSection.style.display = this.checked ? 'block' : 'none';
        if (this.checked) {
            loadStudents();
        }
    });

    // Auto-fill department when course is selected
    courseIdSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const department = selectedOption.dataset.department;
        if (department && !departmentSelect.value) {
            departmentSelect.value = department;
        }
    });

    // Load students function
    function loadStudents() {
        fetch('/admin/classes/get-students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                year: studentYearFilter.value,
                search: studentSearch.value
            })
        })
        .then(response => response.json())
        .then(data => {
            allStudents = data.students || [];
            renderAvailableStudents();
            renderSelectedStudents();
        })
        .catch(error => {
            console.error('Error loading students:', error);
            availableStudentsDiv.innerHTML = '<div class="text-center text-danger">Error loading students</div>';
        });
    }

    // Render available students
    function renderAvailableStudents() {
        const filteredStudents = filterStudents();
        const availableStudents = filteredStudents.filter(student => !selectedStudents.has(student.id));
        
        availableCount.textContent = availableStudents.length;
        
        if (availableStudents.length === 0) {
            availableStudentsDiv.innerHTML = '<div class="text-center text-muted">No available students</div>';
            return;
        }
        
        let html = '';
        availableStudents.forEach(student => {
            html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom hover-bg-light">
                    <input type="checkbox" class="form-check-input me-2" value="${student.id}" 
                           onchange="toggleStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}
                        </small>
                    </div>
                </div>
            `;
        });
        
        availableStudentsDiv.innerHTML = html;
    }

    // Render selected students
    function renderSelectedStudents() {
        const selectedStudentsList = Array.from(selectedStudents).map(id => 
            allStudents.find(student => student.id === id)
        ).filter(Boolean);
        
        selectedCount.textContent = selectedStudentsList.length;
        
        if (selectedStudentsList.length === 0) {
            selectedStudentsDiv.innerHTML = '<div class="text-center text-muted">No students selected</div>';
            assignedStudentsInput.value = '';
            return;
        }
        
        let html = '';
        selectedStudentsList.forEach(student => {
            html += `
                <div class="student-item d-flex align-items-center p-2 border-bottom">
                    <input type="checkbox" class="form-check-input me-2" checked value="${student.id}" 
                           onchange="toggleStudent(${student.id})">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${student.name}</div>
                        <small class="text-muted">
                            ${student.student_id} • ${student.course_name} • Year ${student.year} • ${student.section}
                        </small>
                    </div>
                </div>
            `;
        });
        
        selectedStudentsDiv.innerHTML = html;
        assignedStudentsInput.value = Array.from(selectedStudents).join(',');
    }

    // Filter students
    function filterStudents() {
        return allStudents.filter(student => {
            if (studentYearFilter.value && student.year != studentYearFilter.value) return false;
            if (studentSearch.value) {
                const search = studentSearch.value.toLowerCase();
                return student.name.toLowerCase().includes(search) || 
                       student.student_id.toLowerCase().includes(search);
            }
            return true;
        });
    }

    // Toggle student selection
    window.toggleStudent = function(studentId) {
        if (selectedStudents.has(studentId)) {
            selectedStudents.delete(studentId);
        } else {
            selectedStudents.add(studentId);
        }
        renderAvailableStudents();
        renderSelectedStudents();
    };

    // Event listeners for filters
    studentYearFilter.addEventListener('change', loadStudents);
    studentSearch.addEventListener('input', debounce(loadStudents, 300));

    // Select/Clear buttons
    document.getElementById('selectAllAvailable').addEventListener('click', function() {
        const checkboxes = availableStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                cb.checked = true;
                toggleStudent(parseInt(cb.value));
            }
        });
    });

    document.getElementById('deselectAllAvailable').addEventListener('click', function() {
        const checkboxes = availableStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            if (cb.checked) {
                cb.checked = false;
                toggleStudent(parseInt(cb.value));
            }
        });
    });

    document.getElementById('selectAllSelected').addEventListener('click', function() {
        const checkboxes = selectedStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            if (!cb.checked) {
                cb.checked = true;
                toggleStudent(parseInt(cb.value));
            }
        });
    });

    document.getElementById('deselectAllSelected').addEventListener('click', function() {
        const checkboxes = selectedStudentsDiv.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
            if (cb.checked) {
                cb.checked = false;
                toggleStudent(parseInt(cb.value));
            }
        });
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Form validation
    const form = document.getElementById('classForm');
    form.addEventListener('submit', function(e) {
        if (createAssignmentCheckbox.checked) {
            const teacherSelect = document.getElementById('assignment_teachers');
            if (teacherSelect.selectedOptions.length === 0) {
                e.preventDefault();
                alert('Please select at least one teacher for assignment.');
                teacherSelect.focus();
                return;
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/admin/classes/create.blade.php ENDPATH**/ ?>