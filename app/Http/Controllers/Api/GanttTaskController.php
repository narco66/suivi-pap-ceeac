<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GanttTaskStoreRequest;
use App\Http\Requests\GanttTaskUpdateRequest;
use App\Http\Resources\GanttTaskResource;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GanttTaskController extends Controller
{
    /**
     * Créer une nouvelle tâche
     * POST /api/gantt/tasks
     */
    public function store(GanttTaskStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $tache = Tache::create($request->validated());

            // Log d'audit
            \App\Models\GanttAuditLog::create([
                'user_id' => $request->user()->id,
                'task_id' => $tache->id,
                'action' => 'create',
                'new_value' => $tache->toArray(),
            ]);

            DB::commit();

            return response()->json([
                'data' => new GanttTaskResource($tache),
                'message' => 'Tâche créée avec succès',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de la tâche: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la création de la tâche',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Mettre à jour une tâche
     * PUT /api/gantt/tasks/{tache}
     */
    public function update(GanttTaskUpdateRequest $request, Tache $tache)
    {
        try {
            DB::beginTransaction();

            $oldValues = $tache->toArray();
            $tache->update($request->validated());
            $newValues = $tache->fresh()->toArray();

            // Log d'audit
            \App\Models\GanttAuditLog::create([
                'user_id' => $request->user()->id,
                'task_id' => $tache->id,
                'action' => 'update',
                'old_value' => $oldValues,
                'new_value' => $newValues,
            ]);

            DB::commit();

            return response()->json([
                'data' => new GanttTaskResource($tache),
                'message' => 'Tâche mise à jour avec succès',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de la tâche: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de la tâche',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Supprimer une tâche
     * DELETE /api/gantt/tasks/{tache}
     */
    public function destroy(Request $request, Tache $tache)
    {
        $this->authorize('delete', $tache);

        try {
            DB::beginTransaction();

            // Log d'audit
            \App\Models\GanttAuditLog::create([
                'user_id' => $request->user()->id,
                'task_id' => $tache->id,
                'action' => 'delete',
                'old_value' => $tache->toArray(),
            ]);

            $tache->delete();

            DB::commit();

            return response()->json([
                'message' => 'Tâche supprimée avec succès',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression de la tâche: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la suppression de la tâche',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
