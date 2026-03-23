

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-1 fw-bold">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Create New Class
                            </h1>
                            <p class="mb-0 opacity-90">Set up a new class for your students</p>
                        </div>
                        <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back to Classes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="<?php echo e(route('teacher.classes.store')); ?>" method="POST" id="createClassForm">
        <?php echo csrf_field(); ?>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Basic Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Class Name -->
                        <div class="mb-4">
                            <label for="class_name" class="form-label fw-semibold">
                                Class Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="class_name" 
                                   name="class_name" 
                                   value="<?php echo e(old('class_name')); ?>" 
                                   placeholder="e.g., BSIT 1-A, CS 101, Math 201" 
                                   required>
                            <?php $__errorArgs = ['class_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Enter a descriptive name for your class
                            </small>
                        </div>

                        <!-- Subject Selection with Dynamic Dropdown -->
                        <div class="mb-4">
                            <label for="subject_id" class="form-label fw-semibold">
                                Subject <span class="text-danger">*</span>
                            </label>
                            <?php if (isset($component)) { $__componentOriginal0728ba7b37b7eda62f62767c5dccebf3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.searchable-dropdown','data' => ['name' => 'subject_id','id' => 'subject_id','placeholder' => 'Search and select subject...','apiUrl' => ''.e(route('api.subjects')).'','displayKey' => 'name','valueKey' => 'id','searchKey' => 'name','selected' => old('subject_id'),'required' => 'true','createNew' => 'true','createNewText' => '+ Create New Subject','createNewValue' => 'new-subject','class' => '@error(\'subject_id\') is-invalid @enderror']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('searchable-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'subject_id','id' => 'subject_id','placeholder' => 'Search and select subject...','api-url' => ''.e(route('api.subjects')).'','display-key' => 'name','value-key' => 'id','search-key' => 'name','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('subject_id')),'required' => 'true','create-new' => 'true','create-new-text' => '+ Create New Subject','create-new-value' => 'new-subject','class' => '@error(\'subject_id\') is-invalid @enderror']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3)): ?>
<?php $attributes = $__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3; ?>
<?php unset($__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0728ba7b37b7eda62f62767c5dccebf3)): ?>
<?php $component = $__componentOriginal0728ba7b37b7eda62f62767c5dccebf3; ?>
<?php unset($__componentOriginal0728ba7b37b7eda62f62767c5dccebf3); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Choose from your assigned subjects or create a new one
                            </small>
                        </div>

                        <!-- New Subject Fields (Hidden by default) -->
                        <div id="newSubjectFields" class="mb-4" style="display:none;">
                            <div class="alert alert-info">
                                <i class="fas fa-plus-circle me-2"></i>
                                <strong>Creating New Subject</strong>
                                <p class="mb-0 small">Fill in the details below. Admin approval may be required.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" id="new_subject_name" name="new_subject_name" placeholder="Subject Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Subject Code</label>
                                    <input type="text" class="form-control" id="new_subject_code" name="new_subject_code" placeholder="e.g., CS101">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Credit Hours</label>
                                    <input type="number" class="form-control" id="credit_hours" name="credit_hours" min="1" max="6" value="3">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" id="subject_category" name="category">
                                        <option value="Core">Core</option>
                                        <option value="General Ed">General Ed</option>
                                        <option value="Major">Major</option>
                                        <option value="Specialization">Specialization</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" id="new_subject_description" name="description" placeholder="Optional">
                                </div>
                            </div>
                        </div>

                        <!-- Course Selection with Dynamic Dropdown -->
                        <div class="mb-4">
                            <label for="course_id" class="form-label fw-semibold">
                                Course/Program <span class="text-danger">*</span>
                            </label>
                            <?php if (isset($component)) { $__componentOriginal0728ba7b37b7eda62f62767c5dccebf3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.searchable-dropdown','data' => ['name' => 'course_id','id' => 'course_id','placeholder' => 'Search and select course...','apiUrl' => ''.e(route('api.courses')).'','displayKey' => 'name','valueKey' => 'id','searchKey' => 'name','selected' => old('course_id'),'required' => 'true','createNew' => 'true','createNewText' => '+ Create New Course','createNewValue' => 'new-course','class' => '@error(\'course_id\') is-invalid @enderror']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('searchable-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'course_id','id' => 'course_id','placeholder' => 'Search and select course...','api-url' => ''.e(route('api.courses')).'','display-key' => 'name','value-key' => 'id','search-key' => 'name','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('course_id')),'required' => 'true','create-new' => 'true','create-new-text' => '+ Create New Course','create-new-value' => 'new-course','class' => '@error(\'course_id\') is-invalid @enderror']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3)): ?>
