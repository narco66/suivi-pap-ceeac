<?php

namespace App\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Commissaire;
use Illuminate\Http\Request;

class CommissaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commissaire::with('commission')
            ->orderBy('nom', 'asc')
            ->orderBy('prenom', 'asc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('pays_origine', 'like', "%{$search}%")
                  ->orWhereHas('commission', function($q) use ($search) {
                      $q->where('libelle', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('commission_id')) {
            $query->where('commission_id', $request->commission_id);
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif == '1');
        }

        if ($request->filled('pays_origine')) {
            $query->where('pays_origine', $request->pays_origine);
        }

        $commissaires = $query->paginate(20);

        // Récupérer les commissions pour le filtre
        $commissions = \App\Models\Commission::where('actif', true)
            ->orderBy('libelle')
            ->get()
            ->map(function($commission) {
                return [
                    'id' => $commission->id,
                    'libelle' => $commission->code . ' - ' . $commission->libelle,
                ];
            });

        // Récupérer les pays d'origine uniques
        $pays = Commissaire::whereNotNull('pays_origine')
            ->distinct()
            ->orderBy('pays_origine')
            ->pluck('pays_origine');

        // Statistiques
        $stats = [
            'total' => Commissaire::count(),
            'actifs' => Commissaire::where('actif', true)->count(),
            'inactifs' => Commissaire::where('actif', false)->count(),
        ];

        return view('referentiel.commissaires.index', compact('commissaires', 'commissions', 'pays', 'stats'));
    }

    public function create()
    {
        // Récupérer les commissions actives
        $commissions = \App\Models\Commission::where('actif', true)
            ->orderBy('libelle')
            ->get()
            ->map(function($commission) {
                return [
                    'id' => $commission->id,
                    'libelle' => $commission->code . ' - ' . $commission->libelle,
                ];
            });

        return view('referentiel.commissaires.create', compact('commissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Referentiel\StoreCommissaireRequest $request)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $commissaire = Commissaire::create($request->validated());
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()
                ->route('commissaires.index')
                ->with('success', 'Commissaire créé avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création du commissaire.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commissaire = Commissaire::with('commission')->findOrFail($id);

        return view('referentiel.commissaires.show', compact('commissaire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commissaire = Commissaire::findOrFail($id);

        // Récupérer les commissions actives
        $commissions = \App\Models\Commission::where('actif', true)
            ->orderBy('libelle')
            ->get()
            ->map(function($commission) {
                return [
                    'id' => $commission->id,
                    'libelle' => $commission->code . ' - ' . $commission->libelle,
                ];
            });

        return view('referentiel.commissaires.edit', compact('commissaire', 'commissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Referentiel\UpdateCommissaireRequest $request, string $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $commissaire = Commissaire::findOrFail($id);
            $commissaire->update($request->validated());
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()
                ->route('commissaires.show', $commissaire)
                ->with('success', 'Commissaire modifié avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification du commissaire.']);
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
