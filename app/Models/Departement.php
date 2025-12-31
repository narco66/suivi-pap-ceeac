<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code',
        'libelle',
        'description',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];

    public function directionsTechniques()
    {
        return $this->hasMany(DirectionTechnique::class);
    }
}
