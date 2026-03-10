{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>EduTrack | Admin Panel</title>

    <!-- Local Bootstrap (offline safe) -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons (download locally later if needed) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            background-color: #f4f8fb;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e9f0;
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .logo {
            padding: 15px 0;
        }

        .sidebar.collapsed .logo h6,
        .sidebar.collapsed .logo small,
        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px;
            margin: 4px 0;
        }

        .sidebar.collapsed .section-title {
            display: none;
        }

        .sidebar .logo {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e5e9f0;
            transition: all 0.3s ease;
        }

        .sidebar .logo img {
            width: 60px;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed .logo img {
            width: 45px;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 10px;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .sidebar .nav-link i {
            min-width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar .nav-link:hover {
            background: #eaf6ff;
            color: #198754;
            transform: translateX(5px);
        }

        .sidebar.collapsed .nav-link:hover {
            transform: none;
        }

        .sidebar .nav-link.active {
            background: #198754;
            color: #fff;
        }

        .sidebar .section-title {
            font-size: 11px;
            color: #6c757d;
            padding: 15px 25px 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Main container offset */
        .main-container {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-container.sidebar-collapsed {
            margin-left: 80px;
        }

        /* Topbar */
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e9f0;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 999;
            padding: 0 15px;
        }

        .topbar .navbar {
            padding: 0;
        }

        /* Cards */
        .stat-card {
            border: 0;
            border-radius: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-card h3 {
            font-weight: 700;
            font-size: clamp(1.5rem, 5vw, 2rem);
        }

        .stat-card h6 {
            font-size: clamp(0.75rem, 2vw, 0.9rem);
        }

        /* Main area */
        .content-wrapper {
            min-height: calc(100vh - 60px);
            padding: clamp(15px, 4vw, 30px);
        }

        footer {
            border-top: 1px solid #e5e9f0;
            padding: 20px 15px;
            text-align: center;
            color: #888;
            font-size: clamp(12px, 2vw, 14px);
        }

        /* Action buttons styling */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-action {
            padding: 0.45rem 0.85rem;
            font-size: 13px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            white-space: nowrap;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-action i {
            font-size: 14px;
        }

        .btn-edit {
            background-color: #0dcaf0;
            color: white;
        }

        .btn-edit:hover {
            background-color: #0aa2c0;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            color: white;
            transform: translateY(-2px);
        }

        .btn-view {
            background-color: #0d6efd;
            color: white;
        }

        .btn-view:hover {
            background-color: #0b5ed7;
            color: white;
            transform: translateY(-2px);
        }

        /* Pagination Styling */
        .pagination {
            gap: 4px;
        }

        .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 13px;
            min-width: 40px;
            text-align: center;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 12px;
                min-width: 36px;
            }
        }

        @media (max-width: 480px) {
            .page-link {
                padding: 0.35rem 0.5rem;
                font-size: 11px;
                min-width: 32px;
            }
        }

        /* Page header */
        .page-header {
            margin-bottom: clamp(20px, 4vw, 30px);
            padding-bottom: clamp(15px, 3vw, 20px);
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-weight: 600;
            color: #333;
            font-size: clamp(1.5rem, 5vw, 2.2rem);
            margin: 0;
        }

        /* Mobile responsiveness */
        @media (max-width: 1200px) {
            .main-container {
                margin-left: 80px;
            }

            .sidebar {
                width: 80px;
            }

            .sidebar .logo,
            .sidebar .nav-link span,
            .sidebar .section-title {
                display: none;
            }

            .sidebar .nav-link {
                justify-content: center;
                padding: 12px;
                margin: 4px 0;
            }

            .sidebar .logo {
                display: block;
                padding: 15px 0;
            }

            .sidebar .logo img {
                width: 45px;
            }
        }

        @media (max-width: 992px) {
            .main-container {
                margin-left: 0;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                top: 0;
                left: -100%;
                z-index: 2000;
                min-height: auto;
                max-height: 80vh;
                border-right: none;
                border-bottom: 1px solid #e5e9f0;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.show .logo,
            .sidebar.show .nav-link span,
            .sidebar.show .section-title {
                display: block;
            }

            .sidebar.show .nav-link {
                justify-content: flex-start;
                padding: 12px 20px;
            }

            .sidebar.show .logo {
                padding: 20px;
            }

            .sidebar.show .logo img {
                width: 60px;
            }

            .topbar {
                margin-top: 60px;
                height: auto;
                padding: 10px 15px !important;
            }

            .topbar .navbar {
                flex-wrap: wrap;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .sidebar .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }

            .page-header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }

            .action-buttons {
                gap: 6px;
            }

            .btn-action {
                padding: 0.4rem 0.7rem;
                font-size: 12px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                top: 0;
                left: -100%;
                z-index: 2000;
                min-height: auto;
                max-height: 80vh;
                border-right: none;
                border-bottom: 1px solid #e5e9f0;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar.collapsed {
                width: 100%;
            }

            .main-container {
                margin-left: 0;
            }

            .main-container.sidebar-collapsed {
                margin-left: 0;
            }

            .topbar {
                margin-top: 60px;
                height: auto;
                padding: 10px !important;
            }

            .topbar .d-flex {
                flex-wrap: wrap;
            }

            .stat-card {
                margin-bottom: 10px;
            }

            .sidebar .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }

            .sidebar .logo {
                padding: 12px;
            }

            .sidebar .logo img {
                width: 45px;
            }

            .sidebar .logo h6 {
                font-size: 13px !important;
            }

            .action-buttons {
                gap: 6px;
                flex-wrap: wrap;
            }

            .btn-action {
                padding: 0.4rem 0.65rem;
                font-size: 11px;
                flex: 1 1 48%;
            }

            .page-header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }

            .page-title {
                font-size: 1.3rem;
            }

            /* Adjust table for mobile */
            table {
                font-size: 12px;
            }

            table th {
                padding: 6px 3px !important;
            }

            table td {
                padding: 6px 3px !important;
            }

            .stat-card h3 {
                font-size: clamp(1rem, 4vw, 1.3rem);
            }

            .stat-card h6 {
                font-size: clamp(0.65rem, 1.5vw, 0.8rem);
            }

            .card-body {
                padding: 12px;
            }
        }

        @media (max-width: 480px) {
            .topbar .form-control {
                width: 120px !important;
                font-size: 12px;
                padding: 0.35rem 0.5rem;
            }

            .topbar .btn {
                padding: 0.35rem 0.5rem;
                font-size: 12px;
            }

            .stat-card .card-body {
                padding: 12px !important;
            }

            .stat-card i {
                font-size: 20px !important;
            }

            .stat-card h3 {
                font-size: clamp(0.9rem, 3.5vw, 1.1rem) !important;
            }

            .stat-card h6 {
                font-size: clamp(0.6rem, 1.2vw, 0.75rem) !important;
            }

            .sidebar .nav-link {
                padding: 8px 10px;
                font-size: 11px;
            }

            .sidebar .logo {
                padding: 10px;
            }

            .sidebar .logo img {
                width: 40px;
            }

            .sidebar .logo h6 {
                font-size: 12px !important;
            }

            .page-title {
                font-size: 1.1rem !important;
            }

            .action-buttons {
                gap: 4px;
            }

            .btn-action {
                padding: 0.35rem 0.5rem !important;
                font-size: 10px !important;
                flex: 1 1 100%;
            }

            .btn-action:nth-child(1) {
                flex: 1 1 48%;
            }

            .btn-action:nth-child(2) {
                flex: 1 1 48%;
            }

            .content-wrapper {
                padding: 10px;
            }

            table {
                font-size: 10px;
            }

            table th {
                padding: 4px 2px !important;
            }

            table td {
                padding: 4px 2px !important;
            }

            .card {
                margin-bottom: 10px;
            }

            .card-body {
                padding: 10px;
            }
        }

        .page-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar shadow-sm" id="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="logo">
            <h6 class="text-success fw-bold mt-2">EduTrack</h6>
            <small class="text-muted">Admin Panel</small>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
                </a>
            </li>

            <div class="section-title">Registrar</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}"
                    href="{{ route('admin.students.index') }}"><i class="bi bi-mortarboard me-2"></i>
                    <span>Students</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}"
                    href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>
                    <span>Teachers</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}"
                    href="{{ route('admin.courses.index') }}"><i class="bi bi-award me-2"></i> <span>Courses</span></a>
            </li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}"
                    href="{{ route('admin.classes.index') }}"><i class="bi bi-house-door me-2"></i>
                    <span>Classes</span></a></li>

            <div class="section-title">Academic</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}"
                    href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>
                    <span>Subjects</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}"
                    href="{{ route('admin.attendance.index') }}"><i class="bi bi-check2-square me-2"></i>
                    <span>Attendance</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.grades*') ? 'active' : '' }}"
                    href="{{ route('admin.grades.index') }}"><i class="bi bi-bar-chart-line me-2"></i>
                    <span>Grades</span></a></li>

            <div class="section-title">System</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> <span>User
                        Management</span></a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i
                        class="bi bi-box-arrow-right me-2"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-container" id="mainContainer">

        <!-- TOP BAR -->
        <nav class="navbar topbar px-3 px-md-4 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-success btn-sm me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <span class="fw-bold d-none d-sm-inline">Administrator Dashboard</span>
                <span class="fw-bold d-sm-none">EduTrack</span>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Notifications -->
                <div class="dropdown">
                    @php
                        $unreadCount = \App\Models\Notification::unreadCount(auth()->id());
                        $notifications = \App\Models\Notification::where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    <button class="btn btn-light position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-5"></i>
                        @if ($unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end"
                        style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header">Notifications</h6>
                        </li>
                        @forelse($notifications as $notif)
                            <li>
                                <a class="dropdown-item border-bottom py-2" href="{{ $notif->action_url ?? '#' }}">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-{{ $notif->type }} rounded-circle">
                                            <i class="bi bi-{{ $notif->icon ?? 'bell' }}"></i>
                                        </span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 {{ $notif->isRead() ? 'text-muted' : 'fw-bold' }}">
                                                {{ $notif->title }}
                                            </h6>
                                            <small class="text-muted d-block">{{ $notif->message }}</small>
                                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li>
                                <p class="dropdown-item text-center text-muted py-3">No notifications</p>
                            </li>
                        @endforelse
                        @if (count($notifications) > 0)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center small"
                                    href="{{ route('notifications.index') }}">View All</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Profile -->
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle btn-sm" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.show') }}">
                                <i class="fas fa-user me-2"></i> My Profile
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.profile.change-password') }}">
                                <i class="fas fa-key me-2"></i> Change Password
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div class="p-3 p-md-4 content-wrapper">

            <!-- ANALYTICS ROW -->
            <div class="row mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-mortarboard fs-3 text-success"></i>
                            <h6 class="text-muted mt-2 mb-0">Students</h6>
                            <h3 class="text-success mb-0">{{ $totalStudents ?? '0' }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge fs-3 text-primary"></i>
                            <h6 class="text-muted mt-2 mb-0">Teachers</h6>
                            <h3 class="text-primary mb-0">{{ $totalTeachers ?? '0' }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-house-door fs-3 text-warning"></i>
                            <h6 class="text-muted mt-2 mb-0">Classes</h6>
                            <h3 class="text-warning mb-0">{{ $totalClasses ?? '0' }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-book fs-3 text-danger"></i>
                            <h6 class="text-muted mt-2 mb-0">Subjects</h6>
                            <h3 class="text-danger mb-0">{{ $totalSubjects ?? '0' }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE SLOT -->
            @yield('content')

        </div>

        <!-- FOOTER -->
        <footer>
            © {{ date('Y') }} EduTrack | Academic Management System
        </footer>

    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContainer = document.getElementById('mainContainer');

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContainer.classList.toggle('sidebar-collapsed');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = event.target.closest('.btn-outline-success');

            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggleBtn) {
                sidebar.classList.remove('show');
            }
        });

        // Close sidebar on nav link click
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>
