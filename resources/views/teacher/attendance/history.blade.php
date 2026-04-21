@extends('layouts.teacher')

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0">Attendance History - {{ $class->class_name }}</h1>
                <small class="text-muted">Search and revisit previous attendance records</small>
            </div>
            <div>
                <a href="{{ route('teacher.attendance.sheet', $class->id) }}" class="btn btn-outline-info me-2"
                    target="_blank">
                    <i class="fas fa-file-pdf"></i> Print Sheet
                </a>
                <a href="{{ route('teacher.attendance.manage', $class->id) }}" class="btn btn-outline-secondary me-2">Manage
                    Today's Attendance</a>
                <a href="{{ route('teacher.attendance') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.attendance.history', $class->id) }}"
                class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ $start ?? '' }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ $end ?? '' }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Student</label>
                    <x-searchable-dropdown name="student_id" id="student_id" placeholder="All Students"
                        api-url="{{ route('api.students') }}" :selected="$studentId ?? ''" :clearable="true" class="form-select" />
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>E-Signature</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr @if ($att->status === 'Late') style="background-color: #fff3cd;" @endif>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->student->name ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $att->status === 'Present' ? 'success' : ($att->status === 'Absent' ? 'danger' : ($att->status === 'Late' ? 'warning' : 'secondary')) }}">
                                        {{ $att->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($att->status === 'Absent')
                                        <span class="text-muted small fw-bold">NONE</span>
                                    @elseif ($att->e_signature)
                                        <button type="button" class="btn btn-sm btn-outline-info signature-view-btn"
                                            data-signature="{{ $att->e_signature }}"
                                            data-student="{{ $att->student->name ?? 'Student' }}" data-bs-toggle="modal"
                                            data-bs-target="#signatureViewModal" title="View E-Signature">
                                            <i class="fas fa-image"></i> View
                                        </button>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td>{{ $class->class_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $attendances->links() }}
        </div>
    </div>

    <!-- E-Signature View Modal -->
    <div class="modal fade" id="signatureViewModal" tabindex="-1" aria-labelledby="signatureViewTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="signatureViewTitle">Student E-Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="signatureImageContainer" class="text-center">
                        <img id="signatureImage" src="" alt="Student Signature"
                            style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                        <p class="text-muted mt-3 mb-0">Student: <strong id="signatureStudentName"></strong></p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const signatureViewModal = document.getElementById('signatureViewModal');
            if (!signatureViewModal) return;

            signatureViewModal.addEventListener('show.bs.modal', function(e) {
                const button = e.relatedTarget;
                const signature = button.getAttribute('data-signature');
                const studentName = button.getAttribute('data-student');

                const signatureImage = document.getElementById('signatureImage');
                const studentNameEl = document.getElementById('signatureStudentName');

                if (signature) {
                    signatureImage.src = signature;
                    studentNameEl.textContent = studentName;
                }
            });
        });
    </script>
@endsection
