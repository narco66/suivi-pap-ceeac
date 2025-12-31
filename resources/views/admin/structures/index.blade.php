<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-building me-2"></i>Gestion des Structures
            </h2>
            @can('create', \App\Models\Structure::class)
                <a href="{{ route('admin.structures.create') }}" class="btn btn-ceeac-primary">
                    <i class="bi bi-plus-circle me-2"></i>Nouvelle structure
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques -->
    @if(isset($stats))
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-ceeac-blue mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success mb-0">{{ $stats['actives'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Actives</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-secondary mb-0">{{ $stats['inactives'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Inactives</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.structures.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, nom...">
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">Tous les types</option>
                        @foreach($types ?? [] as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="is_active" class="form-label">Statut</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actives</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-funnel me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($structures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Parent</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($structures as $structure)
                                <tr>
                                    <td><code>{{ $structure->code }}</code></td>
                                    <td><strong>{{ $structure->name }}</strong></td>
                                    <td><span class="badge bg-secondary">{{ $structure->type }}</span></td>
                                    <td>{{ $structure->parent?->name ?? '-' }}</td>
                                    <td>
                                        @if($structure->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $structure)
                                                <a href="{{ route('admin.structures.show', $structure) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $structure)
                                                <a href="{{ route('admin.structures.edit', $structure) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(method_exists($structures, 'hasPages') && $structures->hasPages())
                    {{ $structures->links() }}
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucune structure trouvée</p>
                    @if(request()->hasAny(['search', 'type', 'is_active']))
                        <a href="{{ route('admin.structures.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


