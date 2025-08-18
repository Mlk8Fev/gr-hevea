<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentreCollecte extends Model
{
    use HasFactory;

    protected $table = 'centres_collecte';

    protected $fillable = [
        'code',
        'nom',
        'adresse',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string'
    ];

    // Relations
    public function connaissements()
    {
        return $this->hasMany(Connaissement::class);
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
}
