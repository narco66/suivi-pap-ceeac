<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-bullseye me-2"></i>Objectifs
            </h2>
            <a href="{{ route('objectifs.create') }}" class="btn btn-primary btn-ceeac">
                <i class="bi bi-plus-circle me-2"></i>Créer un objectif
            </a>
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

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-primary">{{ $stats['total'] ?? 0 }}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-warning">{{ $stats['en_cours'] ?? 0 }}</div>
                    <small class="text-muted">En cours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-success">{{ $stats['termines'] ?? 0 }}</div>
                    <small class="text-muted">Terminés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-info">{{ $stats['planifies'] ?? 0 }}</div>
                    <small class="text-muted">Planifiés</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('objectifs.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="version_id" class="form-label small">Version PAPA</label>
                    <select name="version_id" id="version_id" class="form-select form-select-sm">
                        <option value="">Toutes les versions</option>
                        @foreach($versions ?? [] as $version)
                            <option value="{{ $version['id'] }}" {{ request('version_id') == $version['id'] ? 'selected' : '' }}>
                                {{ $version['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="statut" class="form-label small">Statut</label>
                    <select name="statut" id="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                        <option value="bloque" {{ request('statut') == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label small">Recherche</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Code, libellé...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Version sélectionnée -->
    @if($versionSelected)
    <div class="alert alert-info alert-ceeac mb-4">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Filtre actif:</strong> Affichage des objectifs de la version 
        <strong>{{ $versionSelected->libelle }}</strong> 
        ({{ $versionSelected->papa->libelle ?? '' }})
        <a href="{{ route('objectifs.index') }}" class="btn btn-sm btn-outline-primary ms-2">
            <i class="bi bi-x-circle me-1"></i>Retirer le filtre
        </a>
    </div>
    @endif

    <!-- Liste des objectifs -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Liste des objectifs
                <span class="badge bg-primary ms-2">{{ $objectifs->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($objectifs->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="120">Version</th>
                            <th width="120">Statut</th>
                            <th width="150">Avancement</th>
                            <th width="100">Nb Actions</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($objectifs as $objectif)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $objectif->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($objectif->libelle, 60) }}</div>
                                @if($objectif->description)
                                    <small class="text-muted">{{ Str::limit($objectif->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($objectif->papaVersion)
                                    <span class="badge bg-secondary">
                                        {{ $objectif->papaVersion->libelle }}
                                    </span>
                                    @if($objectif->papaVersion->papa)
                                        <small class="d-block text-muted mt-1">
                                            {{ $objectif->papaVersion->papa->code }}
                                        </small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $objectif->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $objectif->statut)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $objectif->pourcentage_avancement ?? 0 }}%"
                                             aria-valuenow="{{ $objectif->pourcentage_avancement ?? 0 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $objectif->pourcentage_avancement ?? 0 }}%</small>
                                </div>
                            </td>
                            <td>
                                @if($objectif->actionsPrioritaires)
                                    <span class="badge bg-info">
                                        {{ $objectif->actionsPrioritaires->count() }} action(s)
                                    </span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('objectifs.show', $objectif->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($objectif->papaVersion && !$objectif->papaVersion->verrouille)
                                    <a href="{{ route('objectifs.edit', $objectif->id) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
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
                {{ $objectifs->links() }}
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>Aucun objectif trouvé</p>
                @if(request()->anyFilled(['version_id', 'statut', 'search']))
                    <a href="{{ route('objectifs.index') }}" class="btn btn-sm btn-outline-primary">
                        Réinitialiser les filtres
                    </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>


