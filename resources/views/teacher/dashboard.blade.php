@extends('layouts.teacher')

@section('content')
<style>
/* ── Base ─────────────────────────────────────────── */
:root {
    --primary: #4f46e5;
    --primary-light: #eef2ff;
    --success: #16a34a;
    --warning: #d97706;
    --danger: #dc2626;
    --info: #0891b2;
    --surface: #ffffff;
    --bg: #f1f5f9;
    --border: #e2e8f0;
    --text: #1e293b;
    --muted: #64748b;
    --radius: 14px;
    --shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
    --shadow-hover: 0 4px 12px rgba(0,0,0,.12), 0 8px 24px rgba(0,0,0,.08);
}
body { background: var(--bg); }

/* ── Hero Header ──────────────────────────────────── */
.dash-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius);
    padding: 1.75rem;
    color: #fff;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 24px rgba(79,70,229,.25);
}
.dash-hero .greeting { font-size: clamp(1.1rem, 3vw, 1.5rem); font-weight: 700; }
.dash-hero .sub { font-size: .85rem; opacity: .85; }
.hero-avatar {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.campus-pill {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(255,255,255,.15);
    border-radius: 20px; padding: .25rem .75rem;
    font-size: .78rem; margin-top: .5rem;
}
.status-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #4ade80; flex-shrink: 0;
}
.status-dot.pending { background: #fbbf24; }
.status-dot.rejected { background: #f87171; }

/* ── Stat Cards ───────────────────────────────────── */
.stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: .75rem; margin-bottom: 1.5rem; }
@media (min-width: 768px) { .stat-grid { grid-template-columns: repeat(4, 1fr); } }

.stat-card {
    background: var(--surface);
    border-radius: var(--radius);
    padding: 1.1rem 1rem;
    box-shadow: var(--shadow);
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid var(--border);
    text-decoration: none; color: inherit;
    display: block;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); color: inherit; }
.stat-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: .6rem; }
.stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
.stat-label { font-size: .75rem; color: var(--muted); margin-top: .2rem; }
.stat-bar { height: 3px; border-radius: 2px; background: var(--border); margin-top: .75rem; overflow: hidden; }
.stat-bar-fill { height: 100%; border-radius: 2px; transition: width .6s ease; }

/* ── Section Cards ────────────────────────────────── */
.dash-card {
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.dash-card-header {
    padding: .9rem 1.1rem;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: #fafafa;
}
.dash-card-header h6 { margin: 0; font-weight: 700; font-size: .9rem; color: var(--text); }
.dash-card-body { padding: 1.1rem; }

/* ── Class Cards ──────────────────────────────────── */
.class-grid { display: grid; grid-template-columns: 1fr; gap: .75rem; }
@media (min-width: 576px) { .class-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 992px) { .class-grid { grid-template-columns: repeat(3, 1fr); } }

.class-card {
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1rem;
    cursor: pointer;
    transition: all .2s;
    background: var(--surface);
    position: relative;
    overflow: hidden;
}
.class-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--primary), #7c3aed);
}
.class-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.class-name { font-weight: 700; font-size: .9rem; color: var(--text); margin-bottom: .4rem; }
.class-meta { font-size: .75rem; color: var(--muted); display: flex; align-items: center; gap: .3rem; margin-bottom: .25rem; }
.class-actions { display: flex; gap: .4rem; margin-top: .75rem; }
.class-actions .btn { flex: 1; font-size: .75rem; padding: .3rem .5rem; border-radius: 8px; }

/* ── Quick Actions ────────────────────────────────── */
.qa-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
@media (max-width: 480px) { .qa-grid { grid-template-columns: repeat(3, 1fr); } }

.qa-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: .9rem .6rem;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none; color: inherit;
    display: block;
}
.qa-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); color: inherit; }
.qa-card.disabled { opacity: .45; pointer-events: none; }
.qa-icon { font-size: 1.4rem; margin-bottom: .4rem; }
.qa-label { font-size: .72rem; font-weight: 600; color: var(--text); line-height: 1.2; }
.qa-badge { font-size: .65rem; margin-top: .3rem; }

