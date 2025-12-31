<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Structure;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected AuditService $auditService
    ) {
        // Le middleware est géré au niveau des routes
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with(['roles', 'structure']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'actif') {
                $query->where(function($q) {
                    $q->where('status', 'actif')
                      ->orWhere(function($q2) {
                          $q2->whereNull('status')->where('actif', true);
                      });
                });
            } elseif ($status === 'suspendu') {
                $query->where('status', 'suspendu');
            } elseif ($status === 'inactif') {
                $query->where(function($q) {
                    $q->where('status', 'inactif')
                      ->orWhere(function($q2) {
                          $q2->whereNull('status')->where('actif', false);
                      });
                });
            }
        }

        // Filtre par structure
        if ($request->filled('structure_id')) {
            $query->where('structure_id', $request->structure_id);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        $structures = Structure::active()->orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles', 'structures'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::all();
        $structures = Structure::active()->orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'structures'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'matricule' => ['nullable', 'string', 'max:32', 'unique:users'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
            'fonction' => ['nullable', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'structure_id' => ['nullable', 'exists:structures,id'],
            'status' => ['nullable', 'in:actif,suspendu,inactif'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'matricule' => $validated['matricule'] ?? null,
            'telephone' => $validated['telephone'] ?? $validated['phone'] ?? null,
            'phone' => $validated['phone'] ?? $validated['telephone'] ?? null,
            'fonction' => $validated['fonction'] ?? $validated['title'] ?? null,
            'title' => $validated['title'] ?? $validated['fonction'] ?? null,
            'structure_id' => $validated['structure_id'] ?? null,
            'status' => $validated['status'] ?? 'actif',
            'actif' => ($validated['status'] ?? 'actif') === 'actif',
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        $this->auditService->log('created', $user, null, 'admin', "Création de l'utilisateur {$user->name}");

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['roles', 'structure', 'permissions']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::all();
        $structures = Structure::active()->orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles', 'structures'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'matricule' => ['nullable', 'string', 'max:32', 'unique:users,matricule,' . $user->id],
            'telephone' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
            'fonction' => ['nullable', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'structure_id' => ['nullable', 'exists:structures,id'],
            'status' => ['nullable', 'in:actif,suspendu,inactif'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'matricule' => $validated['matricule'] ?? $user->matricule,
            'telephone' => $validated['telephone'] ?? $validated['phone'] ?? $user->telephone,
            'phone' => $validated['phone'] ?? $validated['telephone'] ?? $user->phone,
            'fonction' => $validated['fonction'] ?? $validated['title'] ?? $user->fonction,
            'title' => $validated['title'] ?? $validated['fonction'] ?? $user->title,
            'structure_id' => $validated['structure_id'] ?? $user->structure_id,
            'status' => $validated['status'] ?? $user->status,
            'actif' => ($validated['status'] ?? $user->status ?? 'actif') === 'actif',
        ]);

        if (isset($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        $this->auditService->log('updated', $user, ['changes' => $user->getChanges()], 'admin', "Modification de l'utilisateur {$user->name}");

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $userName = $user->name;
        $user->delete();

        $this->auditService->log('deleted', $user, null, 'admin', "Suppression de l'utilisateur {$userName}");

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        $this->authorize('update', $user);

        $user->update(['status' => 'actif', 'actif' => true]);

        $this->auditService->log('activated', $user, null, 'admin', "Activation de l'utilisateur {$user->name}");

        return redirect()->back()->with('success', 'Utilisateur activé avec succès.');
    }

    /**
     * Suspend user
     */
    public function suspend(User $user)
    {
        $this->authorize('update', $user);

        $user->update(['status' => 'suspendu', 'actif' => false]);

        $this->auditService->log('deactivated', $user, null, 'admin', "Suspension de l'utilisateur {$user->name}");

        return redirect()->back()->with('success', 'Utilisateur suspendu avec succès.');
    }
}

