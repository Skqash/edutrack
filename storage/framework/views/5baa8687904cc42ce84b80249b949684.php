<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>EduTrack | Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <?php $theme = Auth::user()->theme ?? 'light'; ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/themes/' . $theme . '.css')); ?>">

    <style>
        /* Modern Admin Layout System */
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --sidebar-bg: #ffffff;
            --sidebar-text: #64748b;
            --sidebar-hover: #f8fafc;
            --sidebar-border: #e2e8f0;
            --topbar-bg: #ffffff;
            --topbar-border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout Container */
        .app-container {
            display: block;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1050;
            transition: var(--transition);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Header */
        .sidebar-header {
            height: var(--topbar-height);
            background: var(--white);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            font-size: 1.25rem;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            flex-shrink: 0;
            color: var(--primary-color);
        }

        .sidebar-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            margin-right: 0.75rem;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.15);
            padding: 2px;
        }

        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0;
            background: var(--white);
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 2px;
        }

        .nav-item {
            margin-bottom: 0.25rem;
            padding: 0 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            position: relative;
            white-space: nowrap;
            overflow: hidden;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .nav-link i {
            font-size: 1.125rem;
            width: 1.5rem;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem;
            gap: 0;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .badge-sidebar {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Main Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .sidebar-toggle {
            background: transparent;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-btn {
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 1.1rem;
            cursor: pointer;
            position: relative;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
        }

        .action-btn:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: var(--danger-color);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.35rem;
            border-radius: 999px;
            line-height: 1;
            min-width: 18px;
            text-align: center;
        }

        .dropdown-menu-custom {
            min-width: 240px;
            border-radius: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            padding: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
        }

        .dropdown-item-custom:hover {
            background: rgba(0, 0, 0, 0.03);
        }

        .notifications-dropdown {
            width: 320px;
            max-height: 420px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .notification-meta {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .notification-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1rem;
        }

        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .mobile-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Sidebar Mobile */
        .sidebar.mobile-show {
            transform: translateX(0);
        }

        .sidebar {
            transform: translateX(-100%);
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
        }

        .sidebar.mobile-show {
            transform: translateX(0);
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-brand">
                    <img src="/images/cpsu-logo.jpg" alt="CPSU logo" class="sidebar-logo" />
                    <span>EduTrack</span>
                </a>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <?php
                    $pendingRequests = \App\Models\SchoolRequest::where('status', 'pending')->count();
                ?>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.school-requests.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.school-requests*') ? 'active' : ''); ?>">
                        <i class="fas fa-school"></i>
                        <span>School Requests</span>
                        <?php if($pendingRequests > 0): ?>
                            <span class="badge bg-danger badge-sidebar"><?php echo e($pendingRequests); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.teachers.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.teachers*') ? 'active' : ''); ?>">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Teachers</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.students.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.students*') ? 'active' : ''); ?>">
                        <i class="fas fa-user-graduate"></i>
                        <span>Students</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.courses.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.courses*') ? 'active' : ''); ?>">
                        <i class="fas fa-book-open"></i>
                        <span>Courses</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.subjects.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.subjects*') ? 'active' : ''); ?>">
                        <i class="fas fa-book"></i>
                        <span>Subjects</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.classes.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.classes*') ? 'active' : ''); ?>">
                        <i class="fas fa-school"></i>
                        <span>Classes</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.grades.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.grades*') ? 'active' : ''); ?>">
                        <i class="fas fa-star"></i>
                        <span>Grades</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.attendance.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.attendance*') ? 'active' : ''); ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Attendance</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="<?php echo e(route('admin.profile.show')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.profile*') ? 'active' : ''); ?>">
                        <i class="fas fa-user-cog"></i>
                        <span>Profile</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <!-- Sidebar Toggle -->
                    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="topbar-right">
                    <div class="topbar-actions">
                        <!-- Notifications -->
                        <?php
                            $unreadCount = \App\Models\Notification::unreadCount(auth()->id());
                            $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        ?>
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false"
                                aria-label="Notifications">
                                <i class="fas fa-bell"></i>
                                <?php if($unreadCount > 0): ?>
                                    <span
                                        class="notification-badge"><?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?></span>
                                <?php endif; ?>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                                <div class="notification-header">
                                    <span>Notifications</span>
                                    <?php if($unreadCount > 0): ?>
                                        <small class="text-muted"><?php echo e($unreadCount); ?> unread</small>
                                    <?php endif; ?>
                                </div>
                                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e($notification->action_url ?? '#'); ?>" class="notification-item">
                                        <div class="notification-content">
                                            <?php
                                                $bgColor = match ($notification->type ?? 'info') {
                                                    'success' => '16, 185, 129',
                                                    'warning' => '245, 158, 11',
                                                    'danger' => '239, 68, 68',
                                                    default => '79, 70, 229',
                                                };
                                                $textColor = match ($notification->type ?? 'info') {
                                                    'success' => 'var(--success-color)',
                                                    'warning' => 'var(--warning-color)',
                                                    'danger' => 'var(--danger-color)',
                                                    default => 'var(--primary-color)',
                                                };
                                            ?>
                                            <div class="notification-icon"
                                                style="background: rgba(<?php echo e($bgColor); ?>, 0.1); color: <?php echo e($textColor); ?>;">
                                                <i class="fas fa-<?php echo e($notification->icon ?? 'bell'); ?>"></i>
                                            </div>
                                            <div class="notification-text">
                                                <div
                                                    class="notification-title <?php echo e($notification->isRead() ? 'text-muted' : ''); ?>">
                                                    <?php echo e($notification->title); ?>

                                                </div>
                                                <div class="notification-meta">
                                                    <?php echo e($notification->created_at->diffForHumans()); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                        <p class="mb-0">No notifications</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Profile -->
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false"
                                aria-label="Profile menu">
                                <i class="fas fa-user-circle"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                <div class="px-3 py-2 border-bottom">
                                    <div class="fw-semibold"><?php echo e(auth()->user()->name ?? 'Admin'); ?></div>
                                    <small
                                        class="text-muted"><?php echo e(auth()->user()->email ?? 'admin@edutrack.com'); ?></small>
                                </div>
                                <a href="<?php echo e(route('admin.profile.show')); ?>" class="dropdown-item-custom">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>
                                <a href="<?php echo e(route('admin.profile.edit')); ?>" class="dropdown-item-custom">
                                    <i class="fas fa-edit"></i>
                                    Edit Profile
                                </a>
                                <a href="<?php echo e(route('admin.profile.change-password')); ?>" class="dropdown-item-custom">
                                    <i class="fas fa-key"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider-custom"></div>
                                <a href="<?php echo e(route('logout')); ?>" class="dropdown-item-custom text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Layout Management
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileOverlay = document.getElementById('mobileOverlay');

        let isCollapsed = false;
        let isMobile = window.innerWidth <= 768;

        // Toggle Sidebar
        function toggleSidebar() {
            if (isMobile) {
                // Mobile: slide in/out
                sidebar.classList.toggle('mobile-show');
                mobileOverlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('mobile-show') ? 'hidden' : '';
            } else {
                // Desktop: collapse/expand
                isCollapsed = !isCollapsed;
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');

                // Save preference
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }

        // Close Mobile Sidebar
        function closeMobileSidebar() {
            if (isMobile) {
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        // Preserve sidebar state on reload
        window.addEventListener('load', () => {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                isCollapsed = true;
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }

            // Detect responsive behavior
            const resizeHandler = () => {
                isMobile = window.innerWidth <= 768;
                if (!isMobile) {
                    sidebar.classList.remove('mobile-show');
                    mobileOverlay.classList.remove('show');
                }
            };

            window.addEventListener('resize', resizeHandler);
        });
    </script>
</body>

</html>
<?php /**PATH C:\laragon\www\edutrack\resources\views/layouts/admin.blade.php ENDPATH**/ ?>