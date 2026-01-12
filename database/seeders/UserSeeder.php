<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'active' => true,
            ]
        );

        // ENCARGADO DE PRUEBA
        User::firstOrCreate(
            ['email' => 'encargado@test.com'],
            [
                'name' => 'Encargado Prueba',
                'password' => Hash::make('12345678'),
                'role' => 'encargado',
                'active' => true,
            ]
        );
    }
}
