<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fonction;
use App\Models\Cooperative;
use Illuminate\Support\Facades\Hash;

class NewUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supprimer tous les anciens utilisateurs (en respectant les contraintes)
        User::query()->delete();

        // Récupérer les fonctions
        $adminFonction = Fonction::where('nom', 'Administrateur')->first();
        $chefDeptFonction = Fonction::where('nom', 'Chef de Département')->first();
        $chefOpsFonction = Fonction::where('nom', 'Chef des Opérations')->first();
        $respCoopFonction = Fonction::where('nom', 'Responsable Coopérative')->first();

        // Récupérer une coopérative pour le test
        $cooperative = Cooperative::first();

        // Créer les 5 nouveaux comptes
        $users = [
            [
                'username' => 'admin_fphci',
                'name' => 'Admin FPH-CI',
                'nom' => 'Administrateur Principal',
                'email' => 'admin@fph-ci.ci',
                'password' => 'admin123',
                'fonction_id' => $adminFonction->id,
                'role' => 'superadmin',
                'secteur' => 'Siège',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'chef_finance',
                'name' => 'Chef Finance',
                'nom' => 'Kouassi Jean',
                'email' => 'finance@fph-ci.ci',
                'password' => 'finance123',
                'fonction_id' => $chefDeptFonction->id,
                'role' => 'admin',
                'secteur' => 'Finances',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'chef_operations',
                'name' => 'Chef Opérations',
                'nom' => 'Traoré Marie',
                'email' => 'operations@fph-ci.ci',
                'password' => 'operations123',
                'fonction_id' => $chefOpsFonction->id,
                'role' => 'admin',
                'secteur' => 'Opérations',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'resp_coop1',
                'name' => 'Responsable Coopérative 1',
                'nom' => 'Bamba Pierre',
                'email' => 'coop1@fph-ci.ci',
                'password' => 'coop123',
                'fonction_id' => $respCoopFonction->id,
                'cooperative_id' => $cooperative ? $cooperative->id : null,
                'role' => 'manager',
                'secteur' => 'Coopératives',
                'siege' => false,
                'status' => 'active'
            ],
            [
                'username' => 'agent_terrain',
                'name' => 'Agent Terrain',
                'nom' => 'Koné Fatou',
                'email' => 'terrain@fph-ci.ci',
                'password' => 'terrain123',
                'fonction_id' => $chefOpsFonction->id,
                'role' => 'user',
                'secteur' => 'Terrain',
                'siege' => false,
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            $password = $userData['password'];
            unset($userData['password']);
            
            User::create([
                ...$userData,
                'password' => Hash::make($password)
            ]);
        }
    }
}
