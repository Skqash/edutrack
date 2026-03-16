@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow auth-card">
            <div class="card-body">

                <h4 class="text-center mb-4">Teacher Sign Up</h4>
                <p class="text-center text-muted mb-4">Create your teacher account and request connection to your institution
                    below.</p>

                <form method="POST" action="/register">
                    @csrf

                    <!-- Name -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input name="first_name" value="{{ old('first_name') }}"
                                class="form-control @error('first_name') is-invalid @enderror" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input name="last_name" value="{{ old('last_name') }}"
                                class="form-control @error('last_name') is-invalid @enderror" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="e.g. +1 (555) 123-4567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <input name="department" value="{{ old('department') }}"
                                class="form-control @error('department') is-invalid @enderror"
                                placeholder="e.g., Science, Math, Language">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <input name="specialization" value="{{ old('specialization') }}"
                            class="form-control @error('specialization') is-invalid @enderror"
                            placeholder="e.g., Physics, English, Computer Science">
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Campus / Institution</label>
                        <select name="campus" id="campus-select" class="form-select @error('campus') is-invalid @enderror"
                            required>
                            @php
                                $campuses = [
                                    'Victorias Campus',
                                    'Main Campus',
                                    'Candoni Campus',
                                    'Cauayan Campus',
                                    'Hinigaran Campus',
                                    'Hinoba-an Campus',
                                    'Ilog Campus',
                                    'Moises Padilla Campus',
                                    'San Carlos Campus',
                                    'Sipalay Campus',
                                ];
                                $otherInstitutions = ['DepEd', 'Private School', 'Training Center'];
                                $selected = old('campus', 'Victorias Campus');
                            @endphp

                            <optgroup label="CPSU Campuses">
                                @foreach ($campuses as $campus)
                                    <option value="{{ $campus }}" {{ $selected === $campus ? 'selected' : '' }}>
                                        {{ $campus }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Common non‑CPSU institutions">
                                @foreach ($otherInstitutions as $institution)
                                    <option value="{{ $institution }}"
                                        {{ $selected === $institution ? 'selected' : '' }}>
                                        {{ $institution }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <option value="Other" {{ $selected === 'Other' ? 'selected' : '' }}>
                                Other institution
                            </option>
                        </select>
                        @error('campus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Choose your campus or specify another institution.</small>
                    </div>

                    <div class="mb-3" id="campus-other-field" style="display: none;">
                        <label class="form-label">If other, please specify</label>
                        <input name="campus_other" value="{{ old('campus_other') }}"
                            class="form-control @error('campus_other') is-invalid @enderror"
                            placeholder="e.g. Some Other Institution">
                        @error('campus_other')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="mb-3">Institution / School Connection</h5>

                    <div class="mb-3">
                        <label class="form-label">School / Institution Name</label>
                        <input name="school_name" value="{{ old('school_name') }}"
                            class="form-control @error('school_name') is-invalid @enderror" placeholder="e.g. CPSU">
                        @error('school_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School Email</label>
                            <input type="email" name="school_email" value="{{ old('school_email') }}"
                                class="form-control @error('school_email') is-invalid @enderror"
                                placeholder="admin@school.edu">
                            @error('school_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">School Phone</label>
                            <input type="tel" name="school_phone" value="{{ old('school_phone') }}"
                                class="form-control @error('school_phone') is-invalid @enderror"
                                placeholder="e.g. +1 (555) 987-6543">
                            @error('school_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">School Address (Optional)</label>
                        <input name="school_address" value="{{ old('school_address') }}"
                            class="form-control @error('school_address') is-invalid @enderror"
                            placeholder="123 Main St, City, Country">
                        @error('school_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const campusSelect = document.getElementById('campus-select');
                            const otherField = document.getElementById('campus-other-field');

                            const toggleOther = () => {
                                const isOther = campusSelect.value === 'Other';
                                otherField.style.display = isOther ? 'block' : 'none';
                            };

                            campusSelect.addEventListener('change', toggleOther);
                            toggleOther();
                        });
                    </script>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="icon-password"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" required>
                            <span class="input-group-text" style="cursor: pointer;"
                                onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="icon-password_confirmation"></i>
                            </span>
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
