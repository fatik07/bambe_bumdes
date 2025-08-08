<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Writer',
            'email' => 'writer@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Content Manager',
            'email' => 'content@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'email_verified_at' => null,
            'password' => Hash::make('password123'),
        ]);
    }
}
