<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alerte;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use App\Models\Kpi;
use App\Models\User;
use Carbon\Carbon;

class GenererAlertes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'papa:generate-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère automatiquement les alertes (retards, KPI sous seuil, blocages)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔔 Génération des alertes automatiques...');

        $nbAlertes = 0;

        // Alertes pour retards
        $nbAlertes += $this->generateRetardAlertes();

        // Alertes pour KPI sous seuil
        $nbAlertes += $this->generateKpiAlertes();

        // Alertes pour blocages
        $nbAlertes += $this->generateBlocageAlertes();

        $this->info("✅ {$nbAlertes} alertes générées");

        return Command::SUCCESS;
    }

    private function generateRetardAlertes(): int
    {
        $nbAlertes = 0;

        // Tâches en retard sans alerte
        $tachesEnRetard = Tache::where('statut', 'en_retard')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->whereDoesntHave('alertes', function($q) {
                $q->where('type', 'echeance_depassee')
                  ->orWhere('type', 'retard_critique');
            })
            ->get();

        foreach ($tachesEnRetard->take(20) as $tache) {
            $joursRetard = Carbon::now()->diffInDays($tache->date_fin_prevue);
            $criticite = $joursRetard > 30 ? 'critique' : 'vigilance';

            Alerte::create([
                'type' => $joursRetard > 30 ? 'retard_critique' : 'echeance_depassee',
                'titre' => 'Tâche en retard: ' . $tache->libelle,
                'message' => "La tâche {$tache->code} est en retard de {$joursRetard} jour(s).",
                'criticite' => $criticite,
                'statut' => 'ouverte',
                'tache_id' => $tache->id,
                'action_prioritaire_id' => $tache->action_prioritaire_id,
                'niveau_escalade' => $criticite === 'critique' ? 'sg' : 'direction',
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'assignee_a_id' => $tache->responsable_id,
                'date_creation' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        return $nbAlertes;
    }

    private function generateKpiAlertes(): int
    {
        $nbAlertes = 0;

        // KPI sous seuil (80%) sans alerte
        $kpisSousSeuil = Kpi::where('pourcentage_realisation', '<', 80)
            ->where('statut', '!=', 'atteint')
            ->whereDoesntHave('alertes', function($q) {
                $q->where('type', 'kpi_non_atteint');
            })
            ->get();

        foreach ($kpisSousSeuil->take(15) as $kpi) {
            $criticite = $kpi->pourcentage_realisation < 50 ? 'critique' : 'vigilance';

            Alerte::create([
                'type' => 'kpi_non_atteint',
                'titre' => 'KPI sous seuil: ' . $kpi->libelle,
                'message' => "Le KPI {$kpi->code} est à {$kpi->pourcentage_realisation}% de la cible.",
                'criticite' => $criticite,
                'statut' => 'ouverte',
                'action_prioritaire_id' => $kpi->action_prioritaire_id,
                'niveau_escalade' => $criticite === 'critique' ? 'direction' : null,
                'cree_par_id' => User::whereHas('roles', fn($q) => $q->where('name', 'admin_dsi'))->first()?->id ?? User::first()?->id,
                'date_creation' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        return $nbAlertes;
    }

    private function generateBlocageAlertes(): int
    {
        $nbAlertes = 0;

        // Tâches bloquées sans alerte
        $tachesBloquees = Tache::where('bloque', true)
            ->where('statut', 'bloque')
            ->whereDoesntHave('alertes', function($q) {
                $q->where('type', 'blocage');
            })
            ->get();

        foreach ($tachesBloquees->take(10) as $tache) {
            Alerte::create([
                'type' => 'blocage',
                'titre' => 'Tâche bloquée: ' . $tache->libelle,
                'message' => "La tâche {$tache->code} est bloquée. Raison: " . ($tache->raison_blocage ?? 'Non spécifiée'),
                'criticite' => 'vigilance',
                'statut' => 'ouverte',
                'tache_id' => $tache->id,
                'action_prioritaire_id' => $tache->action_prioritaire_id,
                'niveau_escalade' => 'direction',
                'cree_par_id' => $tache->responsable_id,
                'assignee_a_id' => $tache->responsable_id,
                'date_creation' => Carbon::now(),
            ]);
            $nbAlertes++;
        }

        return $nbAlertes;
    }
}
