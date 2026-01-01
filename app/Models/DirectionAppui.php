<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectionAppui extends Model
{
    use HasFactory;
    
    protected $table = 'directions_appui';
    
    protected $fillable = [
        'code',
        'libelle',
        'description',
        'actif',
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Relation avec les actions prioritaires
     */
    public function actionsPrioritaires()
    {
        return $this->hasMany(ActionPrioritaire::class, 'direction_appui_id');
    }

    /**
     * Scope pour récupérer uniquement les directions d'appui actives
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Vérifier si la direction est active
     */
    public function isActive(): bool
    {
        return $this->actif === true;
    }
}
