@extends('layouts.app')

@section('content')
    <div class="card shadow auth-card">
        <div class="card-body">

            <h4 class="text-center mb-4">Create Account</h4>

            <form method="POST" action="/register">
                @csrf

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label">Register As</label>
                    <select name="role" class="form-select" required>
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input name="last_name" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="icon-password"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            required>
                        <span class="input-group-text" style="cursor: pointer;"
                            onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="icon-password_confirmation"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>

                <div class="text-center mt-3">
                    <a href="/login">Already have an account?</a>
                </div>
            </form>
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
