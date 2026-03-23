<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

$existing = User::where('role', 'superadmin')->first();
if ($existing) {
    echo "Superadmin already exists: {$existing->email}\n";
    exit(0);
}

$user = User::create([
    'name' => 'Super Admin',
    'email' => 'superadmin@example.com',
    'password' => bcrypt('password123'),
    'role' => 'superadmin',
    'status' => 'Active',
]);

echo "Created superadmin: {$user->email} (id {$user->id})\n";
