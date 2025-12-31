<?php

namespace App\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Departement::withCount('directionsTechniques')
            ->orderBy('code', 'asc');

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

        $departements = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Departement::count(),
            'actifs' => Departement::where('actif', true)->count(),
            'inactifs' => Departement::where('actif', false)->count(),
        ];

        return view('referentiel.departements.index', compact('departements', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('referentiel.departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Referentiel\StoreDepartementRequest $request)
    {
        try {
            $departement = Departement::create($request->validated());

            return redirect()->route('departements.show', $departement)
                ->with('success', 'Département créé avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du département: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du département. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $departement = Departement::with(['directionsTechniques' => function($query) {
            $query->orderBy('code', 'asc');
        }])->findOrFail($id);

        // Statistiques du département
        $stats = [
            'directions_total' => $departement->directionsTechniques->count(),
            'directions_actives' => $departement->directionsTechniques->where('actif', true)->count(),
            'directions_inactives' => $departement->directionsTechniques->where('actif', false)->count(),
        ];

        return view('referentiel.departements.show', compact('departement', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $departement = Departement::findOrFail($id);
        
        $this->authorize('update', $departement);

        return view('referentiel.departements.edit', compact('departement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Referentiel\UpdateDepartementRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $departement = Departement::findOrFail($id);
            $departement->update($request->validated());
            
            DB::commit();
            
            return redirect()
                ->route('departements.show', $departement)
                ->with('success', 'Département modifié avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification du département.']);
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
