<?php

namespace App\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commission::withCount('commissaires')
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

        $commissions = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => Commission::count(),
            'actives' => Commission::where('actif', true)->count(),
            'inactives' => Commission::where('actif', false)->count(),
        ];

        return view('referentiel.commissions.index', compact('commissions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('referentiel.commissions.create');
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
        $commission = Commission::with(['commissaires' => function($query) {
            $query->orderBy('nom', 'asc')->orderBy('prenom', 'asc');
        }])->findOrFail($id);

        // Statistiques de la commission
        $stats = [
            'commissaires_total' => $commission->commissaires->count(),
            'commissaires_actifs' => $commission->commissaires->where('actif', true)->count(),
            'commissaires_inactifs' => $commission->commissaires->where('actif', false)->count(),
        ];

        return view('referentiel.commissions.show', compact('commission', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $commission = Commission::findOrFail($id);

        return view('referentiel.commissions.edit', compact('commission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Referentiel\UpdateCommissionRequest $request, string $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            $commission = Commission::findOrFail($id);
            $commission->update($request->validated());
            
            \Illuminate\Support\Facades\DB::commit();
            
            return redirect()
                ->route('commissions.show', $commission)
                ->with('success', 'Commission modifiée avec succès.');
                
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification de la commission.']);
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
