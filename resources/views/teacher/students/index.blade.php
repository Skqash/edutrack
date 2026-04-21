@extends('layouts.teacher')

@section('title', 'Students - ' . $class->class_name)

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1 text-dark fw-bold">
                            <i class="fas fa-users me-2 text-primary"></i>{{ $class->class_name }} - Students
                        </h1>
                        <p class="text-muted mb-0">
                            <span class="badge bg-primary">{{ $students->count() }} Students</span>
                            <span class="badge bg-secondary">{{ $class->subject->subject_name ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Class
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Summary Statistics Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users text-primary mb-2" style="font-size: 2rem;"></i>
                        <h4 class="mb-1">{{ $students->count() }}</h4>
                        <p class="text-muted mb-0">Total Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check text-info mb-2" style="font-size: 2rem;"></i>
                        <h4 class="mb-1">{{ $students->sum(fn($s) => $s->attendance->count()) }}</h4>
                        <p class="text-muted mb-0">Total Attendance Records</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line text-success mb-2" style="font-size: 2rem;"></i>
                        <h4 class="mb-1">{{ $students->sum(fn($s) => $s->grades->count()) }}</h4>
                        <p class="text-muted mb-0">Total Grade Entries</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-percentage text-warning mb-2" style="font-size: 2rem;"></i>
                        <h4 class="mb-1">{{ $students->filter(fn($s) => $s->grades->count() > 0)->count() }}</h4>
                        <p class="text-muted mb-0">Students with Grades</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark">Class Roster</h5>
                <div class="input-group input-group-sm" style="max-width: 250px;">
                    <span class="input-group-text bg-light border-secondary">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-secondary" id="searchInput" placeholder="Search by name or ID...">
                </div>
            </div>
            <div class="card-body p-0">
                @forelse ($students as $student)
                    @if ($loop->first)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0" id="studentsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Student Name</th>
                                        <th>Student ID</th>
                                        <th class="d-none d-md-table-cell">Email</th>
                                        <th class="d-none d-md-table-cell">Grade Year</th>
                                        <th class="d-none d-md-table-cell">Section</th>
                                        <th class="d-none d-md-table-cell">Attendance</th>
                                        <th class="d-none d-md-table-cell">Grades</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                    @endif

                    <tr class="studentRow">
                        <td class="ps-4 fw-semibold text-dark">
                            <i class="fas fa-user-circle text-primary me-2"></i>{{ ($student->first_name ?? '') . ' ' . ($student->last_name ?? '') }}
                        </td>
                        <td>
                            <code class="text-secondary">{{ $student->student_id ?? 'N/A' }}</code>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <small class="text-muted">{{ $student->email ?? 'N/A' }}</small>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-info text-dark">Year {{ $student->year ?? 'N/A' }}</span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-secondary">{{ $student->section ?? 'N/A' }}</span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-info">
                                <i class="fas fa-calendar-check me-1"></i>{{ $student->attendance->count() }}
                            </span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-success">
                                <i class="fas fa-chart-line me-1"></i>{{ $student->grades->count() }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('attendance.manage', $class->id) }}?student_id={{ $student->id }}" class="btn btn-outline-info" title="View Attendance">
                                    <i class="fas fa-calendar-check"></i>
                                </a>
                                <a href="{{ route('grades.entry.dynamic', ['classId' => $class->id, 'term' => 'midterm']) }}?student_id={{ $student->id }}" class="btn btn-outline-success" title="View Grades">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" 
                                    data-bs-target="#deleteStudentModal{{ $student->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    @if ($loop->last)
                                </tbody>
                            </table>
                        </div>
                    @endif
                @empty
                    <div class="p-5 text-center">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No students enrolled in this class yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Delete Student Modals -->
    @foreach ($students as $student)
        <div class="modal fade" id="deleteStudentModal{{ $student->id }}" tabindex="-1" role="dialog" 
            aria-labelledby="deleteStudentModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-danger">
                    <div class="modal-header bg-danger text-white border-danger">
                        <h5 class="modal-title" id="deleteStudentModalLabel{{ $student->id }}">
                            <i class="fas fa-exclamation-triangle me-2"></i>Remove Student
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger mb-3">
                            <strong>⚠️ Warning!</strong> This action cannot be undone.
                        </div>
                        <p>Are you sure you want to remove <strong>{{ $student->first_name }} {{ $student->last_name }}</strong> from this class?</p>
                        <p class="text-muted small mb-0">
                            All grade entries for this student in this class will be permanently deleted.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="class_id" value="{{ $class->id }}">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>Remove Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @endsection

@push('scripts')
    <script>
        // Debug information
        console.log('Total students: {{ $students->count() }}');
        console.log('Class ID: {{ $class->id }}');
        
        // Simple search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.studentRow');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
@endpush
