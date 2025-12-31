<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Papa;
use App\Models\PapaVersion;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use App\Models\Alerte;
use App\Models\Kpi;
use App\Models\Avancement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'papas_actifs' => Papa::where('statut', '!=', 'cloture')
                ->where('statut', '!=', 'archive')
                ->count(),
            'objectifs_total' => Objectif::count(),
            'objectifs_en_cours' => Objectif::whereIn('statut', ['planifie', 'en_cours'])->count(),
            'actions_total' => ActionPrioritaire::count(),
            'actions_en_cours' => ActionPrioritaire::whereIn('statut', ['planifie', 'en_cours'])->count(),
            'taches_total' => Tache::whereNull('tache_parent_id')->count(), // Seulement les tâches principales
            'taches_en_cours' => Tache::whereNull('tache_parent_id')
                ->whereIn('statut', ['planifie', 'en_cours'])
                ->count(),
            'alertes_total' => Alerte::count(),
            'alertes_ouvertes' => Alerte::whereIn('statut', ['ouverte', 'en_cours'])->count(),
            'alertes_critiques' => Alerte::where('criticite', 'critique')
                ->whereIn('statut', ['ouverte', 'en_cours'])
                ->count(),
            'kpis_total' => Kpi::count(),
            'kpis_sous_seuil' => Kpi::where('pourcentage_realisation', '<', 80)
                ->where('statut', '!=', 'atteint')
                ->count(),
        ];

        // PAPA récents
        $papasRecents = Papa::with('versions')
            ->orderBy('annee', 'desc')
            ->take(5)
            ->get();

        // Alertes récentes (critiques et vigilance)
        $alertesRecentes = Alerte::with(['tache', 'actionPrioritaire'])
            ->whereIn('criticite', ['vigilance', 'critique'])
            ->whereIn('statut', ['ouverte', 'en_cours'])
            ->orderBy('date_creation', 'desc')
            ->take(10)
            ->get();

        // Tâches en retard
        $tachesEnRetard = Tache::whereNull('tache_parent_id')
            ->where('statut', 'en_retard')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->with(['actionPrioritaire.objectif.papaVersion.papa', 'responsable'])
            ->orderBy('date_fin_prevue', 'asc')
            ->take(10)
            ->get();

        // Évolution de l'avancement (derniers 6 mois)
        $avancementEvolution = $this->getAvancementEvolution();

        // Répartition par statut
        $repartitionStatuts = [
            'termine' => Tache::whereNull('tache_parent_id')->where('statut', 'termine')->count(),
            'en_cours' => Tache::whereNull('tache_parent_id')->where('statut', 'en_cours')->count(),
            'en_retard' => Tache::whereNull('tache_parent_id')->where('statut', 'en_retard')->count(),
            'planifie' => Tache::whereNull('tache_parent_id')->where('statut', 'planifie')->count(),
            'bloque' => Tache::whereNull('tache_parent_id')->where('statut', 'bloque')->count(),
        ];

        // Répartition par criticité
        $repartitionCriticite = [
            'normal' => Tache::whereNull('tache_parent_id')->where('criticite', 'normal')->count(),
            'vigilance' => Tache::whereNull('tache_parent_id')->where('criticite', 'vigilance')->count(),
            'critique' => Tache::whereNull('tache_parent_id')->where('criticite', 'critique')->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'papasRecents',
            'alertesRecentes',
            'tachesEnRetard',
            'avancementEvolution',
            'repartitionStatuts',
            'repartitionCriticite'
        ));
    }

    /**
     * Récupère l'évolution de l'avancement sur les 6 derniers mois
     */
    private function getAvancementEvolution(): array
    {
        $evolution = [];
        $now = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Moyenne des avancements pour ce mois
            $avancements = Avancement::whereBetween('date_avancement', [$startOfMonth, $endOfMonth])
                ->avg('pourcentage_avancement');

            $evolution[] = [
                'mois' => $date->format('M Y'),
                'avancement' => round($avancements ?? 0, 1),
            ];
        }

        return $evolution;
    }
}
