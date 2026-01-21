@extends('layouts.app')

@section('content')
<div class="card shadow auth-card">
    <div class="card-body">

        <h4 class="text-center mb-4">Create New Password</h4>
        <p class="text-center text-muted mb-4">Enter your email and new password to reset your account.</p>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Enter your email" value="{{ $email ?? old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter new password (min 8 characters)">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required placeholder="Confirm password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Reset Password</button>

            <div class="text-center mt-3">
                <a href="/login" class="text-decoration-none">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
