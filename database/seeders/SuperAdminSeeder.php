<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le superadmin
        User::create([
            'name' => 'Super Admin',
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('superadmin123'),
            'role' => 'superadmin',
            'secteur' => 'Siège',
            'fonction' => 'Super Administrateur',
            'siege' => true,
            'status' => 'active',
        ]);
    }
}
