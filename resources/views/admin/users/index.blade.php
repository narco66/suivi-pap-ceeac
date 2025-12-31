<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-people me-2"></i>Gestion des Utilisateurs
            </h2>
            @can('create', \App\Models\User::class)
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer un utilisateur
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Total</div>
                            <div class="h3 mb-0 text-ceeac-blue">{{ $users->total() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-green me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Actifs</div>
                            <div class="h3 mb-0 text-success">{{ \App\Models\User::where('status', 'actif')->orWhere(function($q) { $q->whereNull('status')->where('actif', true); })->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-yellow me-3">
                            <i class="bi bi-pause-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Suspendus</div>
                            <div class="h3 mb-0 text-warning">{{ \App\Models\User::where('status', 'suspendu')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-secondary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-gray me-3">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Inactifs</div>
                            <div class="h3 mb-0 text-secondary">{{ \App\Models\User::where('status', 'inactif')->orWhere(function($q) { $q->whereNull('status')->where('actif', false); })->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres de recherche
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Recherche
                    </label>
                    <input type="text" class="form-control form-control-ceeac" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nom, email, matricule...">
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Rôle
                    </label>
                    <select class="form-select form-control-ceeac" id="role" name="role">
                        <option value="">Tous</option>
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label fw-semibold">
                        <i class="bi bi-info-circle me-1"></i>Statut
                    </label>
                    <select class="form-select form-control-ceeac" id="status" name="status">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('status') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ request('status') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="inactif" {{ request('status') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="structure_id" class="form-label fw-semibold">
                        <i class="bi bi-building me-1"></i>Structure
                    </label>
                    <select class="form-select form-control-ceeac" id="structure_id" name="structure_id">
                        <option value="">Toutes</option>
                        @foreach(\App\Models\Structure::active()->orderBy('name')->get() as $structure)
                            <option value="{{ $structure->id }}" {{ request('structure_id') == $structure->id ? 'selected' : '' }}>
                                {{ $structure->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-ceeac w-100">
                        <i class="bi bi-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des utilisateurs
                    <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                </h5>
                @can('create', \App\Models\User::class)
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-plus-circle me-2"></i>Créer un utilisateur
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Matricule</th>
                                <th>Rôles</th>
                                <th>Structure</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->title)
                                            <br><small class="text-muted">{{ $user->title }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->matricule ?? '-' }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary me-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->structure?->name ?? '-' }}</td>
                                    <td>
                                        @if($user->isActive())
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($user->isSuspended())
                                            <span class="badge bg-warning">Suspendu</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->diffForHumans() }}
                                        @else
                                            <span class="text-muted">Jamais</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('view', $user)
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary" title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $user)
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $user)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                            @if($user->isActive() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning" title="Suspendre" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                                                        <i class="bi bi-pause-circle"></i>
                                                    </button>
                                                </form>
                                            @elseif($user->isSuspended() && $user->id !== auth()->id())
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Activer">
                                                        <i class="bi bi-play-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Aucun utilisateur trouvé.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