/* ── KSA Progress ─────────────────────────────────── */
.ksa-item { margin-bottom: 1rem; }
.ksa-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .35rem; }
.ksa-label { font-size: .82rem; font-weight: 600; }
.ksa-bar { height: 7px; border-radius: 4px; background: var(--border); overflow: hidden; }
.ksa-fill { height: 100%; border-radius: 4px; transition: width .8s ease; }
.ksa-sub { font-size: .7rem; color: var(--muted); margin-top: .2rem; }

/* ── Activity Feed ────────────────────────────────── */
.activity-item { display: flex; gap: .75rem; padding: .75rem 0; border-bottom: 1px solid var(--border); }
.activity-item:last-child { border-bottom: none; }
.activity-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; flex-shrink: 0; }
.activity-body { flex: 1; min-width: 0; }
.activity-title { font-size: .82rem; font-weight: 600; color: var(--text); }
.activity-desc { font-size: .75rem; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-time { font-size: .68rem; color: var(--muted); margin-top: .15rem; }

/* ── Pending Tasks ────────────────────────────────── */
.task-item { display: flex; align-items: center; gap: .75rem; padding: .7rem; border-radius: 10px; background: #fff8f0; border: 1px solid #fed7aa; margin-bottom: .5rem; }
.task-item.high { background: #fff1f2; border-color: #fecaca; }
.task-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--warning); flex-shrink: 0; }
.task-dot.high { background: var(--danger); }
.task-body { flex: 1; min-width: 0; }
.task-title { font-size: .8rem; font-weight: 600; }
.task-desc { font-size: .72rem; color: var(--muted); }

/* ── Campus Banner ────────────────────────────────── */
.campus-banner {
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 1rem;
}
.campus-banner.approved { background: #f0fdf4; border: 1px solid #bbf7d0; }
.campus-banner.pending  { background: #fffbeb; border: 1px solid #fde68a; }
.campus-banner.rejected { background: #fff1f2; border: 1px solid #fecaca; }
.campus-banner.independent { background: #f8fafc; border: 1px solid var(--border); }
.campus-banner-icon { font-size: 1.5rem; flex-shrink: 0; }
.campus-banner-text .title { font-weight: 700; font-size: .9rem; }
.campus-banner-text .desc { font-size: .75rem; color: var(--muted); }

/* ── Two-col layout ───────────────────────────────── */
.two-col { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
@media (min-width: 992px) { .two-col { grid-template-columns: 1fr 1fr; } }
.three-col { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
@media (min-width: 768px) { .three-col { grid-template-columns: repeat(3, 1fr); } }

/* ── Empty States ─────────────────────────────────── */
.empty-state { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }
.empty-state i { font-size: 2.5rem; margin-bottom: .75rem; opacity: .4; }
.empty-state p { font-size: .85rem; margin: 0; }

/* ── Responsive tweaks ────────────────────────────── */
@media (max-width: 575px) {
    .dash-hero { padding: 1.25rem; }
    .dash-card-body { padding: .85rem; }
    .stat-value { font-size: 1.5rem; }
    .hide-xs { display: none !important; }
}
</style>

{{-- ── HERO HEADER ──────────────────────────────────────── --}}
<div class="dash-hero">
    <div class="d-flex align-items-center gap-3">
        <div class="hero-avatar">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="flex-grow-1 min-w-0">
            <div class="greeting">Welcome back, {{ auth()->user()->name }}! 👋</div>
            <div class="sub">{{ now()->format('l, F j, Y') }}</div>
            @if(isset($campusInfo))
                <div class="campus-pill">
                    <span class="status-dot {{ $campusInfo['status'] !== 'approved' ? $campusInfo['status'] : '' }}"></span>
                    {{ $campusInfo['name'] }}
                    <span class="opacity-75">· {{ ucfirst($campusInfo['status']) }}</span>
                </div>
            @endif
        </div>
        {{-- Quick Grade button (desktop) --}}
        @if($isApproved && $myClasses && $myClasses->count() > 0)
            <div class="d-none d-md-block flex-shrink-0">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle fw-semibold" data-bs-toggle="dropdown">
                        <i class="fas fa-bolt me-1"></i>Quick Grade
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        @foreach($myClasses->take(5) as $class)
                            <li><span class="dropdown-item-text small text-muted fw-semibold">{{ $class->class_name }}</span></li>
                            <li><a class="dropdown-item small" href="{{ route('teacher.grades.content', $class->id) }}?term=midterm">
                                <i class="fas fa-star me-2 text-warning"></i>Midterm
                            </a></li>
                            <li><a class="dropdown-item small" href="{{ route('teacher.grades.content', $class->id) }}?term=final">
                                <i class="fas fa-trophy me-2 text-success"></i>Finals
                            </a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ── CAMPUS BANNER ────────────────────────────────────── --}}
@if(isset($campusInfo))
    <div class="campus-banner {{ $campusInfo['status'] }}">
        <div class="campus-banner-icon">
            <i class="{{ $campusInfo['icon'] }} text-{{ $campusInfo['color'] }}"></i>
        </div>
        <div class="campus-banner-text flex-grow-1">
            <div class="title">{{ $campusInfo['name'] }}</div>
            <div class="desc">{{ $campusInfo['description'] }}</div>
        </div>
        @if(isset($performanceMetrics))
            <div class="d-none d-sm-flex gap-3 flex-shrink-0">
                <div class="text-center">
                    <div class="fw-bold" style="font-size:1.1rem">{{ $performanceMetrics['current']['grades'] }}</div>
                    <div style="font-size:.68rem;color:var(--muted)">Grades/mo</div>
                </div>
                <div class="text-center">
                    <div class="fw-bold" style="font-size:1.1rem">{{ $performanceMetrics['current']['attendance'] }}</div>
                    <div style="font-size:.68rem;color:var(--muted)">Attendance</div>
                </div>
            </div>
        @endif
    </div>
@endif

{{-- ── STAT CARDS ───────────────────────────────────────── --}}
<div class="stat-grid">
    <a class="stat-card" href="{{ route('teacher.classes') }}">
        <div class="stat-icon" style="background:#eef2ff">
            <i class="fas fa-chalkboard-teacher" style="color:var(--primary)"></i>
        </div>
        <div class="stat-value" style="color:var(--primary)">{{ $statistics['totalClasses'] ?? 0 }}</div>
        <div class="stat-label">My Classes</div>
        <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ min(($statistics['totalClasses'] ?? 0)*10,100) }}%;background:var(--primary)"></div></div>
    </a>
    <a class="stat-card" href="{{ route('teacher.classes') }}">
        <div class="stat-icon" style="background:#f0fdf4">
            <i class="fas fa-users" style="color:var(--success)"></i>
        </div>
        <div class="stat-value" style="color:var(--success)">{{ $statistics['totalStudents'] ?? 0 }}</div>
        <div class="stat-label">Students</div>
        <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ min(($statistics['totalStudents'] ?? 0)*2,100) }}%;background:var(--success)"></div></div>
    </a>
    <a class="stat-card" href="{{ route('teacher.grades') }}">
        <div class="stat-icon" style="background:#fffbeb">
            <i class="fas fa-star" style="color:var(--warning)"></i>
        </div>
        <div class="stat-value" style="color:var(--warning)">{{ $statistics['gradesPosted'] ?? 0 }}</div>
        <div class="stat-label">Grades Posted</div>
        <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ min(($statistics['gradesPosted'] ?? 0),100) }}%;background:var(--warning)"></div></div>
    </a>
    <a class="stat-card" href="{{ route('teacher.grades') }}">
        <div class="stat-icon" style="background:#fff1f2">
            <i class="fas fa-clock" style="color:var(--danger)"></i>
        </div>
        <div class="stat-value" style="color:var(--danger)">{{ $statistics['pendingGrades'] ?? 0 }}</div>
        <div class="stat-label">Pending</div>
        <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ min(($statistics['pendingGrades'] ?? 0)*10,100) }}%;background:var(--danger)"></div></div>
    </a>
