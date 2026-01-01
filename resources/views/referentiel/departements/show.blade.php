<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-building me-2"></i>{{ $departement->libelle }}
                </h2>
                <small class="text-muted">{{ $departement->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('departements.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @can('update', $departement)
                    <a href="{{ route('departements.edit', $departement) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Informations générales -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Code:</strong>
                            <div class="fw-semibold">{{ $departement->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @if($departement->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Nombre de directions:</strong>
                            <div>
                                <span class="badge bg-info">{{ $stats['directions_total'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    @if($departement->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $departement->description }}</p>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Créé le:</strong>
                            <div>{{ $departement->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Modifié le:</strong>
                            <div>{{ $departement->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-ceeac-blue mb-0">{{ $stats['directions_total'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small">Total directions</p>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <strong class="text-muted d-block">Actives</strong>
                            <h4 class="mb-0 text-success">{{ $stats['directions_actives'] ?? 0 }}</h4>
                        </div>
                        <div class="col-6">
                            <strong class="text-muted d-block">Inactives</strong>
                            <h4 class="mb-0 text-secondary">{{ $stats['directions_inactives'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des directions techniques -->
    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i>Directions techniques ({{ $departement->directionsTechniques->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($departement->directionsTechniques->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departement->directionsTechniques as $direction)
                                <tr>
                                    <td>
                                        <strong>{{ $direction->code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $direction->libelle }}</strong>
                                    </td>
                                    <td>
                                        @if($direction->description)
                                            {{ \Illuminate\Support\Str::limit($direction->description, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($direction->actif)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('directions-techniques.show', $direction) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    <p class="mb-0">Aucune direction technique assignée à ce département</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>



