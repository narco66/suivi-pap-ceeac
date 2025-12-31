<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Papa;
use Illuminate\Http\Request;

class PapaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Papa::with(['versions'])
            ->orderBy('annee', 'desc');

        // Filtres
        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('libelle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $papas = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Papa::count(),
            'actifs' => Papa::whereIn('statut', ['actif', 'en_cours'])->count(),
            'verrouilles' => Papa::where('statut', 'verrouille')->count(),
            'archives' => Papa::where('statut', 'archive')->count(),
        ];

        // Années disponibles pour le filtre
        $annees = Papa::select('annee')
            ->distinct()
            ->orderBy('annee', 'desc')
            ->pluck('annee');

        return view('papa.index', compact('papas', 'stats', 'annees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('papa.create');
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
    public function show(string $id)
    {
        $papa = Papa::with([
            'versions.objectifs.actionsPrioritaires.taches',
            'versions.objectifs.actionsPrioritaires.kpis',
        ])->findOrFail($id);

        // Statistiques du PAPA
        $stats = [
            'versions' => $papa->versions->count(),
            'objectifs' => $papa->versions->sum(function($version) {
                return $version->objectifs->count();
            }),
            'actions' => $papa->versions->sum(function($version) {
                return $version->objectifs->sum(function($objectif) {
                    return $objectif->actionsPrioritaires->count();
                });
            }),
            'taches' => $papa->versions->sum(function($version) {
                return $version->objectifs->sum(function($objectif) {
                    return $objectif->actionsPrioritaires->sum(function($action) {
                        return $action->taches->count();
                    });
                });
            }),
        ];

        return view('papa.show', compact('papa', 'stats'));
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
