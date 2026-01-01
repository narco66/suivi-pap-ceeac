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
        'commissioner_user_id',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Relation avec les directions techniques
     */
    public function directionsTechniques()
    {
        return $this->hasMany(DirectionTechnique::class);
    }

    /**
     * Relation 1-1 avec le Commissaire (User)
     * Un département est dirigé par UN seul commissaire
     */
    public function commissaire()
    {
        return $this->belongsTo(User::class, 'commissioner_user_id');
    }

    /**
     * Vérifier si le département a un commissaire assigné
     */
    public function hasCommissaire(): bool
    {
        return $this->commissioner_user_id !== null;
    }

    /**
     * Scope pour récupérer les départements avec commissaire
     */
    public function scopeWithCommissaire($query)
    {
        return $query->whereNotNull('commissioner_user_id');
    }

    /**
     * Scope pour récupérer les départements sans commissaire
     */
    public function scopeWithoutCommissaire($query)
    {
        return $query->whereNull('commissioner_user_id');
    }
}
