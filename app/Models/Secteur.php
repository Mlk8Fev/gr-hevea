<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom'
    ];

    /**
     * Relation avec les utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class, 'secteur', 'code');
    }

    /**
     * Relation avec les coopératives
     */
    public function cooperatives()
    {
        return $this->hasMany(Cooperative::class, 'secteur_id');
    }

    /**
     * Relation avec les producteurs
     */
    public function producteurs()
    {
        return $this->hasMany(Producteur::class, 'secteur_id');
    }
}
