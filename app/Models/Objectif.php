<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'papa_version_id',
        'code',
        'libelle',
        'description',
        'statut',
        'priorite',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'pourcentage_avancement',
    ];
    
    protected $casts = [
        'date_debut_prevue' => 'datetime',
        'date_fin_prevue' => 'datetime',
        'date_debut_reelle' => 'datetime',
        'date_fin_reelle' => 'datetime',
        'pourcentage_avancement' => 'integer',
    ];
    
    public function papaVersion()
    {
        return $this->belongsTo(PapaVersion::class);
    }
    
    public function actionsPrioritaires()
    {
        return $this->hasMany(ActionPrioritaire::class);
    }

    /**
     * Scope pour filtrer les objectifs par département
     * Un objectif appartient à un département s'il a au moins une action prioritaire
     * liée à une direction technique de ce département
     */
    public function scopeForDepartment($query, ?int $departmentId)
    {
        if ($departmentId === null) {
            return $query->whereDoesntHave('actionsPrioritaires.directionTechnique');
        }

        return $query->whereHas('actionsPrioritaires', function ($q) use ($departmentId) {
            $q->forDepartment($departmentId);
        });
    }

    /**
     * Vérifier si l'objectif a des actions dans un département donné
     */
    public function hasActionsInDepartment(?int $departmentId): bool
    {
        if ($departmentId === null) {
            return false;
        }

        return $this->actionsPrioritaires()
            ->forDepartment($departmentId)
            ->exists();
    }
}