</div>

{{-- ── QUICK ACTIONS (mobile-first row) ────────────────── --}}
<div class="dash-card">
    <div class="dash-card-header">
        <h6><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h6>
    </div>
    <div class="dash-card-body">
        <div class="qa-grid">
            <a class="qa-card {{ !$isApproved ? 'disabled' : '' }}" href="{{ $isApproved ? route('teacher.subjects') : '#' }}">
                <div class="qa-icon"><i class="fas fa-book" style="color:var(--primary)"></i></div>
                <div class="qa-label">Subjects</div>
                @if($isApproved && isset($assignedSubjects))
                    <div class="qa-badge"><span class="badge" style="background:var(--primary-light);color:var(--primary)">{{ $assignedSubjects->count() }}</span></div>
                @endif
            </a>
            <a class="qa-card {{ !$isApproved ? 'disabled' : '' }}" href="{{ $isApproved ? route('teacher.grades') : '#' }}">
                <div class="qa-icon"><i class="fas fa-chart-line" style="color:var(--success)"></i></div>
                <div class="qa-label">Grades</div>
                @if($isApproved && isset($statistics))
                    <div class="qa-badge"><span class="badge" style="background:#f0fdf4;color:var(--success)">{{ $statistics['averages']['final_grade'] }}%</span></div>
                @endif
            </a>
            <a class="qa-card {{ !$isApproved ? 'disabled' : '' }}" href="{{ $isApproved ? route('teacher.attendance') : '#' }}">
                <div class="qa-icon"><i class="fas fa-calendar-check" style="color:var(--info)"></i></div>
                <div class="qa-label">Attendance</div>
                @if($isApproved && isset($statistics))
                    <div class="qa-badge"><span class="badge" style="background:#ecfeff;color:var(--info)">{{ $statistics['attendanceRecords'] }}</span></div>
                @endif
            </a>
            <a class="qa-card {{ !$isApproved ? 'disabled' : '' }}" href="{{ $isApproved ? route('teacher.classes.create') : '#' }}">
                <div class="qa-icon"><i class="fas fa-plus-circle" style="color:#7c3aed"></i></div>
                <div class="qa-label">New Class</div>
            </a>
            <a class="qa-card" href="{{ route('teacher.profile.show') }}">
                <div class="qa-icon"><i class="fas fa-user-circle" style="color:var(--muted)"></i></div>
                <div class="qa-label">Profile</div>
            </a>
            <a class="qa-card" href="{{ route('teacher.settings.index') }}">
                <div class="qa-icon"><i class="fas fa-cog" style="color:var(--muted)"></i></div>
                <div class="qa-label">Settings</div>
            </a>
        </div>
    </div>
