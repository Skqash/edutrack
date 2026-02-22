<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduTrack</title>

    <!-- Local Bootstrap (OFFLINE SAFE) -->
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>">

    <!-- Custom Theme -->
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="#">
            EduTrack
        </a>
    </div>
</nav>

<div class="container">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<!-- Local Bootstrap JS -->
<script src="<?php echo e(asset('bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

</body>
</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views/layouts/app.blade.php ENDPATH**/ ?>