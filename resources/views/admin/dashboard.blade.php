{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack | Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 15px;
            color: #2c3e50;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            padding: 0;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar .logo {
            text-align: center;
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #3498db;
            object-fit: cover;
        }

        .sidebar .logo h5 {
            color: #ffffff;
            margin-top: 12px;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        .sidebar .logo small {
            color: #bdc3c7;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar .nav {
            padding: 20px 0;
        }

        .sidebar .nav-label {
            color: #95a5a6;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 20px;
            letter-spacing: 1.2px;
            margin-top: 15px;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            border-radius: 0;
            margin: 2px 0;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 14px;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar .nav-link:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border-left-color: #3498db;
            padding-left: 22px;
        }

        .sidebar .nav-link.active {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border-left-color: #3498db;
            padding-left: 22px;
            font-weight: 600;
        }

        .main-content {
            margin-left: 280px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .topbar {
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 0;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: -0.5px;
        }

        .user-dropdown .btn {
            background: #3498db;
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .user-dropdown .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }

        .page-content {
            padding: 30px;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3498db, #2980b9);
        }

        .stat-card:nth-child(2)::before {
            background: linear-gradient(90deg, #27ae60, #229954);
        }

        .stat-card:nth-child(3)::before {
            background: linear-gradient(90deg, #f39c12, #e67e22);
        }

        .stat-card:nth-child(4)::before {
            background: linear-gradient(90deg, #e74c3c, #c0392b);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-body {
            padding: 24px;
        }

        .stat-card .icon {
            font-size: 32px;
            margin-bottom: 12px;
            display: block;
            height: 50px;
            width: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card:nth-child(1) .icon {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .stat-card:nth-child(2) .icon {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }

        .stat-card:nth-child(3) .icon {
            background: rgba(243, 156, 18, 0.1);
            color: #f39c12;
        }

        .stat-card:nth-child(4) .icon {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #95a5a6;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 12px;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #50779e;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 0;
            }

            .sidebar .logo h5,
            .sidebar .logo small,
            .sidebar .nav-link span {
                display: none;
            }

            .sidebar .nav-link {
                justify-content: center;
                padding: 12px;
                margin: 0;
            }

            .sidebar .nav-link i {
                margin-right: 0;
            }

            .sidebar .nav-label {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .topbar {
                flex-direction: column;
                gap: 12px;
            }

            .page-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo">
                <img src="{{ asset('./images/logo.jpg') }}" alt="EduTrack Logo">
                <h5>EduTrack</h5>
                <small>Administrator</small>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="/admin/dashboard"><i
                            class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>

                <div class="nav-label">Registrar</div>
                <li class="nav-item"><a class="nav-link" href="/admin/students"><i class="fas fa-users"></i>
                        <span>Students</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/teachers"><i class="fas fa-chalkboard-user"></i>
                        <span>Teachers</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/courses"><i class="fas fa-book"></i>
                        <span>Courses</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/departments"><i class="fas fa-building"></i>
                        <span>Departments</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/classes"><i class="fas fa-school"></i>
                        <span>Classes</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/schedule"><i class="fas fa-calendar-alt"></i>
                        <span>Schedule</span></a></li>

                <div class="nav-label">Academic</div>
                <li class="nav-item"><a class="nav-link" href="/admin/subjects"><i class="fas fa-book-open"></i>
                        <span>Subjects</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/attendance"><i class="fas fa-clipboard-list"></i>
                        <span>Attendance</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/grades"><i class="fas fa-star"></i>
                        <span>Grades</span></a></li>

                <div class="nav-label">System</div>
                <li class="nav-item"><a class="nav-link" href="/admin/users"><i class="fas fa-cog"></i>
                        <span>Settings</span></a></li>
                <li class="nav-item"><a class="nav-link" href="/logout"><i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">

            <!-- TOP BAR -->
            <nav class="topbar">
                <span class="topbar-title">Dashboard</span>

                <div class="user-dropdown">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- PAGE CONTENT -->
            <div class="page-content">

                @extends('layouts.admin')


@section('content')


<div class="row mb-4">
<div class="col-md-3">
<div class="card stat-card shadow-sm">
<div class="card-body text-center">
<h6>Total Students</h6>
<h3>{{ $totalStudents }}</h3>
</div>
</div>
</div>


<div class="col-md-3">
<div class="card stat-card shadow-sm">
<div class="card-body text-center">
<h6>Total Teachers</h6>
<h3>{{ $totalTeachers }}</h3>
</div>
</div>
</div>


<div class="col-md-3">
<div class="card stat-card shadow-sm">
<div class="card-body text-center">
<h6>Total Subjects</h6>
<h3>{{ $totalSubjects }}</h3>
</div>
</div>
</div>


<div class="col-md-3">
<div class="card stat-card shadow-sm">
<div class="card-body text-center">
<h6>Total Classes</h6>
<h3>{{ $totalClasses }}</h3>
</div>
</div>
</div>
</div>


<div class="row">
<div class="col-md-6">
<div class="card p-3">
<h5>Student Enrollment Trend</h5>
<canvas id="enrollmentChart"></canvas>
</div>
</div>


<div class="col-md-6">
<div class="card p-3">
<h5>Students per Class</h5>
<canvas id="classChart"></canvas>
</div>
</div>
</div>


@endsection