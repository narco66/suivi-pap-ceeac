<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Enregistrer une action dans le journal d'audit
     */
    public function log(
        string $action,
        ?Model $object = null,
        ?array $metadata = null,
        ?string $module = null,
        ?string $description = null
    ): AuditLog {
        $actorId = Auth::id();
        $request = request();

        return AuditLog::create([
            'actor_id' => $actorId,
            'action' => $action,
            'object_type' => $object ? get_class($object) : null,
            'object_id' => $object?->id,
            'metadata' => $metadata,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'module' => $module ?? $this->detectModule(),
            'description' => $description ?? $this->generateDescription($action, $object),
        ]);
    }

    /**
     * Détecter le module depuis la route
     */
    protected function detectModule(): ?string
    {
        $route = request()->route();
        
        if (!$route) {
            return null;
        }

        $name = $route->getName();
        
        if (str_starts_with($name, 'admin.')) {
            return 'admin';
        }
        
        if (str_contains($name, 'papa')) {
            return 'papa';
        }
        
        if (str_contains($name, 'objectif')) {
            return 'objectif';
        }
        
        if (str_contains($name, 'action')) {
            return 'action';
        }
        
        if (str_contains($name, 'tache')) {
            return 'tache';
        }
        
        return 'other';
    }

    /**
     * Générer une description automatique
     */
    protected function generateDescription(string $action, ?Model $object): string
    {
        $actionLabels = [
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            'viewed' => 'Consultation',
            'exported' => 'Export',
            'imported' => 'Import',
            'activated' => 'Activation',
            'deactivated' => 'Désactivation',
        ];

        $label = $actionLabels[$action] ?? ucfirst($action);
        $objectName = $object ? class_basename($object) : 'élément';

        return "{$label} de {$objectName}";
    }
}


