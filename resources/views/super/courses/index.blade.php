@extends('layouts.app')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3"><i class="fas fa-book"></i> Course Management</h2>
            <a href="{{ route('super.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Course
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
                            <th>Instructor</th>
                            <th>Credits</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td><strong>{{ $course->course_code }}</strong></td>
                                <td>{{ $course->course_name }}</td>
                                <td>{{ $course->instructor->name ?? 'N/A' }}</td>
                                <td>{{ $course->credit_hours ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $course->status === 'Active' ? 'success' : 'secondary' }}">
                                        {{ $course->status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editCourse({{ $course->id }})">Edit</button>
                                    <form action="#" method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No courses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $courses->links() }}
            </div>
        </div>

        <div class="mt-3">
            <form action="{{ route('super.courses.delete-all') }}" method="POST"
                onsubmit="return confirm('Delete ALL courses? This cannot be undone!')">
                @csrf @method('DELETE')
                <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete All Courses</button>
            </form>
        </div>
    </div>
@endsection
