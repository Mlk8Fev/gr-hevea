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
                'code' => 'CC1',
                'nom' => 'PK24',
                'adresse' => 'PK24, Route d\'Abidjan, Côte d\'Ivoire',
                'statut' => 'actif'
            ],
            [
                'code' => 'CC2',
                'nom' => 'COTRAF',
                'adresse' => 'COTRAF, Zone industrielle, Abidjan',
                'statut' => 'actif'
            ],
            [
                'code' => 'CC3',
                'nom' => 'FPH-CI Entrepôt',
                'adresse' => 'Entrepôt FPH-CI, Yopougon, Abidjan',
                'statut' => 'actif'
            ],
            [
                'code' => 'CC4',
                'nom' => 'Centre Abengourou',
                'adresse' => 'Centre de collecte Abengourou, Région de l\'Indénié-Djuablin',
                'statut' => 'actif'
            ],
            [
                'code' => 'CC5',
                'nom' => 'Centre San-Pédro',
                'adresse' => 'Centre de collecte San-Pédro, Région du Bas-Sassandra',
                'statut' => 'actif'
            ]
        ];

        foreach ($centres as $centre) {
            CentreCollecte::create($centre);
        }
    }
}
