<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GanttTaskResource;
use App\Models\Papa;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GanttApiController extends Controller
{
    /**
     * Récupérer les données Gantt pour un projet/version
     * GET /api/projects/{papa}/gantt?version_id=Y&objectif_id=Z&action_id=W
     */
    public function show(Request $request, Papa $papa)
    {
        // Autorisation
        $this->authorize('viewGantt');

        $versionId = $request->get('version_id');
        $objectifId = $request->get('objectif_id');
        $actionId = $request->get('action_id');
        
        try {
            // Construire la requête selon les filtres avec eager loading optimisé
            $query = Tache::with([
                'responsable:id,name',
                'dependencies:task_id,depends_on_task_id,dependency_type',
                'tacheParent:id,code,libelle',
                'sousTaches:id,tache_parent_id,code,libelle,date_debut_prevue,date_fin_prevue,est_jalon',
                'actionPrioritaire:id,code,libelle,objectif_id',
                'actionPrioritaire.objectif:id,code,libelle,papa_version_id',
            ])
            ->whereNotNull('date_debut_prevue')
            ->whereNotNull('date_fin_prevue')
            ->orderBy('gantt_sort_order')
            ->orderBy('tache_parent_id', 'asc') // Tâches parentes en premier
            ->orderBy('date_debut_prevue');

            // Appliquer les filtres
            if ($actionId) {
                $query->where('action_prioritaire_id', $actionId);
            } elseif ($objectifId) {
                $query->whereHas('actionPrioritaire', function($q) use ($objectifId) {
                    $q->where('objectif_id', $objectifId);
                });
            } elseif ($versionId) {
                $query->whereHas('actionPrioritaire.objectif', function($q) use ($versionId) {
                    $q->where('papa_version_id', $versionId);
                });
            } else {
                // Filtrer par PAPA
                $query->whereHas('actionPrioritaire.objectif.papaVersion', function($q) use ($papa) {
                    $q->where('papa_id', $papa->id);
                });
            }

            // Limiter à 500 tâches pour la performance (Phase 1)
            $taches = $query->limit(500)->get();

            // Transformer en format Gantt
            $ganttTasks = GanttTaskResource::collection($taches);

            // Calculer les dates min/max pour la timeline
            $dates = $taches->filter(function($t) {
                return $t->date_debut_prevue && $t->date_fin_prevue;
            })->map(function($t) {
                return [
                    'start' => Carbon::parse($t->date_debut_prevue),
                    'end' => Carbon::parse($t->date_fin_prevue),
                ];
            });

            $minDate = $dates->isNotEmpty() ? $dates->min('start') : Carbon::now();
            $maxDate = $dates->isNotEmpty() ? $dates->max('end') : Carbon::now()->addYear();

            // Vérifier si l'utilisateur peut éditer
            $editable = $request->user()->can('editDates');

            return response()->json([
                'data' => $ganttTasks,
                'meta' => [
                    'min_date' => $minDate->format('Y-m-d'),
                    'max_date' => $maxDate->format('Y-m-d'),
                    'total_tasks' => $taches->count(),
                    'editable' => $editable,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des données Gantt: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors du chargement des données',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
