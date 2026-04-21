<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduTrack | Student Portal</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Modern Student Layout System */
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
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
            --topbar-bg: #ffffff;
            --topbar-border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* Topbar Styles */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--topbar-height);
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            box-shadow: var(--shadow-sm);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 2rem;
        }

        .topbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-spacer {
            flex: 1;
        }

        .topbar-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .topbar-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: var(--transition);
            padding: 0.5rem 1rem;
        }

        .topbar-link:hover {
            color: var(--primary-color);
        }

        .topbar-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .topbar-profile:hover {
            background: var(--light-bg);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content Area */
        .content-wrapper {
            margin-top: var(--topbar-height);
            min-height: calc(100vh - var(--topbar-height));
        }

        .content {
            padding: 2rem 1rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-color: var(--border-color);
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* Tables */
        .table {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .table thead {
            background: var(--light-bg);
            border-color: var(--border-color);
        }

        .table thead th {
            color: var(--text-secondary);
            font-weight: 600;
            border-color: var(--border-color);
            padding: 1rem 0.75rem;
        }

        .table tbody td {
            border-color: var(--border-color);
            padding: 0.75rem;
        }

        .table tbody tr:hover {
            background: var(--light-bg);
        }

        /* Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Alerts */
        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .topbar {
                padding: 0 1rem;
            }

            .content {
                padding: 1rem;
            }

            .topbar-menu {
                gap: 1rem;
            }

            .topbar-link {
                padding: 0.5rem 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <!-- Topbar -->
    <div class="topbar">
        <a href="{{ route('student.dashboard') }}" class="topbar-brand">
            <i class="fas fa-graduation-cap"></i>
            EduTrack
        </a>

        <div class="topbar-spacer"></div>

        <div class="topbar-menu">
            <a href="{{ route('student.dashboard') }}"
                class="topbar-link {{ request()->routeIs('student.dashboard') ? 'text-primary fw-600' : '' }}">
                <i class="fas fa-home me-1"></i> Home
            </a>
            <a href="{{ route('student.attendance') }}"
                class="topbar-link {{ request()->routeIs('student.attendance') ? 'text-primary fw-600' : '' }}">
                <i class="fas fa-calendar-check me-1"></i> Attendance
            </a>
            <a href="{{ route('student.grades') }}"
                class="topbar-link {{ request()->routeIs('student.grades') ? 'text-primary fw-600' : '' }}">
                <i class="fas fa-book me-1"></i> Grades
            </a>

            <div class="dropdown">
                <button class="btn btn-link topbar-profile dropdown-toggle p-0" type="button" id="profileDropdown"
                    data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                    </div>
                    <span class="d-none d-sm-inline">{{ auth()->user()->first_name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="{{ route('student.profile') }}">
                            <i class="fas fa-user me-2"></i> Profile
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('student.signature.form') }}">
                            <i class="fas fa-pen-fancy me-2"></i> E-Signature
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a></li>
                </ul>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
