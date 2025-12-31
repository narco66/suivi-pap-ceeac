<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPrioritaire extends Model
{
    use HasFactory;
    
    protected $table = 'actions_prioritaires';
    
    protected $fillable = [
        'objectif_id',
        'code',
        'libelle',
        'description',
        'type',
        'direction_technique_id',
        'direction_appui_id',
        'statut',
        'priorite',
        'criticite',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'pourcentage_avancement',
        'bloque',
        'raison_blocage',
    ];
    
    protected $casts = [
        'bloque' => 'boolean',
        'date_debut_prevue' => 'datetime',
        'date_fin_prevue' => 'datetime',
        'date_debut_reelle' => 'datetime',
        'date_fin_reelle' => 'datetime',
        'pourcentage_avancement' => 'integer',
    ];
    
    public function objectif()
    {
        return $this->belongsTo(Objectif::class);
    }
    
    public function taches()
    {
        return $this->hasMany(Tache::class);
    }
    
    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
    
    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }
}
