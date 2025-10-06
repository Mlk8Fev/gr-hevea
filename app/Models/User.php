<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Auditable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'fonction_id',
        'cooperative_id',
        'centre_collecte_id',
        'role',
        'secteur',
        'siege',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'siege' => 'boolean',
            'peut_gerer_cooperative' => 'boolean'
        ];
    }

    // Méthodes pour vérifier les rôles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Méthode pour vérifier si l'utilisateur est du siège
    public function isSiege(): bool
    {
        return $this->siege === true;
    }

    // Méthode pour vérifier si l'utilisateur est actif
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // Méthode pour vérifier si l'utilisateur est inactif
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    // Méthode pour obtenir le nom complet
    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Relation avec le secteur
     */
    public function secteurRelation()
    {
        return $this->belongsTo(Secteur::class, 'secteur', 'code');
    }

    /**
     * Relation avec la fonction
     */
    public function fonction()
    {
        return $this->belongsTo(Fonction::class);
    }

    /**
     * Relation avec la coopérative
     */
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec le centre de collecte
     */
    public function centreCollecte()
    {
        return $this->belongsTo(CentreCollecte::class);
    }

    /**
     * Vérifier si l'utilisateur peut gérer une coopérative
     */
    public function peutGererCooperative(): bool
    {
        return $this->fonction && $this->fonction->peut_gerer_cooperative;
    }

    /**
     * Méthode pour vérifier le niveau d'accès
     */
    public function hasNiveauAcces($niveau): bool
    {
        return $this->fonction && $this->fonction->niveau_acces === $niveau;
    }
}
