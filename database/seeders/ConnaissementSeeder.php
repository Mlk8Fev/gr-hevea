<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Connaissement;
use App\Models\Cooperative;
use App\Models\CentreCollecte;
use App\Models\User;
use Carbon\Carbon;

class ConnaissementSeeder extends Seeder
{
    /**
     * Exécuter le seeder
     */
    public function run(): void
    {
        // Récupérer les coopératives existantes
        $cooperatives = Cooperative::all();
        if ($cooperatives->isEmpty()) {
            $this->command->error('Aucune coopérative trouvée. Créez d\'abord des coopératives.');
            return;
        }

        // Récupérer les centres de collecte existants
        $centresCollecte = CentreCollecte::all();
        if ($centresCollecte->isEmpty()) {
            $this->command->error('Aucun centre de collecte trouvé. Créez d\'abord des centres de collecte.');
            return;
        }

        // Récupérer un utilisateur pour créer les connaissements
        $user = User::first();
        if (!$user) {
            $this->command->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        // Données des connaissements avec les vraies coopératives et centres de collecte
        $connaissements = [
            [
                'numero' => 'CN20250001',
                'cooperative_id' => 1, // SCOOPS FADIKA
                'centre_collecte_id' => 1, // PK24
                'lieu_depart' => 'Abidjan',
                'sous_prefecture' => 'Cocody',
                'transporteur_nom' => 'Transport Express',
                'transporteur_immatriculation' => 'AB-123-CD',
                'chauffeur_nom' => 'Kouassi Jean',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 90,
                'poids_brut_estime' => 4500.00,
                'poids_net' => null,
                'statut' => 'programme',
                'created_by' => $user->id
            ],
            [
                'numero' => 'CN20250002',
                'cooperative_id' => 2, // SCOOPS PATRICIA TOURE
                'centre_collecte_id' => 2, // COTRAF
                'lieu_depart' => 'Bouaké',
                'sous_prefecture' => 'Bouaké',
                'transporteur_nom' => 'Transport Froid',
                'transporteur_immatriculation' => 'EF-456-GH',
                'chauffeur_nom' => 'Yao Pierre',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 104,
                'poids_brut_estime' => 5200.00,
                'poids_net' => null,
                'statut' => 'programme',
                'created_by' => $user->id
            ],
            [
                'numero' => 'CN20250003',
                'cooperative_id' => 1, // SCOOPS FADIKA
                'centre_collecte_id' => 5, // Centre San-Pédro
                'lieu_depart' => 'San-Pédro',
                'sous_prefecture' => 'San-Pédro',
                'transporteur_nom' => 'Transport Urgent',
                'transporteur_immatriculation' => 'IJ-789-KL',
                'chauffeur_nom' => 'Bamba François',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 76,
                'poids_brut_estime' => 3800.00,
                'poids_net' => null,
                'statut' => 'valide',
                'created_by' => $user->id,
                'validated_by' => $user->id,
                'date_validation' => Carbon::now()->subHours(6)
            ],
            [
                'numero' => 'CN20250004',
                'cooperative_id' => 2, // SCOOPS PATRICIA TOURE
                'centre_collecte_id' => 4, // Centre Abengourou
                'lieu_depart' => 'Yamoussoukro',
                'sous_prefecture' => 'Yamoussoukro',
                'transporteur_nom' => 'Transport Lourd',
                'transporteur_immatriculation' => 'MN-012-OP',
                'chauffeur_nom' => 'Konan Marie',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 120,
                'poids_brut_estime' => 6000.00,
                'poids_net' => null,
                'statut' => 'programme',
                'created_by' => $user->id
            ],
            [
                'numero' => 'CN20250005',
                'cooperative_id' => 1, // SCOOPS FADIKA
                'centre_collecte_id' => 1, // PK24
                'lieu_depart' => 'Korhogo',
                'sous_prefecture' => 'Korhogo',
                'transporteur_nom' => 'Transport Rural',
                'transporteur_immatriculation' => 'QR-345-ST',
                'chauffeur_nom' => 'Kouame Paul',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 84,
                'poids_brut_estime' => 4200.00,
                'poids_net' => null,
                'statut' => 'programme',
                'created_by' => $user->id
            ],
            [
                'numero' => 'CN20250006',
                'cooperative_id' => 2, // SCOOPS PATRICIA TOURE
                'centre_collecte_id' => 2, // COTRAF
                'lieu_depart' => 'Abengourou',
                'sous_prefecture' => 'Abengourou',
                'transporteur_nom' => 'Transport Standard',
                'transporteur_immatriculation' => 'UV-678-WX',
                'chauffeur_nom' => 'N\'Guessan Koffi',
                'destinataire_type' => 'entrepot',
                'destinataire_id' => 3, // FPH-CI Entrepôt
                'nombre_sacs' => 96,
                'poids_brut_estime' => 4800.00,
                'poids_net' => null,
                'statut' => 'programme',
                'created_by' => $user->id
            ]
        ];

        // Créer les connaissements
        foreach ($connaissements as $connaissementData) {
            Connaissement::create($connaissementData);
        }

        $this->command->info('6 connaissements d\'exemple créés avec succès !');
        $this->command->info('- 5 connaissements programmés');
        $this->command->info('- 1 connaissement validé');
    }
} 