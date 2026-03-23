@extends('layouts.teacher')

@section('content')
<div style="height: var(--topbar-height);"></div>

<style>
    :root {
        --primary-color: #4f46e5;
        --primary-dark: #4338ca;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .attendance-grade-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .body {
        padding: 2rem;
    }

    .term-selector {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .attendance-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .attendance-table th {
        background: var(--primary-color);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .attendance-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .attendance-table tbody tr:hover {
        background: var(--light-bg);
    }

    .grade-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .grade-excellent { background: var(--success-color); color: white; }
    .grade-good { background: #22c55e; color: white; }
    .grade-fair { background: var(--warning-color); color: white; }
    .grade-poor { background: var(--danger-color); color: white; }

    .sync-button {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sync-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-left: 4px solid;
    }

    .alert-info {
        background: rgba(6, 182, 212, 0.1);
        border-left-color: var(--info-color);
        color: #0c4a6e;
    }
</style>

<div class="container-fluid pt-5 pb-4">
    <div class="attendance-grade-container">
        <!-- Header -->
        <div class="header">
            <div>
                <h2 class="h4 mb-1">📊 Attendance-Grade Integration</h2>
                <p class="mb-0 opacity-75">{{ $class->name }} - {{ ucfirst($term) }} Term</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light btn-sm" onclick="refreshData()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
                <button class="btn btn-warning btn-sm" onclick="syncToGrades()">
                    <i class="fas fa-sync me-1"></i>Sync to Grades
                </button>
            </div>
        </div>

        <div class="body">
            <!-- Term Selector -->
            <div class="term-selector">
                <h5 class="mb-3">Select Term</h5>
                <div class="d-flex gap-3">
                    <button class="btn {{ $term === 'Midterm' ? 'btn-primary' : 'btn-outline-primary' }}" 
                            onclick="switchTerm('Midterm')">
                        <i class="fas fa-calendar-alt me-2"></i>Midterm
                    </button>
                    <button class="btn {{ $term === 'Final' ? 'btn-primary' : 'btn-outline-primary' }}" 
                            onclick="switchTerm('Final')">
                        <i class="fas fa-calendar-check me-2"></i>Final
                    </button>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Attendance-Grade Integration:</strong> This system automatically calculates attendance percentages and converts them to grade scores that can be integrated with your KSA grading system.
            </div>

            <!-- Attendance Grades Table -->
            <div id="attendanceGradesContainer">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Total Days</th>
                            <th>Present</th>
                            <th>Late</th>
                            <th>Attendance %</th>
                            <th>Grade Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceGradesBody">
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sync Action -->
            <div class="text-center mt-4">
                <button class="sync-button" onclick="syncToGrades()">
                    <i class="fas fa-sync me-2"></i>
                    Sync All Attendance to Grades
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTerm = '{{ $term }}';
let classId = {{ $class->id }};

// Load attendance grades on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAttendanceGrades(currentTerm);
});

function switchTerm(term) {
    currentTerm = term;
    loadAttendanceGrades(term);
}

function loadAttendanceGrades(term) {
    fetch(`/teacher/attendance/grades/${classId}/${term}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAttendanceGrades(data.data);
            } else {
                showNotification('Failed to load attendance grades', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading attendance grades', 'error');
        });
}

function displayAttendanceGrades(attendanceGrades) {
    const tbody = document.getElementById('attendanceGradesBody');
    tbody.innerHTML = '';

    attendanceGrades.forEach(record => {
        const row = document.createElement('tr');
        
        const gradeClass = getGradeClass(record.attendance_percentage);
        
        row.innerHTML = `
            <td>${record.student_name}</td>
            <td>${record.total_days}</td>
            <td>${record.present_days}</td>
            <td>${record.late_days}</td>
            <td>${record.attendance_percentage}%</td>
            <td>${record.grade_score}</td>
            <td><span class="grade-badge ${gradeClass}">${getGradeLabel(record.attendance_percentage)}</span></td>
        `;
        
        tbody.appendChild(row);
    });
}

function getGradeClass(percentage) {
    if (percentage >= 90) return 'grade-excellent';
    if (percentage >= 80) return 'grade-good';
    if (percentage >= 70) return 'grade-fair';
    return 'grade-poor';
}

function getGradeLabel(percentage) {
    if (percentage >= 90) return 'Excellent';
    if (percentage >= 80) return 'Good';
    if (percentage >= 70) return 'Fair';
    return 'Poor';
}

function syncToGrades() {
    const attendanceWeight = prompt('Enter attendance weight for grade calculation (0-100):', '10');
    
    if (attendanceWeight === null) return;
    
    if (isNaN(attendanceWeight) || attendanceWeight < 0 || attendanceWeight > 100) {
        showNotification('Please enter a valid weight between 0 and 100', 'error');
        return;
    }

    fetch(`/teacher/attendance/sync-grades/${classId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            term: currentTerm,
            attendance_weight: parseFloat(attendanceWeight)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            loadAttendanceGrades(currentTerm); // Refresh the data
        } else {
            showNotification('Failed to sync attendance to grades', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error syncing attendance to grades', 'error');
    });
}

function refreshData() {
    loadAttendanceGrades(currentTerm);
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

@endsection