</div>

{{-- ── MY CLASSES ───────────────────────────────────────── --}}
<div class="dash-card">
    <div class="dash-card-header">
        <h6>
            <i class="fas fa-chalkboard-teacher me-2" style="color:var(--primary)"></i>My Classes
            @if(isset($campusInfo) && $campusInfo['type'] === 'campus')
                <span class="badge ms-1" style="background:var(--primary-light);color:var(--primary);font-size:.65rem">{{ $campusInfo['short_name'] }}</span>
            @endif
        </h6>
        @if($isApproved)
            <a href="{{ route('teacher.classes.create') }}" class="btn btn-sm btn-primary" style="border-radius:8px;font-size:.78rem">
                <i class="fas fa-plus me-1"></i>Add Class
            </a>
        @endif
    </div>
    <div class="dash-card-body">
        @if($isApproved && $myClasses && $myClasses->count() > 0)
            <div class="class-grid">
                @foreach($myClasses as $class)
                    <div class="class-card" onclick="window.location.href='{{ route('teacher.classes.show', $class->id) }}'">
                        <div class="class-name">{{ $class->class_name }}</div>
                        @if($class->subject)
                            <div class="class-meta"><i class="fas fa-book"></i>{{ Str::limit($class->subject->subject_name, 30) }}</div>
                        @endif
                        @if($class->course)
                            <div class="class-meta"><i class="fas fa-graduation-cap"></i>{{ Str::limit($class->course->program_name, 30) }}</div>
                        @endif
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="badge" style="background:var(--primary-light);color:var(--primary);font-size:.7rem">
                                <i class="fas fa-users me-1"></i>{{ $class->student_count ?? 0 }} students
                            </span>
                            <span style="font-size:.7rem;color:var(--muted)">{{ $class->academic_year ?? 'Current' }}</span>
                        </div>
                        <div class="class-actions">
                            <button class="btn btn-outline-success btn-sm"
                                onclick="event.stopPropagation();window.location.href='{{ route('teacher.grades.content', $class->id) }}'"
                                title="Grade Entry">
                                <i class="fas fa-star me-1"></i><span class="hide-xs">Grades</span>
                            </button>
                            <button class="btn btn-outline-info btn-sm"
                                onclick="event.stopPropagation();window.location.href='{{ route('teacher.attendance.manage', $class->id) }}'"
                                title="Attendance">
                                <i class="fas fa-calendar-check me-1"></i><span class="hide-xs">Attend.</span>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm"
                                onclick="event.stopPropagation();window.location.href='{{ route('teacher.classes.edit', $class->id) }}'"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($myClasses->count() > 6)
                <div class="text-center mt-3">
                    <a href="{{ route('teacher.classes') }}" class="btn btn-outline-primary btn-sm">View All Classes</a>
                </div>
            @endif
        @elseif(!$isApproved)
            <div class="empty-state">
                <i class="fas fa-lock" style="color:var(--warning)"></i>
                <p class="fw-semibold mt-2">Campus Approval Required</p>
                <p>Status: <strong>{{ ucfirst(auth()->user()->campus_status ?? 'pending') }}</strong> — Contact your campus admin.</p>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher"></i>
                <p class="fw-semibold mt-2">No Classes Yet</p>
                <p>Create your first class to start managing students and grades.</p>
                <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-plus me-1"></i>Create First Class
                </a>
            </div>
        @endif
    </div>
