<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commissaire extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'prenom',
        'titre',
        'commission_id',
        'pays_origine',
        'date_nomination',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
        'date_nomination' => 'date',
    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }
}
