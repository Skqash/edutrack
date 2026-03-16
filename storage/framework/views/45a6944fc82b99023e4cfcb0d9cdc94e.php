<?php $__env->startSection('content'); ?>

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 fw-bold mb-0">Attendance Management</h1>
            <small class="text-muted">Select a class to manage student attendance</small>
        </div>
    </div>

    <?php if($classes->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You don't have any classes assigned yet.
        </div>
    <?php else: ?>
        <?php
        // Get today's attendance for all classes
        $today = now()->format('Y-m-d');
        $todayAttendances = \App\Models\Attendance::where('date', $today)
            ->whereIn('class_id', $classes->pluck('id'))
            ->get()
            ->keyBy(function($attendance) {
                return $attendance->class_id . '_' . $attendance->student_id;
            });
        ?>
        <div class="attendance-classes-container">
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="class-card mb-3 border rounded-lg overflow-hidden"
                    style="background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Class Header (Clickable) -->
                    <div class="class-header p-4 cursor-pointer d-flex justify-content-between align-items-center"
                        onclick="toggleClassStudents(this)"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; user-select: none;">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <i class="fas fa-door-open fa-lg"></i>
                            <div>
                                <h5 class="mb-0"><?php echo e($class->class_name ?? $class->name); ?></h5>
                                <small>
                                    <?php if($class->course && $class->course->course_name): ?>
                                        <?php echo e($class->course->course_name); ?>

                                    <?php else: ?>
                                        <em>No course assigned</em>
                                    <?php endif; ?>
                                    • <?php echo e($class->students->count()); ?>

                                    student<?php echo e($class->students->count() !== 1 ? 's' : ''); ?>

                                </small>
                            </div>
                        </div>
                        <div class="class-toggle-icon">
                            <i class="fas fa-chevron-down fa-lg" style="transition: transform 0.3s;"></i>
                        </div>
                    </div>

                    <!-- Class Content (Expandable) -->
                    <div class="class-content" style="display: none;">
                        <div class="p-4 border-top">
                            <!-- Student List -->
                            <div class="students-list mb-4">
                                <?php if($class->students->isEmpty()): ?>
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-circle me-2"></i>No students enrolled in this class.
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-muted fw-bold"><?php echo e($class->students->count()); ?> Enrolled
                                            Students</h6>
                                        <?php
                                            $classAttendanceStats = [
                                                'Present' => 0,
                                                'Absent' => 0, 
                                                'Late' => 0,
                                                'Leave' => 0,
                                                'Unmarked' => 0
                                            ];
                                            foreach($class->students as $student) {
                                                $attendanceKey = $class->id . '_' . $student->id;
                                                $currentAttendance = $todayAttendances->get($attendanceKey);
                                                $status = $currentAttendance ? $currentAttendance->status : 'Unmarked';
                                                $classAttendanceStats[$status]++;
                                            }
                                        ?>
                                        <div class="d-flex gap-2 align-items-center">
                                            <small class="text-muted">Today's Status:</small>
                                            <span class="badge bg-success"><?php echo e($classAttendanceStats['Present']); ?> Present</span>
                                            <span class="badge bg-danger"><?php echo e($classAttendanceStats['Absent']); ?> Absent</span>
                                            <span class="badge bg-warning"><?php echo e($classAttendanceStats['Late']); ?> Late</span>
                                            <span class="badge bg-primary"><?php echo e($classAttendanceStats['Leave']); ?> Excused</span>
                                            <span class="badge bg-secondary"><?php echo e($classAttendanceStats['Unmarked']); ?> Unmarked</span>
                                        </div>
                                    </div>
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr style="font-weight: 600;">
                                                    <th style="width: 20%;">Student ID</th>
                                                    <th style="width: 50%;">Student Name</th>
                                                    <th style="width: 30%; text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $class->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr style="align-items: center;">
                                                        <td>
                                                            <span class="badge bg-secondary"
                                                                style="font-size: 0.8rem; font-weight: 600;">
                                                                <?php echo e($student->student_id ?? '-'); ?>

                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="badge rounded-circle"
                                                                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-weight: bold; background-color: #e9ecef; color: #495057;">
                                                                    <?php echo e(strtoupper(substr($student->user->name ?? 'U', 0, 1))); ?>

                                                                </div>
                                                                <span><?php echo e($student->user->name ?? 'N/A'); ?></span>
                                                            </div>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php
                                                                $attendanceKey = $class->id . '_' . $student->id;
                                                                $currentAttendance = $todayAttendances->get($attendanceKey);
                                                                $status = $currentAttendance ? $currentAttendance->status : 'Unmarked';
                                                            ?>
                                                            <a href="<?php echo e(route('teacher.attendance.manage', $class->id)); ?>"
                                                                class="btn btn-sm <?php if($status === 'Present'): ?> btn-success <?php elseif($status === 'Absent'): ?> btn-danger <?php elseif($status === 'Late'): ?> btn-warning <?php elseif($status === 'Leave'): ?> btn-primary <?php else: ?> btn-secondary <?php endif; ?>"
                                                                title="Take Attendance - Current: <?php echo e($status); ?>"
                                                                style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                                                <?php if($status === 'Present'): ?>
                                                                    <i class="fas fa-check"></i> Present
                                                                <?php elseif($status === 'Absent'): ?>
                                                                    <i class="fas fa-times"></i> Absent
                                                                <?php elseif($status === 'Late'): ?>
                                                                    <i class="fas fa-clock"></i> Late
                                                                <?php elseif($status === 'Leave'): ?>
                                                                    <i class="fas fa-umbrella-beach"></i> Excused
                                                                <?php else: ?>
                                                                    <i class="fas fa-question-circle"></i> Unmarked
                                                                <?php endif; ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-between border-top pt-3">
                                <div>
                                    <a href="<?php echo e(route('teacher.attendance.history', $class->id)); ?>"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-history me-1"></i> History
                                    </a>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('teacher.attendance.manage', $class->id)); ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-clipboard-check me-1"></i> Take Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .class-card {
            transition: all 0.3s ease;
        }

        .class-card:hover {
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .class-header:hover {
            opacity: 0.95 !important;
        }

        .class-content {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }

            to {
                opacity: 1;
                max-height: 600px;
            }
        }

        /* Attendance status buttons */
        .btn-sm {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.65rem !important;
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Status badges */
        .badge {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
            font-weight: 500 !important;
        }

        /* Responsive badge container */
        .d-flex.gap-2 {
            flex-wrap: wrap;
            gap: 0.5rem !important;
        }

        /* Table improvements */
        .table-sm td {
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        /* Status button specific styles */
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: #212529;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            border: none;
        }
    </style>

    <script>
        function toggleClassStudents(headerElement) {
            const card = headerElement.closest('.class-card');
            const content = card.querySelector('.class-content');
            const icon = headerElement.querySelector('.class-toggle-icon i');

            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/attendance/index.blade.php ENDPATH**/ ?>