<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cooperative extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'secteur_id',
        'president',
        'contact',
        'sigle',
        'latitude',
        'longitude',
        'kilometrage',
        'a_sechoir',
        'compte_bancaire',
        'code_banque',
        'code_guichet',
        'nom_cooperative_banque',
    ];

    protected $casts = [
        'a_sechoir' => 'boolean',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'kilometrage' => 'float',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function documents()
    {
        return $this->hasMany(CooperativeDocument::class);
    }

    public function producteurs()
    {
        return $this->belongsToMany(Producteur::class);
    }

    /**
     * Relation avec les connaissements
     */
    public function connaissements(): HasMany
    {
        return $this->hasMany(Connaissement::class);
    }

    /**
     * Relation avec les tickets de pesée via les connaissements
     */
    public function ticketsPesee()
    {
        return $this->hasManyThrough(TicketPesee::class, Connaissement::class);
    }

    /**
     * Relation avec les factures
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    /**
     * Relation avec les distances vers les centres de collecte
     */
    public function distances(): HasMany
    {
        return $this->hasMany(CooperativeDistance::class);
    }

    /**
     * Récupérer la distance vers un centre de collecte spécifique
     */
    public function getDistanceToCentre($centreCollecteId): ?float
    {
        $distance = $this->distances()
            ->where('centre_collecte_id', $centreCollecteId)
            ->first();
            
        return $distance ? $distance->distance_km : null;
    }
}
