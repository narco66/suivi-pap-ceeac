<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-people me-2"></i>{{ $commission->libelle }}
                </h2>
                <small class="text-muted">{{ $commission->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('commissions.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @can('update', $commission)
                    <a href="{{ route('commissions.edit', $commission) }}" class="btn btn-warning">
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
                            <div class="fw-semibold">{{ $commission->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @if($commission->actif)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Nombre de commissaires:</strong>
                            <div>
                                <span class="badge bg-info">{{ $stats['commissaires_total'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    @if($commission->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $commission->description }}</p>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Créée le:</strong>
                            <div>{{ $commission->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Modifiée le:</strong>
                            <div>{{ $commission->updated_at->format('d/m/Y à H:i') }}</div>
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
                        <h3 class="text-ceeac-blue mb-0">{{ $stats['commissaires_total'] ?? 0 }}</h3>
                        <p class="text-muted mb-0 small">Total commissaires</p>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <strong class="text-muted d-block">Actifs</strong>
                            <h4 class="mb-0 text-success">{{ $stats['commissaires_actifs'] ?? 0 }}</h4>
                        </div>
                        <div class="col-6">
                            <strong class="text-muted d-block">Inactifs</strong>
                            <h4 class="mb-0 text-secondary">{{ $stats['commissaires_inactifs'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des commissaires -->
    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-person-badge me-2"></i>Commissaires ({{ $commission->commissaires->count() }})
            </h5>
            @can('create', \App\Models\Commissaire::class)
                <a href="{{ route('commissaires.create', ['commission_id' => $commission->id]) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un commissaire
                </a>
            @endcan
        </div>
        <div class="card-body">
            @if($commission->commissaires->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Titre</th>
                                <th>Pays d'origine</th>
                                <th>Date de nomination</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commission->commissaires as $commissaire)
                                <tr>
                                    <td>
                                        <strong>{{ $commissaire->nom }} {{ $commissaire->prenom }}</strong>
                                    </td>
                                    <td>
                                        @if($commissaire->titre)
                                            <span class="badge bg-secondary">{{ $commissaire->titre }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($commissaire->pays_origine)
                                            <span class="badge bg-info">{{ $commissaire->pays_origine }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($commissaire->date_nomination)
                                            {{ $commissaire->date_nomination->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($commissaire->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $commissaire)
                                                <a href="{{ route('commissaires.show', $commissaire) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $commissaire)
                                                <a href="{{ route('commissaires.edit', $commissaire) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
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
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    <p class="mb-0">Aucun commissaire assigné à cette commission</p>
                    @can('create', \App\Models\Commissaire::class)
                        <a href="{{ route('commissaires.create', ['commission_id' => $commission->id]) }}" class="btn btn-outline-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Ajouter un commissaire
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


