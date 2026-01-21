@extends('layouts.app')

@section('content')
    <div class="card shadow auth-card">
        <div class="card-body">

            <!-- Logo & Title -->
            <div class="text-center mb-4">
                <img src="{{ asset('./images/logo.jpg') }}" class="brand-logo">
                <h4 class="brand-title">EduTrack</h4>
                <p class="text-muted">Login to your account</p>
            </div>

            <!-- Alerts -->
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <!-- Password with Show / Hide -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                        <span class="input-group-text toggle-password" onclick="togglePassword()">
                            👁
                        </span>
                    </div>
                </div>

                <!-- Remember -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

                <!-- Links -->
                <div class="text-center mt-3">
                    <a href="/forgot-password" class="text-decoration-none">Forgot Password?</a>
                </div>
            </form>
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