<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('Password123!'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'instructor@example.com'],
            ['name' => 'Instructor', 'password' => Hash::make('Password123!'), 'role' => 'instructor']
        );

        User::updateOrCreate(
            ['email' => 'aprendiz@example.com'],
            ['name' => 'Aprendiz', 'password' => Hash::make('Password123!'), 'role' => 'aprendiz']
        );
    }
}
