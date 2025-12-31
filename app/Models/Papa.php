<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Papa extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'libelle',
        'annee',
        'description',
        'statut',
        'date_debut',
        'date_fin',
    ];
    
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
    
    public function versions()
    {
        return $this->hasMany(PapaVersion::class);
    }
}