</div>

{{-- ── KSA + ACTIVITIES (two-col on desktop) ───────────── --}}
<div class="two-col">

    {{-- KSA Overview --}}
    <div class="dash-card" style="margin-bottom:0">
        <div class="dash-card-header">
            <h6><i class="fas fa-star me-2 text-warning"></i>KSA Grading Overview</h6>
            @if($isApproved && $myClasses && $myClasses->count() > 0)
                <a href="{{ route('teacher.grades') }}" class="btn btn-sm btn-outline-primary" style="font-size:.75rem;border-radius:8px">Analytics</a>
            @endif
        </div>
        <div class="dash-card-body">
            <div class="ksa-item">
                <div class="ksa-header">
                    <span class="ksa-label" style="color:var(--primary)"><i class="fas fa-brain me-1"></i>Knowledge (40%)</span>
                    <span class="badge" style="background:var(--primary-light);color:var(--primary)">{{ $statistics['averages']['knowledge'] ?? 0 }}%</span>
                </div>
                <div class="ksa-bar"><div class="ksa-fill" style="width:{{ $statistics['averages']['knowledge'] ?? 0 }}%;background:var(--primary)"></div></div>
                <div class="ksa-sub">Quizzes 40% · Exams 60%</div>
            </div>
            <div class="ksa-item">
                <div class="ksa-header">
                    <span class="ksa-label" style="color:var(--success)"><i class="fas fa-tools me-1"></i>Skills (50%)</span>
                    <span class="badge" style="background:#f0fdf4;color:var(--success)">{{ $statistics['averages']['skills'] ?? 0 }}%</span>
                </div>
                <div class="ksa-bar"><div class="ksa-fill" style="width:{{ $statistics['averages']['skills'] ?? 0 }}%;background:var(--success)"></div></div>
                <div class="ksa-sub">Output · Class Part · Activities · Assignments</div>
            </div>
            <div class="ksa-item">
                <div class="ksa-header">
                    <span class="ksa-label" style="color:var(--info)"><i class="fas fa-handshake me-1"></i>Attitude (10%)</span>
                    <span class="badge" style="background:#ecfeff;color:var(--info)">{{ $statistics['averages']['attitude'] ?? 0 }}%</span>
                </div>
                <div class="ksa-bar"><div class="ksa-fill" style="width:{{ $statistics['averages']['attitude'] ?? 0 }}%;background:var(--info)"></div></div>
                <div class="ksa-sub">Behavior 50% · Awareness 50%</div>
            </div>
            <div style="background:var(--primary-light);border-radius:10px;padding:.75rem 1rem;margin-top:.5rem">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:.75rem;color:var(--muted)">Final Grade Formula</div>
                        <code style="font-size:.75rem">(K×0.40) + (S×0.50) + (A×0.10)</code>
                    </div>
                    <div class="text-end">
                        <div style="font-size:1.4rem;font-weight:800;color:var(--primary)">{{ $statistics['averages']['final_grade'] ?? 0 }}%</div>
                        <div style="font-size:.68rem;color:var(--muted)">Overall Avg</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activities --}}
    <div class="dash-card" style="margin-bottom:0">
        <div class="dash-card-header">
            <h6><i class="fas fa-clock me-2 text-muted"></i>Recent Activities</h6>
        </div>
        <div class="dash-card-body" style="padding-top:.5rem;padding-bottom:.5rem">
            @if(isset($recentActivities) && is_array($recentActivities) && count($recentActivities) > 0)
                @foreach(array_slice($recentActivities, 0, 6) as $activity)
                    <div class="activity-item">
                        <div class="activity-dot bg-{{ $activity['color'] }} bg-opacity-15">
                            <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                        </div>
                        <div class="activity-body">
                            <div class="activity-title">{{ $activity['title'] }}</div>
                            <div class="activity-desc">{{ $activity['description'] }}</div>
                            <div class="activity-time"><i class="fas fa-clock me-1"></i>{{ $activity['time']->diffForHumans() }}</div>
                        </div>
                        @if(isset($activity['link']))
                            <a href="{{ $activity['link'] }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;padding:.2rem .5rem;font-size:.7rem;flex-shrink:0">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state" style="padding:1.5rem">
                    <i class="fas fa-history"></i>
                    <p class="mt-2">No recent activities yet</p>
                </div>
            @endif
        </div>
    </div>

