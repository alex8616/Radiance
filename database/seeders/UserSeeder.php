<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔴 ADMIN
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@radiance.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'ci' => '12345678',
            'celular' => '70000001',
        ]);

        User::create([
            'name' => 'Doctor',
            'email' => 'ale@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'doctor',
            'ci' => '12345679',
            'celular' => '70000002',
        ]);
    }
}