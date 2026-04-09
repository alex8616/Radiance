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

        // 🔵 DOCTOR
        User::create([
            'name' => 'Dr. Juan Pérez',
            'email' => 'doctor@radiance.com',
            'password' => Hash::make('12345678'),
            'role' => 'doctor',
            'ci' => '87654321',
            'celular' => '70000002',
        ]);

        // 🟢 PACIENTE
        User::create([
            'name' => 'Carlos Ruiz',
            'email' => 'paciente@radiance.com',
            'password' => Hash::make('12345678'),
            'role' => 'paciente',
            'ci' => '11223344',
            'celular' => '70000003',
        ]);
    }
}