</div>


{{-- ── PENDING TASKS ────────────────────────────────────── --}}
@if($isApproved && isset($pendingTasks) && is_array($pendingTasks) && count($pendingTasks) > 0)
    <div class="dash-card" style="margin-top:1.25rem">
        <div class="dash-card-header">
            <h6><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Pending Tasks
                <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">{{ count($pendingTasks) }}</span>
            </h6>
        </div>
        <div class="dash-card-body">
            @foreach(array_slice($pendingTasks, 0, 4) as $task)
                <div class="task-item {{ $task['priority'] === 'high' ? 'high' : '' }}">
                    <div class="task-dot {{ $task['priority'] === 'high' ? 'high' : '' }}"></div>
                    <div class="task-body">
                        <div class="task-title">{{ $task['title'] }}</div>
                        <div class="task-desc">{{ $task['description'] }}</div>
                    </div>
                    @if(isset($task['link']))
                        <a href="{{ $task['link'] }}" class="btn btn-sm btn-outline-warning" style="border-radius:8px;font-size:.72rem;flex-shrink:0">
                            Act <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- ── PROFILE COMPLETION + SECURITY (two-col) ─────────── --}}
@if($isApproved)
    <div class="two-col" style="margin-top:1.25rem">

        {{-- Profile Management --}}
        <div class="dash-card" style="margin-bottom:0">
            <div class="dash-card-header">
                <h6><i class="fas fa-user-cog me-2" style="color:var(--primary)"></i>Profile</h6>
                <a href="{{ route('teacher.profile.edit') }}" class="btn btn-sm btn-outline-primary" style="font-size:.75rem;border-radius:8px">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
            <div class="dash-card-body">
                @if(isset($profileManagement))
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:.82rem;font-weight:600">Profile Completion</span>
                            <span class="badge" style="background:var(--primary-light);color:var(--primary)">{{ $profileManagement['profile_completion']['percentage'] }}%</span>
                        </div>
                        <div class="ksa-bar"><div class="ksa-fill" style="width:{{ $profileManagement['profile_completion']['percentage'] }}%;background:var(--primary)"></div></div>
                        <div style="font-size:.7rem;color:var(--muted);margin-top:.25rem">
                            {{ $profileManagement['profile_completion']['completed_fields'] }}/{{ $profileManagement['profile_completion']['total_fields'] }} fields completed
                        </div>
                    </div>
                    @if(!empty($profileManagement['campus_connections']))
                        @foreach($profileManagement['campus_connections'] as $conn)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2" style="background:#f8fafc;border:1px solid var(--border)">
                                <div>
                                    <div style="font-size:.82rem;font-weight:600">{{ $conn['short_name'] }}</div>
                                    <div style="font-size:.7rem;color:var(--muted)">{{ $conn['role'] }} · {{ $conn['since']->format('M Y') }}</div>
                                </div>
                                <span class="badge bg-{{ $conn['status'] === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($conn['status']) }}</span>
                            </div>
                        @endforeach
                    @endif
                    <div class="d-grid gap-2 mt-2">
                        <a href="{{ route('teacher.profile.change-password') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.78rem">
                            <i class="fas fa-key me-1"></i>Change Password
                        </a>
                        @if(empty(auth()->user()->campus))
                            <button class="btn btn-sm btn-outline-info" style="border-radius:8px;font-size:.78rem" data-bs-toggle="modal" data-bs-target="#campusRequestModal">
                                <i class="fas fa-university me-1"></i>Request Campus Affiliation
                            </button>
                        @else
                            <button class="btn btn-sm btn-outline-warning" style="border-radius:8px;font-size:.78rem" data-bs-toggle="modal" data-bs-target="#campusChangeModal">
                                <i class="fas fa-exchange-alt me-1"></i>Request Campus Change
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Security Policies --}}
        <div class="dash-card" style="margin-bottom:0">
            <div class="dash-card-header">
                <h6><i class="fas fa-shield-alt me-2 text-success"></i>Security & Policies</h6>
            </div>
            <div class="dash-card-body" style="padding-top:.5rem;padding-bottom:.5rem">
                @if(isset($securityPolicies) && count($securityPolicies) > 0)
                    @foreach($securityPolicies as $policy)
                        <div class="activity-item">
                            <div class="activity-dot bg-{{ $policy['type'] }} bg-opacity-15">
                                <i class="{{ $policy['icon'] }} text-{{ $policy['type'] }}"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">{{ $policy['title'] }}</div>
                                <div class="activity-desc">{{ $policy['description'] }}</div>
                            </div>
                            @if($policy['enforced'])
                                <span class="badge bg-success bg-opacity-15 text-success" style="font-size:.65rem;flex-shrink:0">✓</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="empty-state" style="padding:1.5rem">
                        <i class="fas fa-shield-alt"></i>
                        <p class="mt-2">No policies configured</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endif

