<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Papa;
use App\Models\PapaVersion;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

/**
 * Scénario C: PAPA 2024 (archivée)
 * - Données historiques complètes
 * - Version verrouillée et archivée
 * - Permet de tester exports/archivage/rétention
 */
class PapaScenarioCSeeder extends Seeder
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
        $this->faker->seed(config('seeding.seed', 12345) + 2000); // Seed différent

        $this->command->info('📋 Scénario C: PAPA 2024 (archivée)');
        $this->command->info('═══════════════════════════════════════════════════════════');

        DB::transaction(function () {
            // Créer PAPA 2024
            $this->papa = Papa::create([
                'code' => 'PAPA-2024',
                'libelle' => 'Plan d\'Action Prioritaire 2024',
                'annee' => 2024,
                'description' => 'Plan d\'Action Prioritaire de la CEEAC pour l\'année 2024 (archivé).',
                'statut' => 'cloture',
                'date_debut' => '2024-01-01',
                'date_fin' => '2024-12-31',
                'created_at' => Carbon::parse('2023-10-01'),
                'updated_at' => Carbon::parse('2024-12-31'),
            ]);

            // Créer Version v1 (verrouillée et archivée)
            $this->version = PapaVersion::create([
                'papa_id' => $this->papa->id,
                'numero' => 1,
                'libelle' => 'Version 1 - Finale',
                'description' => 'Version finale du PAPA 2024, verrouillée et archivée.',
                'statut' => 'archive',
                'verrouille' => true,
                'date_creation' => Carbon::parse('2023-10-01'),
                'date_verrouillage' => Carbon::parse('2023-12-15'),
                'created_at' => Carbon::parse('2023-10-01'),
                'updated_at' => Carbon::parse('2024-12-31'),
            ]);

            $this->loadDependencies();

            // Créer Objectifs (10-15)
            $nbObjectifs = $this->faker->numberBetween(10, 15);
            $this->createObjectifs($nbObjectifs);

            // Créer Actions (50-80)
            $nbActions = $this->faker->numberBetween(50, 80);
            $this->createActions($nbActions);

            // Créer Tâches (250-500)
            $nbTaches = $this->faker->numberBetween(250, 500);
            $this->createTaches($nbTaches);

            $this->command->info("  ✅ Scénario C terminé!");
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
        ];

        for ($i = 0; $i < $count; $i++) {
            $objectif = Objectif::create([
                'papa_version_id' => $this->version->id,
                'code' => 'OBJ-2024-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'libelle' => $libelles[$i % count($libelles)],
                'description' => $this->faker->paragraph(2),
                'statut' => $this->faker->randomElement(['termine', 'annule']), // Tous terminés ou annulés
                'priorite' => $this->faker->randomElement(['normale', 'haute']),
                'date_debut_prevue' => Carbon::parse('2024-01-01')->addDays($this->faker->numberBetween(0, 30)),
                'date_fin_prevue' => Carbon::parse('2024-12-31'),
                'date_debut_reelle' => Carbon::parse('2024-01-01')->addDays($this->faker->numberBetween(0, 15)),
                'date_fin_reelle' => Carbon::parse('2024-12-31')->subDays($this->faker->numberBetween(0, 30)),
                'pourcentage_avancement' => $this->faker->numberBetween(90, 100),
                'created_at' => Carbon::parse('2023-10-01')->addDays($i),
                'updated_at' => Carbon::parse('2024-12-31'),
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
                    'code' => 'ACT-2024-' . str_pad($actionIndex + 1, 4, '0', STR_PAD_LEFT),
                    'libelle' => 'Action ' . ($actionIndex + 1) . ' - ' . $objectif->libelle,
                    'description' => $this->faker->paragraph(),
                    'type' => $type,
                    'direction_technique_id' => $type === 'technique' ? $this->directionsTech->random()->id : null,
                    'direction_appui_id' => $type === 'appui' ? $this->directionsAppui->random()->id : null,
                    'statut' => $this->faker->randomElement(['termine', 'annule']),
                    'priorite' => $this->faker->randomElement(['normale', 'haute']),
                    'criticite' => 'normal',
                    'date_debut_prevue' => $objectif->date_debut_prevue,
                    'date_fin_prevue' => $objectif->date_fin_prevue,
                    'date_debut_reelle' => $objectif->date_debut_reelle,
                    'date_fin_reelle' => $objectif->date_fin_reelle,
                    'pourcentage_avancement' => $this->faker->numberBetween(90, 100),
                    'created_at' => $objectif->created_at,
                    'updated_at' => Carbon::parse('2024-12-31'),
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
                    'code' => 'TACH-2024-' . str_pad($tacheIndex + 1, 5, '0', STR_PAD_LEFT),
                    'libelle' => 'Tâche ' . ($tacheIndex + 1) . ' - ' . $action->libelle,
                    'statut' => $this->faker->randomElement(['termine', 'annule']),
                    'priorite' => 'normale',
                    'criticite' => 'normal',
                    'date_debut_prevue' => $action->date_debut_prevue,
                    'date_fin_prevue' => $action->date_fin_prevue,
                    'date_debut_reelle' => $action->date_debut_reelle,
                    'date_fin_reelle' => $action->date_fin_reelle,
                    'pourcentage_avancement' => $this->faker->numberBetween(90, 100),
                    'responsable_id' => $this->users->random()->id,
                    'created_at' => $action->created_at,
                    'updated_at' => Carbon::parse('2024-12-31'),
                ]);

                $tacheIndex++;
            }
        }
    }
}



