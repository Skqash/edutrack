<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduTrack | Teacher Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @php $theme = Auth::user()->theme ?? 'light'; @endphp
    <link rel="stylesheet" href="{{ asset('css/themes/' . $theme . '.css') }}">

    <style>
        /* Modern Teacher Layout System */
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
            --sidebar-bg: #1e293b;
            --sidebar-text: #f1f5f9;
            --sidebar-hover: #334155;
            --sidebar-border: #475569;
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

        /* When the sidebar is collapsed, shrink the effective layout width consistently */
        body.sidebar-collapsed {
            --sidebar-width: var(--sidebar-collapsed-width);
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
            background: var(--white);
            border-right: 1px solid var(--border-color);
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

        /* Main Content Area */
        .main-content {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: margin-left var(--transition), width var(--transition);
        }

        body.sidebar-collapsed .main-content {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 1040;
            box-shadow: var(--shadow-sm);
            transition: left var(--transition);
        }

        body.sidebar-collapsed .topbar {
            left: var(--sidebar-collapsed-width);
        }

        /* Mobile overlaid sidebar */
        .sidebar.mobile-show~.main-content .topbar {
            left: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-toggle {
            width: 40px;
            height: 40px;
            border: none;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.125rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .sidebar-toggle:hover {
            background: linear-gradient(135deg, var(--primary-dark), #3730a3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .sidebar-toggle:active {
            transform: translateY(0);
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        /* Topbar Actions */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border: 1px solid var(--border-color);
            background: var(--white);
            color: var(--text-secondary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--light-bg);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger-color);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.625rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--white);
        }

        /* Dropdowns */
        .dropdown-menu-custom {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            min-width: 200px;
            background: var(--white);
            animation: dropdownSlide 0.2s ease-out;
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item-custom {
            padding: 0.625rem 1rem;
            border-radius: 6px;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .dropdown-item-custom:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .dropdown-divider-custom {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        /* Notifications Dropdown */
        .notifications-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            color: var(--text-primary);
            display: block;
        }

        .notification-item:hover {
            background: var(--light-bg);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-content {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .notification-text {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-meta {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Page Content */
        .page-content {
            flex: 1;
            padding: 1.5rem;
            padding-top: calc(var(--topbar-height) + 3.5rem) !important;
            padding-bottom: 3rem !important;
            min-height: calc(100vh - var(--topbar-height));
            overflow-y: auto;
            overflow-x: hidden;
            background: var(--light-bg);
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Grade Entry Table Container Fix */
        .page-content .container-fluid {
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
            overflow-x: auto;
        }

        .page-content .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-left: 0;
            margin-right: 0;
        }

        /* Ensure tables don't overflow */
        .page-content table {
            max-width: 100%;
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045;
            backdrop-filter: blur(2px);
        }

        .mobile-overlay.show {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 260px;
                --sidebar-collapsed-width: 70px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                left: 0;
                padding: 0 1rem;
            }

            .page-content {
                padding: 1rem;
                padding-top: calc(var(--topbar-height) + 1rem);
            }

            .notifications-dropdown {
                width: min(350px, 90vw);
            }

            .page-title {
                font-size: 1.125rem;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            :root {
                --topbar-height: 60px;
            }

            .topbar {
                padding: 0 0.75rem;
            }

            .page-content {
                padding: 0.75rem;
                padding-top: calc(var(--topbar-height) + 0.75rem);
            }

            .sidebar-header {
                padding: 0 1rem;
            }

            .sidebar-brand {
                font-size: 1.125rem;
            }

            .action-btn {
                width: 36px;
                height: 36px;
            }

            .notification-badge {
                width: 16px;
                height: 16px;
                font-size: 0.5625rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            /* Add dark mode styles here if needed */
        }

        /* Print styles */
        @media print {

            .sidebar,
            .topbar,
            .mobile-overlay {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .page-content {
                padding: 0 !important;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus styles */
        *:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        button:focus,
        a:focus {
            outline-offset: 1px;
        }

        /* High contrast mode */
        @media (prefers-contrast: high) {

            .action-btn,
            .sidebar-toggle {
                border-width: 2px;
            }
        }

        /* Reduced motion */
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
                <a href="{{ route('teacher.dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-graduation-cap"></i>
                    <span>EduTrack</span>
                </a>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('teacher.dashboard') }}"
                        class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('teacher.classes') }}"
                        class="nav-link {{ request()->routeIs('teacher.classes*') ? 'active' : '' }}">
                        <i class="fas fa-door-open"></i>
                        <span>My Classes</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('teacher.subjects') }}"
                        class="nav-link {{ request()->routeIs('teacher.subjects*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>My Subjects</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('teacher.grades') }}"
                        class="nav-link {{ request()->routeIs('teacher.grades*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Grades</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('teacher.attendance') }}"
                        class="nav-link {{ request()->routeIs('teacher.attendance*') ? 'active' : '' }}">
                        <i class="fas fa-check-square"></i>
                        <span>Attendance</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('teacher.settings.index') }}"
                        class="nav-link {{ request()->routeIs('teacher.settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
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

                    <!-- Page Title -->
                    <h1 class="page-title">
                        @if (request()->routeIs('teacher.dashboard'))
                            Dashboard
                        @elseif(request()->routeIs('teacher.classes*'))
                            My Classes
                        @elseif(request()->routeIs('teacher.grades*'))
                            Grades
                        @elseif(request()->routeIs('teacher.attendance*'))
                            Attendance
                        @elseif(request()->routeIs('teacher.subjects*'))
                            My Subjects
                        @elseif(request()->routeIs('teacher.settings*'))
                            Settings
                        @else
                            EduTrack
                        @endif
                    </h1>
                </div>

                <div class="topbar-right">
                    <div class="topbar-actions">
                        <!-- Notifications -->
                        @php
                            $unreadCount = \App\Models\Notification::unreadCount(auth()->id());
                            $notifications = \App\Models\Notification::where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" aria-expanded="false"
                                aria-label="Notifications">
                                <i class="fas fa-bell"></i>
                                @if ($unreadCount > 0)
                                    <span
                                        class="notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                                <div class="notification-header">
                                    <span>Notifications</span>
                                    @if ($unreadCount > 0)
                                        <small class="text-muted">{{ $unreadCount }} unread</small>
                                    @endif
                                </div>
                                @forelse($notifications as $notification)
                                    <a href="{{ $notification->action_url ?? '#' }}" class="notification-item">
                                        <div class="notification-content">
                                            @php
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
                                            @endphp
                                            <div class="notification-icon"
                                                style="background: rgba({{ $bgColor }}, 0.1); color: {{ $textColor }};">
                                                <i class="fas fa-{{ $notification->icon ?? 'bell' }}"></i>
                                            </div>
                                            <div class="notification-text">
                                                <div
                                                    class="notification-title {{ $notification->isRead() ? 'text-muted' : '' }}">
                                                    {{ $notification->title }}
                                                </div>
                                                <div class="notification-meta">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                        <p class="mb-0">No notifications</p>
                                    </div>
                                @endforelse
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
                                    <div class="fw-semibold">{{ auth()->user()->name ?? 'Teacher' }}</div>
                                    <small
                                        class="text-muted">{{ auth()->user()->email ?? 'teacher@edutrack.com' }}</small>
                                </div>
                                <a href="{{ route('teacher.profile.show') }}" class="dropdown-item-custom">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>
                                <a href="{{ route('teacher.profile.edit') }}" class="dropdown-item-custom">
                                    <i class="fas fa-edit"></i>
                                    Edit Profile
                                </a>
                                <a href="{{ route('teacher.profile.change-password') }}" class="dropdown-item-custom">
                                    <i class="fas fa-key"></i>
                                    Change Password
                                </a>
                                <a href="{{ route('teacher.settings.index') }}" class="dropdown-item-custom">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider-custom"></div>
                                <a href="{{ route('logout') }}" class="dropdown-item-custom text-danger">
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
                @yield('content')
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
                document.body.classList.toggle('sidebar-collapsed', isCollapsed);
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

        // Check Mobile Status
        function checkMobileStatus() {
            isMobile = window.innerWidth <= 768;

            if (!isMobile) {
                // Reset mobile states when switching to desktop
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }

        // Initialize Sidebar State
        function initializeSidebar() {
            const savedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            if (savedCollapsed && !isMobile) {
                isCollapsed = true;
                sidebar.classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
                mainContent.classList.add('expanded');
            }
        }

        // Handle Navigation Links (Mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (isMobile) {
                    closeMobileSidebar();
                }
            });
        });

        // Window Resize Handler
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                checkMobileStatus();
            }, 250);
        });

        // Initialize on Load
        document.addEventListener('DOMContentLoaded', () => {
            checkMobileStatus();
            initializeSidebar();
        });

        // Keyboard Navigation
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + B to toggle sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                toggleSidebar();
            }

            // Escape to close mobile sidebar
            if (e.key === 'Escape' && isMobile) {
                closeMobileSidebar();
            }
        });

        // Mark notifications as read when viewed
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                // Add AJAX call to mark as read if needed
                console.log('Notification clicked');
            });
        });

        // Prevent dropdown close on click inside
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
