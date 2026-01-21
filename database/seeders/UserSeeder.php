<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@edutrack.com',
            'phone' => '1234567890',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'status' => 'Active',
        ]);

        // Admin Users
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Admin User $i",
                'email' => "admin$i@edutrack.com",
                'phone' => '98' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'Active',
            ]);
        }

        // Teachers
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Dr. Teacher $i",
                'email' => "teacher$i@edutrack.com",
                'phone' => '97' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'status' => 'Active',
            ]);
        }

        // Students
        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name' => "Student $i Name",
                'email' => "student$i@edutrack.com",
                'phone' => '96' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'Active',
            ]);
        }
    }
}
