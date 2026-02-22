@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-users"></i> Student Management</h2>
            <a href="{{ route('super.students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Student
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="search" id="studentSearch" class="form-control form-control-sm"
                            placeholder="Search students...">
                    </div>
                    <div class="col-md-6">
                        <select id="classFilter" class="form-select form-select-sm">
                            <option value="">All Classes</option>
                            @foreach ($classes ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="studentsTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Enrollment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="student-row" data-class="{{ $student->class_id ?? '' }}">
                                <td><strong>{{ $student->student_id }}</strong></td>
                                <td>{{ $student->user->name ?? $student->name }}</td>
                                <td>{{ $student->user->email ?? $student->email }}</td>
                                <td>{{ $student->class->class_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $student->status === 'Active' ? 'success' : 'secondary' }}">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td>{{ $student->enrollment_date ? $student->enrollment_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editStudent({{ $student->id }})">Edit</button>
                                    <form action="#" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No students found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $students->links() }}
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('super.students.delete-all') }}" method="POST"
                onsubmit="return confirm('Delete ALL students? This cannot be undone!')">
                @csrf @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Students</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            let query = this.value.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        });

        document.getElementById('classFilter').addEventListener('change', function() {
            let classId = this.value;
            document.querySelectorAll('.student-row').forEach(row => {
                row.style.display = !classId || row.dataset.class === classId ? '' : 'none';
            });
        });
    </script>
@endsection
