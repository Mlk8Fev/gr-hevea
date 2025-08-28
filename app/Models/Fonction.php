<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'peut_gerer_cooperative',
        'niveau_acces'
    ];

    protected $casts = [
        'peut_gerer_cooperative' => 'boolean'
    ];

    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope pour les fonctions qui peuvent gérer des coopératives
     */
    public function scopePeutGererCooperative($query)
    {
        return $query->where('peut_gerer_cooperative', true);
    }

    /**
     * Scope pour les fonctions admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('niveau_acces', 'admin');
    }

    /**
     * Scope pour les fonctions manager
     */
    public function scopeManager($query)
    {
        return $query->where('niveau_acces', 'manager');
    }
}
