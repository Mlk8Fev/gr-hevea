<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_facture',
        'type',
        'statut',
        'cooperative_id',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_paye',
        'date_emission',
        'date_echeance',
        'date_paiement',
        'conditions_paiement',
        'notes',
        'devise',
        'created_by',
        'validee_par',
        'date_validation'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'date_validation' => 'datetime',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'montant_paye' => 'decimal:2'
    ];

    /**
     * Relation avec la coopérative
     */
    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec l'utilisateur qui a créé la facture
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation avec l'utilisateur qui a validé la facture
     */
    public function valideePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    /**
     * Relation many-to-many avec les tickets de pesée
     */
    public function ticketsPesee(): BelongsToMany
    {
        return $this->belongsToMany(TicketPesee::class, 'facture_ticket_pesee')
                    ->withPivot('montant_ticket')
                    ->withTimestamps();
    }

    /**
     * Relation avec la table pivot pour accéder aux montants
     */
    public function factureTicketsPesee(): HasMany
    {
        return $this->hasMany(FactureTicketPesee::class);
    }

    /**
     * Scope pour les factures individuelles
     */
    public function scopeIndividuelles($query)
    {
        return $query->where('type', 'individuelle');
    }

    /**
     * Scope pour les factures globales
     */
    public function scopeGlobales($query)
    {
        return $query->where('type', 'globale');
    }

    /**
     * Scope pour les factures en brouillon
     */
    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }

    /**
     * Scope pour les factures validées
     */
    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    /**
     * Scope pour les factures payées
     */
    public function scopePayees($query)
    {
        return $query->where('statut', 'payee');
    }

    /**
     * Scope pour les factures en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('date_echeance', '<', now())
                    ->where('statut', '!=', 'payee');
    }

    /**
     * Vérifier si la facture est en retard
     */
    public function isEnRetard(): bool
    {
        return $this->date_echeance < now() && $this->statut !== 'payee';
    }

    /**
     * Vérifier si la facture peut être validée
     */
    public function canBeValidated(): bool
    {
        return $this->statut === 'brouillon';
    }

    /**
     * Vérifier si la facture peut être payée
     */
    public function canBePaid(): bool
    {
        return $this->statut === 'validee';
    }

    /**
     * Calculer le montant restant à payer
     */
    public function getMontantRestantAttribute(): float
    {
        return $this->montant_ttc - $this->montant_paye;
    }

    /**
     * Générer le numéro de facture suivant
     */
    public static function generateNumeroFacture(): string
    {
        $derniereFacture = self::orderBy('id', 'desc')->first();
        
        if ($derniereFacture) {
            $numero = (int) substr($derniereFacture->numero_facture, 4); // Enlever "FACT"
            $numero++;
        } else {
            $numero = 1;
        }
        
        return 'FACT' . $numero;
    }
}
