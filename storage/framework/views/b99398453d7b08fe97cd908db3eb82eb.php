

<?php $__env->startSection('content'); ?>
<style>
:root {
    --primary: #4f46e5;
    --primary-light: #eef2ff;
    --border: #e2e8f0;
    --radius: 14px;
    --surface: #fff;
    --muted: #64748b;
    --bg: #f1f5f9;
}
body { background: var(--bg); }

.page-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius);
    padding: 1.5rem 1.75rem;
    color: #fff;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(79,70,229,.2);
}
.page-header h4 { font-weight: 700; margin: 0; font-size: clamp(1rem, 3vw, 1.3rem); }
.page-header p  { margin: .25rem 0 0; opacity: .85; font-size: .85rem; }

.form-card {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.form-card-header {
    padding: .85rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: #fafafa;
    display: flex; align-items: center; gap: .6rem;
}
.form-card-header h6 { margin: 0; font-weight: 700; font-size: .9rem; }
.form-card-body { padding: 1.25rem; }

.form-label { font-weight: 600; font-size: .82rem; color: #374151; margin-bottom: .35rem; }
.form-control, .form-select {
    border-radius: 9px;
    border: 1.5px solid var(--border);
    font-size: .88rem;
    padding: .55rem .85rem;
    transition: border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}
.form-text { font-size: .73rem; color: var(--muted); }

/* Toggle panels */
.toggle-panel { display: none; }
.toggle-panel.active { display: block; }

/* Student picker */
.student-search-box { position: relative; }
.student-list {
    max-height: 220px; overflow-y: auto;
    border: 1.5px solid var(--border); border-radius: 9px;
    margin-top: .5rem;
}
.student-item {
    display: flex; align-items: center; gap: .6rem;
    padding: .55rem .85rem;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer; transition: background .15s;
    font-size: .83rem;
}
.student-item:last-child { border-bottom: none; }
.student-item:hover { background: var(--primary-light); }
.student-item input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--primary); flex-shrink: 0; }
.selected-students {
    display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .6rem; min-height: 32px;
}
.student-tag {
    background: var(--primary-light); color: var(--primary);
    border-radius: 20px; padding: .2rem .65rem;
    font-size: .75rem; font-weight: 600;
    display: flex; align-items: center; gap: .3rem;
}
.student-tag button { background: none; border: none; padding: 0; color: inherit; cursor: pointer; font-size: .8rem; line-height: 1; }

/* Responsive grid */
.two-col { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 576px) { .two-col { grid-template-columns: 1fr 1fr; } }
.three-col { display: grid; grid-template-columns: 1fr; gap: 1rem; }
@media (min-width: 576px) { .three-col { grid-template-columns: 1fr 1fr; } }
@media (min-width: 768px) { .three-col { grid-template-columns: 1fr 1fr 1fr; } }