{{-- ── MODALS ───────────────────────────────────────────── --}}
<div class="modal fade" id="campusRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius)">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-university me-2"></i>Request Campus Affiliation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.request.campus-change') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Campus *</label>
                        <select class="form-select" name="requested_campus" required>
                            <option value="">Select Campus</option>
                            <option>CPSU Main Campus - Kabankalan City</option>
                            <option>CPSU Victorias Campus</option>
                            <option>CPSU Sipalay Campus - Brgy. Gil Montilla</option>
                            <option>CPSU Cauayan Campus</option>
                            <option>CPSU Candoni Campus</option>
                            <option>CPSU Hinoba-an Campus</option>
                            <option>CPSU Ilog Campus</option>
                            <option>CPSU Hinigaran Campus</option>
                            <option>CPSU Moises Padilla Campus</option>
                            <option>CPSU San Carlos Campus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason *</label>
                        <textarea class="form-control" name="reason" rows="3" required placeholder="Why do you want to affiliate with this campus?"></textarea>
                    </div>
                    <div class="alert alert-info py-2 mb-0" style="font-size:.8rem">
                        <i class="fas fa-info-circle me-1"></i>Your request will be reviewed by the campus administrator.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="campusChangeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--radius)">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-exchange-alt me-2"></i>Request Campus Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.request.campus-change') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning py-2 mb-3" style="font-size:.8rem">
                        <strong>Current:</strong> {{ auth()->user()->campus ?? 'None' }}
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Campus *</label>
                        <select class="form-select" name="requested_campus" required>
                            <option value="">Select Campus</option>
                            <option>CPSU Main Campus - Kabankalan City</option>
                            <option>CPSU Victorias Campus</option>
                            <option>CPSU Sipalay Campus - Brgy. Gil Montilla</option>
                            <option>CPSU Cauayan Campus</option>
                            <option>CPSU Candoni Campus</option>
                            <option>CPSU Hinoba-an Campus</option>
                            <option>CPSU Ilog Campus</option>
                            <option>CPSU Hinigaran Campus</option>
                            <option>CPSU Moises Padilla Campus</option>
                            <option>CPSU San Carlos Campus</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason *</label>
                        <textarea class="form-control" name="reason" rows="3" required placeholder="Why do you want to change campus?"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
