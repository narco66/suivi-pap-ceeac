<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GanttSyncRequest;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GanttSyncController extends Controller
{
    /**
     * Synchronisation en masse (drag & drop)
     * POST /api/projects/{papa}/gantt/sync
     * 
     * Phase 2: Cette méthode sera complétée pour gérer le drag & drop
     */
    public function sync(GanttSyncRequest $request, \App\Models\Papa $papa)
    {
        // Autorisation
        $this->authorize('editDates');

        try {
            DB::beginTransaction();

            $updatedTasks = [];
            $tasks = $request->input('tasks', []);

            foreach ($tasks as $taskData) {
                $tache = Tache::findOrFail($taskData['id']);
                
                $oldValues = $tache->toArray();
                
                // Mettre à jour les champs
                if (isset($taskData['start_date'])) {
                    $tache->date_debut_prevue = $taskData['start_date'];
                }
                if (isset($taskData['end_date'])) {
                    $tache->date_fin_prevue = $taskData['end_date'];
                }
                if (isset($taskData['progress'])) {
                    $tache->pourcentage_avancement = (int)($taskData['progress'] * 100);
                }
                if (isset($taskData['gantt_sort_order'])) {
                    $tache->gantt_sort_order = $taskData['gantt_sort_order'];
                }

                $tache->save();
                $newValues = $tache->fresh()->toArray();

                // Log d'audit
                \App\Models\GanttAuditLog::create([
                    'user_id' => $request->user()->id,
                    'task_id' => $tache->id,
                    'action' => 'reschedule',
                    'old_value' => $oldValues,
                    'new_value' => $newValues,
                ]);

                $updatedTasks[] = $tache;
            }

            DB::commit();

            return response()->json([
                'message' => count($updatedTasks) . ' tâche(s) mise(s) à jour',
                'updated_count' => count($updatedTasks),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la synchronisation Gantt: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erreur lors de la synchronisation',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
