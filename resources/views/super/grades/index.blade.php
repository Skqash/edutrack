@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-star"></i> Grade Management</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select id="classFilter" class="form-select form-select-sm">
                                    <option value="">All Classes</option>
                                    @foreach ($classes ?? [] as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="studentFilter" class="form-select form-select-sm">
                                    <option value="">All Students</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="search" id="subjectSearch" class="form-control form-control-sm"
                                    placeholder="Search subject...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="gradesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr class="grade-row" data-class="{{ $grade->class_id ?? '' }}"
                                data-subject="{{ $grade->subject->subject_code ?? '' }}">
                                <td>{{ $grade->student->user->name ?? $grade->student->name }}</td>
                                <td><strong>{{ $grade->subject->subject_name ?? 'N/A' }}</strong></td>
                                <td>{{ $grade->student->class->class_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $grade->score ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong
                                        class="text-{{ $grade->grade === 'A' ? 'success' : ($grade->grade === 'F' ? 'danger' : 'warning') }}">
                                        {{ $grade->grade ?? 'N/A' }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $grade->status === 'Pass' ? 'success' : 'danger' }}">
                                        {{ $grade->status }}
                                    </span>
                                </td>
                                <td>{{ $grade->graded_date ? $grade->graded_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editGrade({{ $grade->id }})">Edit</button>
                                    <form action="#" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No grades found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $grades->links() }}
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('super.grades.delete-all') }}" method="POST"
                onsubmit="return confirm('Delete ALL grades? This cannot be undone!')">
                @csrf @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Grades</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('subjectSearch').addEventListener('keyup', function() {
            let query = this.value.toLowerCase();
            document.querySelectorAll('.grade-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        });

        document.getElementById('classFilter').addEventListener('change', function() {
            let classId = this.value;
            document.querySelectorAll('.grade-row').forEach(row => {
                row.style.display = !classId || row.dataset.class === classId ? '' : 'none';
            });
        });
    </script>
@endsection
