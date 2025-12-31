<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RessourceAdminController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected AuditService $auditService
    ) {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display a listing of resources
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Ressource::class);

        // Vérifier si la table existe
        if (!Schema::hasTable('ressources')) {
            return view('admin.ressources.index', [
                'ressources' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]),
                    0,
                    20,
                    1,
                    ['path' => $request->url(), 'query' => $request->query()]
                ),
            ])->with('warning', 'La table des ressources n\'existe pas encore. Veuillez exécuter : php artisan migrate');
        }

        $query = Ressource::with('creePar');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('est_actif')) {
            $query->where('est_actif', $request->est_actif === '1');
        }

        $ressources = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.ressources.index', compact('ressources'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $this->authorize('create', Ressource::class);

        return view('admin.ressources.create');
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ressource::class);

        // Vérifier si la table existe avant de créer
        if (!Schema::hasTable('ressources')) {
            return redirect()->route('admin.ressources.index')
                ->with('error', 'La table des ressources n\'existe pas encore. Veuillez exécuter : php artisan migrate');
        }

        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:excel,pdf,zip,doc,docx,image,autre'],
            'categorie' => ['required', 'in:general,import,export,documentation,template,autre'],
            'version' => ['nullable', 'string', 'max:20'],
            'fichier' => ['required', 'file', 'max:10240'], // Max 10MB
            'est_public' => ['boolean'],
            'est_actif' => ['boolean'],
            'date_publication' => ['nullable', 'date'],
        ]);

        // Upload du fichier
        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $filename = Str::slug($validated['titre']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ressources', $filename, 'public');

            $validated['fichier'] = $path;
            $validated['nom_fichier_original'] = $file->getClientOriginalName();
            $validated['taille_fichier'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        $validated['cree_par_id'] = auth()->id();
        $validated['est_public'] = $request->has('est_public');
        $validated['est_actif'] = $request->has('est_actif', true);

        try {
            $ressource = Ressource::create($validated);

            $this->auditService->log('created', $ressource, null, 'admin', "Création de la ressource {$ressource->titre}");

            // Rediriger vers la page publique si la requête vient du modal, sinon vers admin
            $redirectRoute = $request->has('from_public') ? 'ressources' : 'admin.ressources.index';
            
            return redirect()->route($redirectRoute)
                ->with('success', 'Ressource créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la ressource: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de la ressource : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource
     */
    public function show(Ressource $ressource)
    {
        $this->authorize('view', $ressource);

        $ressource->load('creePar');

        return view('admin.ressources.show', compact('ressource'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Ressource $ressource)
    {
        $this->authorize('update', $ressource);

        return view('admin.ressources.edit', compact('ressource'));
    }

    /**
     * Update the specified resource
     */
    public function update(Request $request, Ressource $ressource)
    {
        $this->authorize('update', $ressource);

        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:excel,pdf,zip,doc,docx,image,autre'],
            'categorie' => ['required', 'in:general,import,export,documentation,template,autre'],
            'version' => ['nullable', 'string', 'max:20'],
            'fichier' => ['nullable', 'file', 'max:10240'],
            'est_public' => ['boolean'],
            'est_actif' => ['boolean'],
            'date_publication' => ['nullable', 'date'],
        ]);

        // Upload d'un nouveau fichier si fourni
        if ($request->hasFile('fichier')) {
            // Supprimer l'ancien fichier
            if ($ressource->fichier && Storage::disk('public')->exists($ressource->fichier)) {
                Storage::disk('public')->delete($ressource->fichier);
            }

            $file = $request->file('fichier');
            $filename = Str::slug($validated['titre']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ressources', $filename, 'public');

            $validated['fichier'] = $path;
            $validated['nom_fichier_original'] = $file->getClientOriginalName();
            $validated['taille_fichier'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        $validated['est_public'] = $request->has('est_public');
        $validated['est_actif'] = $request->has('est_actif', true);

        $ressource->update($validated);

        $this->auditService->log('updated', $ressource, ['changes' => $ressource->getChanges()], 'admin', "Modification de la ressource {$ressource->titre}");

        return redirect()->route('admin.ressources.index')
            ->with('success', 'Ressource mise à jour avec succès.');
    }

    /**
     * Remove the specified resource
     */
    public function destroy(Ressource $ressource)
    {
        $this->authorize('delete', $ressource);

        // Supprimer le fichier
        if ($ressource->fichier && Storage::disk('public')->exists($ressource->fichier)) {
            Storage::disk('public')->delete($ressource->fichier);
        }

        $titre = $ressource->titre;
        $ressource->delete();

        $this->auditService->log('deleted', $ressource, null, 'admin', "Suppression de la ressource {$titre}");

        return redirect()->route('admin.ressources.index')
            ->with('success', 'Ressource supprimée avec succès.');
    }
}

