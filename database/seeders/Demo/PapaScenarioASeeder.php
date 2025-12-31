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
use App\Models\Avancement;
use App\Models\User;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

/**
 * Scénario A: PAPA 2025 v1 (verrouillée)
 * - 15-20 Objectifs
 * - 80-120 Actions prioritaires
 * - 400-800 Tâches
 * - Répartition: 25% achevée, 45% en_cours, 15% planifiée, 10% en_attente, 5% annulée
 * - 20-30% des actions en retard
 */
class PapaScenarioASeeder extends Seeder
{
    private $faker;
    private $config;
    private $papa;
    private $version;
    private $objectifs = [];
    private $actions = [];
    private $taches = [];
    private $users = [];
    private $directionsTech = [];
    private $directionsAppui = [];

    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed(config('seeding.seed', 12345));
        $this->config = config('seeding.volumes');

        $this->command->info('📋 Scénario A: PAPA 2025 v1 (verrouillée)');
        $this->command->info('═══════════════════════════════════════════════════════════');

        DB::transaction(function () {
            // 1. Créer PAPA 2025
            $this->createPapa();

            // 2. Créer Version v1 (verrouillée)
            $this->createVersion();

            // 3. Charger les données nécessaires
            $this->loadDependencies();

            // 4. Créer Objectifs (15-20)
            $nbObjectifs = $this->faker->numberBetween(15, 20);
            $this->command->info("  → Création de {$nbObjectifs} objectifs...");
            $this->createObjectifs($nbObjectifs);

            // 5. Créer Actions Prioritaires (80-120)
            $nbActions = $this->faker->numberBetween(80, 120);
            $this->command->info("  → Création de {$nbActions} actions prioritaires...");
            $this->createActions($nbActions);

            // 6. Créer Tâches (400-800)
            $nbTaches = $this->faker->numberBetween(400, 800);
            $this->command->info("  → Création de {$nbTaches} tâches...");
            $this->createTaches($nbTaches);

            // 7. Créer Sous-tâches (0-3 par tâche)
            $this->command->info("  → Création des sous-tâches...");
            $this->createSousTaches();

            // 8. Créer KPI (3-5 par action)
            $this->command->info("  → Création des KPI...");
            $this->createKpis();

            // 9. Créer Avancements (12 par tâche = 3 mois hebdo)
            $this->command->info("  → Création des avancements...");
            $this->createAvancements();

            $this->command->info("  ✅ Scénario A terminé!");
        });
    }

    private function createPapa(): void
    {
        $this->papa = Papa::create([
            'code' => 'PAPA-2025',
            'libelle' => 'Plan d\'Action Prioritaire 2025',
            'annee' => 2025,
            'description' => 'Plan d\'Action Prioritaire de la CEEAC pour l\'année 2025. Ce plan couvre les objectifs stratégiques de l\'institution pour l\'intégration économique, la paix et la sécurité, et le développement régional.',
            'statut' => 'en_cours',
            'date_debut' => '2025-01-01',
            'date_fin' => '2025-12-31',
            'created_at' => Carbon::parse('2024-10-01'),
            'updated_at' => Carbon::parse('2024-12-15'),
        ]);
    }

    private function createVersion(): void
    {
        $this->version = PapaVersion::create([
            'papa_id' => $this->papa->id,
            'numero' => 1,
            'libelle' => 'Version 1 - Plan initial',
            'description' => 'Version initiale du PAPA 2025, verrouillée après validation.',
            'statut' => 'verrouille',
            'verrouille' => true,
            'date_creation' => Carbon::parse('2024-10-01'),
            'date_verrouillage' => Carbon::parse('2024-12-15'),
            'created_at' => Carbon::parse('2024-10-01'),
            'updated_at' => Carbon::parse('2024-12-15'),
        ]);
    }

    private function loadDependencies(): void
    {
        $this->users = User::all();
        $this->directionsTech = DirectionTechnique::all();
        $this->directionsAppui = DirectionAppui::all();

        if ($this->users->isEmpty() || $this->directionsTech->isEmpty() || $this->directionsAppui->isEmpty()) {
            throw new \Exception('Les référentiels (users, directions) doivent être créés avant le scénario A.');
        }
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
            'Promouvoir l\'environnement et le développement durable',
            'Renforcer la coopération internationale',
            'Faciliter la libre circulation des personnes et des biens',
            'Renforcer les mécanismes de prévention des conflits',
            'Améliorer la transparence et la redevabilité',
            'Développer les corridors de transport',
            'Renforcer la résilience face aux changements climatiques',
        ];

        $statuts = ['brouillon', 'planifie', 'en_cours', 'termine', 'annule'];

        for ($i = 0; $i < $count; $i++) {
            $statut = $this->getStatutWithDistribution($statuts, [0.05, 0.15, 0.45, 0.25, 0.10]);
            $dateDebut = Carbon::parse('2025-01-01')->addDays($this->faker->numberBetween(0, 60));
            $dateFin = $dateDebut->copy()->addMonths($this->faker->numberBetween(6, 12));

            $objectif = Objectif::create([
                'papa_version_id' => $this->version->id,
                'code' => 'OBJ-2025-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'libelle' => $libelles[$i % count($libelles)],
                'description' => $this->faker->paragraph(3),
                'statut' => $statut,
                'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
                'date_debut_prevue' => $dateDebut,
                'date_fin_prevue' => $dateFin,
                'date_debut_reelle' => $statut !== 'planifie' ? $dateDebut->copy()->addDays($this->faker->numberBetween(-10, 10)) : null,
                'date_fin_reelle' => $statut === 'termine' ? $dateFin->copy()->addDays($this->faker->numberBetween(-30, 0)) : null,
                'pourcentage_avancement' => $this->getAvancementByStatut($statut),
                'created_at' => Carbon::parse('2024-10-01')->addDays($i),
                'updated_at' => now(),
            ]);

            $this->objectifs[] = $objectif;
        }
    }

    private function createActions(int $count): void
    {
        $libelles = [
            'Mise en place d\'un mécanisme de suivi des échanges commerciaux',
            'Renforcement des capacités des forces de sécurité',
            'Organisation d\'élections transparentes',
            'Construction de routes transfrontalières',
            'Mise en place d\'un système d\'alerte précoce',
            'Formation des agents de développement',
            'Renforcement des systèmes d\'information',
            'Mobilisation de fonds auprès des partenaires',
            'Protection des écosystèmes transfrontaliers',
            'Signature d\'accords de coopération',
        ];

        $statuts = ['brouillon', 'planifie', 'en_cours', 'en_retard', 'bloque', 'termine', 'annule'];

        $actionIndex = 0;
        foreach ($this->objectifs as $objectif) {
            $nbActionsParObjectif = max(3, (int)($count / count($this->objectifs)) + $this->faker->numberBetween(-1, 2));
            
            for ($j = 0; $j < $nbActionsParObjectif && $actionIndex < $count; $j++) {
                $statut = $this->getStatutWithDistribution($statuts, [0.05, 0.15, 0.40, 0.20, 0.05, 0.10, 0.05]);
                $type = $this->faker->randomElement(['technique', 'appui']);
                
                $dateDebut = $objectif->date_debut_prevue ?? Carbon::parse('2025-01-01');
                $dateFin = $dateDebut->copy()->addMonths($this->faker->numberBetween(2, 6));
                
                // 20-30% en retard
                if ($statut === 'en_retard') {
                    $dateFin = Carbon::now()->subDays($this->faker->numberBetween(1, 90));
                }

                $action = ActionPrioritaire::create([
                    'objectif_id' => $objectif->id,
                    'code' => 'ACT-2025-' . str_pad($actionIndex + 1, 4, '0', STR_PAD_LEFT),
                    'libelle' => $libelles[$actionIndex % count($libelles)] . ' - ' . $objectif->libelle,
                    'description' => $this->faker->paragraph(2),
                    'type' => $type,
                    'direction_technique_id' => $type === 'technique' ? $this->directionsTech->random()->id : null,
                    'direction_appui_id' => $type === 'appui' ? $this->directionsAppui->random()->id : null,
                    'statut' => $statut,
                    'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
                    'criticite' => $this->getCriticiteByStatut($statut),
                    'date_debut_prevue' => $dateDebut,
                    'date_fin_prevue' => $dateFin,
                    'date_debut_reelle' => $statut !== 'planifie' ? $dateDebut->copy()->addDays($this->faker->numberBetween(-5, 5)) : null,
                    'date_fin_reelle' => $statut === 'termine' ? $dateFin->copy()->addDays($this->faker->numberBetween(-30, 0)) : null,
                    'pourcentage_avancement' => $this->getAvancementByStatut($statut),
                    'bloque' => $statut === 'bloque',
                    'raison_blocage' => $statut === 'bloque' ? $this->faker->sentence() : null,
                    'created_at' => $objectif->created_at->copy()->addDays($j),
                    'updated_at' => now(),
                ]);

                $this->actions[] = $action;
                $actionIndex++;
            }
        }
    }

    private function createTaches(int $count): void
    {
        $libelles = [
            'Réaliser une étude de faisabilité',
            'Organiser une réunion de coordination',
            'Préparer un document de synthèse',
            'Valider le budget alloué',
            'Former les équipes concernées',
            'Mettre en place un système de suivi',
            'Effectuer une mission sur le terrain',
            'Rédiger un rapport d\'activité',
            'Organiser un atelier de sensibilisation',
            'Finaliser la documentation technique',
        ];

        $statuts = ['brouillon', 'planifie', 'en_cours', 'en_retard', 'bloque', 'termine', 'annule'];

        $tacheIndex = 0;
        foreach ($this->actions as $action) {
            $nbTachesParAction = max(3, (int)($count / count($this->actions)) + $this->faker->numberBetween(-1, 2));
            
            for ($j = 0; $j < $nbTachesParAction && $tacheIndex < $count; $j++) {
                $statut = $this->getStatutWithDistribution($statuts, [0.05, 0.15, 0.40, 0.20, 0.05, 0.10, 0.05]);
                
                $dateDebut = $action->date_debut_prevue ?? Carbon::parse('2025-01-01');
                $dateFin = $dateDebut->copy()->addWeeks($this->faker->numberBetween(2, 12));
                
                if ($statut === 'en_retard') {
                    $dateFin = Carbon::now()->subDays($this->faker->numberBetween(1, 60));
                }

                $tache = Tache::create([
                    'action_prioritaire_id' => $action->id,
                    'tache_parent_id' => null, // Sous-tâches créées après
                    'code' => 'TACH-2025-' . str_pad($tacheIndex + 1, 5, '0', STR_PAD_LEFT),
                    'libelle' => $libelles[$tacheIndex % count($libelles)] . ' - ' . $action->libelle,
                    'description' => $this->faker->optional(0.7)->paragraph(),
                    'statut' => $statut,
                    'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
                    'criticite' => $this->getCriticiteByStatut($statut),
                    'date_debut_prevue' => $dateDebut,
                    'date_fin_prevue' => $dateFin,
                    'date_debut_reelle' => $statut !== 'planifie' ? $dateDebut->copy()->addDays($this->faker->numberBetween(-3, 3)) : null,
                    'date_fin_reelle' => $statut === 'termine' ? $dateFin->copy()->addDays($this->faker->numberBetween(-15, 0)) : null,
                    'pourcentage_avancement' => $this->getAvancementByStatut($statut),
                    'responsable_id' => $this->users->random()->id,
                    'bloque' => $statut === 'bloque',
                    'raison_blocage' => $statut === 'bloque' ? $this->faker->sentence() : null,
                    'est_jalon' => $this->faker->boolean(20), // 20% jalons
                    'created_at' => $action->created_at->copy()->addDays($j),
                    'updated_at' => now(),
                ]);

                $this->taches[] = $tache;
                $tacheIndex++;
            }
        }
    }

    private function createSousTaches(): void
    {
        $nbSousTaches = 0;
        foreach ($this->taches as $tache) {
            if ($this->faker->boolean(30)) { // 30% des tâches ont des sous-tâches
                $nbSousTachesParTache = $this->faker->numberBetween(1, 3);
                
                for ($i = 0; $i < $nbSousTachesParTache; $i++) {
                    Tache::create([
                        'action_prioritaire_id' => $tache->action_prioritaire_id,
                        'tache_parent_id' => $tache->id,
                        'code' => $tache->code . '-ST' . ($i + 1),
                        'libelle' => 'Sous-tâche: ' . $tache->libelle,
                        'description' => $this->faker->optional()->sentence(),
                        'statut' => $this->faker->randomElement(['en_cours', 'termine']),
                        'priorite' => $tache->priorite,
                        'criticite' => $tache->criticite,
                        'date_debut_prevue' => $tache->date_debut_prevue,
                        'date_fin_prevue' => $tache->date_fin_prevue->copy()->subDays($this->faker->numberBetween(1, 7)),
                        'pourcentage_avancement' => $this->faker->numberBetween(0, 100),
                        'responsable_id' => $tache->responsable_id,
                        'est_jalon' => false,
                        'created_at' => $tache->created_at,
                        'updated_at' => now(),
                    ]);
                    $nbSousTaches++;
                }
            }
        }
        $this->command->info("    ✓ {$nbSousTaches} sous-tâches créées");
    }

    private function createKpis(): void
    {
        $libelles = [
            'Taux de réalisation des activités',
            'Nombre de bénéficiaires',
            'Montant des fonds mobilisés',
            'Nombre de formations réalisées',
            'Taux de satisfaction',
        ];

        $unites = ['pourcentage', 'nombre', 'millions USD', 'personnes', 'documents'];

        $nbKpis = 0;
        foreach ($this->actions as $action) {
            $nbKpisParAction = $this->faker->numberBetween(3, 5);
            
            for ($i = 0; $i < $nbKpisParAction; $i++) {
                $cible = $this->faker->numberBetween(50, 1000);
                $realise = $this->faker->numberBetween(0, (int)($cible * 1.2));
                $ecart = $realise - $cible;
                $pourcentage = $cible > 0 ? ($realise / $cible) * 100 : 0;

                Kpi::create([
                    'action_prioritaire_id' => $action->id,
                    'code' => 'KPI-2025-' . str_pad($nbKpis + 1, 6, '0', STR_PAD_LEFT),
                    'libelle' => $libelles[$i % count($libelles)],
                    'description' => $this->faker->optional()->sentence(),
                    'unite' => $unites[$i % count($unites)],
                    'valeur_cible' => $cible,
                    'valeur_realisee' => $realise,
                    'valeur_ecart' => $ecart,
                    'pourcentage_realisation' => $pourcentage,
                    'date_mesure' => $this->faker->dateTimeBetween('-6 months', 'now'),
                    'statut' => $pourcentage >= 100 ? 'atteint' : ($pourcentage >= 80 ? 'en_cours' : 'non_atteint'),
                    'created_at' => $action->created_at,
                    'updated_at' => now(),
                ]);
                $nbKpis++;
            }
        }
        $this->command->info("    ✓ {$nbKpis} KPI créés");
    }

    private function createAvancements(): void
    {
        $nbAvancements = 0;
        foreach ($this->taches as $tache) {
            // 12 avancements = 3 mois hebdomadaires
            $dateDebut = $tache->date_debut_prevue ?? Carbon::now()->subMonths(3);
            $dateFin = min(Carbon::now(), $tache->date_fin_prevue ?? Carbon::now());
            
            if ($dateDebut->gt($dateFin)) {
                continue;
            }

            $nbSemaines = min(12, (int)$dateDebut->diffInWeeks($dateFin));
            
            for ($i = 0; $i < $nbSemaines; $i++) {
                $dateAvancement = $dateDebut->copy()->addWeeks($i);
                if ($dateAvancement->gt(Carbon::now())) {
                    break;
                }

                $pourcentage = min(100, (int)(($i + 1) / $nbSemaines * 100) + $this->faker->numberBetween(-5, 5));

                Avancement::create([
                    'tache_id' => $tache->id,
                    'date_avancement' => $dateAvancement,
                    'pourcentage_avancement' => $pourcentage,
                    'commentaire' => $this->faker->optional(0.7)->sentence(),
                    'fichier_joint' => $this->faker->optional(0.2)->filePath(),
                    'soumis_par_id' => $tache->responsable_id,
                    'valide_par_id' => $this->faker->optional(0.6)->randomElement([$this->users->random()->id]),
                    'date_validation' => $this->faker->optional(0.6)->dateTimeBetween($dateAvancement, 'now'),
                    'statut' => $this->faker->randomElement(['brouillon', 'soumis', 'valide', 'rejete']),
                    'created_at' => $dateAvancement,
                    'updated_at' => $dateAvancement,
                ]);
                $nbAvancements++;
            }
        }
        $this->command->info("    ✓ {$nbAvancements} avancements créés");
    }

    private function getAvancementByStatut(string $statut): int
    {
        return match($statut) {
            'termine' => $this->faker->numberBetween(95, 100),
            'en_cours' => $this->faker->numberBetween(20, 80),
            'planifie' => $this->faker->numberBetween(0, 10),
            'en_attente' => $this->faker->numberBetween(0, 5),
            'annule' => 0,
            default => 0,
        };
    }

    private function getCriticiteByStatut(string $statut): string
    {
        return match($statut) {
            'en_retard' => $this->faker->randomElement(['vigilance', 'critique']),
            'bloque' => $this->faker->randomElement(['vigilance', 'critique']),
            default => $this->faker->randomElement(['normal', 'vigilance']),
        };
    }

    /**
     * Sélectionne un statut selon une distribution de probabilités
     */
    private function getStatutWithDistribution(array $statuts, array $probabilities): string
    {
        $rand = $this->faker->randomFloat(2, 0, 1);
        $cumulative = 0;
        
        foreach ($probabilities as $index => $prob) {
            $cumulative += $prob;
            if ($rand <= $cumulative) {
                return $statuts[$index];
            }
        }
        
        return $statuts[count($statuts) - 1]; // Fallback
    }
}

