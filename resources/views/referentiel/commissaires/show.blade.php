<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-person-badge me-2"></i>{{ $commissaire->nom }} {{ $commissaire->prenom }}
                </h2>
                @if($commissaire->titre)
                    <small class="text-muted">{{ $commissaire->titre }}</small>
                @endif
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('commissaires.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($commissaire->commission)
                    <a href="{{ route('commissions.show', $commissaire->commission) }}" class="btn btn-outline-primary">
                        <i class="bi bi-people me-2"></i>Voir la commission
                    </a>
                @endif
                @can('update', $commissaire)
                    <a href="{{ route('commissaires.edit', $commissaire) }}" class="btn btn-warning">
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
                        <div class="col-md-6">
                            <strong class="text-muted">Nom:</strong>
                            <div class="fw-semibold">{{ $commissaire->nom }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Prénom:</strong>
                            <div class="fw-semibold">{{ $commissaire->prenom }}</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Titre:</strong>
                            <div>
                                @if($commissaire->titre)
                                    <span class="badge bg-secondary">{{ $commissaire->titre }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @if($commissaire->actif)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Pays d'origine:</strong>
                            <div>
                                @if($commissaire->pays_origine)
                                    <span class="badge bg-info">{{ $commissaire->pays_origine }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Date de nomination:</strong>
                            <div>
                                @if($commissaire->date_nomination)
                                    <strong>{{ $commissaire->date_nomination->format('d/m/Y') }}</strong>
                                    <br><small class="text-muted">
                                        Il y a {{ $commissaire->date_nomination->diffForHumans() }}
                                    </small>
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Commission:</strong>
                            <div>
                                @if($commissaire->commission)
                                    <a href="{{ route('commissions.show', $commissaire->commission) }}" class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $commissaire->commission->code }}</span>
                                    </a>
                                    <br><small class="text-muted">{{ $commissaire->commission->libelle }}</small>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Créé le:</strong>
                            <div>{{ $commissaire->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Modifié le:</strong>
                            <div>{{ $commissaire->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-card-heading me-2"></i>Résumé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted d-block">Nom complet</strong>
                        <h5 class="mb-0">{{ $commissaire->titre ? $commissaire->titre . ' ' : '' }}{{ $commissaire->nom }} {{ $commissaire->prenom }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong class="text-muted d-block">Commission</strong>
                        @if($commissaire->commission)
                            <span class="badge bg-primary">{{ $commissaire->commission->code }}</span>
                            <p class="mb-0 mt-1 small">{{ $commissaire->commission->libelle }}</p>
                        @else
                            <span class="text-muted">Non assigné</span>
                        @endif
                    </div>
                    <hr>
                    <div>
                        <strong class="text-muted d-block">Statut</strong>
                        @if($commissaire->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



