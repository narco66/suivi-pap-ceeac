<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GanttDependency extends Model
{
    use HasFactory;

    protected $table = 'gantt_dependencies';

    protected $fillable = [
        'task_id',
        'depends_on_task_id',
        'dependency_type',
        'lag_days',
    ];

    protected $casts = [
        'lag_days' => 'integer',
    ];

    /**
     * Tâche qui dépend
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'task_id');
    }

    /**
     * Tâche dont dépend la tâche actuelle
     */
    public function dependsOnTask(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'depends_on_task_id');
    }

    /**
     * Types de dépendances disponibles
     */
    public static function getDependencyTypes(): array
    {
        return [
            'FS' => 'Finish-to-Start (Fin → Début)',
            'SS' => 'Start-to-Start (Début → Début)',
            'FF' => 'Finish-to-Finish (Fin → Fin)',
            'SF' => 'Start-to-Finish (Début → Fin)',
        ];
    }
}
