<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Avancement;
use Illuminate\Http\Request;

class AvancementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Avancement::with([
            'tache.actionPrioritaire.objectif.papaVersion.papa',
            'soumisPar',
            'validePar',
        ])->orderBy('date_avancement', 'desc');

        // Filtres
        if ($request->filled('tache_id')) {
            $query->where('tache_id', $request->tache_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('soumis_par_id')) {
            $query->where('soumis_par_id', $request->soumis_par_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_avancement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_avancement', '<=', $request->date_fin);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('commentaire', 'like', "%{$search}%")
                  ->orWhereHas('tache', function($q) use ($search) {
                      $q->where('code', 'like', "%{$search}%")
                        ->orWhere('libelle', 'like', "%{$search}%");
                  });
            });
        }

        $avancements = $query->paginate(20);

        // Récupérer les tâches pour le filtre
        $taches = \App\Models\Tache::whereNull('tache_parent_id')
            ->orderBy('code')
            ->get()
            ->map(function($tache) {
                return [
                    'id' => $tache->id,
                    'libelle' => $tache->code . ' - ' . $tache->libelle,
                ];
            });

        // Récupérer les utilisateurs pour le filtre
        $utilisateurs = \App\Models\User::where('status', 'actif')
            ->orWhere('actif', true)
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name . ' (' . $user->email . ')',
                ];
            });

        // Statistiques
        $stats = [
            'total' => Avancement::count(),
            'en_attente' => Avancement::where('statut', 'en_attente')->count(),
            'valides' => Avancement::where('statut', 'valide')->count(),
            'rejetes' => Avancement::where('statut', 'rejete')->count(),
        ];

        return view('papa.avancements.index', compact('avancements', 'taches', 'utilisateurs', 'stats'));
    }
    
    public function create()
    {
        return view('papa.avancements.create');
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
        //
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
