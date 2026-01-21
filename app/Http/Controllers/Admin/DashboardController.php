<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalCourses = Course::count();
        $totalSubjects = Subject::count();
        $totalClasses = ClassModel::count();

        return view('admin.index', compact(
            'totalStudents',
            'totalTeachers',
            'totalAdmins',
            'totalCourses',
            'totalSubjects',
            'totalClasses'
        ));
    }
}