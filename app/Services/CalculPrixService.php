<?php

namespace App\Services;

use App\Models\MatricePrix;
use App\Models\TicketPesee;

class CalculPrixService
{
    /**
     * Calculer le prix complet d'un ticket de pesée
     */
    public function calculerPrixTicket(TicketPesee $ticketPesee)
    {
        // Prix de base selon séchoir (pas de matrice)
        $prixBase = $this->calculerPrixBase($ticketPesee);
        
        // Bonus/malus qualité
        $bonusQualite = $this->calculerBonusQualite($ticketPesee);
        
        // Coût transport
        $coutTransport = $this->calculerCoutTransport($ticketPesee);
        
        // Prix final public (sans part FPH-CI)
        $prixFinalPublic = $prixBase + $bonusQualite + $coutTransport;
        
        // Part FPH-CI cachée
        $partFphci = $this->calculerPartFphci($ticketPesee, $bonusQualite);
        
        // Prix final total (avec part FPH-CI)
        $prixFinalTotal = $prixFinalPublic + $partFphci;
        
        return [
            'prix_base' => $prixBase,
            'bonus_qualite' => $bonusQualite,
            'cout_transport' => $coutTransport,
            'prix_final_public' => $prixFinalPublic, // Prix affiché au public
            'prix_final_total' => $prixFinalTotal,   // Prix total réel
            'part_fphci' => $partFphci,              // Part cachée FPH-CI
            'a_sechoir' => $this->cooperativeAvecSechoir($ticketPesee), // Info séchoir
            'details' => $this->getDetailsCalcul($ticketPesee, $prixBase, $bonusQualite, $coutTransport, $partFphci)
        ];
    }

    /**
     * Calculer le prix de base
     */
    private function calculerPrixBase(TicketPesee $ticketPesee)
    {
        // Vérifier si la coopérative a un séchoir
        $aSechoir = $this->cooperativeAvecSechoir($ticketPesee);
        
        if ($aSechoir) {
            return 94; // Prix avec séchoir
        }
        
        return 93; // Prix sans séchoir
    }

    /**
     * Calculer les bonus/malus de qualité
     */
    private function calculerBonusQualite(TicketPesee $ticketPesee)
    {
        $bonus = 0;
        $aSechoir = $this->cooperativeAvecSechoir($ticketPesee);
        
        // Bonus impuretés (même pour tous)
        $bonus += $this->calculerBonusImpuretes($ticketPesee->taux_impuretes);
        
        // Bonus humidité (selon séchoir)
        $bonus += $this->calculerBonusHumidite($ticketPesee->taux_humidite, $aSechoir);
        
        return $bonus;
    }

    /**
     * Calculer bonus impuretés
     */
    private function calculerBonusImpuretes($tauxImpuretes)
    {
        if ($tauxImpuretes <= 5) {
            return 3; // Bonus +3 FCFA
        } elseif ($tauxImpuretes <= 12) {
            return 0; // Neutre
        } elseif ($tauxImpuretes <= 20) {
            return -(min($tauxImpuretes, 20) - 12); // -1 FCFA par point (max 20%)
        } else {
            return -8; // Limite maximale
        }
    }

    /**
     * Calculer bonus humidité
     */
    private function calculerBonusHumidite($tauxHumidite, $aSechoir)
    {
        if ($tauxHumidite <= 8) {
            return $aSechoir ? 2 : 3; // +2 si séchoir, +3 sinon
        } elseif ($tauxHumidite <= 13) {
            return 0; // Neutre
        } elseif ($tauxHumidite <= 20) {
            return -(min($tauxHumidite, 20) - 13); // -1 FCFA par point (max 20%)
        } else {
            return -7; // Limite maximale
        }
    }

    /**
     * Calculer coût transport
     */
    private function calculerCoutTransport(TicketPesee $ticketPesee)
    {
        // Distance coopérative → COTRAF (à implémenter selon tes besoins)
        $distance = $this->calculerDistance($ticketPesee);
        
        if ($distance <= 100) return 14;
        elseif ($distance <= 200) return 15;
        elseif ($distance <= 300) return 16;
        elseif ($distance <= 400) return 22;
        elseif ($distance <= 500) return 22;
        elseif ($distance <= 600) return 23;
        else return 25;
    }

    /**
     * Calculer la part FPH-CI (cachée)
     */
    private function calculerPartFphci(TicketPesee $ticketPesee, $bonusQualite)
    {
        $partBase = 20; // Part FPH-CI de base
        
        // Bonus FPH-CI UNIQUEMENT si la qualité est excellente (bonus positifs)
        if ($bonusQualite > 0) {
            $aSechoir = $this->cooperativeAvecSechoir($ticketPesee);
            if ($aSechoir) {
                $partBase += 2; // +2 FCFA si séchoir ET qualité excellente
            } else {
                $partBase += 3; // +3 FCFA si pas de séchoir ET qualité excellente
            }
        }
        // Si bonusQualite <= 0 (qualité moyenne ou mauvaise), pas de bonus FPH-CI
        
        return $partBase;
    }

    /**
     * Vérifier si la coopérative a un séchoir
     */
    private function cooperativeAvecSechoir(TicketPesee $ticketPesee)
    {
        // Récupérer la valeur du champ a_sechoir de la coopérative
        return $ticketPesee->connaissement->cooperative->a_sechoir ?? false;
    }

    /**
     * Calculer la distance (utilise la distance vers le centre de collecte)
     */
    private function calculerDistance(TicketPesee $ticketPesee)
    {
        // Récupérer le centre de collecte du connaissement
        $centreCollecte = $ticketPesee->connaissement->centreCollecte;
        $cooperative = $ticketPesee->connaissement->cooperative;
        
        if ($centreCollecte && $cooperative) {
            // Récupérer la distance spécifique vers ce centre
            $distance = $cooperative->getDistanceToCentre($centreCollecte->id);
            
            if ($distance && $distance > 0) {
                return $distance;
            }
        }
        
        // Si pas de distance spécifique, utiliser une distance par défaut
        return 150;
    }

    /**
     * Obtenir les détails du calcul
     */
    private function getDetailsCalcul($ticketPesee, $prixBase, $bonusQualite, $coutTransport, $partFphci)
    {
        $prixFinalPublic = $prixBase + $bonusQualite + $coutTransport;
        $montantPublic = $prixFinalPublic * $ticketPesee->poids_net;
        $montantPrive = $partFphci * $ticketPesee->poids_net;
        
        // Calculer les bonus individuels
        $bonusImpuretes = $this->calculerBonusImpuretes($ticketPesee->taux_impuretes);
        $bonusHumidite = $this->calculerBonusHumidite($ticketPesee->taux_humidite, $this->cooperativeAvecSechoir($ticketPesee));
        
        return [
            'prix_base' => $prixBase,
            'bonus_qualite' => $bonusQualite,
            'bonus_impuretes' => $bonusImpuretes,
            'bonus_humidite' => $bonusHumidite,
            'cout_transport' => $coutTransport,
            'prix_final_public' => $prixFinalPublic,
            'montant_public' => $montantPublic,
            'montant_prive' => $montantPrive,
            'part_fphci' => $partFphci,
            'a_sechoir' => $this->cooperativeAvecSechoir($ticketPesee),
            'distance' => $this->calculerDistance($ticketPesee),
            'centre_collecte' => $ticketPesee->connaissement->centreCollecte->nom ?? 'Non défini'
        ];
    }
} 