<?php $attributes = $__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3; ?>
<?php unset($__attributesOriginal0728ba7b37b7eda62f62767c5dccebf3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0728ba7b37b7eda62f62767c5dccebf3)): ?>
<?php $component = $__componentOriginal0728ba7b37b7eda62f62767c5dccebf3; ?>
<?php unset($__componentOriginal0728ba7b37b7eda62f62767c5dccebf3); ?>
<?php endif; ?>
                            <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Select the course/program this class belongs to
                            </small>
                        </div>

                        <!-- New Course Fields (Hidden by default) -->
                        <div id="newCourseFields" class="mb-4" style="display:none;">
                            <div class="alert alert-info">
                                <i class="fas fa-plus-circle me-2"></i>
                                <strong>Creating New Course</strong>
                                <p class="mb-0 small">Fill in the details below. Admin approval may be required.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Course Name</label>
                                    <input type="text" class="form-control" id="new_course_name" name="new_course_name" placeholder="Course Name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course Code</label>
                                    <input type="text" class="form-control" id="new_course_code" name="new_course_code" placeholder="e.g., BSIT">
                                </div>
                            </div>
                        </div>

                        <!-- Year and Section -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="year" class="form-label fw-semibold">Year Level</label>
                                <select class="form-select <?php $__errorArgs = ['year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="year" name="year">
                                    <option value="">-- Select Year --</option>
                                    <option value="1" <?php echo e(old('year') == '1' ? 'selected' : ''); ?>>1st Year</option>
                                    <option value="2" <?php echo e(old('year') == '2' ? 'selected' : ''); ?>>2nd Year</option>
                                    <option value="3" <?php echo e(old('year') == '3' ? 'selected' : ''); ?>>3rd Year</option>
                                    <option value="4" <?php echo e(old('year') == '4' ? 'selected' : ''); ?>>4th Year</option>
                                </select>
                                <?php $__errorArgs = ['year'];
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
                            <div class="col-md-6">
                                <label for="section" class="form-label fw-semibold">Section</label>
                                <select class="form-select <?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="section" name="section">
                                    <option value="">-- Select Section --</option>
                                    <option value="A" <?php echo e(old('section') == 'A' ? 'selected' : ''); ?>>Section A</option>
                                    <option value="B" <?php echo e(old('section') == 'B' ? 'selected' : ''); ?>>Section B</option>
                                    <option value="C" <?php echo e(old('section') == 'C' ? 'selected' : ''); ?>>Section C</option>
                                    <option value="D" <?php echo e(old('section') == 'D' ? 'selected' : ''); ?>>Section D</option>
                                    <option value="E" <?php echo e(old('section') == 'E' ? 'selected' : ''); ?>>Section E</option>
                                </select>
                                <?php $__errorArgs = ['section'];
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
                        </div>

                        <!-- Semester and Academic Year -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="semester" class="form-label fw-semibold">
                                    Semester <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?php $__errorArgs = ['semester'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="semester" name="semester" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="First" <?php echo e(old('semester') == 'First' ? 'selected' : ''); ?>>First Semester</option>
                                    <option value="Second" <?php echo e(old('semester') == 'Second' ? 'selected' : ''); ?>>Second Semester</option>
                                    <option value="Summer" <?php echo e(old('semester') == 'Summer' ? 'selected' : ''); ?>>Summer</option>
                                </select>
                                <?php $__errorArgs = ['semester'];
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
                            <div class="col-md-6">
                                <label for="academic_year" class="form-label fw-semibold">
                                    Academic Year <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="academic_year" 
                                       name="academic_year" 
                                       value="<?php echo e(old('academic_year', date('Y') . '-' . (date('Y') + 1))); ?>" 
                                       placeholder="e.g., 2024-2025" 
                                       required>
                                <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="form-text text-muted">Format: YYYY-YYYY</small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-0">
                            <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Add any additional information about this class..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
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
                    </div>
                </div>

                <!-- Student Assignment Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-graduate text-primary me-2"></i>
                            Assign Students (Optional)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            You can assign students now or add them later. Use the filters below to find students.
                        </div>

                        <!-- Student Filters -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Filter by Year</label>
                                <select class="form-select" id="filter_year">
                                    <option value="">All Years</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Filter by Section</label>
                                <select class="form-select" id="filter_section">
                                    <option value="">All Sections</option>
                                    <option value="A">Section A</option>
                                    <option value="B">Section B</option>
                                    <option value="C">Section C</option>
                                    <option value="D">Section D</option>
                                    <option value="E">Section E</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Search Student</label>
                                <input type="text" class="form-control" id="student_search" placeholder="Search by name or ID...">
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllStudents">
                                <i class="fas fa-check-double me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllStudents">
                                <i class="fas fa-times me-1"></i>Deselect All
                            </button>
                            <span class="ms-auto text-muted" id="selectedCount">0 students selected</span>
                        </div>

                        <!-- Student List -->
                        <div id="studentListContainer" style="max-height: 400px; overflow-y: auto;">
                            <div class="text-center py-5" id="loadingStudents">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading students...</p>
                            </div>
                            <div id="studentList" style="display: none;"></div>
                            <div id="noStudents" class="text-center py-5" style="display: none;">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No students found</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="<?php echo e(route('teacher.classes')); ?>" class="btn btn-lg btn-light">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fas fa-save me-2"></i>Create Class
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Quick Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Class Name
                            </h6>
                            <p class="small text-muted mb-0">Use a clear, descriptive name that includes the course code and section (e.g., "BSIT 1-A").</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="fw-semibold">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Subject & Course
                            </h6>
                            <p class="small text-muted mb-0">Select from your assigned subjects or create a new one. The course will help organize your classes.</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="fw-semibold">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Student Assignment
                            </h6>
                            <p class="small text-muted mb-0">You can assign students now or add them later. Use filters to find specific students quickly.</p>
                        </div>
                        <hr>
                        <div>
                            <h6 class="fw-semibold">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                Need Help?
                            </h6>
                            <p class="small text-muted mb-0">Contact your admin if you need additional subjects or courses assigned to you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.sticky-top {
    position: sticky;
}

.student-item {
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.student-item:hover {
    background: #f9fafb;
    border-color: #cbd5e1;
}

.student-item.selected {
    background: #eff6ff;
    border-color: #3b82f6;
}

.student-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle create new subject
    document.addEventListener('createNew', function(e) {
        const dropdown = e.target;
        const dropdownId = dropdown.querySelector('input[type="hidden"]').id;
        
        if (dropdownId === 'subject_id_hidden') {
            document.getElementById('newSubjectFields').style.display = 'block';
        } else if (dropdownId === 'course_id_hidden') {
            document.getElementById('newCourseFields').style.display = 'block';
        }
    });

    // Handle subject change - hide new subject fields if regular subject selected
    document.getElementById('subject_id_hidden').addEventListener('change', function() {
        if (this.value !== 'new-subject') {
            document.getElementById('newSubjectFields').style.display = 'none';
        }
    });

    // Handle course change - hide new course fields and load students
    document.getElementById('course_id_hidden').addEventListener('change', function() {
        if (this.value !== 'new-course') {
            document.getElementById('newCourseFields').style.display = 'none';
            loadStudents();
        }
    });

    // Load students on filter change
    document.getElementById('filter_year').addEventListener('change', loadStudents);
    document.getElementById('filter_section').addEventListener('change', loadStudents);
    
    // Search students with debounce
    let searchTimeout;
    document.getElementById('student_search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadStudents, 300);
    });

    // Select/Deselect all
    document.getElementById('selectAllStudents').addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = true;
            cb.closest('.student-item').classList.add('selected');
        });
        updateSelectedCount();
    });

    document.getElementById('deselectAllStudents').addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.checked = false;
            cb.closest('.student-item').classList.remove('selected');
        });
        updateSelectedCount();
    });

    // Load students function
    function loadStudents() {
        const courseId = document.getElementById('course_id_hidden').value;
        const year = document.getElementById('filter_year').value;
        const section = document.getElementById('filter_section').value;
        const search = document.getElementById('student_search').value;

        // Show loading
        document.getElementById('loadingStudents').style.display = 'block';
        document.getElementById('studentList').style.display = 'none';
        document.getElementById('noStudents').style.display = 'none';

        // Build query params
        const params = new URLSearchParams();
        if (courseId && courseId !== 'new-course') params.append('course_id', courseId);
        if (year) params.append('year', year);
        if (section) params.append('section', section);
        if (search) params.append('search', search);

        // Fetch students
        fetch(`<?php echo e(route('teacher.classes.get-students')); ?>?${params.toString()}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayStudents(data.students || []);
        })
        .catch(error => {
            console.error('Error loading students:', error);
            document.getElementById('loadingStudents').style.display = 'none';
            document.getElementById('noStudents').style.display = 'block';
        });
    }

    function displayStudents(students) {
        const container = document.getElementById('studentList');
        container.innerHTML = '';

        document.getElementById('loadingStudents').style.display = 'none';

        if (students.length === 0) {
            document.getElementById('noStudents').style.display = 'block';
            return;
        }

        document.getElementById('studentList').style.display = 'block';

        students.forEach(student => {
            const item = document.createElement('div');
            item.className = 'student-item';
            item.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" 
                           class="student-checkbox" 
                           name="students[]" 
                           value="${student.id}"
                           id="student_${student.id}">
                    <label for="student_${student.id}" class="flex-grow-1 mb-0 cursor-pointer">
                        <div class="fw-semibold">${student.full_name}</div>
                        <div class="small text-muted">
                            ${student.student_id} • ${student.program_name} • Year ${student.year}
                        </div>
                    </label>
                </div>
            `;

            // Handle checkbox change
            const checkbox = item.querySelector('.student-checkbox');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    item.classList.add('selected');
                } else {
                    item.classList.remove('selected');
                }
                updateSelectedCount();
            });

            // Handle item click
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            container.appendChild(item);
        });

        updateSelectedCount();
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = `${count} student${count !== 1 ? 's' : ''} selected`;
    }

    // Initial load
    loadStudents();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/create.blade.php ENDPATH**/ ?>