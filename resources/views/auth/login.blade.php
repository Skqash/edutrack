@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow auth-card">
            <div class="card-body">

                <!-- Logo & Title -->
                <div class="text-center mb-4">
                    <img src="/images/logo.jpg" alt="EduTrack logo" class="brand-logo">
                    <h4 class="brand-title">EduTrack</h4>
                    <p class="text-muted mb-1">Welcome back! Let's make teaching simpler today.</p>
                    <p class="text-muted mb-0">Log in to access your classes, grades, and student tools.</p>
                </div>

                <!-- Alerts -->
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password with Show / Hide -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password" required>
                            <span class="input-group-text toggle-password" onclick="togglePassword()">
                                👁
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember Me
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <div class="text-center mt-4 auth-footer">
                    <p class="mb-1">
                        <a href="/forgot-password" class="text-decoration-none">Forgot Password?</a>
                    </p>
                    <p class="mb-1">
                        <a href="mailto:support@edutrack.example.com" class="text-decoration-none">Contact Support</a>
                    </p>
                    <p class="mb-0 text-muted">
                        Don't have an account? <a href="/register" class="text-decoration-none">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Show / Hide Password Script -->
    <script>
        function togglePassword() {
            const field = document.getElementById('password');
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        }
    </script>
@endsection
