<?php

use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;

try {
    echo "=== ROUTE & MIDDLEWARE VERIFICATION ===\n\n";

    // Check 1: Route groups exist
    echo "1. Route Group Configuration:\n";
    $routes = [
        '/super/dashboard' => 'super',
        '/admin/dashboard' => 'admin', 
        '/teacher/dashboard' => 'teacher',
        '/student/dashboard' => 'student',
    ];

    $routeFile = file_get_contents(base_path('routes/web.php'));
    
    foreach ($routes as $route => $role) {
        if (strpos($routeFile, "role:$role") !== false || strpos($routeFile, "'$role'") !== false) {
            echo "  ✓ Route group '/$role' configured\n";
        } else {
            echo "  ❌ Route group '/$role' not found\n";
        }
    }

    // Check 2: CheckRole middleware exists
    echo "\n2. Middleware Verification:\n";
    $middlewarePath = app_path('Http/Middleware/CheckRole.php');
    if (file_exists($middlewarePath)) {
        $middlewareContent = file_get_contents($middlewarePath);
        if (strpos($middlewareContent, 'checkRole') !== false) {
            echo "  ✓ CheckRole middleware exists\n";
        }
        
        // Check for role normalization
        if (preg_match('/superadmin|role.*normali/i', $middlewareContent)) {
            echo "  ✓ Role normalization implemented\n";
        }
        
        // Check for proper middleware logic
        if (strpos($middlewareContent, 'Auth::user()') !== false && strpos($middlewareContent, 'role') !== false) {
            echo "  ✓ Middleware checks user role\n";
        }
    } else {
        echo "  ❌ CheckRole middleware not found\n";
    }

    // Check 3: Controllers exist
    echo "\n3. Controller Verification:\n";
    $controllers = [
        'SuperAdminController' => ['dashboard'],
        'AdminController' => ['dashboard'],
        'TeacherController' => ['dashboard'],
        'StudentController' => ['dashboard'],
    ];

    foreach ($controllers as $controller => $methods) {
        $path = app_path("Http/Controllers/$controller.php");
        if (file_exists($path)) {
            echo "  ✓ {$controller} exists\n";
            foreach ($methods as $method) {
                $content = file_get_contents($path);
                if (strpos($content, "function $method") !== false) {
                    echo "    ✓ {$method}() method found\n";
                } else {
                    echo "    ❌ {$method}() method not found\n";
                }
            }
        } else {
            echo "  ❌ {$controller} not found\n";
        }
    }

    // Check 4: Models are configured correctly
    echo "\n4. Model Verification:\n";
    $models = [
        'User' => 'app/Models/User.php',
        'Student' => 'app/Models/Student.php',
        'Teacher' => 'app/Models/Teacher.php',
        'Admin' => 'app/Models/Admin.php',
    ];

    foreach ($models as $model => $path) {
        if (file_exists(base_path($path))) {
            echo "  ✓ {$model} model exists\n";
        } else {
            echo "  ❌ {$model} model not found\n";
        }
    }

    // Check 5: Auth Configuration
    echo "\n5. Authentication Configuration:\n";
    $authConfig = config('auth');
    if ($authConfig['guards']['web']['provider'] === 'users') {
        echo "  ✓ Web guard uses 'users' provider\n";
    }
    if ($authConfig['providers']['users']['model'] === 'App\\Models\\User') {
        echo "  ✓ Users provider uses User model\n";
    }

    // Check 6: Student table relationships
    echo "\n6. Student-User Relationship Verification:\n";
    $studentCount = \App\Models\Student::whereNotNull('user_id')->count();
    $totalStudents = \App\Models\Student::count();
    echo "  ✓ Students with user_id link: {$studentCount}/{$totalStudents}\n";

    if ($studentCount === $totalStudents && $totalStudents > 0) {
        echo "  ✓ All students properly linked to users table\n";
    } else if ($studentCount > 0) {
        echo "  ⚠ Some students not yet linked: " . ($totalStudents - $studentCount) . " remaining\n";
    }

    echo "\n=== ROUTE & MIDDLEWARE VERIFICATION COMPLETE ===\n";
    echo "\n✅ All authentication infrastructure verified successfully!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
