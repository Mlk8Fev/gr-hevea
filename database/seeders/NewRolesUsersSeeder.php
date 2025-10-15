<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fonction;
use App\Models\Cooperative;
use App\Models\Secteur;
use Illuminate\Support\Facades\Hash;

class NewRolesUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les fonctions
        $chefSecteurFonction = Fonction::where('nom', 'Chef Secteur')->first();
        $assistanteComptableFonction = Fonction::where('nom', 'Assistante Comptable')->first();
        $responsableTracabiliteFonction = Fonction::where('nom', 'Responsable Traçabilité')->first();
        $responsableDurabiliteFonction = Fonction::where('nom', 'Responsable Durabilité')->first();
        $comptableSiegeFonction = Fonction::where('nom', 'Comptable Siège')->first();
        $controleurUsineFonction = Fonction::where('nom', 'Contrôleur Usine')->first();
        $responsableCooperativeFonction = Fonction::where('nom', 'Responsable Coopérative')->first();

        // Récupérer une coopérative et un secteur pour les tests
        $cooperative = Cooperative::first();
        $secteur = Secteur::first();

        // Créer les utilisateurs avec les nouveaux rôles
        $users = [
            [
                'username' => 'chef_secteur_ab01',
                'name' => 'Chef Secteur AB01',
                'email' => 'chef.secteur.ab01@fph-ci.ci',
                'password' => 'chef123',
                'fonction_id' => $chefSecteurFonction->id,
                'role' => 'cs',
                'secteur' => 'AB01',
                'siege' => false,
                'status' => 'active'
            ],
            [
                'username' => 'assistante_comptable',
                'name' => 'Assistante Comptable',
                'email' => 'assistante.comptable@fph-ci.ci',
                'password' => 'comptable123',
                'fonction_id' => $assistanteComptableFonction->id,
                'role' => 'ac',
                'secteur' => 'Siège',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'responsable_tracabilite',
                'name' => 'Responsable Traçabilité',
                'email' => 'responsable.tracabilite@fph-ci.ci',
                'password' => 'tracabilite123',
                'fonction_id' => $responsableTracabiliteFonction->id,
                'role' => 'rt',
                'secteur' => 'Siège',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'responsable_durabilite',
                'name' => 'Responsable Durabilité',
                'email' => 'responsable.durabilite@fph-ci.ci',
                'password' => 'durabilite123',
                'fonction_id' => $responsableDurabiliteFonction->id,
                'role' => 'rd',
                'secteur' => 'Siège',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'comptable_siege',
                'name' => 'Comptable Siège',
                'email' => 'comptable.siege@fph-ci.ci',
                'password' => 'comptable123',
                'fonction_id' => $comptableSiegeFonction->id,
                'role' => 'comp',
                'secteur' => 'Siège',
                'siege' => true,
                'status' => 'active'
            ],
            [
                'username' => 'controleur_usine',
                'name' => 'Contrôleur Usine',
                'email' => 'controleur.usine@fph-ci.ci',
                'password' => 'usine123',
                'fonction_id' => $controleurUsineFonction->id,
                'role' => 'ctu',
                'secteur' => 'Usine',
                'siege' => false,
                'status' => 'active'
            ],
            [
                'username' => 'responsable_coop_ab01',
                'name' => 'Responsable Coopérative AB01',
                'email' => 'responsable.coop.ab01@fph-ci.ci',
                'password' => 'coop123',
                'fonction_id' => $responsableCooperativeFonction->id,
                'cooperative_id' => $cooperative ? $cooperative->id : null,
                'role' => 'rcoop',
                'secteur' => 'AB01',
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

        $this->command->info('Utilisateurs avec nouveaux rôles créés avec succès !');
    }
}