<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CentreCollecte;

class CentreCollecteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $centres = [
            [
                'code' => 'DUEK',
                'nom' => 'Duekoué',
                'adresse' => 'Centre de collecte Duekoué, Région du Guémon',
                'statut' => 'actif'
            ],
            [
                'code' => 'GUIG',
                'nom' => 'Guiglo',
                'adresse' => 'Centre de collecte Guiglo, Région du Cavally',
                'statut' => 'actif'
            ],
            [
                'code' => 'DIVO',
                'nom' => 'Divo',
                'adresse' => 'Centre de collecte Divo, Région du Lôh-Djiboua',
                'statut' => 'actif'
            ],
            [
                'code' => 'MEAG',
                'nom' => 'Méagui',
                'adresse' => 'Centre de collecte Méagui, Région du Nawa',
                'statut' => 'actif'
            ]
        ];

        foreach ($centres as $centre) {
            // Vérifier si le centre existe déjà
            if (!CentreCollecte::where('nom', $centre['nom'])->exists()) {
                CentreCollecte::create($centre);
            }
        }
    }
}
