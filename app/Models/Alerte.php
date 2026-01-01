<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Alerte extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type',
        'titre',
        'message',
        'criticite',
        'statut',
        'tache_id',
        'action_prioritaire_id',
        'niveau_escalade',
        'cree_par_id',
        'assignee_a_id',
        'date_creation',
        'date_resolution',
    ];
    
    protected $casts = [
        'date_creation' => 'datetime',
        'date_resolution' => 'datetime',
    ];
    
    public function tache()
    {
        return $this->belongsTo(Tache::class);
    }
    
    public function actionPrioritaire()
    {
        return $this->belongsTo(ActionPrioritaire::class);
    }
    
    public function creePar()
    {
        return $this->belongsTo(User::class, 'cree_par_id');
    }
    
    public function assigneeA()
    {
        return $this->belongsTo(User::class, 'assignee_a_id');
    }

    /**
     * Relation avec le département (via action prioritaire ou tâche)
     * Utilise une relation indirecte
     */
    public function departement()
    {
        // Si l'alerte est liée à une action prioritaire
        if ($this->action_prioritaire_id && $this->actionPrioritaire) {
            return $this->actionPrioritaire->departement;
        }

        // Si l'alerte est liée à une tâche
        if ($this->tache_id && $this->tache) {
            return $this->tache->departement;
        }

        return null;
    }

    /**
     * Scope pour filtrer les alertes par département
     */
    public function scopeForDepartment($query, ?int $departmentId)
    {
        if ($departmentId === null) {
            return $query->where(function ($q) {
                $q->whereDoesntHave('actionPrioritaire.directionTechnique')
                  ->orWhereDoesntHave('tache.actionPrioritaire.directionTechnique');
            });
        }

        return $query->where(function ($q) use ($departmentId) {
            $q->whereHas('actionPrioritaire.directionTechnique', function ($subQ) use ($departmentId) {
                $subQ->where('departement_id', $departmentId);
            })->orWhereHas('tache.actionPrioritaire.directionTechnique', function ($subQ) use ($departmentId) {
                $subQ->where('departement_id', $departmentId);
            });
        });
    }

    /**
     * Récupérer l'ID du département de l'alerte
     */
    public function getDepartmentId(): ?int
    {
        if ($this->action_prioritaire_id) {
            return $this->actionPrioritaire?->getDepartmentId();
        }

        if ($this->tache_id) {
            return $this->tache?->getDepartmentId();
        }

        return null;
    }

    /**
     * Vérifier si l'alerte est liée à une action d'appui
     */
    public function isAppui(): bool
    {
        if ($this->action_prioritaire_id && $this->actionPrioritaire) {
            return $this->actionPrioritaire->isAppui();
        }

        if ($this->tache_id && $this->tache) {
            return $this->tache->isAppui();
        }

        return false;
    }

    /**
     * Scope pour filtrer les alertes d'appui uniquement
     */
    public function scopeForAppui($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('actionPrioritaire', function ($subQ) {
                $subQ->whereNotNull('direction_appui_id');
            })->orWhereHas('tache.actionPrioritaire', function ($subQ) {
                $subQ->whereNotNull('direction_appui_id');
            });
        });
    }
}
