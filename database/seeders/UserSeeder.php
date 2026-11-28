<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = config('admin.name');
        $email = config('admin.email');
        $password = config('admin.password');

        if (! $name || ! $email || ! $password) {
            throw new InvalidArgumentException(
                'Configure ADMIN_NAME, ADMIN_EMAIL y ADMIN_PASSWORD antes de ejecutar el seeder.'
            );
        }

        if (mb_strlen($password) < 8) {
            throw new InvalidArgumentException('ADMIN_PASSWORD debe tener al menos 8 caracteres.');
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password), 'role' => 'admin']
        );
    }
}
