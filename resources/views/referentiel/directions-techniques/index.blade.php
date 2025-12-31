<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-diagram-3 me-2"></i>Directions Techniques
            </h2>
            @can('create', \App\Models\DirectionTechnique::class)
                <a href="{{ route('directions-techniques.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer une direction technique
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
                    <h3 class="text-success mb-0">{{ $stats['actifs'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Actives</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-secondary mb-0">{{ $stats['inactifs'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Inactives</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('directions-techniques.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, libellé, description...">
                </div>
                <div class="col-md-3">
                    <label for="departement_id" class="form-label">Département</label>
                    <select class="form-select" id="departement_id" name="departement_id">
                        <option value="">Tous les départements</option>
                        @foreach($departements ?? [] as $departement)
                            <option value="{{ $departement['id'] }}" {{ request('departement_id') == $departement['id'] ? 'selected' : '' }}>
                                {{ $departement['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="actif" class="form-label">Statut</label>
                    <select class="form-select" id="actif" name="actif">
                        <option value="">Tous</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actives</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-funnel me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('directions-techniques.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des directions techniques -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des directions techniques
                    @if(isset($directionsTechniques) && $directionsTechniques->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $directionsTechniques->total() }}</span>
                    @endif
                </h5>
                @can('create', \App\Models\DirectionTechnique::class)
                    <a href="{{ route('directions-techniques.create') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-plus-circle me-2"></i>Créer une direction technique
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if(isset($directionsTechniques) && $directionsTechniques->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Description</th>
                                <th>Département</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($directionsTechniques as $directionTechnique)
                                <tr>
                                    <td>
                                        <strong>{{ $directionTechnique->code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $directionTechnique->libelle }}</strong>
                                    </td>
                                    <td>
                                        @if($directionTechnique->description)
                                            {{ \Illuminate\Support\Str::limit($directionTechnique->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($directionTechnique->departement)
                                            <a href="{{ route('departements.show', $directionTechnique->departement) }}" class="text-decoration-none">
                                                <span class="badge bg-info">{{ $directionTechnique->departement->libelle }}</span>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($directionTechnique->actif)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $directionTechnique)
                                                <a href="{{ route('directions-techniques.show', $directionTechnique) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $directionTechnique)
                                                <a href="{{ route('directions-techniques.edit', $directionTechnique) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
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
                @if(method_exists($directionsTechniques, 'hasPages') && $directionsTechniques->hasPages())
                    {{ $directionsTechniques->links() }}
                @endif
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucune direction technique enregistrée</p>
                    @if(request()->hasAny(['search', 'actif', 'departement_id']))
                        <a href="{{ route('directions-techniques.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
