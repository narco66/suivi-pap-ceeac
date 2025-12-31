<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Tache extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'action_prioritaire_id',
        'tache_parent_id',
        'code',
        'libelle',
        'description',
        'statut',
        'priorite',
        'criticite',
        'date_debut_prevue',
        'date_fin_prevue',
        'date_debut_reelle',
        'date_fin_reelle',
        'pourcentage_avancement',
        'responsable_id',
        'bloque',
        'raison_blocage',
        'est_jalon',
        // Champs Gantt
        'baseline_start',
        'baseline_end',
        'gantt_color',
        'gantt_sort_order',
        'is_critical',
        'gantt_notes',
    ];
    
    protected $casts = [
        'bloque' => 'boolean',
        'est_jalon' => 'boolean',
        'date_debut_prevue' => 'datetime',
        'date_fin_prevue' => 'datetime',
        'date_debut_reelle' => 'datetime',
        'date_fin_reelle' => 'datetime',
        'pourcentage_avancement' => 'integer',
        // Champs Gantt
        'baseline_start' => 'datetime',
        'baseline_end' => 'datetime',
        'gantt_sort_order' => 'integer',
        'is_critical' => 'boolean',
    ];
    
    public function actionPrioritaire()
    {
        return $this->belongsTo(ActionPrioritaire::class);
    }
    
    public function tacheParent()
    {
        return $this->belongsTo(Tache::class, 'tache_parent_id');
    }
    
    public function sousTaches()
    {
        return $this->hasMany(Tache::class, 'tache_parent_id');
    }
    
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    
    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
    
    public function avancements()
    {
        return $this->hasMany(Avancement::class);
    }

    /**
     * Dépendances Gantt : tâches dont dépend cette tâche
     */
    public function dependencies()
    {
        return $this->hasMany(GanttDependency::class, 'task_id');
    }

    /**
     * Tâches qui dépendent de cette tâche
     */
    public function dependentTasks()
    {
        return $this->hasMany(GanttDependency::class, 'depends_on_task_id');
    }

    /**
     * Logs d'audit Gantt
     */
    public function ganttAuditLogs()
    {
        return $this->hasMany(GanttAuditLog::class, 'task_id');
    }
}
