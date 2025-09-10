<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerList extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_livraison',
        'connaissement_id',
        'producteur_id',
        'quantite_livree',
        'nombre_sacs', // Ajouter ce champ
        'geolocalisation_precise',
        'date_livraison',
        'contact_producteur',
        'code_producteur',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'quantite_livree' => 'decimal:2',
        'geolocalisation_precise' => 'boolean',
        'date_livraison' => 'date'
    ];

    /**
     * Relation avec le connaissement
     */
    public function connaissement(): BelongsTo
    {
        return $this->belongsTo(Connaissement::class);
    }

    /**
     * Relation avec le producteur
     */
    public function producteur(): BelongsTo
    {
        return $this->belongsTo(Producteur::class);
    }

    /**
     * Relation avec l'utilisateur créateur
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculer le poids total de la farmer list
     */
    public static function getPoidsTotal($connaissementId): float
    {
        return self::where('connaissement_id', $connaissementId)
            ->sum('quantite_livree');
    }

    /**
     * Calculer le total des sacs pour une livraison
     */
    public static function getSacsTotal($connaissementId)
    {
        return self::where('connaissement_id', $connaissementId)
            ->sum('nombre_sacs');
    }

    /**
     * Vérifier si la farmer list est complète
     */
    public static function isComplete($connaissementId, $poidsNetLivraison): bool
    {
        $poidsTotal = self::getPoidsTotal($connaissementId);
        return abs($poidsTotal - $poidsNetLivraison) < 0.01; // Tolérance de 0.01 kg
    }
}
