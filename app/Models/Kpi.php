<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'action_prioritaire_id',
        'code',
        'libelle',
        'description',
        'unite',
        'valeur_cible',
        'valeur_realisee',
        'valeur_ecart',
        'pourcentage_realisation',
        'date_mesure',
        'statut',
    ];
    
    protected $casts = [
        'valeur_cible' => 'decimal:2',
        'valeur_realisee' => 'decimal:2',
        'valeur_ecart' => 'decimal:2',
        'pourcentage_realisation' => 'decimal:2',
        'date_mesure' => 'datetime',
    ];
    
    public function actionPrioritaire()
    {
        return $this->belongsTo(ActionPrioritaire::class);
    }
    
    public function alertes()
    {
        return $this->hasMany(Alerte::class, 'action_prioritaire_id', 'action_prioritaire_id')
            ->where('type', 'kpi_non_atteint');
    }
}
