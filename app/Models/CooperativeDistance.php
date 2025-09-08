<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CooperativeDistance extends Model
{
    protected $fillable = [
        'cooperative_id',
        'centre_collecte_id',
        'distance_km'
    ];

    protected $casts = [
        'distance_km' => 'decimal:2'
    ];

    /**
     * Relation avec la coopérative
     */
    public function cooperative(): BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    /**
     * Relation avec le centre de collecte
     */
    public function centreCollecte(): BelongsTo
    {
        return $this->belongsTo(CentreCollecte::class);
    }
}
