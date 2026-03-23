@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow auth-card">
            <div class="card-body">

                <h4 class="text-center mb-4">Teacher Sign Up</h4>
                <p class="text-center text-muted mb-4">Create your teacher account for CPSU EduTrack system.</p>

                <form method="POST" action="/register">
                    @csrf

                    <h5 class="mb-3">Personal Information</h5>

                    <!-- Name -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input name="first_name" value="{{ old('first_name') }}"
                                class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input name="last_name" value="{{ old('last_name') }}"
                                class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="e.g. +63 912 345 6789">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CPSU Campus</label>
                        <select name="campus" id="campus-select" class="form-select @error('campus') is-invalid @enderror">
                            @php
                                $campuses = [
                                    '' => 'Select Campus (Optional)',
                                    'Kabankalan' => 'CPSU Main Campus - Kabankalan City',
                                    'Victorias' => 'CPSU Victorias Campus',
                                    'Sipalay' => 'CPSU Sipalay Campus - Brgy. Gil Montilla',
                                    'Cauayan' => 'CPSU Cauayan Campus',
                                    'Candoni' => 'CPSU Candoni Campus',
                                    'Hinigaran' => 'CPSU Hinigaran Campus',
                                    'Hinoba-an' => 'CPSU Hinoba-an Campus',
                                    'Ilog' => 'CPSU Ilog Campus',
                                    'Moises Padilla' => 'CPSU Moises Padilla Campus',
                                    'San Carlos' => 'CPSU San Carlos Campus',
                                ];
                                $selected = old('campus', '');
                            @endphp

                            @foreach ($campuses as $value => $label)
                                <option value="{{ $value }}" {{ $selected === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('campus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave blank if you're not affiliated with any CPSU campus.</small>
                    </div>

                    <hr>

                    <h5 class="mb-3">Account Security</h5>

                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="icon-password"></i>
                            </button>
                        </div>
                        <small class="text-muted">Must contain uppercase, lowercase, and numbers. Minimum 8 characters.</small>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="icon-password_confirmation"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100">Register</button>

                    <div class="text-center mt-3">
                        <a href="/login">Already have an account?</a>
                    </div>
                </form>

                <div class="text-center mt-4 auth-footer">
                    <p class="mb-1">
                        <a href="mailto:support@edutrack.example.com" class="text-decoration-none">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('icon-' + fieldId);
            
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
