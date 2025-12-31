<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Alerte::with([
                'tache',
                'actionPrioritaire',
                'creePar',
                'assigneeA'
            ])
            ->orderBy('date_creation', 'desc');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('criticite')) {
            $query->where('criticite', $request->criticite);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $alertes = $query->paginate(20);

        // Statistiques pour les filtres
        $stats = [
            'total' => Alerte::count(),
            'ouvertes' => Alerte::whereIn('statut', ['ouverte', 'en_cours'])->count(),
            'resolues' => Alerte::where('statut', 'resolue')->count(),
            'critiques' => Alerte::where('criticite', 'critique')->whereIn('statut', ['ouverte', 'en_cours'])->count(),
        ];

        return view('papa.alertes.index', compact('alertes', 'stats'));
    }
    
    public function create()
    {
        // Récupérer les tâches et actions pour les select
        $taches = \App\Models\Tache::whereNull('tache_parent_id')
            ->orderBy('code')
            ->get()
            ->map(function($tache) {
                return [
                    'id' => $tache->id,
                    'libelle' => $tache->code . ' - ' . $tache->libelle,
                ];
            });
        
        $actions = \App\Models\ActionPrioritaire::orderBy('code')
            ->get()
            ->map(function($action) {
                return [
                    'id' => $action->id,
                    'libelle' => $action->code . ' - ' . $action->libelle,
                ];
            });
        
        $users = \App\Models\User::orderBy('name')->get();
        
        return view('papa.alertes.create', compact('taches', 'actions', 'users'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Papa\StoreAlerteRequest $request)
    {
        try {
            $alerte = Alerte::create(array_merge(
                $request->validated(),
                [
                    'cree_par_id' => auth()->id(),
                    'date_creation' => now(),
                ]
            ));
            
            return redirect()
                ->route('alertes.index')
                ->with('success', 'Alerte créée avec succès.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'alerte.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerte $alerte)
    {
        $alerte->load([
            'tache',
            'actionPrioritaire',
            'creePar',
            'assigneeA'
        ]);
        
        return view('papa.alertes.show', compact('alerte'));
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
