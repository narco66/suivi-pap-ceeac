<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Kpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Kpi::with([
            'actionPrioritaire.objectif.papaVersion.papa',
        ])->orderBy('date_mesure', 'desc');

        // 🔒 SÉCURITÉ : Scope département pour les commissaires
        // Un commissaire ne voit que les KPIs de son département
        if ($user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi'])) {
            $departmentId = $user->getDepartmentId();
            if ($departmentId) {
                $query->forDepartment($departmentId);
            }
        }

        // Filtres
        if ($request->filled('action_prioritaire_id')) {
            $query->where('action_prioritaire_id', $request->action_prioritaire_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_mesure', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_mesure', '<=', $request->date_fin);
        }

        // Filtre par performance (sous objectif, atteint, dépassé)
        if ($request->filled('performance')) {
            $performance = $request->performance;
            if ($performance === 'sous_objectif') {
                $query->whereColumn('valeur_realisee', '<', 'valeur_cible');
            } elseif ($performance === 'atteint') {
                $query->whereColumn('valeur_realisee', '>=', 'valeur_cible')
                      ->whereColumn('valeur_realisee', '<=', DB::raw('valeur_cible * 1.1'));
            } elseif ($performance === 'depasse') {
                $query->whereColumn('valeur_realisee', '>', DB::raw('valeur_cible * 1.1'));
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('libelle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('actionPrioritaire', function($q) use ($search) {
                      $q->where('code', 'like', "%{$search}%")
                        ->orWhere('libelle', 'like', "%{$search}%");
                  });
            });
        }

        $kpis = $query->paginate(20);

        // Récupérer les actions pour le filtre (scoppées par département pour les commissaires)
        $actionsQuery = \App\Models\ActionPrioritaire::orderBy('code');
        if ($user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi'])) {
            $departmentId = $user->getDepartmentId();
            if ($departmentId) {
                $actionsQuery->forDepartment($departmentId);
            }
        }
        $actions = $actionsQuery->get()
            ->map(function($action) {
                return [
                    'id' => $action->id,
                    'libelle' => $action->code . ' - ' . $action->libelle,
                ];
            });

        // Statistiques (scoppées par département pour les commissaires)
        $statsQuery = Kpi::query();
        
        // 🔒 SÉCURITÉ : Scope département pour les commissaires
        if ($user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi'])) {
            $departmentId = $user->getDepartmentId();
            if ($departmentId) {
                $statsQuery->forDepartment($departmentId);
            }
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'atteints' => (clone $statsQuery)->whereColumn('valeur_realisee', '>=', 'valeur_cible')->count(),
            'sous_objectif' => (clone $statsQuery)->whereColumn('valeur_realisee', '<', 'valeur_cible')->count(),
            'depasses' => (clone $statsQuery)->whereColumn('valeur_realisee', '>', DB::raw('valeur_cible * 1.1'))->count(),
        ];

        return view('papa.kpis.index', compact('kpis', 'actions', 'stats'));
    }
    
    public function create()
    {
        return view('papa.kpis.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();
        
        $kpi = Kpi::with([
            'actionPrioritaire.objectif.papaVersion.papa',
            'alertes' => function($query) {
                $query->whereIn('statut', ['ouverte', 'en_cours'])->orderBy('date_creation', 'desc');
            },
        ])->findOrFail($id);

        // 🔒 SÉCURITÉ : Vérifier que le commissaire peut voir ce KPI
        $this->authorize('view', $kpi);

        // Calculer les statistiques si non définies
        if (!$kpi->valeur_ecart && $kpi->valeur_realisee !== null && $kpi->valeur_cible !== null) {
            $kpi->valeur_ecart = $kpi->valeur_realisee - $kpi->valeur_cible;
        }

        if (!$kpi->pourcentage_realisation && $kpi->valeur_cible > 0 && $kpi->valeur_realisee !== null) {
            $kpi->pourcentage_realisation = ($kpi->valeur_realisee / $kpi->valeur_cible) * 100;
        }

        // Statistiques du KPI
        $stats = [
            'performance' => $kpi->valeur_cible > 0 && $kpi->valeur_realisee !== null 
                ? ($kpi->valeur_realisee >= $kpi->valeur_cible ? 'atteint' : 'sous_objectif')
                : 'non_mesure',
            'alertes' => $kpi->alertes->count(),
        ];

        return view('papa.kpis.show', compact('kpi', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
