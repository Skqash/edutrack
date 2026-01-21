@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-user-edit"></i>
            </span>
            Edit Student
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                   name="name" placeholder="Enter student full name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                                   name="email" placeholder="student@example.com" value="{{ old('email', $student->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="role" class="form-label"><i class="fas fa-badge"></i> Role</label>
                            <select class="form-select" id="role" name="role" disabled>
                                <option value="student" selected>Student</option>
                            </select>
                            <small class="text-muted">Role cannot be changed after creation</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label"><i class="fas fa-calendar"></i> Enrollment Date</label>
                            <input type="text" class="form-control" value="{{ $student->created_at->format('d M Y, H:i A') }}" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label"><i class="fas fa-toggle-on"></i> Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Update Student
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Student Information</h5>
                    <ul class="small list-unstyled">
                        <li><strong>ID:</strong> {{ $student->id }}</li>
                        <li><strong>Email:</strong> {{ $student->email }}</li>
                        <li><strong>Created:</strong> {{ $student->created_at->format('d M Y') }}</li>
                        <li><strong>Last Login:</strong> {{ $student->last_login_at ? $student->last_login_at->format('d M Y, H:i A') : 'Never' }}</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Delete this student permanently</p>
                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('This action cannot be undone. Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash"></i> Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #4099ff, #73b4ff);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #2970cc, #4099ff);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4099ff;
        box-shadow: 0 0 0 0.2rem rgba(64, 153, 255, 0.25);
    }
</style>
@endsection
