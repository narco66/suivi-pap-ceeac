<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-building me-2"></i>Départements
            </h2>
            @can('create', \App\Models\Departement::class)
                <a href="{{ route('departements.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer un département
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
                    <p class="text-muted mb-0 small">Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-secondary mb-0">{{ $stats['inactifs'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Inactifs</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('departements.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, libellé, description...">
                </div>
                <div class="col-md-3">
                    <label for="actif" class="form-label">Statut</label>
                    <select class="form-select" id="actif" name="actif">
                        <option value="">Tous</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-funnel me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('departements.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des départements -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des départements
                    @if(isset($departements) && $departements->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $departements->total() }}</span>
                    @endif
                </h5>
                @can('create', \App\Models\Departement::class)
                    <a href="{{ route('departements.create') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-plus-circle me-2"></i>Créer un département
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if($departements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Description</th>
                                <th>Nombre de directions</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departements as $departement)
                                <tr>
                                    <td>
                                        <strong>{{ $departement->code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $departement->libelle }}</strong>
                                    </td>
                                    <td>
                                        @if($departement->description)
                                            {{ \Illuminate\Support\Str::limit($departement->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $departement->directions_techniques_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($departement->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $departement)
                                                <a href="{{ route('departements.show', $departement) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $departement)
                                                <a href="{{ route('departements.edit', $departement) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
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
                {{ $departements->links() }}
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucun département enregistré</p>
                    @if(request()->hasAny(['search', 'actif']))
                        <a href="{{ route('departements.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
