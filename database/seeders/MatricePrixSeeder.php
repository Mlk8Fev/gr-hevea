<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MatricePrix;

class MatricePrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer la matrice de prix 2025
        MatricePrix::create([
            'annee' => '2025',
            'prix_bord_champs' => 72.00,
            'transport_base' => 10.00,
            'cooperatives' => 4.00,
            'stockage' => 5.00,
            'chargeur_dechargeur' => 2.00,
            'soutien_sechoirs' => 1.00,
            'prodev' => 10.00,
            'sac' => 4.00,
            'sechage' => 3.00,
            'certification' => 3.00,
            'fphci' => 2.00,
            'moyenne_transfert' => 22.00,
            'active' => true
        ]);

        $this->command->info('Matrice de prix 2025 créée avec succès !');
    }
}
