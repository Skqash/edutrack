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

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
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
            transition: all 0.3s;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e5e9f0;
        }

        .sidebar .logo img {
            width: 60px;
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
        }

        .sidebar .nav-link:hover {
            background: #eaf6ff;
            color: #198754;
        }

        .sidebar .nav-link.active {
            background: #198754;
            color: #fff;
        }

        .sidebar .section-title {
            font-size: 12px;
            color: #6c757d;
            padding: 10px 25px 4px;
            text-transform: uppercase;
        }

        /* Main container offset */
        .main-container {
            margin-left: 260px;
            transition: margin-left 0.3s;
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
        }

        /* Cards */
        .stat-card {
            border: 0;
            border-radius: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }

        .stat-card h3 {
            font-weight: 700;
        }

        /* Main area */
        .content-wrapper {
            min-height: calc(100vh - 60px);
        }

        footer {
            border-top: 1px solid #e5e9f0;
            padding: 10px;
            text-align: center;
            color: #888;
            font-size: 14px;
        }

        /* Action buttons styling */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action i {
            font-size: 16px;
        }

        .btn-edit {
            background-color: #0dcaf0;
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #0aa2c0;
            color: white;
            transform: scale(1.05);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c82333;
            color: white;
            transform: scale(1.05);
        }

        .btn-view {
            background-color: #0d6efd;
            color: white;
            border: none;
        }

        .btn-view:hover {
            background-color: #0b5ed7;
            color: white;
            transform: scale(1.05);
        }

        /* Mobile responsiveness */
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
                padding: 15px;
            }

            .sidebar .logo img {
                width: 50px;
            }

            .sidebar .logo h6 {
                font-size: 14px !important;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            /* Adjust table for mobile */
            table {
                font-size: 13px;
            }

            table th {
                padding: 8px 4px !important;
            }

            table td {
                padding: 8px 4px !important;
            }
        }

        @media (max-width: 480px) {
            .topbar .form-control {
                width: 150px !important;
            }

            .stat-card .card-body {
                padding: 15px !important;
            }

            .stat-card i {
                font-size: 24px !important;
            }

            .stat-card h3 {
                font-size: 20px !important;
            }

            .sidebar .nav-link {
                padding: 8px 12px;
                font-size: 12px;
            }
        }

        /* Smooth transitions */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-title {
            font-weight: 600;
            color: #333;
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
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            <h6 class="text-success fw-bold mt-2">EduTrack</h6>
            <small class="text-muted">Admin Panel</small>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
                </a>
            </li>

            <div class="section-title">Registrar</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}"><i class="bi bi-mortarboard me-2"></i> <span>Students</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i> <span>Teachers</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}"><i class="bi bi-award me-2"></i> <span>Courses</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}"><i class="bi bi-building me-2"></i> <span>Departments</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}"><i class="bi bi-house-door me-2"></i> <span>Classes</span></a></li>

            <div class="section-title">Academic</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.subjects*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i> <span>Subjects</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.attendance*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}"><i class="bi bi-check2-square me-2"></i> <span>Attendance</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.grades*') ? 'active' : '' }}" href="{{ route('admin.grades.index') }}"><i class="bi bi-bar-chart-line me-2"></i> <span>Grades</span></a></li>

            <div class="section-title">System</div>

            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> <span>User Management</span></a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span></a></li>
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
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
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
                            <li><p class="dropdown-item text-center text-muted py-3">No notifications</p></li>
                        @endforelse
                        @if(count($notifications) > 0)
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">View All</a></li>
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
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
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