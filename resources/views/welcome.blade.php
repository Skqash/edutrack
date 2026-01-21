<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>EasyAttend - Login</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ruang-admin.min.css') }}" rel="stylesheet">
</head>

<body class="bg-gradient-login" style="background-image: url('{{ asset('img/logo/loral1.jpg') }}');">

<div class="container-login">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow-sm my-5">
                <div class="card-body p-0">
                    <div class="login-form">

                        <h5 align="center">Easy-Attend</h5>

                        <div class="text-center">
                            <img src="{{ asset('img/logo/attnlg.jpg') }}" style="width:100px;height:100px">
                            <br><br>
                            <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                        </div>

                        <form method="POST" action="{{ url('/login') }}">
                            @csrf

                            <div class="form-group">
                                <input type="email" class="form-control" required name="username" placeholder="Enter Email Address">
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" name="password" required class="form-control" id="passwordField" placeholder="Enter Password">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox small">
                                    <input type="checkbox" class="custom-control-input" id="customCheck" name="rememberMe">
                                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-block" value="Login">
                            </div>

                            <div class="text-center">
                                <a class="small" href="#">Forgot Password?</a>
                            </div>
                        </form>

                        @if(session('errorMessage'))
                            <div class="alert alert-danger mt-3">
                                {{ session('errorMessage') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordField = document.getElementById('passwordField');
    const icon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/ruang-admin.min.js') }}"></script>

</body>
</html>
