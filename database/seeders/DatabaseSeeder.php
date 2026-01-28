<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // ✅ Tambahkan ini
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), 
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        // Users (user role)
        User::factory(10)->create([
            'role' => 'user',
        ]);
    }
}

// php artisan migrate:fresh --seed

