<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="#198754">
    <title>EduTrack</title>

    <!-- Local Bootstrap (OFFLINE SAFE) -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Theme -->
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container d-flex align-items-center gap-2">
            <img src="/images/cpsu-logo.jpg" alt="CPSU logo" class="login-topbar-logo">
            <a class="navbar-brand fw-bold text-success mb-0" href="#">
                EduTrack
            </a>
        </div>
    </nav>

    <style>
        .login-topbar-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 15px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.85);
            padding: 4px;
        }
    </style>

    <div class="container">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Local Bootstrap JS -->
    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views/layouts/app.blade.php ENDPATH**/ ?>