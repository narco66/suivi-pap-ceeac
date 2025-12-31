<?php

namespace App\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\DirectionAppui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DirectionAppuiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', DirectionAppui::class);

        $query = DirectionAppui::orderBy('code', 'asc');

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

        $directionsAppui = $query->paginate(20);

        // Statistiques
        $stats = [
            'total' => DirectionAppui::count(),
            'actifs' => DirectionAppui::where('actif', true)->count(),
            'inactifs' => DirectionAppui::where('actif', false)->count(),
        ];

        return view('referentiel.directions-appui.index', compact('directionsAppui', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('referentiel.directions-appui.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Referentiel\StoreDirectionAppuiRequest $request)
    {
        try {
            $directionAppui = DirectionAppui::create($request->validated());

            return redirect()->route('directions-appui.show', $directionAppui)
                ->with('success', 'Direction d\'appui créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la direction d\'appui: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la direction d\'appui. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $directionAppui = DirectionAppui::findOrFail($id);

        $this->authorize('view', $directionAppui);

        // Statistiques (si nécessaire pour des relations futures)
        $stats = [];

        return view('referentiel.directions-appui.show', compact('directionAppui', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $directionAppui = DirectionAppui::findOrFail($id);
        
        $this->authorize('update', $directionAppui);

        return view('referentiel.directions-appui.edit', compact('directionAppui'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Referentiel\UpdateDirectionAppuiRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $directionAppui = DirectionAppui::findOrFail($id);
            $directionAppui->update($request->validated());
            
            DB::commit();
            
            return redirect()
                ->route('directions-appui.show', $directionAppui)
                ->with('success', 'Direction d\'appui modifiée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la modification de la direction d\'appui.']);
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
