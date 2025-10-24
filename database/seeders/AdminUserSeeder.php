<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Create admin safely
        User::updateOrCreate(
            ['email' => 'admin@eduorbit.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // ✅ Create default student safely
        User::updateOrCreate(
            ['email' => 'student@eduorbit.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // ✅ Create default teacher safely
        User::updateOrCreate(
            ['email' => 'teacher@eduorbit.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]
        );
    }
}
