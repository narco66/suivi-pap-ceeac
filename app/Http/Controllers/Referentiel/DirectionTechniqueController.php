<?php

namespace App\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\DirectionTechnique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectionTechniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', DirectionTechnique::class);

        $query = DirectionTechnique::with('departement')->orderBy('code', 'asc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('libelle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif == '1');
        }

        if ($request->filled('departement_id')) {
            $query->where('departement_id', $request->departement_id);
        }

        $directionsTechniques = $query->paginate(20);

        // Récupérer les départements pour le filtre
        $departements = \App\Models\Departement::where('actif', true)
            ->orderBy('libelle', 'asc')
            ->get()
            ->map(function($departement) {
                return [
                    'id' => $departement->id,
                    'libelle' => $departement->code . ' - ' . $departement->libelle,
                ];
            });

        // Statistiques
        $stats = [
            'total' => DirectionTechnique::count(),
            'actifs' => DirectionTechnique::where('actif', true)->count(),
            'inactifs' => DirectionTechnique::where('actif', false)->count(),
        ];

        return view('referentiel.directions-techniques.index', compact('directionsTechniques', 'stats', 'departements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departements = \App\Models\Departement::where('actif', true)
            ->orderBy('libelle', 'asc')
            ->get();
        
        return view('referentiel.directions-techniques.create', compact('departements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Referentiel\StoreDirectionTechniqueRequest $request)
    {
        try {
            $directionTechnique = DirectionTechnique::create($request->validated());

            return redirect()->route('directions-techniques.show', $directionTechnique)
                ->with('success', 'Direction technique créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la direction technique: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la direction technique. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $directionTechnique = DirectionTechnique::with('departement')->findOrFail($id);

        $this->authorize('view', $directionTechnique);

        // Statistiques (si nécessaire pour des relations futures)
        $stats = [
            'departement' => $directionTechnique->departement ? $directionTechnique->departement->libelle : 'Non assigné',
        ];

        return view('referentiel.directions-techniques.show', compact('directionTechnique', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $directionTechnique = DirectionTechnique::with('departement')->findOrFail($id);
        
        $this->authorize('update', $directionTechnique);

        // Récupérer les départements actifs pour le formulaire
        $departements = \App\Models\Departement::where('actif', true)
            ->orderBy('libelle', 'asc')
            ->get()
            ->map(function($departement) {
                return [
                    'id' => $departement->id,
                    'libelle' => $departement->code . ' - ' . $departement->libelle,
                ];
            });

        return view('referentiel.directions-techniques.edit', compact('directionTechnique', 'departements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Referentiel\UpdateDirectionTechniqueRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $directionTechnique = DirectionTechnique::findOrFail($id);
            $directionTechnique->update($request->validated());
            
            DB::commit();
            
            return redirect()
                ->route('directions-techniques.show', $directionTechnique)
                ->with('success', 'Direction technique modifiée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification de la direction technique.']);
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
