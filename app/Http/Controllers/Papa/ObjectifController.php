<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\PapaVersion;
use App\Models\Objectif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObjectifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Objectif::with(['papaVersion.papa', 'actionsPrioritaires'])
            ->orderBy('code', 'asc');

        // Filtre par version
        if ($request->filled('version_id')) {
            $query->where('papa_version_id', $request->version_id);
        }

        // Filtre par statut
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

        $objectifs = $query->paginate(20);

        // Récupérer toutes les versions pour le filtre
        $versions = PapaVersion::with('papa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($version) {
                return [
                    'id' => $version->id,
                    'libelle' => $version->papa->libelle . ' - ' . $version->libelle . ' (V' . $version->numero . ')',
                ];
            });

        // Version sélectionnée
        $versionSelected = null;
        if ($request->filled('version_id')) {
            $versionSelected = PapaVersion::with('papa')->find($request->version_id);
        }

        // Statistiques
        $stats = [
            'total' => Objectif::count(),
            'en_cours' => Objectif::where('statut', 'en_cours')->count(),
            'termines' => Objectif::where('statut', 'termine')->count(),
            'planifies' => Objectif::where('statut', 'planifie')->count(),
        ];

        return view('papa.objectifs.index', compact('objectifs', 'versions', 'versionSelected', 'stats'));
    }
    
    public function create()
    {
        // Récupérer toutes les versions de PAPA avec leurs PAPA associés
        $versions = PapaVersion::with('papa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($version) {
                return [
                    'id' => $version->id,
                    'libelle' => $version->papa->libelle . ' - ' . $version->libelle . ' (V' . $version->numero . ')',
                ];
            });
        
        return view('papa.objectifs.create', [
            'versions' => $versions,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Papa\StoreObjectifRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $objectif = Objectif::create($request->validated());
            
            DB::commit();
            
            return redirect()
                ->route('objectifs.index')
                ->with('success', 'Objectif créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'objectif.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objectif = Objectif::with([
            'papaVersion.papa',
            'actionsPrioritaires.taches' => function($query) {
                $query->whereNull('tache_parent_id')->with('sousTaches');
            },
            'actionsPrioritaires.kpis',
            'actionsPrioritaires.alertes',
        ])->findOrFail($id);

        // Statistiques de l'objectif
        $stats = [
            'actions' => $objectif->actionsPrioritaires->count(),
            'taches' => $objectif->actionsPrioritaires->sum(function($action) {
                return $action->taches->count();
            }),
            'kpis' => $objectif->actionsPrioritaires->sum(function($action) {
                return $action->kpis->count();
            }),
            'alertes' => $objectif->actionsPrioritaires->sum(function($action) {
                return $action->alertes->whereIn('statut', ['ouverte', 'en_cours'])->count();
            }),
        ];

        return view('papa.objectifs.show', compact('objectif', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objectif = Objectif::with('papaVersion.papa')->findOrFail($id);

        // Récupérer toutes les versions de PAPA avec leurs PAPA associés
        $versions = PapaVersion::with('papa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($version) {
                return [
                    'id' => $version->id,
                    'libelle' => $version->papa->libelle . ' - ' . $version->libelle . ' (V' . $version->numero . ')',
                ];
            });

        return view('papa.objectifs.edit', compact('objectif', 'versions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Papa\UpdateObjectifRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $objectif = Objectif::findOrFail($id);
            $objectif->update($request->validated());
            
            DB::commit();
            
            return redirect()
                ->route('objectifs.show', $objectif)
                ->with('success', 'Objectif modifié avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification de l\'objectif.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
