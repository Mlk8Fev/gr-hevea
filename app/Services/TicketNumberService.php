<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketNumberService
{
    /**
     * Génère un numéro de ticket unique basé sur le numéro de livraison
     * (un seul ticket par livraison)
     */
    public function generateNumber(string $numeroLivraison): string
    {
        // Vérifier qu'il n'existe pas déjà un ticket pour cette livraison
        if ($this->exists($numeroLivraison)) {
            throw new \Exception("Un ticket de pesée existe déjà pour la livraison {$numeroLivraison}.");
        }

        // Le numéro de ticket est le même que le numéro de livraison
        return $numeroLivraison;
    }

    /**
     * Récupère le prochain numéro de ticket pour une livraison (sans l'incrémenter)
     *
     * @param string $numeroLivraison Numéro de livraison
     * @return int Prochain numéro disponible
     */
    public function getNextNumber(string $numeroLivraison): int
    {
        $counter = DB::table('ticket_counters')
            ->where('numero_livraison', $numeroLivraison)
            ->first();

        return $counter ? $counter->dernier_numero + 1 : 1;
    }

    /**
     * Vérifie si un numéro de ticket existe déjà
     *
     * @param string $numeroTicket Numéro de ticket à vérifier
     * @return bool True si le numéro existe, false sinon
     */
    public function exists(string $numeroTicket): bool
    {
        return DB::table('tickets_pesee')
            ->where('numero_ticket', $numeroTicket)
            ->exists();
    }
} 