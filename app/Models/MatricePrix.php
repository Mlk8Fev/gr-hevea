<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatricePrix extends Model
{
    use HasFactory;

    protected $table = 'matrice_prix';
    
    protected $fillable = [
        'annee',
        'prix_bord_champs',
        'transport_base',
        'cooperatives',
        'stockage',
        'chargeur_dechargeur',
        'soutien_sechoirs',
        'prodev',
        'sac',
        'sechage',
        'certification',
        'fphci',
        'moyenne_transfert',
        'active'
    ];

    protected $casts = [
        'prix_bord_champs' => 'decimal:2',
        'transport_base' => 'decimal:2',
        'cooperatives' => 'decimal:2',
        'stockage' => 'decimal:2',
        'chargeur_dechargeur' => 'decimal:2',
        'soutien_sechoirs' => 'decimal:2',
        'prodev' => 'decimal:2',
        'sac' => 'decimal:2',
        'sechage' => 'decimal:2',
        'certification' => 'decimal:2',
        'fphci' => 'decimal:2',
        'moyenne_transfert' => 'decimal:2',
        'active' => 'boolean'
    ];

    /**
     * Obtenir la matrice active pour une année donnée
     */
    public static function getActiveForYear($annee = null)
    {
        if (!$annee) {
            $annee = date('Y');
        }
        
        return static::where('annee', $annee)
                    ->where('active', true)
                    ->first();
    }

    /**
     * Calculer le prix total de base
     */
    public function getPrixTotalAttribute()
    {
        return $this->prix_bord_champs + 
               $this->transport_base + 
               $this->cooperatives + 
               $this->stockage + 
               $this->chargeur_dechargeur + 
               $this->soutien_sechoirs + 
               $this->prodev + 
               $this->sac + 
               $this->sechage + 
               $this->certification + 
               $this->fphci + 
               $this->moyenne_transfert;
    }

    /**
     * Calculer le prix de base (sans transfert)
     */
    public function getPrixBaseAttribute()
    {
        return $this->prix_bord_champs + 
               $this->transport_base + 
               $this->cooperatives + 
               $this->stockage + 
               $this->chargeur_dechargeur + 
               $this->soutien_sechoirs + 
               $this->prodev + 
               $this->sac + 
               $this->sechage + 
               $this->certification + 
               $this->fphci;
    }
}
