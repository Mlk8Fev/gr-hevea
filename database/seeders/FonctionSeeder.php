<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fonction;

class FonctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fonctions = [
            [
                'nom' => 'Administrateur',
                'description' => 'Accès complet au système, gestion des utilisateurs et configurations',
                'peut_gerer_cooperative' => false,
                'niveau_acces' => 'admin'
            ],
            [
                'nom' => 'Chef de Département',
                'description' => 'Gestion d\'un département spécifique, supervision des équipes',
                'peut_gerer_cooperative' => false,
                'niveau_acces' => 'manager'
            ],
            [
                'nom' => 'Chef des Opérations',
                'description' => 'Gestion des opérations quotidiennes, coordination des activités',
                'peut_gerer_cooperative' => false,
                'niveau_acces' => 'manager'
            ],
            [
                'nom' => 'Responsable Coopérative',
                'description' => 'Gestion d\'une coopérative spécifique, suivi des producteurs',
                'peut_gerer_cooperative' => true,
                'niveau_acces' => 'manager'
            ]
        ];

        foreach ($fonctions as $fonction) {
            Fonction::create($fonction);
        }
    }
}