.btn-primary-custom {
    background: var(--primary); color: #fff; border: none;
    border-radius: 10px; padding: .65rem 1.5rem;
    font-weight: 600; font-size: .9rem;
    transition: background .2s, transform .1s;
    cursor: pointer;
}
.btn-primary-custom:hover { background: #4338ca; transform: translateY(-1px); }
.btn-secondary-custom {
    background: #f1f5f9; color: #374151; border: 1.5px solid var(--border);
    border-radius: 10px; padding: .65rem 1.25rem;
    font-weight: 600; font-size: .9rem; cursor: pointer;
    transition: background .2s;
    text-decoration: none; display: inline-block;
}
.btn-secondary-custom:hover { background: #e2e8f0; color: #374151; }

.required-star { color: #dc2626; }
</style>


<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <div>
            <h4><i class="fas fa-plus-circle me-2"></i>Create New Class</h4>
            <p>Fill in the details below to set up your class.</p>
        </div>
    </div>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger border-0 rounded-3 mb-3" style="font-size:.85rem">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-1 ps-3">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('teacher.classes.store')); ?>" method="POST" id="createClassForm">
<?php echo csrf_field(); ?>


<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-info-circle" style="color:var(--primary)"></i>
        <h6>Basic Information</h6>
    </div>
    <div class="form-card-body">
        <div class="mb-3">
            <label class="form-label">Class Name <span class="required-star">*</span></label>
            <input type="text" name="class_name" class="form-control"
                   value="<?php echo e(old('class_name')); ?>"
                   placeholder="e.g. BSIT 2A — Web Development"
                   required>
            <div class="form-text">A descriptive name students and admins will see.</div>
        </div>

        <div class="two-col">
            <div>
                <label class="form-label">Year Level <span class="required-star">*</span></label>
                <select name="year_level" class="form-select" required>
                    <option value="">Select Year</option>
                    <?php $__currentLoopData = [1=>'1st Year',2=>'2nd Year',3=>'3rd Year',4=>'4th Year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(old('year_level') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Section <span class="required-star">*</span></label>
                <select name="section" class="form-select" required>
                    <option value="">Select Section</option>
                    <?php $__currentLoopData = ['A','B','C','D','E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sec); ?>" <?php echo e(old('section') === $sec ? 'selected' : ''); ?>>Section <?php echo e($sec); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="two-col mt-3">
            <div>
                <label class="form-label">Academic Year <span class="required-star">*</span></label>
                <select name="academic_year" class="form-select" required>
                    <option value="">Select Year</option>
                    <?php
                        $currentYear = now()->year;
                        $years = [
                            ($currentYear-1).'-'.$currentYear,
                            $currentYear.'-'.($currentYear+1),
                            ($currentYear+1).'-'.($currentYear+2),
                        ];
                    ?>
                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr); ?>" <?php echo e(old('academic_year', $years[1]) === $yr ? 'selected' : ''); ?>><?php echo e($yr); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Semester <span class="required-star">*</span></label>
                <select name="semester" class="form-select" required>
                    <option value="">Select Semester</option>
                    <?php $__currentLoopData = ['First','Second','Summer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sem); ?>" <?php echo e(old('semester') === $sem ? 'selected' : ''); ?>><?php echo e($sem); ?> Semester</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2"
                      placeholder="Optional notes about this class..."><?php echo e(old('description')); ?></textarea>
        </div>
    </div>
</div>


<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-book" style="color:#7c3aed"></i>
        <h6>Subject</h6>
    </div>
    <div class="form-card-body">
        <div class="mb-3">
            <label class="form-label">Subject <span class="required-star">*</span></label>
            <select name="subject_id" class="form-select" id="subjectSelect" onchange="toggleNewSubject(this.value)">
                <option value="">— Select an assigned subject —</option>
                <?php $__currentLoopData = $assignedSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>>
                        <?php echo e($subject->subject_name); ?>

                        <?php if($subject->subject_code): ?> (<?php echo e($subject->subject_code); ?>) <?php endif; ?>
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <option value="new-subject" <?php echo e(old('subject_id') === 'new-subject' ? 'selected' : ''); ?>>
                    ➕ Create a new subject
                </option>
            </select>
            <?php if($assignedSubjects->isEmpty()): ?>
                <div class="form-text text-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    You have no assigned subjects yet. You can create one below, or ask your admin to assign subjects to you.
                </div>
            <?php endif; ?>
        </div>

        
        <div id="newSubjectPanel" class="toggle-panel <?php echo e(old('subject_id') === 'new-subject' ? 'active' : ''); ?>">
            <div style="background:#faf5ff;border:1px solid #e9d5ff;border-radius:10px;padding:1rem">
                <div class="form-text mb-3" style="color:#7c3aed;font-weight:600">
                    <i class="fas fa-plus-circle me-1"></i>New Subject Details
                </div>
                <div class="two-col">
                    <div>
                        <label class="form-label">Subject Name</label>
                        <input type="text" name="new_subject_name" class="form-control"
                               value="<?php echo e(old('new_subject_name')); ?>"
                               placeholder="e.g. Web Development">
                    </div>
                    <div>
                        <label class="form-label">Subject Code</label>
                        <input type="text" name="new_subject_code" class="form-control"
                               value="<?php echo e(old('new_subject_code')); ?>"
                               placeholder="e.g. IT301">
                    </div>
                </div>
                <div class="two-col mt-2">
                    <div>
                        <label class="form-label">Credit Hours</label>
                        <select name="credit_hours" class="form-select">
                            <?php $__currentLoopData = [1,2,3,4,5,6]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ch); ?>" <?php echo e(old('credit_hours', 3) == $ch ? 'selected' : ''); ?>><?php echo e($ch); ?> Credit Hour<?php echo e($ch > 1 ? 's' : ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <?php $__currentLoopData = ['Core','Major','Minor','Elective','General','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>" <?php echo e(old('category') === $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-graduation-cap" style="color:#0891b2"></i>
        <h6>Course / Program</h6>
    </div>
    <div class="form-card-body">
        <div class="mb-3">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select" id="courseSelect" onchange="toggleNewCourse(this.value)">
                <option value="">— Select a course (optional) —</option>
                <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($course->id); ?>" <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                        <?php echo e($course->program_name); ?>

                        <?php if($course->program_code): ?> (<?php echo e($course->program_code); ?>) <?php endif; ?>
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <option value="new-course" <?php echo e(old('course_id') === 'new-course' ? 'selected' : ''); ?>>
                    ➕ Create a new course
                </option>
            </select>
            <div class="form-text">Leave blank to use "Independent Studies".</div>
        </div>

        
        <div id="newCoursePanel" class="toggle-panel <?php echo e(old('course_id') === 'new-course' ? 'active' : ''); ?>">
            <div style="background:#ecfeff;border:1px solid #a5f3fc;border-radius:10px;padding:1rem">
                <div class="form-text mb-3" style="color:#0891b2;font-weight:600">
                    <i class="fas fa-plus-circle me-1"></i>New Course Details
                </div>
                <div class="two-col">
                    <div>
                        <label class="form-label">Course Name</label>
                        <input type="text" name="new_course_name" class="form-control"
                               value="<?php echo e(old('new_course_name')); ?>"
                               placeholder="e.g. Bachelor of Science in IT">
                    </div>
                    <div>
                        <label class="form-label">Course Code</label>
                        <input type="text" name="new_course_code" class="form-control"
                               value="<?php echo e(old('new_course_code')); ?>"
                               placeholder="e.g. BSIT">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-users" style="color:#16a34a"></i>
        <h6>Assign Students <span style="font-weight:400;color:var(--muted)">(optional)</span></h6>
    </div>
    <div class="form-card-body">
        <?php if($students->isEmpty()): ?>
            <div class="text-center py-3" style="color:var(--muted);font-size:.85rem">
                <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-40"></i>
                No students found for your campus. You can add students after creating the class.
            </div>
        <?php else: ?>
            <div class="student-search-box mb-2">
                <input type="text" id="studentSearch" class="form-control"
                       placeholder="🔍 Search students by name or ID..."
                       oninput="filterStudents(this.value)">
            </div>
            <div class="student-list" id="studentList">
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="student-item" for="student_<?php echo e($student->id); ?>">
                        <input type="checkbox" id="student_<?php echo e($student->id); ?>"
                               class="student-checkbox"
                               value="<?php echo e($student->id); ?>"
                               data-name="<?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>"
                               data-id="<?php echo e($student->student_id); ?>"
                               onchange="updateSelectedStudents()">
                        <div>
                            <div style="font-weight:600"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></div>
                            <div style="font-size:.72rem;color:var(--muted)">
                                <?php echo e($student->student_id); ?>

                                <?php if($student->course): ?> · <?php echo e($student->course->program_code); ?> <?php endif; ?>
                                · Year <?php echo e($student->year); ?><?php echo e($student->section); ?>

                            </div>
                        </div>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="selected-students" id="selectedStudents"></div>
            <input type="hidden" name="assigned_students" id="assignedStudentsInput">
            <div class="form-text mt-1" id="selectedCount">0 students selected</div>
        <?php endif; ?>
    </div>
</div>


<div class="form-card">
    <div class="form-card-header">
        <i class="fas fa-sliders-h" style="color:var(--muted)"></i>
        <h6>Options</h6>
    </div>
    <div class="form-card-body">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="auto_assign" value="1"
                   id="autoAssign" <?php echo e(old('auto_assign') ? 'checked' : ''); ?>>
            <label class="form-check-label" for="autoAssign" style="font-size:.85rem">
                <strong>Auto-assign me as teacher</strong>
                <span class="form-text d-block">Creates a teacher assignment record for this class.</span>
            </label>
        </div>
    </div>
</div>


<div class="d-flex gap-2 flex-wrap justify-content-end pb-4">
    <a href="<?php echo e(route('teacher.classes')); ?>" class="btn-secondary-custom">
        <i class="fas fa-arrow-left me-1"></i>Cancel
    </a>
    <button type="submit" class="btn-primary-custom">
        <i class="fas fa-plus me-1"></i>Create Class
    </button>
</div>

</form>

<script>
function toggleNewSubject(val) {
    document.getElementById('newSubjectPanel').classList.toggle('active', val === 'new-subject');
}
function toggleNewCourse(val) {
    document.getElementById('newCoursePanel').classList.toggle('active', val === 'new-course');
}

// Student search filter
function filterStudents(query) {
    const q = query.toLowerCase();
    document.querySelectorAll('.student-item').forEach(item => {
        const name = item.querySelector('[data-name]')?.dataset.name?.toLowerCase() || '';
        const id   = item.querySelector('[data-id]')?.dataset.id?.toLowerCase() || '';
        item.style.display = (!q || name.includes(q) || id.includes(q)) ? '' : 'none';
    });
}

// Track selected students and update hidden input + tags
function updateSelectedStudents() {
    const checked = [...document.querySelectorAll('.student-checkbox:checked')];
    const ids = checked.map(c => c.value);

    document.getElementById('assignedStudentsInput').value = ids.join(',');
    document.getElementById('selectedCount').textContent = ids.length + ' student' + (ids.length !== 1 ? 's' : '') + ' selected';

    const container = document.getElementById('selectedStudents');
    container.innerHTML = checked.map(c => `
        <span class="student-tag">
            ${c.dataset.name}
            <button type="button" onclick="removeStudent('${c.value}')">×</button>
        </span>
    `).join('');
}

function removeStudent(id) {
    const cb = document.getElementById('student_' + id);
    if (cb) { cb.checked = false; updateSelectedStudents(); }
}

// Init on page load (restore old values if validation failed)
document.addEventListener('DOMContentLoaded', () => {
    const oldIds = '<?php echo e(old('assigned_students', '')); ?>'.split(',').filter(Boolean);
    oldIds.forEach(id => {
        const cb = document.getElementById('student_' + id);
        if (cb) cb.checked = true;
    });
    updateSelectedStudents();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/classes/create.blade.php ENDPATH**/ ?>