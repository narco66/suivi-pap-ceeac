<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use App\Models\Alerte;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use App\Models\Kpi;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

/**
 * Génère des alertes automatiques basées sur les données existantes
 * - Retards de tâches/actions
 * - KPI sous seuil
 * - Blocages
 */
class AlertesAutoSeeder extends Seeder
{
    private $faker;

    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed(config('seeding.seed', 12345) + 3000);

        $this->command->info('🔔 Génération des alertes automatiques...');

        // Alertes pour retards
        $this->generateRetardAlertes();

        // Alertes pour KPI sous seuil
        $this->generateKpiAlertes();

        // Alertes pour blocages
        $this->generateBlocageAlertes();

        $this->command->info("  ✅ Alertes générées");
    }

    private function generateRetardAlertes(): void
    {
        // Tâches en retard
        $tachesEnRetard = Tache::where('statut', 'en_retard')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->get();

        $nbAlertes = 0;
        foreach ($tachesEnRetard->take(30) as $tache) {
            $joursRetard = Carbon::now()->diffInDays($tache->date_fin_prevue);
            $criticite = $joursRetard > 30 ? 'critique' : 'vigilance';

            Alerte::create([
                'type' => $joursRetard > 30 ? 'retard_critique' : 'echeance_depassee',
                'titre' => 'Tâche en retard: ' . $tache->libelle,
                'message' => "La tâche {$tache->code} est en retard de {$joursRetard} jour(s). Date prévue: " . $tache->date_fin_prevue->format('d/m/Y'),
                'criticite' => $criticite,
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours']),
                'tache_id' => $tache->id,
                'action_prioritaire_id' => $tache->action_prioritaire_id,
                'niveau_escalade' => $criticite === 'critique' ? $this->faker->randomElement(['sg', 'commissaire']) : 'direction',
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'assignee_a_id' => $tache->responsable_id,
                'date_creation' => Carbon::now()->subDays($this->faker->numberBetween(0, 7)),
                'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 7)),
                'updated_at' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        // Actions en retard
        $actionsEnRetard = ActionPrioritaire::where('statut', 'en_retard')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->get();

        foreach ($actionsEnRetard->take(20) as $action) {
            $joursRetard = Carbon::now()->diffInDays($action->date_fin_prevue);
            $criticite = $joursRetard > 30 ? 'critique' : 'vigilance';

            Alerte::create([
                'type' => $joursRetard > 30 ? 'retard_critique' : 'echeance_depassee',
                'titre' => 'Action en retard: ' . $action->libelle,
                'message' => "L'action {$action->code} est en retard de {$joursRetard} jour(s).",
                'criticite' => $criticite,
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours']),
                'action_prioritaire_id' => $action->id,
                'niveau_escalade' => $criticite === 'critique' ? $this->faker->randomElement(['sg', 'commissaire']) : 'direction',
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'date_creation' => Carbon::now()->subDays($this->faker->numberBetween(0, 7)),
                'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 7)),
                'updated_at' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        $this->command->info("    ✓ {$nbAlertes} alertes de retard créées");
    }

    private function generateKpiAlertes(): void
    {
        // KPI sous seuil (pourcentage < 80%)
        $kpisSousSeuil = Kpi::where('pourcentage_realisation', '<', 80)
            ->where('statut', '!=', 'atteint')
            ->get();

        $nbAlertes = 0;
        foreach ($kpisSousSeuil->take(25) as $kpi) {
            $ecart = $kpi->valeur_cible - $kpi->valeur_realisee;
            $criticite = $kpi->pourcentage_realisation < 50 ? 'critique' : 'vigilance';

            Alerte::create([
                'type' => 'kpi_non_atteint',
                'titre' => 'KPI sous seuil: ' . $kpi->libelle,
                'message' => "Le KPI {$kpi->code} est à {$kpi->pourcentage_realisation}% de la cible. Écart: {$ecart} {$kpi->unite}",
                'criticite' => $criticite,
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours']),
                'action_prioritaire_id' => $kpi->action_prioritaire_id,
                'niveau_escalade' => $criticite === 'critique' ? 'direction' : null,
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'date_creation' => Carbon::now()->subDays($this->faker->numberBetween(0, 14)),
                'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 14)),
                'updated_at' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        $this->command->info("    ✓ {$nbAlertes} alertes KPI créées");
    }

    private function generateBlocageAlertes(): void
    {
        // Tâches bloquées
        $tachesBloquees = Tache::where('bloque', true)
            ->where('statut', 'bloque')
            ->get();

        $nbAlertes = 0;
        foreach ($tachesBloquees->take(15) as $tache) {
            Alerte::create([
                'type' => 'blocage',
                'titre' => 'Tâche bloquée: ' . $tache->libelle,
                'message' => "La tâche {$tache->code} est bloquée. Raison: " . ($tache->raison_blocage ?? 'Non spécifiée'),
                'criticite' => $this->faker->randomElement(['vigilance', 'critique']),
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours']),
                'tache_id' => $tache->id,
                'action_prioritaire_id' => $tache->action_prioritaire_id,
                'niveau_escalade' => 'direction',
                'cree_par_id' => $tache->responsable_id,
                'assignee_a_id' => $tache->responsable_id,
                'date_creation' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
                'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        // Actions bloquées
        $actionsBloquees = ActionPrioritaire::where('bloque', true)
            ->where('statut', 'bloque')
            ->get();

        foreach ($actionsBloquees->take(10) as $action) {
            Alerte::create([
                'type' => 'blocage',
                'titre' => 'Action bloquée: ' . $action->libelle,
                'message' => "L'action {$action->code} est bloquée. Raison: " . ($action->raison_blocage ?? 'Non spécifiée'),
                'criticite' => $this->faker->randomElement(['vigilance', 'critique']),
                'statut' => $this->faker->randomElement(['ouverte', 'en_cours']),
                'action_prioritaire_id' => $action->id,
                'niveau_escalade' => 'direction',
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'date_creation' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
                'created_at' => Carbon::now()->subDays($this->faker->numberBetween(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        $this->command->info("    ✓ {$nbAlertes} alertes de blocage créées");
    }
}


