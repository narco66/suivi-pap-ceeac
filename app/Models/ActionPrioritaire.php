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

    /**
     * Relation avec la direction technique
     */
    public function directionTechnique()
    {
        return $this->belongsTo(DirectionTechnique::class);
    }

    /**
     * Relation avec le département (via direction technique)
     */
    public function departement()
    {
        return $this->hasOneThrough(
            Departement::class,
            DirectionTechnique::class,
            'id', // Foreign key on directions_techniques
            'id', // Foreign key on departements
            'direction_technique_id', // Local key on actions_prioritaires
            'departement_id' // Local key on directions_techniques
        );
    }

    /**
     * Scope pour filtrer les actions par département
     */
    public function scopeForDepartment($query, ?int $departmentId)
    {
        if ($departmentId === null) {
            return $query->whereNull('direction_technique_id');
        }

        return $query->whereHas('directionTechnique', function ($q) use ($departmentId) {
            $q->where('departement_id', $departmentId);
        });
    }

    /**
     * Récupérer l'ID du département de l'action
     */
    public function getDepartmentId(): ?int
    {
        return $this->directionTechnique?->departement_id;
    }

    /**
     * Relation avec la direction d'appui
     */
    public function directionAppui()
    {
        return $this->belongsTo(DirectionAppui::class);
    }

    /**
     * Vérifier si l'action est une action d'appui
     */
    public function isAppui(): bool
    {
        return $this->direction_appui_id !== null;
    }

    /**
     * Vérifier si l'action est une action technique
     */
    public function isTechnique(): bool
    {
        return $this->direction_technique_id !== null;
    }

    /**
     * Scope pour filtrer les actions d'appui uniquement
     */
    public function scopeForAppui($query)
    {
        return $query->whereNotNull('direction_appui_id');
    }

    /**
     * Scope pour filtrer les actions techniques uniquement
     */
    public function scopeForTechnique($query)
    {
        return $query->whereNotNull('direction_technique_id');
    }
}
