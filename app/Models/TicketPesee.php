<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TicketPesee extends Model
{
    use HasFactory;

    protected $table = 'tickets_pesee';

    protected $fillable = [
        'numero_livraison',
        'connaissement_id',
        'numero_ticket',
        'campagne',
        'client',
        'fournisseur',
        'numero_bl',
        'origine',
        'destination',
        'produit',
        'numero_camion',
        'transporteur',
        'chauffeur',
        'equipe_chargement',
        'equipe_dechargement',
        'poids_entree',
        'poids_sortie',
        'poids_net',
        'nombre_sacs_bidons_cartons',
        'poids_100_graines',
        'gp',
        'ga',
        'me',
        'taux_humidite',
        'taux_impuretes',
        'date_entree',
        'heure_entree',
        'date_sortie',
        'heure_sortie',
        'nom_peseur',
        'signature',
        'statut',
        'statut_ene',
        'created_by',
        'validated_by',
        'date_validation',
        'valide_par_ene',
        'date_validation_ene',
        'commentaire_ene'
    ];

    protected $casts = [
        'poids_entree' => 'decimal:2',
        'poids_sortie' => 'decimal:2',
        'poids_net' => 'decimal:2',
        'poids_100_graines' => 'decimal:2',
        'gp' => 'decimal:2',
        'ga' => 'decimal:2',
        'me' => 'decimal:2',
        'taux_humidite' => 'decimal:2',
        'taux_impuretes' => 'decimal:2',
        'date_entree' => 'date',
        'date_sortie' => 'date',
        'heure_entree' => 'datetime',
        'heure_sortie' => 'datetime',
        'date_validation' => 'datetime',
        'date_validation_ene' => 'datetime'
    ];

    // Relations
    public function connaissement(): BelongsTo
    {
        return $this->belongsTo(Connaissement::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function valideParEne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par_ene');
    }

    public function factures(): BelongsToMany
    {
        return $this->belongsToMany(Facture::class, 'facture_ticket_pesee');
    }

    /**
     * Relation avec la coopérative via le connaissement
     */
    public function cooperative()
    {
        return $this->connaissement->cooperative();
    }

    // Scopes
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValideEne($query)
    {
        return $query->where('statut_ene', 'valide_par_ene');
    }

    // Méthodes
    public function isValide()
    {
        return $this->statut === 'valide';
    }

    public function isValideEne()
    {
        return $this->statut_ene === 'valide_par_ene';
    }

    public function canBeFactured()
    {
        return $this->isValide() && $this->isValideEne() && $this->factures()->count() === 0;
    }

    public function getPoidsNetAttribute($value)
    {
        if ($this->poids_entree && $this->poids_sortie) {
            return $this->poids_entree - $this->poids_sortie;
        }
        return $value;
    }
}
