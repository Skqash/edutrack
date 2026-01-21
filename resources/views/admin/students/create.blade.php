@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fas fa-user-plus"></i>
            </span>
            Add New Student
        </h3>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                   name="name" placeholder="Enter student full name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                                   name="email" placeholder="student@example.com" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" 
                                   name="password" placeholder="Enter password (minimum 6 characters)" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Confirm password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="class_id" class="form-label"><i class="fas fa-chalkboard"></i> Assign to Class (Optional)</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" id="class_id" name="class_id">
                                <option value="">Select a class...</option>
                                @foreach(\App\Models\ClassModel::where('status', 'Active')->get() as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->class_name }} - Level {{ $class->class_level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="fas fa-save"></i> Add Student
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
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Instructions</h5>
                    <ul class="small">
                        <li>Fill all required fields (*)</li>
                        <li>Email must be unique</li>
                        <li>Password must be at least 6 characters</li>
                        <li>Class assignment is optional and can be done later</li>
                        <li>Student will receive credentials via email</li>
                    </ul>
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
