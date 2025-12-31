<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\ActionPrioritaire;
use Illuminate\Http\Request;

class ActionPrioritaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ActionPrioritaire::with([
            'objectif.papaVersion.papa',
            'taches' => function($q) {
                $q->whereNull('tache_parent_id');
            },
        ]);

        // Filtres
        if ($request->filled('objectif_id')) {
            $query->where('objectif_id', $request->objectif_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->filled('criticite')) {
            $query->where('criticite', $request->criticite);
        }

        if ($request->filled('papa_id')) {
            $query->whereHas('objectif.papaVersion', function($q) use ($request) {
                $q->where('papa_id', $request->papa_id);
            });
        }

        if ($request->filled('version_id')) {
            $query->whereHas('objectif', function($q) use ($request) {
                $q->where('papa_version_id', $request->version_id);
            });
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

        // Filtre par date (actions en retard)
        if ($request->filled('en_retard') && $request->en_retard == '1') {
            $query->where('date_fin_prevue', '<', now())
                  ->where('statut', '!=', 'termine');
        }

        $actions = $query->orderBy('date_fin_prevue', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Récupérer les objectifs pour le filtre
        $objectifs = \App\Models\Objectif::with('papaVersion.papa')
            ->orderBy('code')
            ->get()
            ->map(function($objectif) {
                return [
                    'id' => $objectif->id,
                    'libelle' => $objectif->code . ' - ' . $objectif->libelle . ' (' . ($objectif->papaVersion->papa->annee ?? 'N/A') . ')',
                ];
            });

        // Statistiques
        $stats = [
            'total' => ActionPrioritaire::count(),
            'par_statut' => ActionPrioritaire::selectRaw('statut, count(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut'),
            'en_retard' => ActionPrioritaire::where('date_fin_prevue', '<', now())
                ->where('statut', '!=', 'termine')
                ->count(),
            'en_cours' => ActionPrioritaire::where('statut', 'en_cours')->count(),
            'terminees' => ActionPrioritaire::where('statut', 'termine')->count(),
        ];

        return view('papa.actions-prioritaires.index', compact('actions', 'objectifs', 'stats'));
    }
    
    public function create()
    {
        $objectifs = \App\Models\Objectif::with('papaVersion.papa')
            ->orderBy('code')
            ->get();
        
        $directionsTechniques = \App\Models\DirectionTechnique::where('actif', true)->orderBy('libelle')->get();
        $directionsAppui = \App\Models\DirectionAppui::where('actif', true)->orderBy('libelle')->get();
        
        return view('papa.actions-prioritaires.create', compact('objectifs', 'directionsTechniques', 'directionsAppui'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'objectif_id' => ['required', 'exists:objectifs,id'],
            'code' => ['required', 'string', 'max:32', 'unique:actions_prioritaires,code'],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:technique,appui,administratif,autre'],
            'direction_technique_id' => ['nullable', 'exists:directions_techniques,id'],
            'direction_appui_id' => ['nullable', 'exists:directions_appui,id'],
            'statut' => ['nullable', 'string', 'in:brouillon,planifie,en_cours,en_attente,termine,annule'],
            'priorite' => ['nullable', 'string', 'in:faible,normale,moyenne,elevee,critique'],
            'criticite' => ['nullable', 'string', 'in:faible,normal,moyenne,elevee,critique'],
            'date_debut_prevue' => ['nullable', 'date'],
            'date_fin_prevue' => ['nullable', 'date', 'after_or_equal:date_debut_prevue'],
            'date_debut_reelle' => ['nullable', 'date'],
            'date_fin_reelle' => ['nullable', 'date', 'after_or_equal:date_debut_reelle'],
            'pourcentage_avancement' => ['nullable', 'integer', 'min:0', 'max:100'],
            'bloque' => ['nullable', 'boolean'],
            'raison_blocage' => ['nullable', 'string', 'required_if:bloque,1'],
        ]);

        try {
            $action = ActionPrioritaire::create($validated);

            return redirect()->route('actions-prioritaires.show', $action)
                ->with('success', 'Action prioritaire créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de l\'action prioritaire: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'action prioritaire. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $action = ActionPrioritaire::with([
            'objectif.papaVersion.papa',
            'taches' => function($query) {
                $query->whereNull('tache_parent_id')->with(['sousTaches', 'responsable']);
            },
            'kpis',
            'alertes' => function($query) {
                $query->whereIn('statut', ['ouverte', 'en_cours'])->orderBy('date_creation', 'desc');
            },
        ])->findOrFail($id);

        // Statistiques de l'action
        $stats = [
            'taches' => $action->taches->whereNull('tache_parent_id')->count(),
            'taches_terminees' => $action->taches->whereNull('tache_parent_id')->where('statut', 'termine')->count(),
            'taches_en_cours' => $action->taches->whereNull('tache_parent_id')->where('statut', 'en_cours')->count(),
            'taches_en_retard' => $action->taches->whereNull('tache_parent_id')->where('statut', 'en_retard')->count(),
            'kpis' => $action->kpis->count(),
            'alertes' => $action->alertes->whereIn('statut', ['ouverte', 'en_cours'])->count(),
        ];

        return view('papa.actions-prioritaires.show', compact('action', 'stats'));
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
