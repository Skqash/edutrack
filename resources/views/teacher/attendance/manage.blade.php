@extends('layouts.teacher')

@section('content')
    <div class="container-fluid px-2 px-md-3">

        {{-- Page header --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <div>
                <h1 class="h5 fw-bold mb-1">Take Attendance</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.attendance') }}">Attendance</a></li>
                        <li class="breadcrumb-item active">{{ $class->class_name ?? ($class->name ?? 'Class') }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        @if ($students->isEmpty())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> No students enrolled in this class.
            </div>
        @else
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('teacher.attendance.record', $class->id) }}" method="POST" id="attendanceForm">
                @csrf
                <input type="hidden" name="date" id="dateInput" value="{{ $today }}">
                <input type="hidden" name="term" id="termInput" value="{{ $currentTerm }}">

                {{-- Class info + date + term --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body py-3">
                        <div class="row align-items-center g-2">
                            <div class="col-12 col-md">
                                <div class="fw-bold text-primary">
                                    <i class="fas fa-door-open me-1"></i>
                                    {{ $class->class_name ?? ($class->name ?? 'Class') }}
                                </div>
                                <small class="text-muted">
                                    @if ($class->program)
                                        {{ $class->program->program_name ?? '' }}
                                        @if ($class->program->program_code)
                                            ({{ $class->program->program_code }})
                                        @endif
                                    @else
                                        No program assigned
                                    @endif
                                </small>
                            </div>
                            <div class="col-auto">
                                <label class="form-label small text-muted mb-1 d-block">
                                    <i class="fas fa-calendar-alt me-1"></i>Academic Term
                                </label>
                                <select id="termSelect" class="form-select form-select-sm fw-bold"
                                    style="min-width: 120px;">
                                    <option value="Midterm" {{ $currentTerm === 'Midterm' ? 'selected' : '' }}>
                                        📚 Midterm Term
                                    </option>
                                    <option value="Final" {{ $currentTerm === 'Final' ? 'selected' : '' }}>
                                        🎓 Final Term
                                    </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label class="form-label small text-muted mb-1 d-block">
                                    <i class="fas fa-calendar-day me-1"></i>Attendance Date
                                </label>
                                <input type="date" id="attendanceDate" class="form-control form-control-sm"
                                    value="{{ $today }}" max="{{ $today }}">
                            </div>
                        </div>

                        {{-- Term Indicator --}}
                        <div class="mt-2">
                            <div class="alert alert-info py-2 px-3 mb-0" id="termIndicator">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Currently taking attendance for:</strong>
                                <span class="badge bg-primary ms-1" id="currentTermBadge">{{ $currentTerm }} Term</span>
                                <small class="text-muted ms-2">All records will be saved under this term</small>
                            </div>
                        </div>

                        {{-- Attendance Settings Info --}}
                        <div class="mt-3 pt-3 border-top">
                            <div class="row g-2 small">
                                <div class="col-auto">
                                    <span class="text-muted">Total Meetings ({{ $currentTerm }}):</span>
                                    <strong class="text-primary ms-1">
                                        {{ $currentTerm === 'Midterm' ? $class->total_meetings_midterm : $class->total_meetings_final }}
                                    </strong>
                                </div>
                                <div class="col-auto">
                                    <span class="text-muted">Attendance Weight:</span>
                                    <strong class="text-success ms-1">{{ $class->attendance_percentage }}%</strong>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('teacher.attendance.settings', $class->id) }}"
                                        class="text-decoration-none">
                                        <i class="fas fa-cog me-1"></i>Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-success" onclick="markAll('Present')">
                        <i class="fas fa-check-double me-1"></i> All Present
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="markAll('Absent')">
                        <i class="fas fa-times me-1"></i> All Absent
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAll()">
                        <i class="fas fa-eraser me-1"></i> Clear
                    </button>
                    <span class="ms-auto align-self-center small text-muted">
                        {{ $students->count() }} student(s)
                    </span>
                </div>

                {{-- Legend --}}
                <div class="d-flex flex-wrap gap-3 mb-3 small">
                    <span><span class="status-dot present"></span> Present</span>
                    <span><span class="status-dot absent"></span> Absent</span>
                    <span><span class="status-dot late"></span> Late</span>
                </div>

                {{-- E-Signature Status Notice --}}
                @php
                    // Check students' permanent e-signatures, not today's attendance
                    $studentsWithoutSignatures = $students->filter(function($student) {
                        return empty($student->e_signature);
                    });
                    $studentsWithSignatures = $students->count() - $studentsWithoutSignatures->count();
                @endphp

                @if ($studentsWithoutSignatures->count() > 0)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="signatureAlert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                <strong>Missing E-Signatures (<span id="missingCount">{{ $studentsWithoutSignatures->count() }}</span> student{{ $studentsWithoutSignatures->count() > 1 ? 's' : '' }})</strong>
                                <p class="mb-2 small">The following students haven't captured their e-signatures yet:</p>
                                <div class="small" id="missingStudentsList">
                                    @foreach ($studentsWithoutSignatures->take(10) as $student)
                                        <span class="badge bg-warning text-dark me-1 mb-1" data-student-id="{{ $student->id }}">
                                            <i class="fas fa-user me-1"></i>{{ $student->first_name }} {{ $student->last_name }}
                                        </span>
                                    @endforeach
                                    @if ($studentsWithoutSignatures->count() > 10)
                                        <span class="badge bg-secondary">+{{ $studentsWithoutSignatures->count() - 10 }} more</span>
                                    @endif
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>Click the pen icon next to each student to capture their signature.
                                </small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @else
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="signatureAlert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>All students have e-signatures!</strong>
                        <small class="d-block">{{ $studentsWithSignatures }} of {{ $students->count() }} students have captured their signatures.</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Student cards (mobile-first layout) --}}
                <div class="student-list">
                    @foreach ($students as $index => $student)
                        @php $current = $attendances[$student->id] ?? null; @endphp
                        <div class="student-card card border-0 shadow-sm mb-2">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Number + name --}}
                                    <div class="student-num text-muted small fw-bold">{{ $index + 1 }}</div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold text-truncate">{{ $student->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $student->student_id ?? '–' }}</small>
                                    </div>
                                    {{-- Signature Button --}}
                                    <button type="button" class="btn btn-sm btn-outline-info signature-btn"
                                        data-student-id="{{ $student->id }}"
                                        data-student-name="{{ $student->name ?? 'Student' }}"
                                        data-has-signature="{{ $student->e_signature ? 'true' : 'false' }}"
                                        data-has-permanent-sig="{{ !empty($student->e_signature) ? '1' : '0' }}"
                                        data-bs-toggle="modal" data-bs-target="#signatureModal"
                                        title="Capture/View E-Signature">
                                        <i class="fas fa-pen-fancy"></i>
                                    </button>
                                    {{-- Status buttons --}}
                                    <div class="status-btns d-flex gap-1">
                                        <label class="status-btn present-btn" title="Present">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                value="Present" class="sr-only"
                                                {{ $current && $current->status === 'Present' ? 'checked' : '' }}>
                                            <span><i class="fas fa-check"></i></span>
                                        </label>
                                        <label class="status-btn absent-btn" title="Absent">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                value="Absent" class="sr-only"
                                                {{ $current && $current->status === 'Absent' ? 'checked' : '' }}>
                                            <span><i class="fas fa-times"></i></span>
                                        </label>
                                        <label class="status-btn late-btn" title="Late">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]"
                                                value="Late" class="sr-only"
                                                {{ $current && $current->status === 'Late' ? 'checked' : '' }}>
                                            <span><i class="fas fa-clock"></i></span>
                                        </label>
                                    </div>
                                    {{-- Hidden field to store signature --}}
                                    <input type="hidden" name="attendance[{{ $student->id }}][e_signature]"
                                        class="signature-data" value="">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Save footer --}}
                <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pb-3">
                    <small class="text-muted">Tap a status and capture signature for each student, then save.</small>
                    <div class="d-flex gap-2">
                        <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                        <a href="{{ route('teacher.attendance.sheet', ['classId' => $class->id]) }}?date={{ $today }}&term={{ $currentTerm }}"
                            class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> View Sheet
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Save
                        </button>
                    </div>
                </div>

            </form>
        @endif
    </div>

    <style>
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Legend dots */
        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
        }

        .status-dot.present {
            background: #198754;
        }

        .status-dot.absent {
            background: #dc3545;
        }

        .status-dot.late {
            background: #fd7e14;
        }

        .status-dot.excused {
            background: #0d6efd;
        }

        /* Student number */
        .student-num {
            min-width: 22px;
            text-align: center;
        }

        /* Status buttons */
        .status-btns {
            flex-shrink: 0;
        }

        .status-btn {
            cursor: pointer;
            margin: 0;
        }

        .status-btn span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: #fff;
            color: #adb5bd;
            font-size: 14px;
            transition: all 0.15s ease;
        }

        .status-btn:hover span {
            border-color: #adb5bd;
            background: #f8f9fa;
        }

        /* Checked states */
        .present-btn input:checked~span {
            background: #198754;
            border-color: #198754;
            color: #fff;
        }

        .absent-btn input:checked~span {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .late-btn input:checked~span {
            background: #fd7e14;
            border-color: #fd7e14;
            color: #fff;
        }

        .excused-btn input:checked~span {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        /* Highlight row when any status selected */
        .student-card:has(input:checked) {
            border-left: 3px solid #667eea !important;
        }

        /* Mobile tweaks */
        @media (max-width: 576px) {
            .status-btn span {
                width: 34px;
                height: 34px;
                font-size: 13px;
                border-radius: 6px;
            }

            .student-card .card-body {
                padding: 10px 12px;
            }

            .student-num {
                min-width: 18px;
                font-size: 12px;
            }
        }

        /* Larger screens: show as table-like layout */
        @media (min-width: 768px) {
            .status-btn span {
                width: 42px;
                height: 42px;
                font-size: 15px;
            }
        }
    </style>

    <script>
        document.getElementById('attendanceDate').addEventListener('change', function() {
            const newDate = this.value;
            if (!newDate) return;
            // Reload page with new date so server returns correct existing attendance
            const url = new URL(window.location.href);
            url.searchParams.set('date', newDate);
            url.searchParams.set('term', document.getElementById('termSelect').value);
            window.location.href = url.toString();
        });

        document.getElementById('termSelect').addEventListener('change', function() {
            const selectedTerm = this.value;
            document.getElementById('termInput').value = selectedTerm;

            // Update term indicator
            const badge = document.getElementById('currentTermBadge');
            const indicator = document.getElementById('termIndicator');

            if (badge) {
                badge.textContent = selectedTerm + ' Term';
                badge.className = selectedTerm === 'Midterm' ? 'badge bg-warning ms-1' : 'badge bg-success ms-1';
            }

            // Update indicator message
            if (indicator) {
                const termIcon = selectedTerm === 'Midterm' ? '📚' : '🎓';
                indicator.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                <strong>Currently taking attendance for:</strong>
                <span class="badge ${selectedTerm === 'Midterm' ? 'bg-warning' : 'bg-success'} ms-1" id="currentTermBadge">${termIcon} ${selectedTerm} Term</span>
                <small class="text-muted ms-2">All records will be saved under this term</small>
            `;
            }

            // Show confirmation before reloading
            if (confirm(`Switch to ${selectedTerm} term? Any unsaved attendance will be lost.`)) {
                // Reload page to get attendance for selected term
                const url = new URL(window.location.href);
                url.searchParams.set('term', selectedTerm);
                url.searchParams.set('date', document.getElementById('attendanceDate').value);
                window.location.href = url.toString();
            } else {
                // Revert selection if cancelled
                this.value = '{{ $currentTerm }}';
            }
        });

        // Initialize term indicator on page load
        document.addEventListener('DOMContentLoaded', function() {
            const currentTerm = '{{ $currentTerm }}';
            const badge = document.getElementById('currentTermBadge');
            if (badge) {
                badge.className = currentTerm === 'Midterm' ? 'badge bg-warning ms-1' : 'badge bg-success ms-1';
            }
        });

        function markAll(status) {
            document.querySelectorAll('input[value="' + status + '"]').forEach(i => i.checked = true);
        }

        function clearAll() {
            document.querySelectorAll('#attendanceForm input[type="radio"]').forEach(i => i.checked = false);
        }

        // Function to update signature notice dynamically
        function updateSignatureNotice(studentId) {
            const badge = document.querySelector(`#missingStudentsList .badge[data-student-id="${studentId}"]`);
            if (badge) {
                badge.remove();
            }
            
            // Update count
            const remainingBadges = document.querySelectorAll('#missingStudentsList .badge[data-student-id]').length;
            const countElement = document.getElementById('missingCount');
            if (countElement) {
                countElement.textContent = remainingBadges;
            }
            
            // If no more missing signatures, change alert to success
            if (remainingBadges === 0) {
                const alert = document.getElementById('signatureAlert');
                if (alert) {
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>All students have e-signatures!</strong>
                        <small class="d-block">All students in this session have captured their signatures.</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                }
            }
        }

        // Add form submission handler for validation and warnings
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default submission
            
            const formData = new FormData(this);
            let signatureCount = 0;
            let attendanceMarked = 0;
            const studentsWithoutSignatures = [];
            const studentsWithSignatures = [];
            
            // Count attendance marked and signatures
            const allStudents = document.querySelectorAll('.student-card');
            allStudents.forEach(card => {
                const studentId = card.querySelector('.signature-btn')?.getAttribute('data-student-id');
                const studentName = card.querySelector('.signature-btn')?.getAttribute('data-student-name');
                const hasPermanentSig = card.querySelector('.signature-btn')?.getAttribute('data-has-permanent-sig') === '1';
                const hasAttendance = card.querySelector('input[type="radio"]:checked');
                const signatureField = card.querySelector('.signature-data');
                const hasNewSignature = signatureField?.value; // Newly captured signature
                
                if (hasAttendance) {
                    attendanceMarked++;
                    // Check if student has permanent signature OR newly captured one
                    if (!hasPermanentSig && !hasNewSignature) {
                        studentsWithoutSignatures.push(studentName);
                    } else {
                        signatureCount++;
                        studentsWithSignatures.push({
                            id: studentId,
                            name: studentName,
                            hasPermanent: hasPermanentSig,
                            hasNew: !!hasNewSignature
                        });
                    }
                }
            });
            
            console.log('Form submitting:', {
                attendanceMarked,
                signatureCount,
                withoutSignatures: studentsWithoutSignatures.length,
                withSignatures: studentsWithSignatures
            });
            
            // Validate: at least one attendance marked
            if (attendanceMarked === 0) {
                showSaveModal('No Attendance Marked', 
                    'Please mark attendance for at least one student before saving.', 
                    'warning', 
                    false);
                return false;
            }
            
            // Warning: students without signatures
            if (studentsWithoutSignatures.length > 0) {
                const studentList = studentsWithoutSignatures.slice(0, 5).join(', ');
                const moreText = studentsWithoutSignatures.length > 5 ? ` and ${studentsWithoutSignatures.length - 5} more` : '';
                
                showSaveModal(
                    'Missing E-Signatures',
                    `<p><strong>${studentsWithoutSignatures.length} student(s)</strong> don't have e-signatures:</p>
                    <p class="text-muted small">${studentList}${moreText}</p>
                    <p class="mb-0">Do you want to save attendance anyway?</p>
                    <p class="small text-muted mt-2"><i class="fas fa-info-circle"></i> Tip: Click the pen icon (🖊️) next to each student to capture their signature.</p>`,
                    'warning',
                    true,
                    () => {
                        // User confirmed, submit the form
                        document.getElementById('attendanceForm').submit();
                    }
                );
            } else {
                // All good, show success confirmation
                showSaveModal(
                    'Save Attendance',
                    `<p>Ready to save attendance for <strong>${attendanceMarked} student(s)</strong></p>
                    <p class="small text-success"><i class="fas fa-check-circle"></i> All students have e-signatures (${signatureCount})</p>
                    <p class="mb-0">Continue?</p>`,
                    'success',
                    true,
                    () => {
                        // User confirmed, submit the form
                        document.getElementById('attendanceForm').submit();
                    }
                );
            }
        });

        // Function to show save confirmation modal
        function showSaveModal(title, message, type, showConfirm, onConfirm) {
            const modal = document.getElementById('saveConfirmModal');
            const modalTitle = document.getElementById('saveModalTitle');
            const modalBody = document.getElementById('saveModalBody');
            const modalIcon = document.getElementById('saveModalIcon');
            const confirmBtn = document.getElementById('saveConfirmBtn');
            
            // Set title and message
            modalTitle.textContent = title;
            modalBody.innerHTML = message;
            
            // Set icon based on type
            if (type === 'warning') {
                modalIcon.className = 'fas fa-exclamation-triangle text-warning';
                confirmBtn.className = 'btn btn-warning';
                confirmBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Anyway';
            } else if (type === 'success') {
                modalIcon.className = 'fas fa-check-circle text-success';
                confirmBtn.className = 'btn btn-success';
                confirmBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Attendance';
            } else {
                modalIcon.className = 'fas fa-info-circle text-info';
                confirmBtn.className = 'btn btn-primary';
                confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> OK';
            }
            
            // Show/hide confirm button
            if (showConfirm) {
                confirmBtn.style.display = 'inline-block';
                confirmBtn.onclick = () => {
                    bootstrap.Modal.getInstance(modal).hide();
                    if (onConfirm) onConfirm();
                };
            } else {
                confirmBtn.style.display = 'none';
            }
            
            // Show modal
            new bootstrap.Modal(modal).show();
        }

        // E-Signature Modal Handler
        let currentStudentId = null;
        let currentStudentName = null;
        let signaturePad = null;

        document.addEventListener('DOMContentLoaded', function() {
            const signatureModal = document.getElementById('signatureModal');
            const canvas = document.getElementById('signatureCanvas');

            if (!signatureModal || !canvas) {
                console.error('Signature modal or canvas not found');
                return;
            }

            // Check if SignaturePad library is loaded
            if (typeof SignaturePad === 'undefined') {
                console.error('SignaturePad library not loaded!');
                alert('Signature capture is not available. Please refresh the page.');
                return;
            }

            // Resize canvas to fit container while maintaining aspect ratio
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = canvas.getBoundingClientRect();
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                
                if (signaturePad) {
                    signaturePad.clear();
                }
            }

            // Initialize Signature.js
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)',
                velocityFilterWeight: 0.7,
                minWidth: 0.5,
                maxWidth: 2.5,
                throttle: 16
            });

            console.log('SignaturePad initialized successfully');

            // Handle modal show event
            signatureModal.addEventListener('show.bs.modal', function(e) {
                const button = e.relatedTarget;
                currentStudentId = button.getAttribute('data-student-id');
                currentStudentName = button.getAttribute('data-student-name');

                console.log('Opening signature modal for student:', currentStudentName, 'ID:', currentStudentId);

                const modalTitle = document.getElementById('studentSignatureTitle');
                if (modalTitle) {
                    modalTitle.textContent = `E-Signature for ${currentStudentName}`;
                }

                // Resize canvas and clear when opening modal
                setTimeout(() => {
                    resizeCanvas();
                    signaturePad.clear();
                }, 100);
            });

            // Clear button
            document.getElementById('clearSignatureBtn').addEventListener('click', function() {
                signaturePad.clear();
                console.log('Signature cleared');
            });

            // Undo button
            document.getElementById('undoSignatureBtn').addEventListener('click', function() {
                const data = signaturePad.toData();
                if (data.length > 1) {
                    data.pop(); // Remove last point group
                    signaturePad.fromData(data);
                    console.log('Undo last stroke');
                }
            });

            // Save button
            document.getElementById('saveSignatureBtn').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature before saving.');
                    return;
                }

                // Get signature as base64
                const signatureData = canvas.toDataURL('image/png');
                console.log('Signature captured, length:', signatureData.length);

                // Store signature in hidden field
                const hiddenField = document.querySelector(
                    `input[name="attendance[${currentStudentId}][e_signature]"]`
                );

                if (hiddenField) {
                    hiddenField.value = signatureData;
                    console.log('Signature stored in hidden field for student ID:', currentStudentId);

                    // Update button to show signature captured
                    const signatureBtn = document.querySelector(
                        `button[data-student-id="${currentStudentId}"].signature-btn`
                    );
                    if (signatureBtn) {
                        signatureBtn.classList.add('btn-info');
                        signatureBtn.classList.remove('btn-outline-info');
                        signatureBtn.innerHTML = '<i class="fas fa-pen-fancy"></i> ✓';
                        signatureBtn.title = 'Signature Captured - Click to Update';
                        console.log('Button updated to show signature captured');
                    }

                    // Update the missing signatures notice
                    updateSignatureNotice(currentStudentId);

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                    successMsg.style.zIndex = '9999';
                    successMsg.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Signature captured for ${currentStudentName}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                    successMsg.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Signature captured for ${currentStudentName}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    console.error('Hidden field not found for student ID:', currentStudentId);
                    alert('Error: Could not save signature. Please try again.');
                    return;
                }

                // Close modal
                const modal = bootstrap.Modal.getInstance(signatureModal);
                if (modal) {
                    modal.hide();
                } else {
                    console.error('Could not get modal instance');
                }
            });

            // Handle window resize
            window.addEventListener('resize', resizeCanvas);
        });
    </script>

    <!-- Save Confirmation Modal -->
    <div class="modal fade" id="saveConfirmModal" tabindex="-1" aria-labelledby="saveModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="saveModalTitle">
                        <i id="saveModalIcon" class="fas fa-info-circle"></i>
                        <span>Confirmation</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="saveModalBody">
                    <!-- Dynamic content will be inserted here -->
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveConfirmBtn">
                        <i class="fas fa-check me-1"></i> Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- E-Signature Modal -->
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="studentSignatureTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="studentSignatureTitle">E-Signature Capture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="signature-container p-3">
                        <canvas id="signatureCanvas" width="700" height="250" class="border w-100"
                            style="background-color: #fff; touch-action: none; max-width: 100%;"></canvas>
                        <small class="text-muted d-block mt-2">Draw your signature above. Use your finger or
                            stylus.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="clearSignatureBtn">
                        <i class="fas fa-redo"></i> Clear
                    </button>
                    <button type="button" class="btn btn-warning" id="undoSignatureBtn">
                        <i class="fas fa-undo"></i> Undo
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSignatureBtn">
                        <i class="fas fa-save"></i> Save Signature
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    @endpush

@endsection
