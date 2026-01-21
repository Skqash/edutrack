<?php

namespace App\Http\Controllers\Super;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAdmins = User::where('role', 'admin')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalUsers = User::count();

        $totalCourses = Course::count();
        $activeCourses = Course::where('status', 'Active')->count();
        $inactiveCourses = Course::where('status', 'Inactive')->count();

        $totalSubjects = Subject::count();
        $totalClasses = ClassModel::count();
        $activeClasses = ClassModel::where('status', 'Active')->count();

        $totalCapacity = ClassModel::sum('capacity');
        $totalEnrolled = DB::table('class_students')->count();

        $recentUsers = User::latest()->limit(10)->get();
        $recentCourses = Course::with('instructor')->latest()->limit(5)->get();

        return view('super.dashboard', compact(
            'totalAdmins',
            'totalTeachers',
            'totalStudents',
            'totalUsers',
            'totalCourses',
            'activeCourses',
            'inactiveCourses',
            'totalSubjects',
            'totalClasses',
            'activeClasses',
            'totalCapacity',
            'totalEnrolled',
            'recentUsers',
            'recentCourses'
        ));
    }
}
