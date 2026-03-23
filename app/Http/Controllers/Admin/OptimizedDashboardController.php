<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OptimizedDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the admin dashboard with campus-aware statistics
     */
    public function index()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        // Get dashboard statistics with campus filtering
        $stats = $this->dashboardService->getDashboardStats($adminCampus);
        
        // Get pending approvals for this admin's campus
        $pendingApprovals = $this->dashboardService->getPendingApprovals($adminCampus);
        
        // Get recent activities
        $recentActivities = $this->dashboardService->getRecentActivities($adminCampus);
        
        // Get chart data for visualizations
        $chartData = $this->dashboardService->getChartData($adminCampus);

        return view('admin.dashboard.index', compact(
            'stats',
            'pendingApprovals',
            'recentActivities',
            'chartData',
            'adminCampus'
        ));
    }

    /**
     * Get filtered data for dashboard widgets
     */
    public function getFilteredData(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $type = $request->input('type'); // 'grades', 'attendance', 'classes'
        $filters = $request->only(['campus', 'course_id', 'class_id', 'date_range']);
        
        // Apply campus restriction
        if ($adminCampus) {
            $filters['campus'] = $adminCampus;
        }

        $data = $this->dashboardService->getFilteredData($type, $filters);

        return response()->json($data);
    }

    /**
     * Get system health metrics
     */
    public function getSystemHealth()
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;

        $health = $this->dashboardService->getSystemHealth($adminCampus);

        return response()->json($health);
    }

    /**
     * Export dashboard data
     */
    public function exportData(Request $request)
    {
        $admin = Auth::user();
        $adminCampus = $admin->campus;
        
        $type = $request->input('type');
        $format = $request->input('format', 'csv');
        
        return $this->dashboardService->exportData($type, $format, $adminCampus);
    }
}