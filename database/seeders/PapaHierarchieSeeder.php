<?php

namespace Database\Seeders;

use App\Models\Papa;
use App\Models\PapaVersion;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use App\Models\Kpi;
use App\Models\Alerte;
use App\Models\Avancement;
use App\Models\Anomalie;
use App\Models\User;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

class PapaHierarchieSeeder extends Seeder
{
    private $config;
    private $users;
    private $directionsTech;
    private $directionsAppui;
    private $statutsDistribution;
    private $faker;
    
    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed(config('seeding.seed', 12345));
        
        $this->config = config('seeding.volumes');
        $this->users = User::all();
        $this->directionsTech = DirectionTechnique::all();
        $this->directionsAppui = DirectionAppui::all();
        $this->statutsDistribution = $this->config['statuts'];
        
        $this->command->info('📋 Création de la hiérarchie PAPA complète...');
        
        DB::transaction(function () {
            // Créer les PAPA
            $papas = $this->createPapas();
            
            foreach ($papas as $papa) {
                $this->command->info("  → Traitement du PAPA {$papa->code}...");
                
                // Créer les versions pour chaque PAPA
                $versions = $this->createVersions($papa);
                
                foreach ($versions as $version) {
                    $this->command->info("    → Traitement de la version {$version->numero}...");
                    
                    // Créer les objectifs
                    $objectifs = $this->createObjectifs($version);
                    
                    foreach ($objectifs as $objectif) {
                        // Créer les actions prioritaires
                        $actions = $this->createActions($objectif);
                        
                        foreach ($actions as $action) {
                            // Créer les KPI
                            $this->createKpis($action);
                            
                            // Créer les tâches
                            $taches = $this->createTaches($action);
                            
                            foreach ($taches as $tache) {
                                // Créer les sous-tâches
                                $this->createSousTaches($tache);
                                
                                // Créer les avancements
                                $this->createAvancements($tache);
                            }
                        }
                    }
                }
            }
            
            // Créer les alertes globales
            $this->createAlertes();
            
            // Créer les anomalies
            $this->createAnomalies();
        });
        
