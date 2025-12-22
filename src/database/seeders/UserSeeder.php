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
            'name' => 'Administrador',
            'email' => 'admin@smartbudget.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'timezone' => 'Europe/Madrid',
            'currency' => 'EUR',
        ]);

        User::create([
            'name' => 'Usuario Demo',
            'email' => 'user@smartbudget.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'timezone' => 'Europe/Madrid',
            'currency' => 'EUR',
        ]);
    }
}
