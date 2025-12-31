<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-briefcase me-2"></i>{{ $directionAppui->libelle }}
                </h2>
                <small class="text-muted">{{ $directionAppui->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('directions-appui.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @can('update', $directionAppui)
                    <a href="{{ route('directions-appui.edit', $directionAppui) }}" class="btn btn-warning">
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
        <div class="col-md-12">
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
                            <div class="fw-semibold">{{ $directionAppui->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @if($directionAppui->actif)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Libellé:</strong>
                            <div class="fw-semibold">{{ $directionAppui->libelle }}</div>
                        </div>
                    </div>
                    @if($directionAppui->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $directionAppui->description }}</p>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Créé le:</strong>
                            <div>{{ $directionAppui->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Modifié le:</strong>
                            <div>{{ $directionAppui->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

