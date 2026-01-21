<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('head')->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $heads = User::where('role', 'teacher')->get();
        return view('admin.departments.create', compact('heads'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_code' => 'required|unique:departments|max:50',
            'department_name' => 'required|max:100',
            'head_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        Department::create($validated);
        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully');
    }

    public function edit(Department $department)
    {
        $heads = User::where('role', 'teacher')->get();
        return view('admin.departments.edit', compact('department', 'heads'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_code' => 'required|unique:departments,department_code,' . $department->id . '|max:50',
            'department_name' => 'required|max:100',
            'head_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $department->update($validated);
        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully');
    }
}
