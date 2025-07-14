<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'nom' => 'Admin',
            'prenom' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'secteur' => 'Siège',
            'fonction' => 'Administrateur',
            'siege' => true,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Manager User',
            'nom' => 'Manager',
            'prenom' => 'User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'secteur' => 'Ventes',
            'fonction' => 'Chef de Service',
            'siege' => false,
            'status' => 'active',
        ]);

        User::create([
            'name' => 'User Test',
            'nom' => 'Test',
            'prenom' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'secteur' => 'Marketing',
            'fonction' => 'Opérateur',
            'siege' => false,
            'status' => 'inactive', // Cet utilisateur sera désactivé pour tester
        ]);
    }
}
