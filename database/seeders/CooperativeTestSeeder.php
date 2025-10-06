<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cooperative;
use App\Models\Secteur;

class CooperativeTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les secteurs
        $secteurs = Secteur::all();
        
        if ($secteurs->isEmpty()) {
            $this->command->error('Aucun secteur trouvé. Veuillez d\'abord créer des secteurs.');
            return;
        }

        $cooperatives = [];
        
        // Générer 100 coopératives fictives
        for ($i = 1; $i <= 100; $i++) {
            $secteur = $secteurs->random();
            $secteurCode = $secteur->code;
            $coopNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $cooperatives[] = [
                'code' => $secteurCode . '-COOP' . $coopNumber,
                'nom' => 'SCOOPS ' . $this->generateRandomName(),
                'secteur_id' => $secteur->id,
                'president' => $this->generateRandomName(),
                'contact' => '0' . rand(100000000, 999999999),
                'sigle' => 'SCOOPS-' . substr($this->generateRandomName(), 0, 3),
                'latitude' => rand(5000000, 6000000) / 1000000, // Entre 5.0 et 6.0
                'longitude' => rand(-5000000, -3000000) / 1000000, // Entre -5.0 et -3.0
                'kilometrage' => rand(50, 500),
                'a_sechoir' => rand(0, 1) == 1,
                'compte_bancaire' => str_pad(rand(100000000000, 999999999999), 12, '0', STR_PAD_LEFT),
                'code_banque' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'code_guichet' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'nom_cooperative_banque' => 'SCOOPS ' . $this->generateRandomName(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insérer toutes les coopératives
        Cooperative::insert($cooperatives);
        
        $this->command->info('100 coopératives fictives créées avec succès !');
    }

    /**
     * Générer un nom aléatoire
     */
    private function generateRandomName(): string
    {
        $firstNames = ['KOUAME', 'TRAORE', 'DIABATE', 'KONE', 'OUATTARA', 'SANGARE', 'DIALLO', 'CAMARA', 'KEITA', 'BAMBA', 'FADIKA', 'DARELLE', 'JAMES', 'DIDIER', 'DROGBA', 'YAYA', 'TOURE', 'KALOU', 'BONI', 'KOUADIO'];
        $lastNames = ['JEAN', 'MARIE', 'PAUL', 'ANNE', 'PIERRE', 'CLAIRE', 'MARC', 'SOPHIE', 'LUC', 'NATHALIE', 'DAVID', 'ISABELLE', 'THOMAS', 'VALERIE', 'NICOLAS', 'CAROLINE', 'ANTOINE', 'CECILE', 'FRANCOIS', 'HELENE'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}
