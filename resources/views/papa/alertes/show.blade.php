<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-bell me-2"></i>{{ $alerte->titre }}
                </h2>
                <small class="text-muted">Alerte #{{ $alerte->id }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('alertes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @can('update', $alerte)
                <a href="{{ route('alertes.edit', $alerte->id) }}" class="btn btn-warning">
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
                            <strong class="text-muted">Type:</strong>
                            <div class="mt-1">
                                @switch($alerte->type)
                                    @case('echeance_depassee')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Échéance dépassée
                                        </span>
                                        @break
                                    @case('retard_critique')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Retard critique
                                        </span>
                                        @break
                                    @case('blocage')
                                        <span class="badge bg-dark">
                                            <i class="bi bi-pause-circle me-1"></i>Blocage
                                        </span>
                                        @break
                                    @case('kpi_non_atteint')
                                        <span class="badge bg-info">
                                            <i class="bi bi-graph-down me-1"></i>KPI non atteint
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-info-circle me-1"></i>{{ ucfirst(str_replace('_', ' ', $alerte->type)) }}
                                </span>
                                @endswitch
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Criticité:</strong>
                            <div class="mt-1">
                                @if($alerte->criticite === 'critique')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Critique
                                    </span>
                                @elseif($alerte->criticite === 'haute')
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-circle me-1"></i>Haute
                                    </span>
                                @elseif($alerte->criticite === 'moyenne')
                                    <span class="badge bg-info">
                                        <i class="bi bi-info-circle me-1"></i>Moyenne
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-circle me-1"></i>{{ ucfirst($alerte->criticite ?? 'Normale') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Statut:</strong>
                            <div class="mt-1">
                                @if($alerte->statut === 'resolue')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Résolue
                                    </span>
                                @elseif($alerte->statut === 'en_cours')
                                    <span class="badge bg-info">
                                        <i class="bi bi-hourglass-split me-1"></i>En cours
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-circle me-1"></i>{{ ucfirst(str_replace('_', ' ', $alerte->statut ?? 'Ouverte')) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Niveau d'escalade:</strong>
                            <div class="mt-1">
                                <span class="badge bg-secondary">
                                    Niveau {{ $alerte->niveau_escalade ?? 1 }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Message:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $alerte->message ?? 'Aucun message' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Dates -->
            <div class="card card-ceeac mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar me-2"></i>Dates
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted small">Date de création:</strong>
                        <div class="fw-semibold">
                            {{ $alerte->date_creation ? $alerte->date_creation->format('d/m/Y H:i') : 'N/A' }}
                        </div>
                    </div>
                    @if($alerte->date_resolution)
                    <div>
                        <strong class="text-muted small">Date de résolution:</strong>
                        <div class="fw-semibold">
                            {{ $alerte->date_resolution->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Personnes impliquées -->
            <div class="card card-ceeac">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-people me-2"></i>Personnes impliquées
                    </h6>
                </div>
                <div class="card-body">
                    @if($alerte->creePar)
                    <div class="mb-3">
                        <strong class="text-muted small">Créée par:</strong>
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-person-circle me-2 text-primary"></i>
                            <span class="fw-semibold">{{ $alerte->creePar->name }}</span>
                        </div>
                        <small class="text-muted">{{ $alerte->creePar->email }}</small>
                    </div>
                    @endif
                    
                    @if($alerte->assigneeA)
                    <div>
                        <strong class="text-muted small">Assignée à:</strong>
                        <div class="d-flex align-items-center mt-1">
                            <i class="bi bi-person-check me-2 text-success"></i>
                            <span class="fw-semibold">{{ $alerte->assigneeA->name }}</span>
                        </div>
                        <small class="text-muted">{{ $alerte->assigneeA->email }}</small>
                    </div>
                    @else
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>Aucune assignation
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contexte -->
    @if($alerte->tache || $alerte->actionPrioritaire)
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card card-ceeac">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-link-45deg me-2"></i>Contexte
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($alerte->tache)
                        <div class="col-md-6">
                            <strong class="text-muted">Tâche associée:</strong>
                            <div class="mt-2">
                                <a href="{{ route('taches.show', $alerte->tache->id) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-list-task me-2 text-primary"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $alerte->tache->code }} - {{ $alerte->tache->libelle }}</div>
                                            <small class="text-muted">
                                                @if($alerte->tache->date_debut_prevue)
                                                    Du {{ $alerte->tache->date_debut_prevue->format('d/m/Y') }}
                                                @endif
                                                @if($alerte->tache->date_fin_prevue)
                                                    au {{ $alerte->tache->date_fin_prevue->format('d/m/Y') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($alerte->actionPrioritaire)
                        <div class="col-md-6">
                            <strong class="text-muted">Action prioritaire associée:</strong>
                            <div class="mt-2">
                                <a href="{{ route('actions-prioritaires.show', $alerte->actionPrioritaire->id) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-lightning-charge me-2 text-warning"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $alerte->actionPrioritaire->code }} - {{ $alerte->actionPrioritaire->libelle }}</div>
                                            <small class="text-muted">
                                                @if($alerte->actionPrioritaire->objectif)
                                                    Objectif: {{ $alerte->actionPrioritaire->objectif->code }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>

