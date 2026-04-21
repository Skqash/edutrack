<?php $__env->startSection('content'); ?>
    <?php
        $classes = $classes instanceof \Illuminate\Support\Collection ? $classes : collect();
    ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-0">Attendance Management</h1>
                    <small class="text-muted">Select a class to view and manage attendance</small>
                </div>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> Print
                </button>
            </div>
        </div>
    </div>

    <?php if($classes->isEmpty()): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> You don't have any classes assigned yet.
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">
                    <i class="fas fa-clock me-2"></i> Take Attendance
                </h5>
                <p class="card-text mb-3">Select a class and click "Take Attendance" to mark students present/absent and capture e-signatures.</p>
                
                <div class="row g-2 mb-3">
                    <div class="col-auto">
                        <label class="form-label small text-white mb-1">Select Class</label>
                        <select id="classSelect" class="form-select form-select-sm">
                            <option value="">-- Choose a Class --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>">
                                    <?php echo e($class->class_name ?? $class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-label small text-white mb-1">Term</label>
                        <select id="attendanceTermSelect" class="form-select form-select-sm">
                            <option value="Midterm">📚 Midterm</option>
                            <option value="Final">🎓 Final</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-label small text-white mb-1">Date</label>
                        <input type="date" id="attendanceDateFilter" class="form-control form-control-sm"
                            value="<?php echo e(now()->format('Y-m-d')); ?>" max="<?php echo e(now()->format('Y-m-d')); ?>">
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="button" id="takeAttendanceBtn" class="btn btn-light btn-sm" style="font-weight: bold; padding: 0.5rem 1.5rem;">
                            <i class="fas fa-arrow-right me-2"></i> Take Attendance
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous Attendance Preview Section -->
        <div class="mb-3">
            <h5 class="mb-3">
                <i class="fas fa-history me-2"></i> View Previous Attendance Sheet
            </h5>
            <p class="text-muted small">Select a different class or date to view and print previous attendance records.</p>
        </div>

        <div class="row mb-3">
            <div class="col-auto">
                <label class="form-label small mb-1">View Sheet - Select Class</label>
                <select id="classSelectView" class="form-select form-select-sm">
                    <option value="">-- Select a Class --</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>">
                            <?php echo e($class->class_name ?? $class->name); ?> - <?php echo e($class->course->course_name ?? 'No Course'); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Term</label>
                <select id="attendanceTermSelectView" class="form-select form-select-sm">
                    <option value="Midterm">Midterm</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Date</label>
                <input type="date" id="attendanceDateFilterView" class="form-control form-control-sm"
                    value="<?php echo e(now()->format('Y-m-d')); ?>" max="<?php echo e(now()->format('Y-m-d')); ?>">
            </div>
        </div>

        <!-- Attendance Sheet (Print Friendly) -->
        <div id="attendanceSheetContainer"
            style="display: none; background: white; padding: 2rem; page-break-inside: avoid;">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="mb-1">CENTRAL PHILIPPINES STATE UNIVERSITY</h5>
                <h6 class="text-muted mb-3">Kabankalan, Negros Occidental</h6>
                <h5 class="fw-bold">CLASS ATTENDANCE</h5>
            </div>

            <!-- Form Fields -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="row mb-2">
                        <div class="col-auto">
                            <strong>Subject:</strong>
                        </div>
                        <div class="col">
                            <input type="text" id="attendanceSubject" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <strong>Course:</strong>
                        </div>
                        <div class="col">
                            <input type="text" id="attendanceCourse" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row mb-2">
                        <div class="col-auto">
                            <strong>Date:</strong>
                        </div>
                        <div class="col">
                            <input type="text" id="attendanceSheetDate" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Time Schedule:</strong>
                    <input type="text" id="attendanceTimeSchedule" class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-6">
                    <strong>Faculty in-charge:</strong>
                    <input type="text" id="attendanceFaculty" class="form-control form-control-sm" readonly>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-sm" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr style="border: 1px solid #000;">
                                <th style="width: 5%; border: 1px solid #000;">No.</th>
                                <th style="width: 45%; border: 1px solid #000;">Name<br><small>(Last, First, Middle
                                        Initial)</small></th>
                                <th style="width: 15%; border: 1px solid #000;">Signature</th>
                                <th style="width: 5%; border: 1px solid #000;">No.</th>
                                <th style="width: 45%; border: 1px solid #000;">Name<br><small>(Last, First, Middle
                                        Initial)</small></th>
                                <th style="width: 15%; border: 1px solid #000;">Signature</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- No Class Selected Message -->
        <div id="noClassMessage" class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Please select a class to view attendance.
        </div>
    <?php endif; ?>

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            #classSelect,
            #attendanceTermSelect,
            #attendanceDateFilter,
            .btn {
                display: none;
            }

            #attendanceSheetContainer {
                display: block !important;
                padding: 0;
            }

            #noClassMessage {
                display: none;
            }

            table {
                page-break-inside: avoid;
            }
        }

        .table-bordered td {
            min-height: 25px;
            vertical-align: top;
            border: 1px solid #000;
        }
    </style>

    <script>
        let allClasses = <?php echo json_encode($classes, 15, 512) ?>;
        let selectedClassId = null;

        // Take Attendance button handler
        document.getElementById('takeAttendanceBtn').addEventListener('click', function() {
            const classId = document.getElementById('classSelect').value;
            const term = document.getElementById('attendanceTermSelect').value;
            const date = document.getElementById('attendanceDateFilter').value;

            if (!classId) {
                alert('Please select a class first');
                return;
            }

            // Navigate to the attendance taking page
            window.location.href = `/teacher/attendance/manage/${classId}?term=${term}&date=${date}`;
        });

        // Attendance Sheet Preview handlers
        document.getElementById('classSelectView').addEventListener('change', function(e) {
            selectedClassId = this.value;
            if (selectedClassId) {
                loadAttendanceSheet();
            } else {
                document.getElementById('attendanceSheetContainer').style.display = 'none';
                document.getElementById('noClassMessage').style.display = 'block';
            }
        });

        document.getElementById('attendanceTermSelectView').addEventListener('change', loadAttendanceSheet);
        document.getElementById('attendanceDateFilterView').addEventListener('change', loadAttendanceSheet);

        function loadAttendanceSheet() {
            if (!selectedClassId) return;

            const selectedClass = allClasses.find(c => c.id == selectedClassId);
            if (!selectedClass) return;

            const term = document.getElementById('attendanceTermSelectView').value;
            const date = document.getElementById('attendanceDateFilterView').value;

            console.log('Loading attendance sheet:', { classId: selectedClassId, term, date });

            // Show loading state
            const container = document.getElementById('attendanceSheetContainer');
            container.style.display = 'block';
            document.getElementById('noClassMessage').style.display = 'none';

            // Populate form fields
            document.getElementById('attendanceSubject').value = selectedClass.class_name || selectedClass.name;
            document.getElementById('attendanceCourse').value = selectedClass.course?.course_name || 'N/A';
            document.getElementById('attendanceSheetDate').value = date;
            document.getElementById('attendanceTimeSchedule').value = selectedClass.schedule || 'N/A';
            document.getElementById('attendanceFaculty').value = selectedClass.teacher?.name || 'N/A';

            // Fetch attendance records from server
            const url = `/teacher/attendance/fetch/${selectedClassId}?date=${date}&term=${term}`;
            console.log('Fetching from URL:', url);

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);

                    if (!data.success) {
                        console.error('Failed to load attendance:', data);
                        alert('Failed to load attendance: ' + (data.error || 'Unknown error'));
                        return;
                    }

                    console.log(`Found ${data.total_records} attendance records for ${data.total_students} students`);

                    // Populate attendance table in two-column format
                    const tbody = document.getElementById('attendanceTableBody');
                    tbody.innerHTML = '';

                    const students = data.attendance || [];
                    const totalRows = Math.ceil(students.length / 2);

                    for (let i = 0; i < totalRows; i++) {
                        const row = document.createElement('tr');

                        // Left column student
                        const student1 = students[i * 2];
                        const firstName1 = student1?.first_name || '';
                        const lastName1 = student1?.last_name || '';
                        const middleName1 = student1?.middle_name || '';
                        const name1 = `${lastName1}, ${firstName1} ${middleName1}`.trim();
                        const status1 = student1?.status || '';
                        const signature1 = student1?.e_signature;

                        // Right column student
                        const student2 = students[i * 2 + 1];
                        let name2 = '';
                        let status2 = '';
                        let signature2 = null;
                        
                        if (student2) {
                            const firstName2 = student2?.first_name || '';
                            const lastName2 = student2?.last_name || '';
                            const middleName2 = student2?.middle_name || '';
                            name2 = `${lastName2}, ${firstName2} ${middleName2}`.trim();
                            status2 = student2?.status || '';
                            signature2 = student2?.e_signature;
                        }

                        // Build signature cell content for student 1
                        let signatureCell1 = '';
                        if (signature1) {
                            signatureCell1 = `<img src="${signature1}" alt="Signature" style="max-width: 100px; max-height: 40px; display: block; margin: 0 auto;">`;
                        } else if (status1) {
                            signatureCell1 = `<span style="color: #6c757d; font-style: italic;">No signature</span>`;
                        }

                        // Build signature cell content for student 2
                        let signatureCell2 = '';
                        if (signature2) {
                            signatureCell2 = `<img src="${signature2}" alt="Signature" style="max-width: 100px; max-height: 40px; display: block; margin: 0 auto;">`;
                        } else if (status2) {
                            signatureCell2 = `<span style="color: #6c757d; font-style: italic;">No signature</span>`;
                        }

                        row.innerHTML = `
                            <td style="text-align: center; border: 1px solid #000; padding: 8px;">${i * 2 + 1}</td>
                            <td style="border: 1px solid #000; padding: 8px;">${name1}</td>
                            <td style="border: 1px solid #000; text-align: center; padding: 8px; vertical-align: middle;">
                                ${signatureCell1}
                            </td>
                            <td style="text-align: center; border: 1px solid #000; padding: 8px;">${student2 ? i * 2 + 2 : ''}</td>
                            <td style="border: 1px solid #000; padding: 8px;">${name2}</td>
                            <td style="border: 1px solid #000; text-align: center; padding: 8px; vertical-align: middle;">
                                ${signatureCell2}
                            </td>
                        `;

                        tbody.appendChild(row);
                    }

                    // Show message if no records found
                    if (!data.has_records) {
                        const messageRow = document.createElement('tr');
                        messageRow.innerHTML = `
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #6c757d; border: 1px solid #000;">
                                <i class="fas fa-info-circle me-2"></i>
                                No attendance records found for this date and term.
                            </td>
                        `;
                        tbody.appendChild(messageRow);
                    }
                })
                .catch(error => {
                    console.error('Error loading attendance:', error);
                    alert('Failed to load attendance records. Please try again.');
                });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\edutrack\resources\views/teacher/attendance/index.blade.php ENDPATH**/ ?>