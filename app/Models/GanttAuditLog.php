<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GanttAuditLog extends Model
{
    use HasFactory;

    protected $table = 'gantt_audit_logs';

    protected $fillable = [
        'user_id',
        'task_id',
        'action',
        'field_name',
        'old_value',
        'new_value',
        'requires_approval',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'requires_approval' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Utilisateur qui a effectué l'action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tâche concernée
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Tache::class, 'task_id');
    }

    /**
     * Utilisateur qui a approuvé (si applicable)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Actions disponibles
     */
    public static function getActions(): array
    {
        return [
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'reschedule' => 'Réplanification',
            'dependency_add' => 'Ajout dépendance',
            'dependency_remove' => 'Suppression dépendance',
            'progress_update' => 'Mise à jour progression',
        ];
    }
}
