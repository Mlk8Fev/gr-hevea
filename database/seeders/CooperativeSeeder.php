<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cooperative;
use App\Models\Secteur;

class CooperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $secteur = Secteur::first();

        $cooperatives = [
            [
                'code' => 'AB01-COOP1',
                'nom' => 'SCOOPS FADIKA',
                'secteur_id' => $secteur->id,
                'president' => 'Kouassi Jean',
                'contact' => '0123456789',
                'sigle' => 'SCOOPS-FAD',
                'latitude' => 5.3600,
                'longitude' => -4.0083,
                'kilometrage' => 150,
                'a_sechoir' => false,
                'compte_bancaire' => '123456789012',
                'code_banque' => '12345',
                'code_guichet' => '67890',
                'nom_cooperative_banque' => 'SCOOPS FADIKA'
            ],
            [
                'code' => 'AB02-COOP2',
                'nom' => 'SCOOPS TRAORE',
                'secteur_id' => $secteur->id,
                'president' => 'Traoré Marie',
                'contact' => '0987654321',
                'sigle' => 'SCOOPS-TRA',
                'latitude' => 5.3700,
                'longitude' => -4.0183,
                'kilometrage' => 200,
                'a_sechoir' => true,
                'compte_bancaire' => '987654321098',
                'code_banque' => '54321',
                'code_guichet' => '09876',
                'nom_cooperative_banque' => 'SCOOPS TRAORE'
            ]
        ];

        foreach ($cooperatives as $cooperative) {
            Cooperative::create($cooperative);
        }
    }
}
