<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CooperativeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'cooperative_id',
        'type',
        'fichier',
    ];

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }
}
