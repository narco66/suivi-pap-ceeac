<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Papa;
use App\Models\PapaVersion;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use App\Models\Kpi;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

/**
 * Scénario B: PAPA 2025 v2 (brouillon)
 * - 8-12 Objectifs
 * - 40-60 Actions prioritaires
 * - 200-400 Tâches
 * - Version non verrouillée (brouillon)
 */
class PapaScenarioBSeeder extends Seeder
{
    private $faker;
    private $papa;
    private $version;
    private $objectifs = [];
    private $actions = [];
    private $users = [];
    private $directionsTech = [];
    private $directionsAppui = [];

    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed(config('seeding.seed', 12345) + 1000); // Seed différent

        $this->command->info('📋 Scénario B: PAPA 2025 v2 (brouillon)');
        $this->command->info('═══════════════════════════════════════════════════════════');

        DB::transaction(function () {
            // Récupérer PAPA 2025 existant ou créer
            $this->papa = Papa::where('annee', 2025)->first();
            if (!$this->papa) {
                $this->papa = Papa::create([
                    'code' => 'PAPA-2025',
                    'libelle' => 'Plan d\'Action Prioritaire 2025',
                    'annee' => 2025,
                    'statut' => 'en_cours',
                    'date_debut' => '2025-01-01',
                    'date_fin' => '2025-12-31',
                ]);
            }

            // Créer Version v2 (brouillon)
            $this->version = PapaVersion::create([
                'papa_id' => $this->papa->id,
                'numero' => 2,
                'libelle' => 'Version 2 - Révision en cours',
                'description' => 'Version de révision du PAPA 2025, en cours d\'élaboration.',
                'statut' => 'brouillon',
                'verrouille' => false,
                'date_creation' => Carbon::now()->subDays(30),
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now(),
            ]);

            $this->loadDependencies();

            // Créer Objectifs (8-12)
            $nbObjectifs = $this->faker->numberBetween(8, 12);
            $this->createObjectifs($nbObjectifs);

            // Créer Actions (40-60)
            $nbActions = $this->faker->numberBetween(40, 60);
            $this->createActions($nbActions);

            // Créer Tâches (200-400)
            $nbTaches = $this->faker->numberBetween(200, 400);
            $this->createTaches($nbTaches);

            // Créer KPI (2-3 par action)
            $this->createKpis();

            $this->command->info("  ✅ Scénario B terminé!");
        });
    }

    private function loadDependencies(): void
    {
        $this->users = \App\Models\User::all();
        $this->directionsTech = \App\Models\DirectionTechnique::all();
        $this->directionsAppui = \App\Models\DirectionAppui::all();
    }

    private function createObjectifs(int $count): void
    {
        $libelles = [
            'Renforcer l\'intégration économique régionale',
            'Promouvoir la paix et la sécurité',
            'Améliorer la gouvernance démocratique',
            'Développer les infrastructures régionales',
            'Renforcer la coopération en matière de sécurité alimentaire',
            'Promouvoir le développement social',
            'Renforcer les capacités institutionnelles',
            'Améliorer la mobilisation des ressources',
        ];

        for ($i = 0; $i < $count; $i++) {
            $objectif = Objectif::create([
                'papa_version_id' => $this->version->id,
                'code' => 'OBJ-2025-V2-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'libelle' => $libelles[$i % count($libelles)] . ' (V2)',
                'description' => $this->faker->paragraph(2),
                'statut' => $this->faker->randomElement(['brouillon', 'planifie']),
                'priorite' => $this->faker->randomElement(['normale', 'haute']),
                'date_debut_prevue' => Carbon::parse('2025-01-01')->addDays($this->faker->numberBetween(0, 90)),
                'date_fin_prevue' => Carbon::parse('2025-12-31'),
                'pourcentage_avancement' => $this->faker->numberBetween(0, 30),
                'created_at' => Carbon::now()->subDays(30 - $i),
                'updated_at' => Carbon::now(),
            ]);

            $this->objectifs[] = $objectif;
        }
    }

    private function createActions(int $count): void
    {
        $actionIndex = 0;
        foreach ($this->objectifs as $objectif) {
            $nbActionsParObjectif = max(3, (int)($count / count($this->objectifs)) + $this->faker->numberBetween(-1, 1));
            
            for ($j = 0; $j < $nbActionsParObjectif && $actionIndex < $count; $j++) {
                $type = $this->faker->randomElement(['technique', 'appui']);

                $action = ActionPrioritaire::create([
                    'objectif_id' => $objectif->id,
                    'code' => 'ACT-2025-V2-' . str_pad($actionIndex + 1, 4, '0', STR_PAD_LEFT),
                    'libelle' => 'Action ' . ($actionIndex + 1) . ' - ' . $objectif->libelle,
                    'description' => $this->faker->paragraph(),
                    'type' => $type,
                    'direction_technique_id' => $type === 'technique' ? $this->directionsTech->random()->id : null,
                    'direction_appui_id' => $type === 'appui' ? $this->directionsAppui->random()->id : null,
                    'statut' => $this->faker->randomElement(['brouillon', 'planifie']),
                    'priorite' => $this->faker->randomElement(['normale', 'haute']),
                    'criticite' => 'normal',
                    'date_debut_prevue' => $objectif->date_debut_prevue,
                    'date_fin_prevue' => $objectif->date_fin_prevue,
                    'pourcentage_avancement' => 0,
                    'created_at' => $objectif->created_at,
                    'updated_at' => Carbon::now(),
                ]);

                $this->actions[] = $action;
                $actionIndex++;
            }
        }
    }

    private function createTaches(int $count): void
    {
        $tacheIndex = 0;
        foreach ($this->actions as $action) {
            $nbTachesParAction = max(3, (int)($count / count($this->actions)) + $this->faker->numberBetween(-1, 1));
            
            for ($j = 0; $j < $nbTachesParAction && $tacheIndex < $count; $j++) {
                Tache::create([
                    'action_prioritaire_id' => $action->id,
                    'code' => 'TACH-2025-V2-' . str_pad($tacheIndex + 1, 5, '0', STR_PAD_LEFT),
                    'libelle' => 'Tâche ' . ($tacheIndex + 1) . ' - ' . $action->libelle,
                    'statut' => 'brouillon',
                    'priorite' => 'normale',
                    'criticite' => 'normal',
                    'date_debut_prevue' => $action->date_debut_prevue,
                    'date_fin_prevue' => $action->date_fin_prevue,
                    'responsable_id' => $this->users->random()->id,
                    'created_at' => $action->created_at,
                    'updated_at' => Carbon::now(),
                ]);

                $tacheIndex++;
            }
        }
    }

    private function createKpis(): void
    {
        $nbKpis = 0;
        foreach ($this->actions as $action) {
            $nbKpisParAction = $this->faker->numberBetween(2, 3);
            
            for ($i = 0; $i < $nbKpisParAction; $i++) {
                $cible = $this->faker->numberBetween(50, 500);
                Kpi::create([
                    'action_prioritaire_id' => $action->id,
                    'code' => 'KPI-2025-V2-' . str_pad($nbKpis + 1, 6, '0', STR_PAD_LEFT),
                    'libelle' => 'KPI ' . ($nbKpis + 1),
                    'unite' => $this->faker->randomElement(['pourcentage', 'nombre', 'millions USD']),
                    'valeur_cible' => $cible,
                    'valeur_realisee' => 0,
                    'valeur_ecart' => -$cible,
                    'pourcentage_realisation' => 0,
                    'statut' => 'en_cours',
                    'created_at' => $action->created_at,
                    'updated_at' => Carbon::now(),
                ]);
                $nbKpis++;
            }
        }
    }
}


