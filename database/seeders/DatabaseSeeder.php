<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove this if you already have AdminUserSeeder
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'student@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'student',
        // ]);

        // ✅ Only call AdminUserSeeder
        $this->call(AdminUserSeeder::class);
        $this->call(SriLankaCurriculumSeeder::class);
        
    }
}
