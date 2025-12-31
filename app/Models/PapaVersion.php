<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PapaVersion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'papa_id',
        'numero',
        'libelle',
        'description',
        'statut',
        'date_creation',
        'date_verrouillage',
        'verrouille',
    ];
    
    protected $casts = [
        'verrouille' => 'boolean',
        'date_creation' => 'datetime',
        'date_verrouillage' => 'datetime',
    ];
    
    public function papa()
    {
        return $this->belongsTo(Papa::class);
    }
    
    public function objectifs()
    {
        return $this->hasMany(Objectif::class);
    }
}
