<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lidervyg.pe'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'operador@lidervyg.pe'],
            [
                'name' => 'Operador',
                'password' => Hash::make('Operador123!'),
                'role' => 'operador',
            ]
        );
    }
}
