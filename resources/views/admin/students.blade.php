@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-user-graduate"></i>
            </span>
            Students Management
        </h3>
        <div class="page-breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">All Students</h4>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Student
                        </a>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="badge badge-gradient-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                <span class="ms-3">{{ $student->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-gradient-success">Active</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted">No students found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-gradient-primary {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
        color: white;
    }

    .btn-gradient-primary {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #2970cc, #4099ff);
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
