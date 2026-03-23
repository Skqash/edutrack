@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="fas fa-plus-circle"></i>
        </span>
        Add New Teacher
    </h3>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                               name="name" placeholder="Enter full name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" 
                               name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone" class="form-label"><i class="fas fa-phone"></i> Phone Number (Optional)</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" 
                               name="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="employee_id" class="form-label"><i class="fas fa-id-card"></i> Employee ID (Optional)</label>
                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" 
                               name="employee_id" placeholder="Enter employee ID" value="{{ old('employee_id') }}">
                        @error('employee_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="qualification" class="form-label"><i class="fas fa-graduation-cap"></i> Qualification (Optional)</label>
                        <input type="text" class="form-control @error('qualification') is-invalid @enderror" id="qualification" 
                               name="qualification" placeholder="e.g., Master of Education, PhD in Mathematics" value="{{ old('qualification') }}">
                        @error('qualification')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="specialization" class="form-label"><i class="fas fa-star"></i> Specialization (Optional)</label>
                        <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" 
                               name="specialization" placeholder="e.g., Mathematics, Computer Science, English" value="{{ old('specialization') }}">
                        @error('specialization')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="department" class="form-label"><i class="fas fa-building"></i> Department (Optional)</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" 
                               name="department" placeholder="e.g., College of Engineering, College of Education" value="{{ old('department') }}">
                        @error('department')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($adminCampus ?? null)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This teacher will be assigned to <strong>{{ $adminCampus }}</strong> campus.
                    </div>
                    @endif

                    <div class="form-group mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" 
                                   name="password" placeholder="Enter password (min 8 characters)" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock-check"></i> Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Confirm password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="fas fa-save"></i> Create Teacher
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(45deg, #0056b3, #003d82);
        color: white;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group .btn {
        border: 1px solid #ced4da;
    }

    .input-group .btn:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection
