<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Auditable;

class Connaissement extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'numero_livraison',
        'secteur_id',
        'statut',
        'cooperative_id',
        'centre_collecte_id',
        'lieu_depart',
        'sous_prefecture',
        'transporteur_nom',
        'transporteur_immatriculation',
        'chauffeur_nom',
        'destinataire_type',
        'destinataire_id',
        'nombre_sacs',
        'poids_brut_estime',
        'poids_net',
        'signature_cooperative',
        'signature_fphci',
        'date_validation',
        'created_by',
        'validated_by',
        'date_reception',
        'heure_arrivee',
        'programmed_by',
        'date_programmation',
        'poids_net_reel',
        'date_validation_reelle'
    ];

    protected $casts = [
        'statut' => 'string',
        'destinataire_type' => 'string',
        'poids_brut_estime' => 'decimal:2',
        'poids_net' => 'decimal:2',
        'date_validation' => 'datetime',
        'date_reception' => 'date',
        'heure_arrivee' => 'datetime',
        'date_programmation' => 'datetime',
        'poids_net_reel' => 'decimal:2',
        'date_validation_reelle' => 'datetime'
    ];

    // Relations
    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function centreCollecte()
    {
        return $this->belongsTo(CentreCollecte::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function programmedBy()
    {
        return $this->belongsTo(User::class, 'programmed_by');
    }

    public function ticketsPesee()
    {
        return $this->hasMany(TicketPesee::class);
    }

    public function ticketPesee()
    {
        return $this->hasOne(TicketPesee::class);
    }

    // Scopes
    public function scopeProgramme($query)
    {
        return $query->where('statut', 'programme');
    }

    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    // Méthodes
    public function isProgramme()
    {
        return $this->statut === 'programme';
    }

    public function isValide()
    {
        return $this->statut === 'valide';
    }
}
