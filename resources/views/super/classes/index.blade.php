@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-school"></i> Class Management</h2>
            <a href="{{ route('super.classes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Class
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
                    data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Level</th>
                            <th>Capacity</th>
                            <th>Enrolled</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td><strong>{{ $class->class_code }}</strong></td>
                                <td>{{ $class->class_name }}</td>
                                <td>{{ $class->level }}</td>
                                <td>{{ $class->capacity ?? '30' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $class->students_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $class->status === 'Active' ? 'success' : 'secondary' }}">
                                        {{ $class->status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editClass({{ $class->id }})">Edit</button>
                                    <form action="#" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No classes found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $classes->links() }}
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('super.classes.delete-all') }}" method="POST"
                onsubmit="return confirm('Delete ALL classes? This cannot be undone!')">
                @csrf @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Classes</button>
            </form>
        </div>
    </div>
@endsection
