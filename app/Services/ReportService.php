<?php

namespace App\Services;

use App\Models\Rapport;
use App\Models\User;
use App\Models\ActionPrioritaire;
use App\Models\Objectif;
use App\Models\Tache;
use App\Models\Kpi;
use App\Models\Alerte;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * ReportService
 * 
 * Service pour la génération de rapports institutionnels.
 * Gère la logique métier de génération, le scoping par périmètre, et la traçabilité.
 */
class ReportService
{
    protected ReportQueryBuilder $queryBuilder;
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->queryBuilder = new ReportQueryBuilder($user);
    }

    /**
     * Générer un rapport selon son type et format
     */
    public function generateReport(Rapport $rapport): string
    {
        // Vérifier que l'utilisateur peut générer ce type de rapport
        $this->validateReportScope($rapport);

        $directory = 'rapports/' . now()->format('Y/m');
        $filename = Str::slug($rapport->code) . '_' . now()->format('Y-m-d_His');
        
        if ($rapport->format === 'pdf') {
            return $this->generatePdf($rapport, $directory, $filename);
        } elseif ($rapport->format === 'excel') {
            return $this->generateExcel($rapport, $directory, $filename);
        } elseif ($rapport->format === 'csv') {
            return $this->generateCsv($rapport, $directory, $filename);
        } else {
            return $this->generateHtml($rapport, $directory, $filename);
        }
    }

    /**
     * Valider que le rapport respecte le périmètre de l'utilisateur
     */
    protected function validateReportScope(Rapport $rapport): void
    {
        $scopeLevel = $this->queryBuilder->getScopeLevel();
        
        // Vérifier que le scope_level du rapport correspond au rôle
        if ($rapport->scope_level && $rapport->scope_level !== $scopeLevel) {
            // Si le rapport a un scope_level défini, il doit correspondre
            if ($scopeLevel === 'COMMISSAIRE' && $rapport->scope_level !== 'COMMISSAIRE') {
                throw new \Exception('Vous ne pouvez générer que des rapports de votre département.');
            }
            if ($scopeLevel === 'SG' && $rapport->scope_level !== 'SG') {
                throw new \Exception('Vous ne pouvez générer que des rapports des Directions d\'Appui.');
            }
        }

        // Les admins peuvent générer tous les rapports
        if (!$this->queryBuilder->canGenerateGlobalReports() && $rapport->scope_level === 'GLOBAL') {
            throw new \Exception('Seuls les administrateurs peuvent générer des rapports globaux.');
        }
    }

    /**
     * Récupérer les données pour le rapport selon le périmètre
     */
    public function getReportData(Rapport $rapport): array
    {
        // Déterminer l'année de référence : utiliser l'année du PAPA si associé, sinon année en cours
        $referenceYear = now()->year;
        if ($rapport->papa_id && $rapport->papa) {
            $referenceYear = $rapport->papa->annee ?? now()->year;
        }
        
        // Convertir les dates en Carbon (le modèle cast en 'date' retourne déjà un Carbon)
        $periodStart = $rapport->date_debut 
            ? ($rapport->date_debut instanceof Carbon ? $rapport->date_debut->copy() : Carbon::parse($rapport->date_debut))
            : $this->getPeriodStart($rapport->periode, $referenceYear);
        $periodEnd = $rapport->date_fin 
            ? ($rapport->date_fin instanceof Carbon ? $rapport->date_fin->copy() : Carbon::parse($rapport->date_fin))
            : $this->getPeriodEnd($rapport->periode, $referenceYear);
        
        // S'assurer que les dates sont au format datetime pour les comparaisons
        if ($periodStart instanceof Carbon) {
            $periodStart = $periodStart->copy()->startOfDay();
        }
        if ($periodEnd instanceof Carbon) {
            $periodEnd = $periodEnd->copy()->endOfDay();
        }

        $data = [
            'rapport' => $rapport,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'scope_level' => $this->queryBuilder->getScopeLevel(),
            'generated_by' => $this->user,
            'generated_at' => now(),
        ];

        // Récupérer les données selon le type de rapport
        switch ($rapport->type) {
            case 'synthese':
                $data = array_merge($data, $this->getSyntheseData($periodStart, $periodEnd));
                break;
            case 'papa':
                $data = array_merge($data, $this->getPapaData($periodStart, $periodEnd));
                break;
            case 'objectif':
                $data = array_merge($data, $this->getObjectifData($rapport, $periodStart, $periodEnd));
                break;
            case 'action_prioritaire':
                $data = array_merge($data, $this->getActionData($periodStart, $periodEnd));
                break;
            case 'kpi':
                $data = array_merge($data, $this->getKpiData($periodStart, $periodEnd));
                break;
            case 'alerte':
                $data = array_merge($data, $this->getAlerteData($periodStart, $periodEnd));
                break;
            case 'risques_retards':
                $data = array_merge($data, $this->getRisquesRetardsData($periodStart, $periodEnd));
                break;
            default:
                $data = array_merge($data, $this->getSyntheseData($periodStart, $periodEnd));
        }

        return $data;
    }

    /**
     * Données pour rapport de synthèse
     */
    protected function getSyntheseData(Carbon $start, Carbon $end): array
    {
        // Queries de base pour les statistiques (toutes les données du périmètre, pas seulement créées dans la période)
        $actionsQueryBase = $this->queryBuilder->buildActionsQuery();
        $objectifsQueryBase = $this->queryBuilder->buildObjectifsQuery();
        $tachesQueryBase = $this->queryBuilder->buildTachesQuery();
        $kpisQueryBase = $this->queryBuilder->buildKpisQuery();
        $alertesQueryBase = $this->queryBuilder->buildAlertesQuery();
        
        // Queries filtrées par période pour les listes détaillées
        $actionsQuery = (clone $actionsQueryBase)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->orWhereBetween('date_debut_prevue', [$start, $end])
                  ->orWhereBetween('date_fin_prevue', [$start, $end]);
            });
        
        $objectifsQuery = (clone $objectifsQueryBase)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->orWhereBetween('date_debut_prevue', [$start, $end])
                  ->orWhereBetween('date_fin_prevue', [$start, $end])
                  ->orWhereBetween('date_debut_reelle', [$start, $end])
                  ->orWhereBetween('date_fin_reelle', [$start, $end]);
            });
        
        $tachesQuery = (clone $tachesQueryBase)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->orWhereBetween('date_debut_prevue', [$start, $end])
                  ->orWhereBetween('date_fin_prevue', [$start, $end]);
            });
        
        $kpisQuery = (clone $kpisQueryBase)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('date_mesure', [$start, $end])
                  ->orWhereBetween('created_at', [$start, $end]);
            });
        
        $alertesQuery = (clone $alertesQueryBase)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('date_creation', [$start, $end])
                  ->orWhereBetween('date_resolution', [$start, $end]);
            });

        // Calculer les statistiques sur TOUTES les données du périmètre (pas seulement celles de la période)
        $stats = [
            'objectifs_total' => (clone $objectifsQueryBase)->count(),
            'objectifs_en_cours' => (clone $objectifsQueryBase)->whereIn('statut', ['planifie', 'en_cours'])->count(),
            'actions_total' => (clone $actionsQueryBase)->count(),
            'actions_en_cours' => (clone $actionsQueryBase)->whereIn('statut', ['planifie', 'en_cours'])->count(),
            'taches_total' => (clone $tachesQueryBase)->count(),
            'taches_terminees' => (clone $tachesQueryBase)->where('statut', 'termine')->count(),
            'taches_en_retard' => (clone $tachesQueryBase)
                ->where('statut', '!=', 'termine')
                ->where('date_fin_prevue', '<', now())
                ->count(),
            'kpis_total' => (clone $kpisQueryBase)->count(),
            'kpis_sous_seuil' => (clone $kpisQueryBase)
                ->where(function($q) {
                    $q->whereColumn('valeur_realisee', '<', 'valeur_cible')
                      ->orWhere('pourcentage_realisation', '<', 80);
                })
                ->count(),
            'alertes_total' => (clone $alertesQueryBase)->count(),
            'alertes_ouvertes' => (clone $alertesQueryBase)->whereIn('statut', ['ouverte', 'en_cours'])->count(),
        ];

        return [
            'objectifs' => $objectifsQuery->with('papaVersion.papa')->get(),
            'actions' => $actionsQuery->with('objectif', 'directionTechnique', 'directionAppui')->get(),
            'taches' => $tachesQuery->with('actionPrioritaire', 'responsable')->get(),
            'kpis' => $kpisQuery->with('actionPrioritaire')->get(),
            'alertes' => $alertesQuery->with('actionPrioritaire', 'tache')->get(),
            'stats' => $stats,
        ];
    }

    /**
     * Données pour rapport PAPA
     */
    protected function getPapaData(Carbon $start, Carbon $end): array
    {
        $objectifsQuery = $this->queryBuilder->buildObjectifsQuery()
            ->whereBetween('created_at', [$start, $end]);
        
        $actionsQuery = $this->queryBuilder->buildActionsQuery()
            ->whereBetween('created_at', [$start, $end]);

        return [
            'objectifs' => $objectifsQuery->with('papaVersion.papa', 'actionsPrioritaires')->get(),
            'actions' => $actionsQuery->with('objectif', 'directionTechnique', 'directionAppui')->get(),
        ];
    }

    /**
     * Données pour rapport Objectif
     */
    protected function getObjectifData(Rapport $rapport, Carbon $start, Carbon $end): array
    {
        $objectif = $rapport->objectif;
        
        if (!$objectif) {
            return [];
        }

        $actionsQuery = $this->queryBuilder->buildActionsQuery()
            ->where('objectif_id', $objectif->id)
            ->whereBetween('created_at', [$start, $end]);

        return [
            'objectif' => $objectif->load('papaVersion.papa'),
            'actions' => $actionsQuery->with('directionTechnique', 'directionAppui', 'taches', 'kpis')->get(),
        ];
    }

    /**
     * Données pour rapport Actions Prioritaires
     */
    protected function getActionData(Carbon $start, Carbon $end): array
    {
        $actionsQuery = $this->queryBuilder->buildActionsQuery()
            ->whereBetween('created_at', [$start, $end])
            ->with('objectif.papaVersion.papa', 'directionTechnique', 'directionAppui', 'taches', 'kpis', 'alertes');

        return [
            'actions' => $actionsQuery->get(),
        ];
    }

    /**
     * Données pour rapport KPI
     */
    protected function getKpiData(Carbon $start, Carbon $end): array
    {
        $kpisQuery = $this->queryBuilder->buildKpisQuery()
            ->whereBetween('date_mesure', [$start, $end])
            ->with('actionPrioritaire.objectif');

        return [
            'kpis' => $kpisQuery->get(),
            'stats' => [
                'total' => $kpisQuery->count(),
                'atteints' => (clone $kpisQuery)->whereColumn('valeur_realisee', '>=', 'valeur_cible')->count(),
                'sous_objectif' => (clone $kpisQuery)->whereColumn('valeur_realisee', '<', 'valeur_cible')->count(),
            ],
        ];
    }

    /**
     * Données pour rapport Alertes
     */
    protected function getAlerteData(Carbon $start, Carbon $end): array
    {
        $alertesQuery = $this->queryBuilder->buildAlertesQuery()
            ->whereBetween('date_creation', [$start, $end])
            ->with('actionPrioritaire', 'tache', 'creePar', 'assigneeA');

        return [
            'alertes' => $alertesQuery->get(),
        ];
    }

    /**
     * Données pour rapport Risques & Retards
     */
    protected function getRisquesRetardsData(Carbon $start, Carbon $end): array
    {
        $tachesQuery = $this->queryBuilder->buildTachesQuery()
            ->whereBetween('date_fin_prevue', [$start, $end])
            ->where('statut', '!=', 'termine')
            ->where('date_fin_prevue', '<', now())
            ->with('actionPrioritaire', 'responsable');

        $alertesQuery = $this->queryBuilder->buildAlertesQuery()
            ->whereBetween('date_creation', [$start, $end])
            ->whereIn('criticite', ['vigilance', 'critique'])
            ->whereIn('statut', ['ouverte', 'en_cours'])
            ->with('actionPrioritaire', 'tache');

        return [
            'taches_en_retard' => $tachesQuery->get(),
            'alertes_critiques' => $alertesQuery->get(),
        ];
    }

    /**
     * Générer un PDF
     */
    protected function generatePdf(Rapport $rapport, string $directory, string $filename): string
    {
        $data = $this->getReportData($rapport);
        $view = $this->getReportView($rapport);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data);
        
        Storage::makeDirectory($directory);
        $filePath = $directory . '/' . $filename . '.pdf';
        Storage::put($filePath, $pdf->output());

        return $filePath;
    }

    /**
     * Générer un Excel
     */
    protected function generateExcel(Rapport $rapport, string $directory, string $filename): string
    {
        $data = $this->getReportData($rapport);
        
        // Utiliser les exports existants ou créer des exports spécifiques
        $exportClass = $this->getExportClass($rapport->type);
        
        if (!class_exists($exportClass)) {
            throw new \Exception("Classe d'export non trouvée : {$exportClass}");
        }
        
        Storage::makeDirectory($directory);
        $filePath = $directory . '/' . $filename . '.xlsx';
        
        \Maatwebsite\Excel\Facades\Excel::store(
            new $exportClass($data),
            $filePath
        );

        return $filePath;
    }

    /**
     * Générer un CSV
     */
    protected function generateCsv(Rapport $rapport, string $directory, string $filename): string
    {
        // Similaire à Excel mais en CSV
        return $this->generateExcel($rapport, $directory, $filename);
    }

    /**
     * Générer un HTML
     */
    protected function generateHtml(Rapport $rapport, string $directory, string $filename): string
    {
        $data = $this->getReportData($rapport);
        $view = $this->getReportView($rapport);
        
        $html = view($view, $data)->render();
        
        Storage::makeDirectory($directory);
        $filePath = $directory . '/' . $filename . '.html';
        Storage::put($filePath, $html);

        return $filePath;
    }

    /**
     * Obtenir la vue Blade pour le rapport
     */
    protected function getReportView(Rapport $rapport): string
    {
        $scopeLevel = $this->queryBuilder->getScopeLevel();
        $type = $rapport->type;
        
        // Vue selon le type et le scope
        $viewPath = "rapports.templates.{$scopeLevel}.{$type}";
        
        // Fallback sur vue générique si spécifique n'existe pas
        if (!view()->exists($viewPath)) {
            $viewPath = "rapports.templates.{$scopeLevel}.synthese";
        }
        
        if (!view()->exists($viewPath)) {
            $viewPath = "rapports.templates.synthese";
        }

        return $viewPath;
    }

    /**
     * Obtenir la classe d'export Excel
     */
    protected function getExportClass(string $type): string
    {
        $classes = [
            'synthese' => \App\Exports\SyntheseReportExport::class,
            'papa' => \App\Exports\PapaReportExport::class,
            'objectif' => \App\Exports\ObjectifReportExport::class,
            'action_prioritaire' => \App\Exports\ActionReportExport::class,
            'kpi' => \App\Exports\KpiReportExport::class,
            'alerte' => \App\Exports\AlerteReportExport::class,
            'risques_retards' => \App\Exports\RisquesRetardsReportExport::class,
        ];

        return $classes[$type] ?? \App\Exports\SyntheseReportExport::class;
    }

    /**
     * Calculer la date de début selon la période et l'année de référence
     */
    protected function getPeriodStart(string $periode, ?int $referenceYear = null): Carbon
    {
        $year = $referenceYear ?? now()->year;
        
        return match($periode) {
            'jour' => Carbon::createFromDate($year, now()->month, now()->day)->startOfDay(),
            'semaine' => Carbon::createFromDate($year, now()->month, now()->day)->startOfWeek(),
            'mois' => Carbon::createFromDate($year, now()->month, 1)->startOfMonth(),
            'trimestre' => Carbon::createFromDate($year, now()->month, 1)->startOfQuarter(),
            'semestre' => now()->month <= 6 
                ? Carbon::createFromDate($year, 1, 1)->startOfYear() 
                : Carbon::createFromDate($year, 7, 1)->startOfMonth(),
            'annee' => Carbon::createFromDate($year, 1, 1)->startOfYear(),
            default => Carbon::createFromDate($year, now()->month, 1)->startOfMonth(),
        };
    }

    /**
     * Calculer la date de fin selon la période et l'année de référence
     */
    protected function getPeriodEnd(string $periode, ?int $referenceYear = null): Carbon
    {
        $year = $referenceYear ?? now()->year;
        
        return match($periode) {
            'jour' => Carbon::createFromDate($year, now()->month, now()->day)->endOfDay(),
            'semaine' => Carbon::createFromDate($year, now()->month, now()->day)->endOfWeek(),
            'mois' => Carbon::createFromDate($year, now()->month, 1)->endOfMonth(),
            'trimestre' => Carbon::createFromDate($year, now()->month, 1)->endOfQuarter(),
            'semestre' => now()->month <= 6 
                ? Carbon::createFromDate($year, 6, 30)->endOfDay()
                : Carbon::createFromDate($year, 12, 31)->endOfDay(),
            'annee' => Carbon::createFromDate($year, 12, 31)->endOfDay(),
            default => Carbon::createFromDate($year, now()->month, 1)->endOfMonth(),
        };
    }
}

