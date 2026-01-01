<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRapportRequest;
use App\Http\Requests\UpdateRapportRequest;
use App\Models\Rapport;
use App\Models\Papa;
use App\Models\Objectif;
use App\Services\ReportService;
use App\Services\ReportQueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Rapport::class);

        $query = Rapport::with(['creePar', 'papa', 'objectif'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        if ($request->filled('scope_level')) {
            $query->where('scope_level', $request->scope_level);
        }

        $rapports = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Rapport::count(),
            'generes' => Rapport::where('statut', 'genere')->count(),
            'brouillons' => Rapport::where('statut', 'brouillon')->count(),
            'automatiques' => Rapport::where('est_automatique', true)->count(),
        ];

        return view('rapports.index', compact('rapports', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Rapport::class);

        $user = auth()->user();
        $queryBuilder = new ReportQueryBuilder($user);
        $scopeLevel = $queryBuilder->getScopeLevel();

        // Filtrer les données selon le périmètre
        $papasQuery = Papa::orderBy('annee', 'desc');
        $objectifsQuery = Objectif::with('papaVersion.papa')->orderBy('code');

        if ($scopeLevel === 'COMMISSAIRE') {
            $objectifsQuery->whereHas('actionsPrioritaires', function($q) use ($queryBuilder) {
                $q->forDepartment($queryBuilder->getDepartmentId());
            });
        } elseif ($scopeLevel === 'SG') {
            $objectifsQuery->whereHas('actionsPrioritaires', function($q) {
                $q->forAppui();
            });
        }

        $papas = $papasQuery->get();
        $objectifs = $objectifsQuery->get()->map(function($objectif) {
            return [
                'id' => $objectif->id,
                'libelle' => $objectif->code . ' - ' . $objectif->libelle . ' (' . ($objectif->papaVersion->papa->annee ?? 'N/A') . ')',
            ];
        });

        return view('rapports.create', compact('papas', 'objectifs', 'scopeLevel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRapportRequest $request)
    {
        $this->authorize('create', Rapport::class);

        try {
            $user = auth()->user();
            $queryBuilder = new ReportQueryBuilder($user);
            $scopeLevel = $queryBuilder->getScopeLevel();

            // Vérifier que l'utilisateur peut créer un rapport avec ce scope
            if (!$queryBuilder->canGenerateGlobalReports() && $request->input('scope_level') === 'GLOBAL') {
                throw new \Exception('Vous n\'avez pas l\'autorisation de créer des rapports globaux.');
            }

            $validated = $request->validated();
            $validated['cree_par_id'] = $user->id;
            $validated['statut'] = 'brouillon';
            
            // Définir automatiquement le scope_level si non fourni
            if (!isset($validated['scope_level'])) {
                $validated['scope_level'] = $scopeLevel;
            }

            // Convertir filtres et paramètres en JSON si nécessaire
            if (isset($validated['filtres']) && is_array($validated['filtres'])) {
                $validated['filtres'] = json_encode($validated['filtres']);
            }
            if (isset($validated['parametres']) && is_array($validated['parametres'])) {
                $validated['parametres'] = json_encode($validated['parametres']);
            }

            $rapport = Rapport::create($validated);

            return redirect()->route('rapports.show', $rapport)
                ->with('success', 'Rapport créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du rapport: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du rapport: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapport $rapport)
    {
        $this->authorize('view', $rapport);

        // Incrémenter le nombre de vues
        $rapport->incrementerVues();

        $rapport->load(['creePar', 'papa', 'objectif']);

        return view('rapports.show', compact('rapport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rapport $rapport)
    {
        $this->authorize('update', $rapport);

        $papas = Papa::orderBy('annee', 'desc')->get();
        $objectifs = Objectif::with('papaVersion.papa')
            ->orderBy('code')
            ->get()
            ->map(function($objectif) {
                return [
                    'id' => $objectif->id,
                    'libelle' => $objectif->code . ' - ' . $objectif->libelle . ' (' . ($objectif->papaVersion->papa->annee ?? 'N/A') . ')',
                ];
            });

        return view('rapports.edit', compact('rapport', 'papas', 'objectifs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRapportRequest $request, Rapport $rapport)
    {
        $this->authorize('update', $rapport);

        try {
            $validated = $request->validated();

            // Convertir filtres et paramètres en JSON si nécessaire
            if (isset($validated['filtres']) && is_array($validated['filtres'])) {
                $validated['filtres'] = json_encode($validated['filtres']);
            }
            if (isset($validated['parametres']) && is_array($validated['parametres'])) {
                $validated['parametres'] = json_encode($validated['parametres']);
            }

            $rapport->update($validated);

            return redirect()->route('rapports.show', $rapport)
                ->with('success', 'Rapport mis à jour avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du rapport: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du rapport: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapport $rapport)
    {
        $this->authorize('delete', $rapport);

        try {
            // Supprimer le fichier généré si existe
            if ($rapport->fichier_genere && Storage::exists($rapport->fichier_genere)) {
                Storage::delete($rapport->fichier_genere);
            }

            $rapport->delete();

            return redirect()->route('rapports.index')
                ->with('success', 'Rapport supprimé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression du rapport: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du rapport: ' . $e->getMessage());
        }
    }

    /**
     * Generate the report.
     */
    public function generate(Rapport $rapport)
    {
        $this->authorize('generate', $rapport);

        try {
            DB::beginTransaction();

            // Charger le PAPA si associé pour que le service puisse utiliser son année
            $rapport->load('papa');

            $user = auth()->user();
            $reportService = new ReportService($user);
            
            $fichierPath = $reportService->generateReport($rapport);
            
            // Vérifier que le fichier existe et calculer le checksum
            if (!Storage::exists($fichierPath)) {
                throw new \Exception("Le fichier généré n'existe pas : {$fichierPath}");
            }
            
            // Calculer le checksum à partir du contenu du fichier
            $fileContent = Storage::get($fichierPath);
            $checksum = md5($fileContent);
            
            // Obtenir la taille du fichier
            $fileSize = Storage::size($fichierPath);
            
            $rapport->marquerCommeGenere($fichierPath);
            $rapport->update([
                'checksum' => $checksum,
                'taille_fichier' => $fileSize,
            ]);

            DB::commit();

            return redirect()->route('rapports.show', $rapport)
                ->with('success', 'Rapport généré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la génération du rapport: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du rapport: ' . $e->getMessage());
        }
    }

    /**
     * Download the generated report.
     */
    public function download(Rapport $rapport)
    {
        $this->authorize('download', $rapport);

        if (!$rapport->est_disponible) {
            return redirect()->back()
                ->with('error', 'Le rapport n\'est pas encore généré ou le fichier est introuvable.');
        }

        $rapport->incrementerTelechargements();

        // Utiliser Storage pour télécharger le fichier
        $filePath = $rapport->fichier_genere;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $filename = Str::slug($rapport->titre) . '.' . $extension;

        return Storage::download($filePath, $filename);
    }

    /**
     * Generate the report file based on type and format.
     */
    private function generateReportFile(Rapport $rapport): string
    {
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
     * Generate PDF report.
     */
    private function generatePdf(Rapport $rapport, string $directory, string $filename): string
    {
        $data = $this->getReportData($rapport);
        $view = $this->getReportView($rapport);

        $pdf = Pdf::loadView($view, array_merge($data, ['rapport' => $rapport]));
        
        $filePath = $directory . '/' . $filename . '.pdf';
        \Storage::put($filePath, $pdf->output());

        return $filePath;
    }

    /**
     * Generate Excel report.
     */
    private function generateExcel(Rapport $rapport, string $directory, string $filename): string
    {
        // TODO: Implémenter les exports Excel spécifiques par type
        // Pour l'instant, on utilise les exports existants
        $data = $this->getReportData($rapport);
        
        // Utiliser les exports existants selon le type
        switch ($rapport->type) {
            case 'papa':
                $export = new \App\Exports\PapaExport();
                break;
            case 'objectif':
                $export = new \App\Exports\ObjectifExport($data['objectifs'] ?? collect());
                break;
            case 'kpi':
                $export = new \App\Exports\KpiExport($data['kpis'] ?? collect());
                break;
            case 'alerte':
                $export = new \App\Exports\AlerteExport($data['alertes'] ?? collect());
                break;
            default:
                throw new \Exception('Type de rapport non supporté pour Excel');
        }

        $filePath = $directory . '/' . $filename . '.xlsx';
        Excel::store($export, $filePath);

        return $filePath;
    }

    /**
     * Generate CSV report.
     */
    private function generateCsv(Rapport $rapport, string $directory, string $filename): string
    {
        // Similaire à Excel mais avec format CSV
        return $this->generateExcel($rapport, $directory, $filename);
    }

    /**
     * Generate HTML report.
     */
    private function generateHtml(Rapport $rapport, string $directory, string $filename): string
    {
        $data = $this->getReportData($rapport);
        $view = $this->getReportView($rapport);

        $html = view($view, array_merge($data, ['rapport' => $rapport]))->render();
        
        $filePath = $directory . '/' . $filename . '.html';
        \Storage::put($filePath, $html);

        return $filePath;
    }

    /**
     * Get data for the report based on type.
     */
    private function getReportData(Rapport $rapport): array
    {
        $filtres = $rapport->filtres ?? [];
        $dateDebut = $rapport->date_debut ?? now()->startOfMonth();
        $dateFin = $rapport->date_fin ?? now()->endOfMonth();

        switch ($rapport->type) {
            case 'papa':
                $query = Papa::with('versions');
                if ($rapport->papa_id) {
                    $query->where('id', $rapport->papa_id);
                }
                return ['papas' => $query->get()];

            case 'objectif':
                $query = Objectif::with(['papaVersion.papa', 'actionPrioritaires']);
                if ($rapport->papa_id) {
                    $query->whereHas('papaVersion', function($q) use ($rapport) {
                        $q->where('papa_id', $rapport->papa_id);
                    });
                }
                if ($rapport->objectif_id) {
                    $query->where('id', $rapport->objectif_id);
                }
                return ['objectifs' => $query->get()];

            case 'kpi':
                $query = \App\Models\Kpi::with(['objectif', 'creePar']);
                if (isset($filtres['objectif_id'])) {
                    $query->where('objectif_id', $filtres['objectif_id']);
                }
                return ['kpis' => $query->get()];

            case 'alerte':
                $query = \App\Models\Alerte::with(['tache', 'actionPrioritaire', 'creePar', 'assigneeA']);
                if (isset($filtres['statut'])) {
                    $query->where('statut', $filtres['statut']);
                }
                $query->whereBetween('created_at', [$dateDebut, $dateFin]);
                return ['alertes' => $query->get()];

            case 'synthese':
                return [
                    'papas' => Papa::with('versions')->get(),
                    'objectifs' => Objectif::with(['papaVersion.papa'])->get(),
                    'kpis' => \App\Models\Kpi::with(['objectif'])->get(),
                    'alertes' => \App\Models\Alerte::whereBetween('created_at', [$dateDebut, $dateFin])->get(),
                ];

            default:
                return [];
        }
    }

    /**
     * Get the view name for the report.
     */
    private function getReportView(Rapport $rapport): string
    {
        return 'rapports.templates.' . $rapport->type . '_' . $rapport->format;
    }
}
