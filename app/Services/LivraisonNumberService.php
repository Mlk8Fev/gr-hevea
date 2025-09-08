<?php

namespace App\Services;

use App\Models\Secteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LivraisonNumberService
{
    /**
     * Génère un numéro de livraison unique pour un secteur donné
     *
     * @param string $secteurCode Code du secteur (ex: GUI12, AB01)
     * @return string Numéro de livraison généré (ex: GUI12L00001)
     * @throws \Exception En cas d'erreur de génération
     */
    public function generateNumber(string $secteurCode): string
    {
        try {
            // Vérifier que le secteur existe
            $secteur = Secteur::where('code', $secteurCode)->first();
            if (!$secteur) {
                throw new \Exception("Le secteur avec le code '{$secteurCode}' n'existe pas.");
            }

            // Utiliser une transaction pour éviter les conflits de concurrence
            return DB::transaction(function () use ($secteurCode) {
                // Récupérer ou créer le compteur pour ce secteur
                $counter = DB::table('livraison_counters')
                    ->where('secteur_code', $secteurCode)
                    ->lockForUpdate()
                    ->first();

                if (!$counter) {
                    // Créer un nouveau compteur pour ce secteur
                    DB::table('livraison_counters')->insert([
                        'secteur_code' => $secteurCode,
                        'dernier_numero' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $nextNumber = 1;
                } else {
                    // Incrémenter le compteur existant
                    $nextNumber = $counter->dernier_numero + 1;
                }

                // Mettre à jour le compteur
                DB::table('livraison_counters')
                    ->where('secteur_code', $secteurCode)
                    ->update([
                        'dernier_numero' => $nextNumber,
                        'updated_at' => now(),
                    ]);

                // Générer le numéro de livraison
                $numeroLivraison = $secteurCode . 'L' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

                Log::info("Numéro de livraison généré: {$numeroLivraison} pour le secteur {$secteurCode}");

                return $numeroLivraison;
            });

        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération du numéro de livraison pour le secteur {$secteurCode}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère le prochain numéro de livraison pour un secteur (sans l'incrémenter)
     *
     * @param string $secteurCode Code du secteur
     * @return int Prochain numéro disponible
     */
    public function getNextNumber(string $secteurCode): int
    {
        $counter = DB::table('livraison_counters')
            ->where('secteur_code', $secteurCode)
            ->first();

        return $counter ? $counter->dernier_numero + 1 : 1;
    }

    /**
     * Vérifie si un numéro de livraison existe déjà
     *
     * @param string $numeroLivraison Numéro de livraison à vérifier
     * @return bool True si le numéro existe, false sinon
     */
    public function exists(string $numeroLivraison): bool
    {
        return DB::table('connaissements')
            ->where('numero_livraison', $numeroLivraison)
            ->exists();
    }

    /**
     * Récupère tous les compteurs par secteur
     *
     * @return \Illuminate\Support\Collection Collection des compteurs
     */
    public function getAllCounters()
    {
        return DB::table('livraison_counters')
            ->join('secteurs', 'livraison_counters.secteur_code', '=', 'secteurs.code')
            ->select(
                'livraison_counters.*',
                'secteurs.nom as secteur_nom'
            )
            ->orderBy('secteurs.nom')
            ->get();
    }

    /**
     * Réinitialise le compteur d'un secteur
     *
     * @param string $secteurCode Code du secteur
     * @param int $newValue Nouvelle valeur du compteur
     * @return bool True si la réinitialisation a réussi
     */
    public function resetCounter(string $secteurCode, int $newValue = 0): bool
    {
        try {
            DB::table('livraison_counters')
                ->where('secteur_code', $secteurCode)
                ->update([
                    'dernier_numero' => $newValue,
                    'updated_at' => now(),
                ]);

            Log::info("Compteur réinitialisé pour le secteur {$secteurCode} à la valeur {$newValue}");
            return true;

        } catch (\Exception $e) {
            Log::error("Erreur lors de la réinitialisation du compteur pour le secteur {$secteurCode}: " . $e->getMessage());
            return false;
        }
    }
}
