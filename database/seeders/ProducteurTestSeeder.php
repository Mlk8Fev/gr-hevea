<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producteur;
use App\Models\Secteur;
use App\Models\Cooperative;
use Faker\Factory as Faker;

class ProducteurTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        
        // Récupérer tous les secteurs disponibles
        $secteurs = Secteur::all();
        if ($secteurs->isEmpty()) {
            $this->command->error('Aucun secteur trouvé. Veuillez d\'abord exécuter le SecteurSeeder.');
            return;
        }

        // Récupérer toutes les coopératives disponibles
        $cooperatives = Cooperative::all();
        if ($cooperatives->isEmpty()) {
            $this->command->error('Aucune coopérative trouvée. Veuillez d\'abord exécuter le CooperativeSeeder.');
            return;
        }

        // Noms et prénoms ivoiriens réalistes
        $noms = [
            'Kouame', 'Kone', 'Traore', 'Ouattara', 'Diabate', 'Sangare', 'Coulibaly', 'Fofana',
            'Toure', 'Diallo', 'Keita', 'Camara', 'Sissoko', 'Ba', 'Diarra', 'Sangare',
            'Kone', 'Traore', 'Ouattara', 'Diabate', 'Coulibaly', 'Fofana', 'Toure', 'Diallo',
            'Keita', 'Camara', 'Sissoko', 'Ba', 'Diarra', 'Kouame', 'Kone', 'Traore',
            'Ouattara', 'Diabate', 'Sangare', 'Coulibaly', 'Fofana', 'Toure', 'Diallo',
            'Keita', 'Camara', 'Sissoko', 'Ba', 'Diarra', 'Kouame', 'Kone', 'Traore'
        ];

        $prenoms = [
            'Aminata', 'Fatou', 'Mariam', 'Aissata', 'Kadiatou', 'Aminata', 'Fatou', 'Mariam',
            'Aissata', 'Kadiatou', 'Aminata', 'Fatou', 'Mariam', 'Aissata', 'Kadiatou',
            'Moussa', 'Ibrahim', 'Amadou', 'Boubacar', 'Cheick', 'Mamadou', 'Ousmane', 'Sekou',
            'Yaya', 'Alpha', 'Bakary', 'Djibril', 'Fode', 'Habib', 'Idriss', 'Karim',
            'Lamine', 'Modibo', 'Naby', 'Omar', 'Papa', 'Rachid', 'Samba', 'Tidiane',
            'Usman', 'Vieux', 'Wahab', 'Yacine', 'Zakaria', 'Abdoulaye', 'Bakary', 'Cheick'
        ];

        $genres = ['M', 'F'];
        $localites = [
            'Abidjan', 'Bouake', 'Daloa', 'Korhogo', 'San-Pedro', 'Yamoussoukro', 'Gagnoa',
            'Man', 'Divo', 'Anyama', 'Abengourou', 'Bondoukou', 'Agboville', 'Dabou',
            'Grand-Bassam', 'Sinfra', 'Toumodi', 'Bingerville', 'Issia', 'Vavoua',
            'Touba', 'Seguela', 'Bouna', 'Ferkessedougou', 'Odienne', 'Boundiali',
            'Katiola', 'Mankono', 'Tengrela', 'Sakassou', 'Beoumi', 'Botro', 'Bouafle',
            'Soubre', 'Meagui', 'Guiglo', 'Duekoue', 'Bangolo', 'Toulepleu', 'Blolequin',
            'Danane', 'Zouan-Hounien', 'Biankouma', 'Sipilou', 'Vavoua', 'Zuenoula',
            'Bouake', 'Sakassou', 'Beoumi', 'Botro', 'Bouafle', 'Soubre', 'Meagui'
        ];

        $this->command->info('Génération de 1000 producteurs fictifs...');
        
        $bar = $this->command->getOutput()->createProgressBar(1000);
        $bar->start();

        for ($i = 1; $i <= 1000; $i++) {
            // Générer un code FPHCI unique
            $codeFphci = 'FPH' . str_pad($i, 6, '0', STR_PAD_LEFT);
            
            // Sélectionner un secteur aléatoire
            $secteur = $secteurs->random();
            
            // Générer un ID Agronica (optionnel, 70% de chance)
            $agronicaId = $faker->optional(0.7)->numerify('AGR####');
            
            // Sélectionner une localité aléatoire
            $localite = $faker->randomElement($localites);
            
            // Générer un contact (numéro de téléphone ivoirien)
            $contact = '0' . $faker->randomElement(['5', '7']) . $faker->numerify('########');
            
            // Générer une superficie totale (entre 0.5 et 15 hectares)
            $superficieTotale = $faker->randomFloat(2, 0.5, 15.0);

            // Créer le producteur
            $producteur = Producteur::create([
                'nom' => $faker->randomElement($noms),
                'prenom' => $faker->randomElement($prenoms),
                'code_fphci' => $codeFphci,
                'agronica_id' => $agronicaId,
                'localite' => $localite,
                'secteur_id' => $secteur->id,
                'genre' => $faker->randomElement($genres),
                'contact' => $contact,
                'superficie_totale' => $superficieTotale,
            ]);

            // Associer 1 à 3 coopératives aléatoires du même secteur
            $cooperativesDuSecteur = $cooperatives->where('secteur_id', $secteur->id);
            if ($cooperativesDuSecteur->isNotEmpty()) {
                $nombreCooperatives = $faker->numberBetween(1, min(3, $cooperativesDuSecteur->count()));
                $cooperativesSelectionnees = $cooperativesDuSecteur->random($nombreCooperatives);
                $producteur->cooperatives()->attach($cooperativesSelectionnees->pluck('id')->toArray());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✅ 1000 producteurs créés avec succès !');
        $this->command->info('📊 Répartition par secteur :');
        
        // Afficher la répartition par secteur
        $repartition = Producteur::with('secteur')
            ->get()
            ->groupBy('secteur.nom')
            ->map->count()
            ->sortDesc();
            
        foreach ($repartition as $secteurNom => $nombre) {
            $this->command->line("   • {$secteurNom} : {$nombre} producteurs");
        }
    }
}
