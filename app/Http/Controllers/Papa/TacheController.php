<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Tache::with(['actionPrioritaire.objectif.papaVersion.papa', 'responsable', 'tacheParent'])
            ->whereNull('tache_parent_id') // Seulement les tâches principales
            ->orderBy('date_fin_prevue', 'asc');

        // 🔒 SÉCURITÉ : Scope département pour les commissaires
        // Un commissaire ne voit que les tâches de son département
        if ($user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi'])) {
            $departmentId = $user->getDepartmentId();
            if ($departmentId) {
                $query->forDepartment($departmentId);
            }
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par action prioritaire
        if ($request->filled('action_id')) {
            $query->where('action_prioritaire_id', $request->action_id);
        }

        // Filtre par criticité
        if ($request->filled('criticite')) {
            $query->where('criticite', $request->criticite);
        }

        // Filtre par responsable
        if ($request->filled('responsable_id')) {
            $query->where('responsable_id', $request->responsable_id);
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

        // Filtre par date (tâches en retard)
        if ($request->filled('en_retard') && $request->en_retard == '1') {
            $query->where('date_fin_prevue', '<', now())
                  ->where('statut', '!=', 'termine');
        }

        $taches = $query->paginate(20);

        // Récupérer les actions pour le filtre (scoppées par département pour les commissaires)
        $actionsQuery = ActionPrioritaire::orderBy('code');
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
        $statsQuery = Tache::whereNull('tache_parent_id');
        
        // 🔒 SÉCURITÉ : Scope département pour les commissaires
        if ($user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi'])) {
            $departmentId = $user->getDepartmentId();
            if ($departmentId) {
                $statsQuery->forDepartment($departmentId);
            }
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'en_cours' => (clone $statsQuery)->where('statut', 'en_cours')->count(),
            'terminees' => (clone $statsQuery)->where('statut', 'termine')->count(),
            'en_retard' => (clone $statsQuery)->where('statut', 'en_retard')->count(),
            'planifiees' => (clone $statsQuery)->where('statut', 'planifie')->count(),
            'bloquees' => (clone $statsQuery)->where('statut', 'bloque')->count(),
        ];

        return view('papa.taches.index', compact('taches', 'actions', 'stats'));
    }
    
    public function create()
    {
        return view('papa.taches.create');
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
        try {
            $tache = Tache::with([
                'actionPrioritaire.objectif.papaVersion.papa',
                'tacheParent',
                'sousTaches.responsable',
                'responsable',
                'alertes' => function($query) {
                    $query->whereIn('statut', ['ouverte', 'en_cours'])->orderBy('date_creation', 'desc');
                },
                'avancements' => function($query) {
                    $query->orderBy('date_avancement', 'desc')->with('soumisPar', 'validePar');
                },
            ])->findOrFail($id);
            
            // 🔒 SÉCURITÉ : Vérifier que le commissaire peut voir cette tâche
            $this->authorize('view', $tache);

            // Statistiques de la tâche
            $stats = [
                'sous_taches' => $tache->sousTaches->count(),
                'sous_taches_terminees' => $tache->sousTaches->where('statut', 'termine')->count(),
                'avancements' => $tache->avancements->count(),
                'alertes' => $tache->alertes->whereIn('statut', ['ouverte', 'en_cours'])->count(),
            ];

            return view('papa.taches.show', compact('tache', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans TacheController::show: ' . $e->getMessage());
            return redirect()->route('taches.index')
                ->with('error', 'Erreur lors du chargement de la tâche: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tache = Tache::with(['actionPrioritaire.objectif.papaVersion', 'tacheParent', 'responsable'])
            ->findOrFail($id);

        // Récupérer les actions pour le select
        $actions = ActionPrioritaire::orderBy('code')
            ->get()
            ->map(function($action) {
                return [
                    'id' => $action->id,
                    'libelle' => $action->code . ' - ' . $action->libelle,
                ];
            });

        // Récupérer les tâches parentes possibles (tâches principales de la même action)
        $tachesParentes = Tache::where('action_prioritaire_id', $tache->action_prioritaire_id)
            ->whereNull('tache_parent_id')
            ->where('id', '!=', $tache->id)
            ->orderBy('code')
            ->get()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'libelle' => $t->code . ' - ' . $t->libelle,
                ];
            });

        // Récupérer les utilisateurs pour le select responsable
        $users = \App\Models\User::orderBy('name')->get();

        return view('papa.taches.edit', compact('tache', 'actions', 'tachesParentes', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Papa\UpdateTacheRequest $request, string $id)
    {
        try {
            $tache = Tache::findOrFail($id);
            
            $tache->update($request->validated());
            
            return redirect()
                ->route('taches.show', $tache->id)
                ->with('success', 'Tâche mise à jour avec succès.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour de la tâche.']);
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
