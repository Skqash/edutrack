
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>EduTrack | Teacher Panel</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('bootstrap/css/bootstrap.min.css')); ?>">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
            background-color: #f4f8fb;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Method: body left padding reserves space so content CANNOT overlap sidebar */
        body.teacher-panel {
            padding-left: 260px;
            width: 100%;
            box-sizing: border-box;
        }
        body.teacher-panel.sidebar-collapsed {
            padding-left: 80px;
        }
        @media (max-width: 768px) {
            body.teacher-panel {
                padding-left: 0;
            }
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(102, 126, 234, 0.15);
            transition: all 0.3s ease;
            border-right: 1px solid #e8f0f8;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 2px solid #e8f0f8;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            color: #667eea;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: #764ba2;
            background-color: #f0f4ff;
            border-left-color: #667eea;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-menu a span {
            display: none;
        }

        .main-wrapper {
            margin-left: 0;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            overflow-x: hidden;
            position: relative;
        }

        .main-wrapper.sidebar-collapsed .topbar {
            left: 80px;
        }

        .topbar {
            background: white;
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            z-index: 1000;
            border-bottom: 2px solid #e8f0f8;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.08);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .content-wrapper {
            padding: 20px;
            padding-top: 80px;
            flex: 1;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: hidden;
            overflow-y: auto;
            display: block;
        }
        .content-wrapper > * {
            min-width: 0;
            max-width: 100%;
        }
        .content-wrapper .card {
            max-width: 100%;
            overflow: hidden;
        }
        .content-wrapper .table-responsive {
            max-width: 100%;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .toggle-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .toggle-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 260px;
                margin-left: -260px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-wrapper {
                margin-left: 0;
            }

            .topbar {
                left: 0;
                padding: 10px 15px;
            }

            .content-wrapper {
                padding: 15px;
                padding-top: 70px;
            }
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        footer {
            background: white;
            border-top: 1px solid #e5e9f0;
            padding: 20px;
            text-align: center;
            color: #666;
            margin-top: 40px;
        }

        /* KSA Grading System Colors */
        .grade-badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .grade-a {
            background-color: #d4edda;
            color: #155724;
        }

        .grade-b {
            background-color: #cfe2ff;
            color: #084298;
        }

        .grade-c {
            background-color: #fff3cd;
            color: #664d03;
        }

        .grade-d {
            background-color: #f8d7da;
            color: #842029;
        }

        .grade-f {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>

    <!-- Theme CSS -->
    <?php
        $theme = Auth::user()->theme ?? 'light';
    ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/themes/' . $theme . '.css')); ?>">
</head>

<body class="teacher-panel">

    <div id="mainContainer" class="main-wrapper">
        <!-- SIDEBAR -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-chalkboard-user"></i>
                <span>EduTrack</span>
            </div>

            <ul class="sidebar-menu">
                <li><a href="<?php echo e(route('teacher.dashboard')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.dashboard') ? 'active' : ''); ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a></li>

                <li><a href="<?php echo e(route('teacher.classes')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.classes*') ? 'active' : ''); ?>">
                        <i class="fas fa-door-open"></i>
                        <span>My Classes</span>
                    </a></li>

                <li><a href="<?php echo e(route('teacher.subjects')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.subjects') ? 'active' : ''); ?>">
                        <i class="fas fa-book"></i>
                        <span>Courses</span>
                    </a></li>

                <li><a href="<?php echo e(route('teacher.grades')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.grades*') ? 'active' : ''); ?>">
                        <i class="fas fa-star"></i>
                        <span>Grades</span>
                    </a></li>

                <li><a href="<?php echo e(route('teacher.attendance')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.attendance*') ? 'active' : ''); ?>">
                        <i class="fas fa-check-square"></i>
                        <span>Attendance</span>
                    </a></li>

                <li><a href="<?php echo e(route('teacher.settings.index')); ?>"
                        class="<?php echo e(request()->routeIs('teacher.settings*') ? 'active' : ''); ?>">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a></li>
            </ul>
        </div>

        <!-- TOP BAR -->
        <nav class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="fw-bold d-none d-sm-inline">Teacher Dashboard</span>
                <span class="fw-bold d-sm-none">EduTrack</span>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Notifications -->
                <div class="dropdown">
                    <?php
                        $unreadCount = \App\Models\Notification::unreadCount(auth()->id());
                        $notifications = \App\Models\Notification::where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    ?>
                    <button class="btn btn-light position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <?php if($unreadCount > 0): ?>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 0.7rem;">
                                <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

                            </span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end"
                        style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header">Notifications</h6>
                        </li>
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <a class="dropdown-item border-bottom py-2" href="<?php echo e($notif->action_url ?? '#'); ?>">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-<?php echo e($notif->type); ?> rounded-circle">
                                            <i class="fas fa-<?php echo e($notif->icon ?? 'bell'); ?>"></i>
                                        </span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 <?php echo e($notif->isRead() ? 'text-muted' : 'fw-bold'); ?>">
                                                <?php echo e($notif->title); ?>

                                            </h6>
                                            <small class="text-muted d-block"><?php echo e($notif->message); ?></small>
                                            <small class="text-muted"><?php echo e($notif->created_at->diffForHumans()); ?></small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li>
                                <p class="dropdown-item text-center text-muted py-3">No notifications</p>
                            </li>
                        <?php endif; ?>
                        <?php if(count($notifications) > 0): ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center small" href="#">View All</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Profile -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="d-none d-sm-inline"><?php echo e(auth()->user()->name ?? 'Teacher'); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo e(route('teacher.profile.show')); ?>">
                                <i class="fas fa-user me-2"></i> My Profile
                            </a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('teacher.profile.edit')); ?>">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('teacher.profile.change-password')); ?>">
                                <i class="fas fa-key me-2"></i> Change Password
                            </a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('teacher.settings.index')); ?>">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?php echo e(route('logout')); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        <!-- FOOTER -->
        <footer>
            © <?php echo e(date('Y')); ?> EduTrack | Academic Management System
        </footer>

    </div>

    <script src="<?php echo e(asset('bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('mainContainer');

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContainer.classList.toggle('sidebar-collapsed');
                document.body.classList.toggle('sidebar-collapsed');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = event.target.closest('.toggle-btn');

            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggleBtn) {
                sidebar.classList.remove('show');
            }
        });

        // Close sidebar on nav link click
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views/layouts/teacher.blade.php ENDPATH**/ ?>