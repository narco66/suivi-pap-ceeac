<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class GanttTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Dates prévues
        $startDate = $this->resource->date_debut_prevue 
            ? Carbon::parse($this->resource->date_debut_prevue)->format('Y-m-d')
            : Carbon::now()->format('Y-m-d');
        
        $endDate = $this->resource->date_fin_prevue 
            ? Carbon::parse($this->resource->date_fin_prevue)->format('Y-m-d')
            : Carbon::now()->addDay()->format('Y-m-d');
        
        // Calculer la durée en jours
        $duration = max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));
        
        // Si c'est un jalon, durée = 0
        if ($this->resource->est_jalon) {
            $duration = 0;
        }

        // Récupérer les dépendances (utiliser la relation déjà chargée si disponible)
        $dependencies = [];
        if ($this->resource->relationLoaded('dependencies')) {
            $dependencies = $this->resource->dependencies
                ->map(function ($dependency) {
                    return (string) $dependency->depends_on_task_id;
                })
                ->toArray();
        } else {
            // Fallback si la relation n'est pas chargée
            $dependencies = $this->resource->dependencies()
                ->get()
                ->map(function ($dependency) {
                    return (string) $dependency->depends_on_task_id;
                })
                ->toArray();
        }

        // Déterminer le type
        $type = 'task';
        if ($this->resource->est_jalon) {
            $type = 'milestone';
        } elseif ($this->resource->tache_parent_id === null && $this->resource->actionPrioritaire) {
            // Tâche principale d'une action = phase
            $type = 'phase';
        }

        return [
            'id' => (string) $this->resource->id,
            'name' => $this->resource->code . ' - ' . $this->resource->libelle,
            'start' => $startDate,
            'end' => $endDate,
            'duration' => $duration,
            'progress' => ($this->resource->pourcentage_avancement ?? 0) / 100,
            'dependencies' => $dependencies,
            'responsible' => $this->resource->responsable ? $this->resource->responsable->name : null,
            'responsible_id' => $this->resource->responsable_id,
            'type' => $type,
            'color' => $this->resource->gantt_color ?? $this->getColorByCriticite(),
            'critical' => $this->resource->is_critical ?? false,
            'parent' => $this->resource->tache_parent_id ? (string) $this->resource->tache_parent_id : 0,
            // Données additionnelles
            'statut' => $this->resource->statut,
            'priorite' => $this->resource->priorite,
            'criticite' => $this->resource->criticite,
            'description' => $this->resource->description,
            // Baseline pour comparaison (Phase 3)
            'baseline_start' => $this->resource->baseline_start ? Carbon::parse($this->resource->baseline_start)->format('Y-m-d') : null,
            'baseline_end' => $this->resource->baseline_end ? Carbon::parse($this->resource->baseline_end)->format('Y-m-d') : null,
            // Actual dates (Phase 3)
            'actual_start' => $this->resource->date_debut_reelle ? Carbon::parse($this->resource->date_debut_reelle)->format('Y-m-d') : null,
            'actual_end' => $this->resource->date_fin_reelle ? Carbon::parse($this->resource->date_fin_reelle)->format('Y-m-d') : null,
        ];
    }

    /**
     * Obtenir la couleur selon la criticité
     */
    private function getColorByCriticite(): string
    {
        return match($this->resource->criticite ?? 'normal') {
            'critique' => '#dc3545', // Rouge
            'vigilance' => '#ffc107', // Jaune/Orange
            'haute' => '#fd7e14', // Orange
            default => '#0d6efd', // Bleu (normal)
        };
    }
}
