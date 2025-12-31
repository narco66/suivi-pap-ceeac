<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tache;
use App\Models\GanttDependency;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GanttDemoSeeder extends Seeder
{
    /**
     * Créer des dépendances Gantt pour les tâches existantes
     * et s'assurer que les tâches ont des dates valides
     */
    public function run(): void
    {
        $this->command->info('🎯 Création des dépendances Gantt et mise à jour des dates...');

        // Vérifier que la table existe
        if (!\Illuminate\Support\Facades\Schema::hasTable('taches')) {
            $this->command->warn('⚠️  La table "taches" n\'existe pas. Veuillez exécuter les migrations d\'abord.');
            return;
        }

        // Récupérer toutes les tâches avec dates
        $taches = Tache::whereNotNull('date_debut_prevue')
            ->whereNotNull('date_fin_prevue')
            ->orderBy('date_debut_prevue')
            ->get();

        if ($taches->isEmpty()) {
            $this->command->warn('⚠️  Aucune tâche avec dates trouvée. Création de dates pour les tâches existantes...');
            $this->createDatesForTasks();
            $taches = Tache::whereNotNull('date_debut_prevue')
                ->whereNotNull('date_fin_prevue')
                ->orderBy('date_debut_prevue')
                ->get();
        }

        if ($taches->count() < 2) {
            $this->command->warn('⚠️  Pas assez de tâches pour créer des dépendances (minimum 2 requises).');
            return;
        }

        // Supprimer les dépendances existantes pour éviter les doublons
        GanttDependency::truncate();

        $dependenciesCreated = 0;
        $chunks = $taches->chunk(5); // Créer des dépendances par groupes de 5

        foreach ($chunks as $chunk) {
            $tasks = $chunk->values();
            
            // Créer des dépendances en chaîne (tâche 1 → tâche 2 → tâche 3, etc.)
            for ($i = 0; $i < $tasks->count() - 1; $i++) {
                $currentTask = $tasks[$i];
                $nextTask = $tasks[$i + 1];

                // Vérifier que la tâche suivante commence après la fin de la tâche actuelle
                $currentEnd = Carbon::parse($currentTask->date_fin_prevue);
                $nextStart = Carbon::parse($nextTask->date_debut_prevue);

                // Si la tâche suivante commence avant la fin de la tâche actuelle, ajuster
                if ($nextStart->lt($currentEnd)) {
                    $nextTask->date_debut_prevue = $currentEnd->copy()->addDay();
                    // Calculer la durée originale
                    $originalStart = Carbon::parse($nextTask->getOriginal('date_debut_prevue'));
                    $originalEnd = Carbon::parse($nextTask->getOriginal('date_fin_prevue'));
                    $originalDuration = max(1, $originalEnd->diffInDays($originalStart));
                    $nextTask->date_fin_prevue = $nextTask->date_debut_prevue->copy()->addDays($originalDuration);
                    $nextTask->save();
                }

                // Créer la dépendance Finish-to-Start (FS)
                GanttDependency::create([
                    'task_id' => $nextTask->id,
                    'depends_on_task_id' => $currentTask->id,
                    'dependency_type' => 'FS',
                    'lag_days' => 0,
                ]);

                $dependenciesCreated++;
            }

            // Créer quelques dépendances croisées (tous les 3 groupes)
            if ($chunk->count() >= 3) {
                $first = $tasks[0];
                $third = $tasks[2];

                // Vérifier que la dépendance n'existe pas déjà
                $exists = GanttDependency::where('task_id', $third->id)
                    ->where('depends_on_task_id', $first->id)
                    ->exists();

                if (!$exists) {
                    GanttDependency::create([
                        'task_id' => $third->id,
                        'depends_on_task_id' => $first->id,
                        'dependency_type' => 'FS',
                        'lag_days' => 0,
                    ]);
                    $dependenciesCreated++;
                }
            }
        }

        // Marquer quelques tâches comme critiques
        $criticalTasks = $taches->random(min(3, $taches->count()));
        foreach ($criticalTasks as $task) {
            $task->is_critical = true;
            $task->criticite = 'critique';
            $task->gantt_color = '#dc3545';
            $task->save();
        }

        // Ajouter quelques jalons
        $milestoneTasks = $taches->where('est_jalon', false)->random(min(2, $taches->where('est_jalon', false)->count()));
        foreach ($milestoneTasks as $task) {
            $task->est_jalon = true;
            $task->date_fin_prevue = $task->date_debut_prevue; // Jalons : même date début/fin
            $task->save();
        }

        // Mettre à jour les sort_order pour un affichage cohérent
        $sortOrder = 0;
        foreach ($taches->sortBy('date_debut_prevue') as $task) {
            $task->gantt_sort_order = $sortOrder++;
            $task->save();
        }

        $this->command->info("✅ {$dependenciesCreated} dépendances Gantt créées");
        $this->command->info("✅ " . $criticalTasks->count() . " tâches marquées comme critiques");
        $this->command->info("✅ " . $milestoneTasks->count() . " jalons créés");
    }

    /**
     * Créer des dates pour les tâches qui n'en ont pas
     */
    private function createDatesForTasks(): void
    {
        $taches = Tache::whereNull('date_debut_prevue')
            ->orWhereNull('date_fin_prevue')
            ->get();

        $startDate = Carbon::now()->startOfYear();
        $dayOffset = 0;

        foreach ($taches as $tache) {
            if (!$tache->date_debut_prevue) {
                $tache->date_debut_prevue = $startDate->copy()->addDays($dayOffset);
            }

            if (!$tache->date_fin_prevue) {
                // Durée par défaut : 7 jours pour les tâches normales, 0 pour les jalons
                $duration = $tache->est_jalon ? 0 : 7;
                $tache->date_fin_prevue = Carbon::parse($tache->date_debut_prevue)->addDays($duration);
            }

            $tache->save();
            $dayOffset += 10; // Espacer les tâches de 10 jours
        }

        $this->command->info("✅ Dates créées pour {$taches->count()} tâches");
    }
}
