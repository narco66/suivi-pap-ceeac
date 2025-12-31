<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StructureController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected AuditService $auditService
    ) {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display a listing of structures
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Structure::class);

        $query = Structure::with('parent', 'children');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $structures = $query->orderBy('order')->orderBy('name')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Structure::count(),
            'actives' => Structure::where('is_active', true)->count(),
            'inactives' => Structure::where('is_active', false)->count(),
        ];

        // Types disponibles pour le filtre
        $types = Structure::distinct()->pluck('type')->sort()->values();

        return view('admin.structures.index', compact('structures', 'stats', 'types'));
    }

    /**
     * Show the form for creating a new structure
     */
    public function create()
    {
        $this->authorize('create', Structure::class);

        $parentStructures = Structure::active()->orderBy('name')->get();

        return view('admin.structures.create', compact('parentStructures'));
    }

    /**
     * Store a newly created structure
     */
    public function store(Request $request)
    {
        $this->authorize('create', Structure::class);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:structures,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:structures,id'],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $structure = Structure::create($validated);

        $this->auditService->log('created', $structure, null, 'admin', "Création de la structure {$structure->name}");

        return redirect()->route('admin.structures.index')
            ->with('success', 'Structure créée avec succès.');
    }

    /**
     * Display the specified structure
     */
    public function show(Structure $structure)
    {
        $this->authorize('view', $structure);

        $structure->load('parent', 'children', 'users');

        return view('admin.structures.show', compact('structure'));
    }

    /**
     * Show the form for editing the specified structure
     */
    public function edit(Structure $structure)
    {
        $this->authorize('update', $structure);

        $parentStructures = Structure::where('id', '!=', $structure->id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.structures.edit', compact('structure', 'parentStructures'));
    }

    /**
     * Update the specified structure
     */
    public function update(Request $request, Structure $structure)
    {
        $this->authorize('update', $structure);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:structures,code,' . $structure->id],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:structures,id'],
            'description' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $structure->update($validated);

        $this->auditService->log('updated', $structure, ['changes' => $structure->getChanges()], 'admin', "Modification de la structure {$structure->name}");

        return redirect()->route('admin.structures.index')
            ->with('success', 'Structure mise à jour avec succès.');
    }

    /**
     * Remove the specified structure
     */
    public function destroy(Structure $structure)
    {
        $this->authorize('delete', $structure);

        // Vérifier s'il y a des utilisateurs ou des structures enfants
        if ($structure->users()->count() > 0) {
            return redirect()->route('admin.structures.index')
                ->with('error', 'Impossible de supprimer une structure ayant des utilisateurs.');
        }

        if ($structure->children()->count() > 0) {
            return redirect()->route('admin.structures.index')
                ->with('error', 'Impossible de supprimer une structure ayant des structures enfants.');
        }

        $structureName = $structure->name;
        $structure->delete();

        $this->auditService->log('deleted', $structure, null, 'admin', "Suppression de la structure {$structureName}");

        return redirect()->route('admin.structures.index')
            ->with('success', 'Structure supprimée avec succès.');
    }
}

