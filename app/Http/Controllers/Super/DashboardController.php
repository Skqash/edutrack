<?php

namespace App\Http\Controllers\Super;

use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Main dashboard with system statistics
     */
    public function index()
    {
        try {
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

            $totalCapacity = ClassModel::sum('capacity') ?? 0;
            $totalEnrolled = DB::table('class_students')->count();

            $recentUsers = User::latest()->limit(10)->get();
            $recentCourses = Course::with('instructor')->latest()->limit(5)->get();

            // Database health stats
            $dbStats = $this->getDatabaseStats();

            return view('super.dashboard_optimized', compact(
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
                'recentCourses',
                'dbStats'
            ));
        } catch (\Exception $e) {
            Log::error("Dashboard error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load dashboard: ' . $e->getMessage());
        }
    }

    /**
     * ==================== USER MANAGEMENT ====================
     */

    public function listUsers(Request $request)
    {
        try {
            $query = User::query();
            
            if ($role = $request->query('role')) {
                $query->where('role', $role);
            }
            
            if ($search = $request->query('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            }

            $users = $query->latest()->paginate(30);
            return view('super.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error("List users error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load users');
        }
    }

    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('super.users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error("Show user error: " . $e->getMessage());
            return redirect()->route('super.users.index')->with('error', 'User not found');
        }
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('super.users.create');
        }

        try {
            $data = $request->only(['name', 'email', 'password', 'role', 'phone', 'status']);
            
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,teacher,student',
                'phone' => 'nullable|string|max:20',
                'status' => 'nullable|in:Active,Inactive',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data['password'] = Hash::make($data['password']);
            $data['status'] = $data['status'] ?? 'Active';
            $data['email_verified_at'] = now();

            $user = User::create($data);
            Log::info("New user created by Super Admin: {$user->email}");

            return redirect()->route('super.users.show', $user->id)->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log::error("Create user error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->only(['name', 'email', 'password', 'role', 'phone', 'status']);

            $rules = [
                'name' => 'sometimes|required|string|max:255',
                'email' => "sometimes|required|email|unique:users,email,{$id}",
                'role' => 'sometimes|in:admin,teacher,student',
                'phone' => 'nullable|string|max:20',
                'status' => 'nullable|in:Active,Inactive',
            ];

            if (!empty($data['password'])) {
                $rules['password'] = 'string|min:8';
            } else {
                unset($data['password']);
            }

            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            Log::info("User updated by Super Admin: {$user->email}");

            return redirect()->route('super.users.show', $user->id)->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error("Update user error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $email = $user->email;
            $user->delete();
            Log::warning("User deleted by Super Admin: {$email}");

            return redirect()->route('super.users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error("Delete user error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete user');
        }
    }

    public function toggleRole(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $role = $request->input('role');

            if (!in_array($role, ['admin', 'teacher', 'student'])) {
                return redirect()->back()->with('error', 'Invalid role');
            }

            $oldRole = $user->role;
            $user->update(['role' => $role]);
            Log::info("User role changed by Super Admin: {$user->email} from {$oldRole} to {$role}");

            return redirect()->back()->with('success', 'Role updated successfully');
        } catch (\Exception $e) {
            Log::error("Toggle role error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update role');
        }
    }

    public function exportUsersCSV()
    {
        try {
            $users = User::all(['id', 'name', 'email', 'role', 'phone', 'status', 'created_at']);
            $filename = 'users_' . now()->format('Ymd_His') . '.csv';
            $path = storage_path('app/' . $filename);

            $handle = fopen($path, 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Phone', 'Status', 'Created At']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->phone ?? '',
                    $user->status ?? 'Active',
                    $user->created_at
                ]);
            }

            fclose($handle);
            Log::info("Users exported to CSV by Super Admin: $filename");

            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("CSV export error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export CSV: ' . $e->getMessage());
        }
    }

    public function importUsersCSV(Request $request)
    {
        try {
            $file = $request->file('csv');

            if (!$file || !$file->isValid()) {
                return redirect()->back()->with('error', 'Invalid file upload');
            }

            $path = $file->getRealPath();
            $imported = 0;
            $errors = [];

            if (($handle = fopen($path, 'r')) !== false) {
                $header = fgetcsv($handle);
                $lineNo = 1;

                while (($row = fgetcsv($handle)) !== false) {
                    $lineNo++;
                    try {
                        $data = array_combine($header, $row);

                        if (empty($data['email'])) continue;

                        $existing = User::where('email', $data['email'])->first();
                        $password = Hash::make(Str::random(12));

                        $payload = [
                            'name' => $data['name'] ?? 'Imported User',
                            'email' => $data['email'],
                            'password' => $password,
                            'role' => $data['role'] ?? 'student',
                            'phone' => $data['phone'] ?? null,
                            'status' => $data['status'] ?? 'Active',
                            'email_verified_at' => now(),
                        ];

                        if ($existing) {
                            $existing->update($payload);
                        } else {
                            User::create($payload);
                        }

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Line $lineNo: " . $e->getMessage();
                    }
                }

                fclose($handle);
            }

            Log::info("CSV import completed by Super Admin: $imported users imported");

            if (count($errors) > 0) {
                return redirect()->back()->with('warning', "Imported $imported users. Errors: " . implode('; ', array_slice($errors, 0, 5)));
            }

            return redirect()->back()->with('success', "Successfully imported $imported users");
        } catch (\Exception $e) {
            Log::error("CSV import error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * ==================== COURSE MANAGEMENT ====================
     */

    public function manageCourses(Request $request)
    {
        try {
            $courses = Course::with('instructor')->paginate(20);
            return view('super.courses.index', compact('courses'));
        } catch (\Exception $e) {
            Log::error("Manage courses error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load courses');
        }
    }

    public function createCourse(Request $request)
    {
        if ($request->isMethod('get')) {
            $teachers = User::where('role', 'teacher')->get();
            return view('super.courses.create', compact('teachers'));
        }

        try {
            $data = $request->validate([
                'course_code' => 'required|unique:courses',
                'course_name' => 'required|string',
                'description' => 'nullable|string',
                'instructor_id' => 'nullable|exists:users,id',
                'credit_hours' => 'nullable|integer',
                'status' => 'in:Active,Inactive',
            ]);

            Course::create($data);
            Log::info("Course created by Super Admin: {$data['course_code']}");

            return redirect()->route('super.courses.index')->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            Log::error("Create course error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create course: ' . $e->getMessage())->withInput();
        }
    }

    public function storeCourse(Request $request)
    {
        return $this->createCourse($request);
    }

    public function showCourse($id)
    {
        try {
            $course = Course::with('instructor')->findOrFail($id);
            return view('super.courses.show', compact('course'));
        } catch (\Exception $e) {
            return redirect()->route('super.courses.index')->with('error', 'Course not found');
        }
    }

    public function updateCourse(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);
            $data = $request->validate([
                'course_code' => "required|unique:courses,course_code,{$id}",
                'course_name' => 'required|string',
                'description' => 'nullable|string',
                'instructor_id' => 'nullable|exists:users,id',
                'credit_hours' => 'nullable|integer',
                'status' => 'in:Active,Inactive',
            ]);

            $course->update($data);
            Log::info("Course updated by Super Admin: {$course->course_code}");

            return redirect()->route('super.courses.index')->with('success', 'Course updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update course')->withInput();
        }
    }

    public function deleteCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $code = $course->course_code;
            $course->delete();
            Log::warning("Course deleted by Super Admin: {$code}");

            return redirect()->route('super.courses.index')->with('success', 'Course deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete course');
        }
    }

    public function deleteAllCourses()
    {
        try {
            $count = Course::count();
            Course::truncate();
            Log::warning("All $count courses deleted by Super Admin");

            return redirect()->back()->with('success', "All $count courses deleted");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete courses');
        }
    }

    /**
     * ==================== CLASS MANAGEMENT ====================
     */

    public function manageClasses(Request $request)
    {
        try {
            $classes = ClassModel::with('teacher')->paginate(20);
            return view('super.classes.index', compact('classes'));
        } catch (\Exception $e) {
            Log::error("Manage classes error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load classes');
        }
    }

    public function createClass(Request $request)
    {
        if ($request->isMethod('get')) {
            $teachers = User::where('role', 'teacher')->get();
            return view('super.classes.create', compact('teachers'));
        }

        try {
            $data = $request->validate([
                'class_code' => 'required|unique:classes',
                'class_name' => 'required|string',
                'level' => 'nullable|string',
                'capacity' => 'nullable|integer',
                'teacher_id' => 'nullable|exists:users,id',
                'status' => 'in:Active,Inactive',
            ]);

            ClassModel::create($data);
            Log::info("Class created by Super Admin: {$data['class_code']}");

            return redirect()->route('super.classes.index')->with('success', 'Class created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create class: ' . $e->getMessage())->withInput();
        }
    }

    public function storeClass(Request $request)
    {
        return $this->createClass($request);
    }

    public function showClass($id)
    {
        try {
            $class = ClassModel::with('teacher')->findOrFail($id);
            return view('super.classes.show', compact('class'));
        } catch (\Exception $e) {
            return redirect()->route('super.classes.index')->with('error', 'Class not found');
        }
    }

    public function updateClass(Request $request, $id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $data = $request->validate([
                'class_code' => "required|unique:classes,class_code,{$id}",
                'class_name' => 'required|string',
                'level' => 'nullable|string',
                'capacity' => 'nullable|integer',
                'teacher_id' => 'nullable|exists:users,id',
                'status' => 'in:Active,Inactive',
            ]);

            $class->update($data);
            Log::info("Class updated by Super Admin: {$class->class_code}");

            return redirect()->route('super.classes.index')->with('success', 'Class updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update class')->withInput();
        }
    }

    public function deleteClass($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $code = $class->class_code;
            $class->delete();
            Log::warning("Class deleted by Super Admin: {$code}");

            return redirect()->route('super.classes.index')->with('success', 'Class deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete class');
        }
    }

    public function deleteAllClasses()
    {
        try {
            $count = ClassModel::count();
            ClassModel::truncate();
            Log::warning("All $count classes deleted by Super Admin");

            return redirect()->back()->with('success', "All $count classes deleted");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete classes');
        }
    }

    /**
     * ==================== STUDENT MANAGEMENT ====================
     */

    public function manageStudents(Request $request)
    {
        try {
            $students = Student::with('user', 'class')->paginate(30);
            $classes = ClassModel::all();
            return view('super.students.index', compact('students', 'classes'));
        } catch (\Exception $e) {
            Log::error("Manage students error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load students');
        }
    }

    public function createStudent(Request $request)
    {
        if ($request->isMethod('get')) {
            $classes = ClassModel::all();
            $users = User::where('role', 'student')->get();
            return view('super.students.create', compact('classes', 'users'));
        }

        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'student_id' => 'required|unique:students',
                'class_id' => 'required|exists:classes,id',
                'enrollment_date' => 'required|date',
                'status' => 'in:Active,Inactive',
            ]);

            Student::create($data);
            Log::info("Student created by Super Admin: {$data['student_id']}");

            return redirect()->route('super.students.index')->with('success', 'Student created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
        }
    }

    public function showStudent($id)
    {
        try {
            $student = Student::with('user', 'class')->findOrFail($id);
            return view('super.students.show', compact('student'));
        } catch (\Exception $e) {
            return redirect()->route('super.students.index')->with('error', 'Student not found');
        }
    }

    public function updateStudent(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);
            $data = $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'student_id' => "sometimes|unique:students,student_id,{$id}",
                'class_id' => 'sometimes|exists:classes,id',
                'enrollment_date' => 'sometimes|date',
                'status' => 'in:Active,Inactive',
            ]);

            $student->update($data);
            Log::info("Student updated by Super Admin: {$student->student_id}");

            return redirect()->route('super.students.index')->with('success', 'Student updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update student')->withInput();
        }
    }

    public function deleteStudent($id)
    {
        try {
            $student = Student::findOrFail($id);
            $studentId = $student->student_id;
            $student->delete();
            Log::warning("Student deleted by Super Admin: {$studentId}");

            return redirect()->route('super.students.index')->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete student');
        }
    }

    public function deleteAllStudents()
    {
        try {
            $count = Student::count();
            Student::truncate();
            Log::warning("All $count students deleted by Super Admin");

            return redirect()->back()->with('success', "All $count students deleted");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete students');
        }
    }

    /**
     * ==================== GRADE MANAGEMENT ====================
     */

    public function manageGrades(Request $request)
    {
        try {
            $grades = Grade::with('student.user', 'class', 'subject')->paginate(30);
            $classes = ClassModel::all();
            return view('super.grades.index', compact('grades', 'classes'));
        } catch (\Exception $e) {
            Log::error("Manage grades error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load grades');
        }
    }

    public function showGrade($id)
    {
        try {
            $grade = Grade::with('student.user', 'class', 'subject')->findOrFail($id);
            return view('super.grades.show', compact('grade'));
        } catch (\Exception $e) {
            return redirect()->route('super.grades.index')->with('error', 'Grade not found');
        }
    }

    public function updateGrade(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $data = $request->validate([
                'score' => 'required|numeric|min:0|max:100',
                'grade' => 'required|string',
                'status' => 'in:Pass,Fail',
            ]);

            $grade->update($data);
            Log::info("Grade updated by Super Admin for student: {$grade->student_id}");

            return redirect()->back()->with('success', 'Grade updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update grade')->withInput();
        }
    }

    public function deleteGrade($id)
    {
        try {
            $grade = Grade::findOrFail($id);
            $studentId = $grade->student_id;
            $grade->delete();
            Log::warning("Grade deleted by Super Admin for student: {$studentId}");

            return redirect()->back()->with('success', 'Grade deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete grade');
        }
    }

    public function deleteAllGrades()
    {
        try {
            $count = Grade::count();
            Grade::truncate();
            Log::warning("All $count grades deleted by Super Admin");

            return redirect()->back()->with('success', "All $count grades deleted");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete grades');
        }
    }

    /**
     * ==================== ATTENDANCE MANAGEMENT ====================
     */

    public function manageAttendance(Request $request)
    {
        try {
            $attendance = Attendance::with('student.user', 'class')->paginate(30);
            $classes = ClassModel::all();
            return view('super.attendance.index', compact('attendance', 'classes'));
        } catch (\Exception $e) {
            Log::error("Manage attendance error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load attendance');
        }
    }

    public function showAttendance($id)
    {
        try {
            $record = Attendance::with('student.user', 'class')->findOrFail($id);
            return view('super.attendance.show', compact('record'));
        } catch (\Exception $e) {
            return redirect()->route('super.attendance.index')->with('error', 'Attendance record not found');
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        try {
            $record = Attendance::findOrFail($id);
            $data = $request->validate([
                'date' => 'required|date',
                'status' => 'required|in:present,absent,late',
                'time_in' => 'nullable|date_format:H:i',
                'time_out' => 'nullable|date_format:H:i',
                'remarks' => 'nullable|string',
            ]);

            $record->update($data);
            Log::info("Attendance updated by Super Admin for student: {$record->student_id}");

            return redirect()->back()->with('success', 'Attendance updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update attendance')->withInput();
        }
    }

    public function deleteAttendance($id)
    {
        try {
            $record = Attendance::findOrFail($id);
            $studentId = $record->student_id;
            $record->delete();
            Log::warning("Attendance deleted by Super Admin for student: {$studentId}");

            return redirect()->back()->with('success', 'Attendance record deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete attendance');
        }
    }

    public function deleteAllAttendance()
    {
        try {
            $count = Attendance::count();
            Attendance::truncate();
            Log::warning("All $count attendance records deleted by Super Admin");

            return redirect()->back()->with('success', "All $count attendance records deleted");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete attendance');
        }
    }

    /**
     * ==================== DATABASE OPERATIONS ====================
     */

    public function getDatabaseStats()
    {
        try {
            DB::connection()->getPDO();
            $database = config('database.connections.mysql.database');
            
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);
            
            return [
                'status' => 'Connected',
                'database' => $database,
                'tableCount' => count($tables),
                'tables' => array_map(fn($t) => $t->table_name, $tables),
            ];
        } catch (\Exception $e) {
            Log::error("Database stats error: " . $e->getMessage());
            return [
                'status' => 'Error',
                'database' => 'Unknown',
                'tableCount' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function databaseStats()
    {
        try {
            $stats = $this->getDatabaseStats();
            $connection = [
                'host' => config('database.connections.mysql.host'),
                'database' => config('database.connections.mysql.database'),
                'driver' => 'MySQL',
            ];

            $tables = [];
            if ($stats['status'] === 'Connected') {
                $result = DB::select("
                    SELECT 
                        table_name as name,
                        table_rows as rows,
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) as size,
                        engine,
                        table_collation as collation
                    FROM information_schema.tables
                    WHERE table_schema = ?
                ", [config('database.connections.mysql.database')]);
                $tables = $result;
            }

            $storage = [
                'database_size' => $this->getDatabaseSize(),
                'total_tables' => count($tables),
                'total_rows' => array_sum(array_map(fn($t) => $t->rows, $tables)),
                'connections' => DB::select("SHOW PROCESSLIST")[0]->Id ?? 1,
            ];

            return view('super.tools.database', compact('connection', 'tables', 'storage', 'stats'));
        } catch (\Exception $e) {
            Log::error("Database stats view error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load database statistics');
        }
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select("
                SELECT ROUND(SUM(((data_length + index_length) / 1024 / 1024)), 2) as size
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [config('database.connections.mysql.database')]);
            
            return ($result[0]->size ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    public function runSafeQuery(Request $request)
    {
        try {
            $sql = trim($request->input('sql', ''));

            if (!$sql) {
                return redirect()->back()->with('error', 'SQL query required');
            }

            $lower = strtolower(ltrim($sql));
            if (!Str::startsWith($lower, 'select')) {
                return redirect()->back()->with('error', 'Only SELECT queries allowed for security');
            }

            $results = DB::select($sql);
            Log::info("Safe query executed by Super Admin");
            
            return view('super.tools.query', compact('results', 'sql'));
        } catch (\Exception $e) {
            Log::error("Query error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Query failed: ' . $e->getMessage());
        }
    }

    public function backupDatabase()
    {
        try {
            $conn = config('database.default');
            $cfg = config("database.connections.{$conn}");

            if (($cfg['driver'] ?? '') !== 'mysql') {
                return redirect()->back()->with('error', 'Backup supported only for MySQL');
            }

            $host = $cfg['host'] ?? '127.0.0.1';
            $port = $cfg['port'] ?? 3306;
            $db = $cfg['database'] ?? '';
            $user = $cfg['username'] ?? '';
            $pass = $cfg['password'] ?? '';

            $filename = 'backup_' . $db . '_' . now()->format('Ymd_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            File::ensureDirectoryExists(storage_path('app/backups'));

            $cmd = "mysqldump --host={$host} --port={$port} --user={$user} --password='{$pass}' {$db} > " . escapeshellarg($path);
            exec($cmd, $output, $return);

            if ($return !== 0) {
                Log::error("Database backup failed");
                return redirect()->back()->with('error', 'Backup command failed');
            }

            Log::info("Database backup created by Super Admin: {$filename}");
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("Backup error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * ==================== SYSTEM MANAGEMENT ====================
     */

    public function clearCaches()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Log::info("All caches cleared by Super Admin");

            return redirect()->back()->with('success', 'All caches cleared successfully');
        } catch (\Exception $e) {
            Log::error("Cache clear error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to clear caches: ' . $e->getMessage());
        }
    }

    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            Log::info("Database migrations executed by Super Admin");
            return redirect()->back()->with('success', 'Migrations executed successfully');
        } catch (\Exception $e) {
            Log::error("Migration error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    public function runSeeders()
    {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            Log::info("Database seeders executed by Super Admin");
            return redirect()->back()->with('success', 'Seeders executed successfully');
        } catch (\Exception $e) {
            Log::error("Seeder error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Seeders failed: ' . $e->getMessage());
        }
    }

    /**
     * ==================== SYSTEM MONITORING ====================
     */

    public function viewLogs(Request $request)
    {
        try {
            $lines = max(50, min(1000, (int) $request->query('lines', 200)));
            $logPath = storage_path('logs/laravel.log');

            if (!File::exists($logPath)) {
                return view('super.tools.logs', ['lines' => ['No log file found'], 'lines_count' => 0]);
            }

            $content = File::get($logPath);
            $rows = preg_split('/\r?\n/', trim($content));
            $tail = array_slice($rows, -$lines);
            
            return view('super.tools.logs', ['lines' => $tail, 'lines_count' => count($tail)]);
        } catch (\Exception $e) {
            Log::error("View logs error: " . $e->getMessage());
            return view('super.tools.logs', ['lines' => ['Error reading logs: ' . $e->getMessage()], 'lines_count' => 0]);
        }
    }

    /**
     * ==================== DATABASE CLEANUP ====================
     */

    public function databaseCleanup()
    {
        try {
            $stats = $this->getDatabaseStats();
            return view('super.tools.cleanup', compact('stats'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load cleanup page');
        }
    }

    public function cleanupDatabase(Request $request)
    {
        try {
            $action = $request->input('action');
            $confirmed = $request->input('confirm') === 'true';

            if (!$confirmed) {
                return redirect()->back()->with('error', 'Action not confirmed');
            }

            switch ($action) {
                case 'clear_users':
                    $count = User::where('role', '!=', 'admin')->count();
                    User::where('role', '!=', 'admin')->delete();
                    Log::warning("$count non-admin users deleted by Super Admin");
                    return redirect()->back()->with('success', "Deleted $count users");

                case 'clear_courses':
                    $count = Course::count();
                    Course::truncate();
                    Log::warning("$count courses deleted by Super Admin");
                    return redirect()->back()->with('success', "Deleted $count courses");

                case 'clear_grades':
                    $count = Grade::count();
                    Grade::truncate();
                    Log::warning("$count grades deleted by Super Admin");
                    return redirect()->back()->with('success', "Deleted $count grades");

                case 'clear_attendance':
                    $count = Attendance::count();
                    Attendance::truncate();
                    Log::warning("$count attendance records deleted by Super Admin");
                    return redirect()->back()->with('success', "Deleted $count attendance records");

                case 'reset_database':
                    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
                    Log::warning("Database reset and reseeded by Super Admin");
                    return redirect()->route('super.dashboard')->with('success', 'Database reset successfully');

                default:
                    return redirect()->back()->with('error', 'Unknown action');
            }
        } catch (\Exception $e) {
            Log::error("Cleanup error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * ==================== SYSTEM HEALTH ====================
     */

    public function systemHealth()
    {
        try {
            $health = [
                'database' => $this->checkDatabase(),
                'storage' => $this->checkStorage(),
                'cache' => $this->checkCache(),
                'logs' => $this->checkLogs(),
                'overall' => 'Healthy',
                'recommendations' => [],
            ];

            // Determine overall health
            $statuses = [$health['database']['status'], $health['storage']['status'], $health['cache']['status'], $health['logs']['status']];
            if (in_array('Error', $statuses)) {
                $health['overall'] = 'Unhealthy';
            } elseif (in_array('Warning', $statuses)) {
                $health['overall'] = 'Warning';
            }

            return view('super.tools.health', compact('health'));
        } catch (\Exception $e) {
            Log::error("System health error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to check system health');
        }
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPDO();
            $database = config('database.connections.mysql.database');
            
            $tables = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?", [$database]);
            $tableCount = $tables[0]->count ?? 0;

            $userCount = User::count();

            return [
                'status' => 'Healthy',
                'connected' => true,
                'name' => $database,
                'table_count' => $tableCount,
                'total_records' => $userCount,
            ];
        } catch (\Exception $e) {
            Log::error("Database health check error: " . $e->getMessage());
            return [
                'status' => 'Error',
                'connected' => false,
                'name' => 'Unknown',
                'table_count' => 0,
                'total_records' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function checkStorage()
    {
        try {
            $path = storage_path();
            $writable = is_writable($path);
            
            $total = disk_total_space('/');
            $free = disk_free_space('/');
            $usagePercent = round((($total - $free) / $total) * 100, 2);

            return [
                'status' => $writable ? 'Healthy' : 'Warning',
                'writable' => $writable,
                'total_space' => $this->formatBytes($total),
                'free_space' => $this->formatBytes($free),
                'usage_percent' => $usagePercent,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'Error',
                'writable' => false,
                'total_space' => 'N/A',
                'free_space' => 'N/A',
                'usage_percent' => 0,
            ];
        }
    }

    private function checkCache()
    {
        try {
            Cache::put('health_check', 'ok', 60);
            $value = Cache::get('health_check');
            
            return [
                'status' => $value === 'ok' ? 'Healthy' : 'Error',
                'driver' => config('cache.default'),
                'accessible' => true,
                'size' => 'N/A',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'Error',
                'driver' => config('cache.default'),
                'accessible' => false,
                'size' => 'N/A',
            ];
        }
    }

    private function checkLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            $exists = File::exists($logPath);
            $writable = $exists ? is_writable($logPath) : false;
            $size = $exists ? $this->formatBytes(File::size($logPath)) : '0 B';

            $errorCount = 0;
            if ($exists) {
                $content = File::get($logPath);
                $errorCount = substr_count(strtolower($content), 'error');
            }

            return [
                'status' => $errorCount > 100 ? 'Warning' : 'Healthy',
                'file_exists' => $exists,
                'writable' => $writable,
                'size' => $size,
                'error_count' => $errorCount,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'Error',
                'file_exists' => false,
                'writable' => false,
                'size' => '0 B',
                'error_count' => 0,
            ];
        }
    }

    /**
     * Clean up old log files
     */
    public function cleanupLogs(Request $request)
    {
        try {
            $confirmed = $request->input('confirm') === 'true';

            if (!$confirmed) {
                return redirect()->back()->with('error', 'Action not confirmed');
            }

            $logPath = storage_path('logs');
            $files = File::files($logPath);
            $days = 90;
            $thresholdTime = now()->subDays($days)->timestamp;
            $deletedCount = 0;

            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < $thresholdTime) {
                    File::delete($file);
                    $deletedCount++;
                }
            }

            Log::info("$deletedCount old log files cleaned by Super Admin");
            return redirect()->back()->with('success', "Deleted $deletedCount old log files");
        } catch (\Exception $e) {
            Log::error("Log cleanup error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
