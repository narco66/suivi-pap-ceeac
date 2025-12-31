<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anomalie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type',
        'titre',
        'description',
        'severite',
        'statut',
        'tache_id',
        'action_prioritaire_id',
        'date_detection',
        'date_correction',
    ];
    
    protected $casts = [
        'date_detection' => 'datetime',
        'date_correction' => 'datetime',
    ];
}