        $this->command->info('✅ Hiérarchie PAPA créée avec succès!');
    }
    
    private function createPapas(): \Illuminate\Support\Collection
    {
        $this->command->info('  → Création des PAPA...');
        $papas = collect();
        
        // PAPA 2024
        $papa2024 = Papa::factory()->annee2024()->create([
            'statut' => 'en_cours',
        ]);
        $papas->push($papa2024);
        
        // PAPA 2025
        if ($this->config['papas'] > 1) {
            $papa2025 = Papa::factory()->annee2025()->create([
                'statut' => 'brouillon',
            ]);
            $papas->push($papa2025);
        }
        
        $this->command->info("    ✓ {$papas->count()} PAPA créé(s)");
        return $papas;
    }
    
    private function createVersions(Papa $papa): \Illuminate\Support\Collection
    {
        $versions = collect();
        $count = $this->config['versions_per_papa'];
        
        for ($i = 1; $i <= $count; $i++) {
            $verrouille = $i === 1; // V1 verrouillée
            $version = PapaVersion::factory()->create([
                'papa_id' => $papa->id,
                'numero' => $i,
                'libelle' => 'Version ' . $i,
                'statut' => $verrouille ? 'verrouille' : 'active',
                'verrouille' => $verrouille,
                'date_verrouillage' => $verrouille ? now()->subMonths(2) : null,
            ]);
            $versions->push($version);
        }
        
        return $versions;
    }
    
    private function createObjectifs(PapaVersion $version): \Illuminate\Support\Collection
    {
        $objectifs = collect();
        $count = $this->config['objectifs_per_version'];
        
        // Charger la relation papa si elle n'est pas déjà chargée
        if (!$version->relationLoaded('papa')) {
            $version->load('papa');
        }
        
        $papa = $version->papa;
        if (!$papa) {
            throw new \Exception("PAPA introuvable pour la version {$version->id}");
        }
        
        for ($i = 1; $i <= $count; $i++) {
            $statut = $this->getStatutAleatoire();
            $objectif = Objectif::factory()->create([
                'papa_version_id' => $version->id,
                'code' => 'OBJ-' . $version->papa_id . '-' . $version->numero . '-' . $i,
                'statut' => $statut,
                'priorite' => $this->getPrioriteAleatoire(),
                'date_debut_prevue' => $papa->date_debut,
                'date_fin_prevue' => $papa->date_fin,
                'pourcentage_avancement' => $statut === 'termine' ? 100 : rand(0, 95),
            ]);
            $objectifs->push($objectif);
        }
        
        return $objectifs;
    }
    
    private function createActions(Objectif $objectif): \Illuminate\Support\Collection
    {
        $actions = collect();
        $count = $this->config['actions_per_objectif'];
        
        for ($i = 1; $i <= $count; $i++) {
            $type = $this->faker->randomElement(['technique', 'appui']);
            $statut = $this->getStatutAleatoire();
            $criticite = $this->getCriticiteAleatoire();
            
            // Déterminer les dates selon le statut
            $dates = $this->getDatesSelonStatut($statut, $objectif);
            
            $action = ActionPrioritaire::factory()->create([
                'objectif_id' => $objectif->id,
                'code' => 'ACT-' . $objectif->id . '-' . $i,
                'type' => $type,
                'direction_technique_id' => $type === 'technique' ? $this->directionsTech->random()->id : null,
                'direction_appui_id' => $type === 'appui' ? $this->directionsAppui->random()->id : null,
                'statut' => $statut,
                'criticite' => $criticite,
                'priorite' => $this->getPrioriteAleatoire(),
                'date_debut_prevue' => $dates['debut_prevue'],
                'date_fin_prevue' => $dates['fin_prevue'],
                'date_debut_reelle' => $dates['debut_reelle'],
                'date_fin_reelle' => $dates['fin_reelle'],
                'pourcentage_avancement' => $statut === 'termine' ? 100 : ($statut === 'bloque' ? rand(0, 50) : rand(0, 95)),
                'bloque' => $statut === 'bloque',
                'raison_blocage' => $statut === 'bloque' ? $this->faker->sentence() : null,
            ]);
            
            $actions->push($action);
        }
        
        return $actions;
    }
    
    private function createTaches(ActionPrioritaire $action): \Illuminate\Support\Collection
    {
        $taches = collect();
        $count = $this->config['taches_per_action'];
        $responsables = $this->users->where('fonction', 'Point Focal')->take(5);
        
        for ($i = 1; $i <= $count; $i++) {
            $statut = $this->getStatutAleatoire();
            $criticite = $this->getCriticiteAleatoire();
            $dates = $this->getDatesSelonStatut($statut, $action, 'tache');
            
            $tache = Tache::factory()->create([
                'action_prioritaire_id' => $action->id,
                'code' => 'TACH-' . $action->id . '-' . $i,
                'statut' => $statut,
                'criticite' => $criticite,
                'priorite' => $this->getPrioriteAleatoire(),
                'date_debut_prevue' => $dates['debut_prevue'],
                'date_fin_prevue' => $dates['fin_prevue'],
                'date_debut_reelle' => $dates['debut_reelle'],
                'date_fin_reelle' => $dates['fin_reelle'],
                'pourcentage_avancement' => $statut === 'termine' ? 100 : ($statut === 'bloque' ? rand(0, 50) : rand(0, 95)),
                'responsable_id' => $responsables->random()->id,
                'bloque' => $statut === 'bloque',
                'raison_blocage' => $statut === 'bloque' ? $this->faker->sentence() : null,
                'est_jalon' => $i === 1 || $i === $count, // Première et dernière tâche sont des jalons
            ]);
            
            $taches->push($tache);
        }
        
        return $taches;
    }
    
    private function createSousTaches(Tache $tacheParent): void
    {
        $count = $this->config['sous_taches_per_tache'];
        if ($count <= 0 || $tacheParent->statut === 'termine') {
            return;
        }
        
        for ($i = 1; $i <= $count; $i++) {
            Tache::factory()->create([
                'action_prioritaire_id' => $tacheParent->action_prioritaire_id,
                'tache_parent_id' => $tacheParent->id,
                'code' => 'TACH-' . $tacheParent->id . '-' . $i . '-ST',
                'statut' => $this->getStatutAleatoire(),
                'criticite' => $this->getCriticiteAleatoire(),
                'date_debut_prevue' => $tacheParent->date_debut_prevue,
                'date_fin_prevue' => $tacheParent->date_fin_prevue,
                'responsable_id' => $tacheParent->responsable_id,
                'pourcentage_avancement' => rand(0, 100),
            ]);
        }
    }
    
    private function createKpis(ActionPrioritaire $action): void
    {
        $count = $this->config['kpis_per_action'];
        
        for ($i = 1; $i <= $count; $i++) {
            Kpi::factory()->create([
                'action_prioritaire_id' => $action->id,
                'code' => 'KPI-' . $action->id . '-' . $i,
            ]);
        }
    }
    
    private function createAvancements(Tache $tache): void
    {
        $count = $this->config['avancements_per_tache'];
        $debut = Carbon::parse($tache->date_debut_prevue ?? now()->subMonths(6));
        $fin = Carbon::now();
        
        // Créer des avancements hebdomadaires
        $interval = $debut->diffInWeeks($fin);
        $step = max(1, (int)($interval / $count));
        
        $date = $debut->copy();
        $pourcentagePrecedent = 0;
        
        for ($i = 0; $i < $count && $date->lte($fin); $i++) {
            $pourcentage = min(100, $pourcentagePrecedent + rand(5, 15));
            $pourcentagePrecedent = $pourcentage;
            
            Avancement::factory()->create([
                'tache_id' => $tache->id,
                'date_avancement' => $date->copy(),
                'pourcentage_avancement' => $pourcentage,
                'soumis_par_id' => $tache->responsable_id,
                'valide_par_id' => $this->users->where('fonction', 'Directeur')->random()->id,
                'statut' => $this->faker->randomElement(['soumis', 'valide']),
            ]);
            
            $date->addWeeks($step);
        }
    }
    
    private function createAlertes(): void
    {
        $this->command->info('  → Création des alertes...');
        $count = $this->config['alertes_total'];
        $taches = Tache::all();
        $actions = ActionPrioritaire::all();
        
        for ($i = 0; $i < $count; $i++) {
            $criticite = $this->getCriticiteAlerteAleatoire();
            $type = $this->getTypeAlerteAleatoire();
            
            Alerte::factory()->create([
                'type' => $type,
                'criticite' => $criticite,
                'tache_id' => $taches->isNotEmpty() ? $taches->random()->id : null,
                'action_prioritaire_id' => $actions->isNotEmpty() ? $actions->random()->id : null,
                'niveau_escalade' => $this->getNiveauEscalade($criticite),
                'cree_par_id' => $this->users->random()->id,
                'assignee_a_id' => $this->users->random()->id,
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours', 'resolue']),
            ]);
        }
        
        $this->command->info("    ✓ {$count} alertes créées");
    }
    
    private function createAnomalies(): void
    {
        $this->command->info('  → Création des anomalies...');
        $count = $this->config['anomalies_total'];
        $taches = Tache::all();
        $actions = ActionPrioritaire::all();
        
        for ($i = 0; $i < $count; $i++) {
            Anomalie::factory()->create([
                'tache_id' => $taches->isNotEmpty() ? $taches->random()->id : null,
                'action_prioritaire_id' => $actions->isNotEmpty() ? $actions->random()->id : null,
                'statut' => $this->faker->randomElement(['detectee', 'en_cours', 'corrigee']),
            ]);
        }
        
        $this->command->info("    ✓ {$count} anomalies créées");
    }
    
    // Helpers pour la distribution des statuts
    private function getStatutAleatoire(): string
    {
        $rand = rand(1, 100);
        $cumul = 0;
        
        if ($rand <= ($cumul += $this->statutsDistribution['a_temps'])) {
            return 'en_cours';
        }
        if ($rand <= ($cumul += $this->statutsDistribution['vigilance'])) {
            return 'en_retard';
        }
        if ($rand <= ($cumul += $this->statutsDistribution['critique'])) {
            return 'en_retard';
        }
        if ($rand <= ($cumul += $this->statutsDistribution['bloque'])) {
            return 'bloque';
        }
        return 'termine';
    }
    
    private function getCriticiteAleatoire(): string
    {
        $rand = rand(1, 100);
        $dist = $this->config['criticites'];
        
        if ($rand <= $dist['normal']) {
            return 'normal';
        }
        if ($rand <= $dist['normal'] + $dist['vigilance']) {
            return 'vigilance';
        }
        return 'critique';
    }
    
    private function getCriticiteAlerteAleatoire(): string
    {
        return $this->getCriticiteAleatoire();
    }
    
    private function getPrioriteAleatoire(): string
    {
        return $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']);
    }
    
    private function getTypeAlerteAleatoire(): string
    {
        return $this->faker->randomElement([
            'echeance_depassee',
            'retard_critique',
            'blocage',
            'anomalie',
            'escalade',
            'kpi_non_atteint',
        ]);
    }
    
    private function getNiveauEscalade(string $criticite): string
    {
        if ($criticite === 'critique') {
            return $this->faker->randomElement(['sg', 'commissaire', 'presidence']);
        }
        if ($criticite === 'vigilance') {
            return $this->faker->randomElement(['direction', 'sg']);
        }
        return 'direction';
    }
    
    private function getDatesSelonStatut(string $statut, $parent, string $type = 'action'): array
    {
        $now = Carbon::now();
        
        // Dates du parent avec valeurs par défaut sécurisées
        $debutParent = $parent->date_debut_prevue 
            ? Carbon::parse($parent->date_debut_prevue) 
            : $now->copy()->subMonths(6);
        
        $finParent = $parent->date_fin_prevue 
            ? Carbon::parse($parent->date_fin_prevue) 
            : $now->copy()->addMonths(6);
        
        // S'assurer que début < fin
        if ($debutParent->gt($finParent)) {
            $finParent = $debutParent->copy()->addMonths(6);
        }
        
        // Générer des dates prévues cohérentes
        $debutPrevue = $debutParent->copy()->addDays(rand(0, min(30, $debutParent->diffInDays($finParent))));
        $finPrevue = $finParent->copy()->subDays(rand(0, min(30, $debutPrevue->diffInDays($finParent))));
        
        // S'assurer que début prévue < fin prévue
        if ($debutPrevue->gte($finPrevue)) {
            $finPrevue = $debutPrevue->copy()->addDays(rand(7, 30));
        }
        
        $debutReelle = null;
        $finReelle = null;
        
        switch ($statut) {
            case 'termine':
                $debutReelle = $debutPrevue->copy()->addDays(rand(0, 10));
                $finReelle = $finPrevue->copy()->subDays(rand(0, min(10, $debutPrevue->diffInDays($finPrevue))));
                // S'assurer que fin réelle est dans le passé
                if ($finReelle->gt($now)) {
                    $finReelle = $now->copy()->subDays(rand(1, 30));
                }
                break;
            case 'en_cours':
                $debutReelle = $debutPrevue->copy()->addDays(rand(0, 15));
                // S'assurer que début réelle est dans le passé ou présent
                if ($debutReelle->gt($now)) {
                    $debutReelle = $now->copy()->subDays(rand(1, 30));
                }
                break;
            case 'en_retard':
                $debutReelle = $debutPrevue->copy()->addDays(rand(5, 20));
                // Retard : fin prévue est dans le passé
                $finPrevue = $now->copy()->subDays(rand(1, 90));
                // S'assurer que début réelle est avant fin prévue
                if ($debutReelle->gt($finPrevue)) {
                    $debutReelle = $finPrevue->copy()->subDays(rand(1, 30));
                }
                break;
            case 'bloque':
                $debutReelle = $debutPrevue->copy()->addDays(rand(0, 10));
                // S'assurer que début réelle est dans le passé ou présent
                if ($debutReelle->gt($now)) {
                    $debutReelle = $now->copy()->subDays(rand(1, 30));
                }
                break;
        }
        
        return [
            'debut_prevue' => $debutPrevue,
            'fin_prevue' => $finPrevue,
            'debut_reelle' => $debutReelle,
            'fin_reelle' => $finReelle,
        ];
    }
}

