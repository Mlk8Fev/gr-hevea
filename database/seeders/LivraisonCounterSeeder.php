<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Secteur;

class LivraisonCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les secteurs existants
        $secteurs = Secteur::all();

        foreach ($secteurs as $secteur) {
            // Vérifier si un compteur existe déjà pour ce secteur
            $existingCounter = DB::table('livraison_counters')
                ->where('secteur_code', $secteur->code)
                ->first();

            if (!$existingCounter) {
                // Créer un nouveau compteur pour ce secteur
                DB::table('livraison_counters')->insert([
                    'secteur_code' => $secteur->code,
                    'dernier_numero' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->command->info("Compteur créé pour le secteur: {$secteur->code} ({$secteur->nom})");
            } else {
                $this->command->info("Compteur existe déjà pour le secteur: {$secteur->code} ({$secteur->nom})");
            }
        }

        $this->command->info('Initialisation des compteurs de livraison terminée.');
    }
}
