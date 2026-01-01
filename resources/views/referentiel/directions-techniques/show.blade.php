<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-diagram-3 me-2"></i>{{ $directionTechnique->libelle }}
                </h2>
                <small class="text-muted">{{ $directionTechnique->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('directions-techniques.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($directionTechnique->departement)
                    <a href="{{ route('departements.show', $directionTechnique->departement) }}" class="btn btn-outline-info">
                        <i class="bi bi-building me-2"></i>Voir le département
                    </a>
                @endif
                @can('update', $directionTechnique)
                    <a href="{{ route('directions-techniques.edit', $directionTechnique) }}" class="btn btn-warning">
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
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
                            <div class="fw-semibold">{{ $directionTechnique->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @if($directionTechnique->actif)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Département:</strong>
                            <div>
                                @if($directionTechnique->departement)
                                    <a href="{{ route('departements.show', $directionTechnique->departement) }}" class="text-decoration-none">
                                        <span class="badge bg-info">{{ $directionTechnique->departement->libelle }}</span>
                                    </a>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($directionTechnique->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $directionTechnique->description }}</p>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Créé le:</strong>
                            <div>{{ $directionTechnique->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Modifié le:</strong>
                            <div>{{ $directionTechnique->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations du département -->
        @if($directionTechnique->departement)
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>Département
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-2">Code:</strong>
                        <div class="fw-semibold">{{ $directionTechnique->departement->code }}</div>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-2">Libellé:</strong>
                        <div>{{ $directionTechnique->departement->libelle }}</div>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted d-block mb-2">Statut:</strong>
                        <div>
                            @if($directionTechnique->departement->actif)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('departements.show', $directionTechnique->departement) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-eye me-2"></i>Voir les détails
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>


