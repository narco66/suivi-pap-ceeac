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
    public function index(Request $request)
    {
        $user = $request->user();
        
        // 🔒 SÉCURITÉ : Déterminer le scope selon le rôle
        $isCommissaire = $user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi']);
        $departmentId = $isCommissaire ? $user->getDepartmentId() : null;
        
        // Base queries avec scope département pour les commissaires
        $actionsQuery = ActionPrioritaire::query();
        $tachesQuery = Tache::whereNull('tache_parent_id');
        $alertesQuery = Alerte::query();
        $kpisQuery = Kpi::query();
        $objectifsQuery = Objectif::query();
        
        // 🔒 SÉCURITÉ : Appliquer scope département pour les commissaires
        if ($isCommissaire && $departmentId) {
            $actionsQuery->forDepartment($departmentId);
            $tachesQuery->forDepartment($departmentId);
            $alertesQuery->forDepartment($departmentId);
            $kpisQuery->forDepartment($departmentId);
            // Objectifs : filtrer via actions prioritaires du département
            $objectifsQuery->whereHas('actionsPrioritaires', function($q) use ($departmentId) {
                $q->forDepartment($departmentId);
            });
        }
        
        // Statistiques générales (scoppées par département pour les commissaires)
        $stats = [
            'papas_actifs' => Papa::where('statut', '!=', 'cloture')
                ->where('statut', '!=', 'archive')
                ->count(),
            'objectifs_total' => (clone $objectifsQuery)->count(),
            'objectifs_en_cours' => (clone $objectifsQuery)->whereIn('statut', ['planifie', 'en_cours'])->count(),
            'actions_total' => (clone $actionsQuery)->count(),
            'actions_en_cours' => (clone $actionsQuery)->whereIn('statut', ['planifie', 'en_cours'])->count(),
            'taches_total' => (clone $tachesQuery)->count(),
            'taches_en_cours' => (clone $tachesQuery)
                ->whereIn('statut', ['planifie', 'en_cours'])
                ->count(),
            'alertes_total' => (clone $alertesQuery)->count(),
            'alertes_ouvertes' => (clone $alertesQuery)->whereIn('statut', ['ouverte', 'en_cours'])->count(),
            'alertes_critiques' => (clone $alertesQuery)->where('criticite', 'critique')
                ->whereIn('statut', ['ouverte', 'en_cours'])
                ->count(),
            'kpis_total' => (clone $kpisQuery)->count(),
            'kpis_sous_seuil' => (clone $kpisQuery)->where('pourcentage_realisation', '<', 80)
                ->where('statut', '!=', 'atteint')
                ->count(),
        ];

        // PAPA récents (tous pour admins, filtrés pour commissaires)
        $papasQuery = Papa::with('versions')->orderBy('annee', 'desc');
        if ($isCommissaire && $departmentId) {
            // Pour les commissaires, filtrer les PAPA qui ont des objectifs avec actions du département
            $papasQuery->whereHas('versions.objectifs.actionsPrioritaires', function($q) use ($departmentId) {
                $q->forDepartment($departmentId);
            });
        }
        $papasRecents = $papasQuery->take(5)->get();

        // Alertes récentes (critiques et vigilance) - scoppées par département
        $alertesRecentesQuery = Alerte::with(['tache', 'actionPrioritaire'])
            ->whereIn('criticite', ['vigilance', 'critique'])
            ->whereIn('statut', ['ouverte', 'en_cours'])
            ->orderBy('date_creation', 'desc');
        if ($isCommissaire && $departmentId) {
            $alertesRecentesQuery->forDepartment($departmentId);
        }
        $alertesRecentes = $alertesRecentesQuery->take(10)->get();

        // Tâches en retard - scoppées par département
        $tachesEnRetardQuery = Tache::whereNull('tache_parent_id')
            ->where('statut', 'en_retard')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->with(['actionPrioritaire.objectif.papaVersion.papa', 'responsable'])
            ->orderBy('date_fin_prevue', 'asc');
        if ($isCommissaire && $departmentId) {
            $tachesEnRetardQuery->forDepartment($departmentId);
        }
        $tachesEnRetard = $tachesEnRetardQuery->take(10)->get();

        // Évolution de l'avancement (derniers 6 mois)
        $avancementEvolution = $this->getAvancementEvolution();

        // Répartition par statut - scoppée par département
        $repartitionStatutsQuery = Tache::whereNull('tache_parent_id');
        if ($isCommissaire && $departmentId) {
            $repartitionStatutsQuery->forDepartment($departmentId);
        }
        $repartitionStatuts = [
            'termine' => (clone $repartitionStatutsQuery)->where('statut', 'termine')->count(),
            'en_cours' => (clone $repartitionStatutsQuery)->where('statut', 'en_cours')->count(),
            'en_retard' => (clone $repartitionStatutsQuery)->where('statut', 'en_retard')->count(),
            'planifie' => (clone $repartitionStatutsQuery)->where('statut', 'planifie')->count(),
            'bloque' => (clone $repartitionStatutsQuery)->where('statut', 'bloque')->count(),
        ];

        // Répartition par criticité - scoppée par département
        $repartitionCriticiteQuery = Tache::whereNull('tache_parent_id');
        if ($isCommissaire && $departmentId) {
            $repartitionCriticiteQuery->forDepartment($departmentId);
        }
        $repartitionCriticite = [
            'normal' => (clone $repartitionCriticiteQuery)->where('criticite', 'normal')->count(),
            'vigilance' => (clone $repartitionCriticiteQuery)->where('criticite', 'vigilance')->count(),
            'critique' => (clone $repartitionCriticiteQuery)->where('criticite', 'critique')->count(),
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
