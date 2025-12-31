<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-bullseye me-2"></i>{{ $objectif->libelle }}
                </h2>
                <small class="text-muted">{{ $objectif->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('objectifs.index', ['version_id' => $objectif->papa_version_id]) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($objectif->papaVersion && !$objectif->papaVersion->verrouille)
                <a href="{{ route('objectifs.edit', $objectif->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
                @endif
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
                            <div class="fw-semibold">{{ $objectif->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $objectif->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $objectif->statut)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Priorité:</strong>
                            <div>
                                <span class="badge bg-info">{{ ucfirst($objectif->priorite ?? 'Normale') }}</span>
                            </div>
                        </div>
                    </div>
                    @if($objectif->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $objectif->description }}</p>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Version PAPA:</strong>
                            <div>
                                @if($objectif->papaVersion)
                                    <span class="badge bg-secondary">{{ $objectif->papaVersion->libelle }}</span>
                                    @if($objectif->papaVersion->papa)
                                        <small class="d-block text-muted mt-1">
                                            {{ $objectif->papaVersion->papa->libelle }} ({{ $objectif->papaVersion->papa->code }})
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Avancement:</strong>
                            <div class="d-flex align-items-center">
                                <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $objectif->pourcentage_avancement ?? 0 }}%"
                                         aria-valuenow="{{ $objectif->pourcentage_avancement ?? 0 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $objectif->pourcentage_avancement ?? 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début prévue:</strong>
                            <div>{{ $objectif->date_debut_prevue ? $objectif->date_debut_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin prévue:</strong>
                            <div>{{ $objectif->date_fin_prevue ? $objectif->date_fin_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                    @if($objectif->date_debut_reelle || $objectif->date_fin_reelle)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début réelle:</strong>
                            <div>{{ $objectif->date_debut_reelle ? $objectif->date_debut_reelle->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin réelle:</strong>
                            <div>{{ $objectif->date_fin_reelle ? $objectif->date_fin_reelle->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Actions prioritaires</span>
                            <span class="fw-bold text-primary">{{ $stats['actions'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Tâches</span>
                            <span class="fw-bold text-success">{{ $stats['taches'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">KPI</span>
                            <span class="fw-bold text-warning">{{ $stats['kpis'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Alertes ouvertes</span>
                            <span class="fw-bold text-danger">{{ $stats['alertes'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions prioritaires -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>Actions prioritaires
                    <span class="badge bg-primary ms-2">{{ $objectif->actionsPrioritaires->count() }}</span>
                </h5>
                @if($objectif->papaVersion && !$objectif->papaVersion->verrouille)
                <a href="{{ route('actions-prioritaires.create', ['objectif_id' => $objectif->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter une action
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($objectif->actionsPrioritaires->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="120">Type</th>
                            <th width="120">Statut</th>
                            <th width="120">Criticité</th>
                            <th width="150">Avancement</th>
                            <th width="100">Tâches</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($objectif->actionsPrioritaires as $action)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $action->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($action->libelle, 60) }}</div>
                                @if($action->description)
                                    <small class="text-muted">{{ Str::limit($action->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($action->type ?? '-') }}</span>
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $action->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $action->statut)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-gravite-{{ $action->criticite ?? 'normal' }}">
                                    {{ ucfirst($action->criticite ?? 'Normal') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $action->pourcentage_avancement ?? 0 }}%"
                                             aria-valuenow="{{ $action->pourcentage_avancement ?? 0 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $action->pourcentage_avancement ?? 0 }}%</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $action->taches->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('actions-prioritaires.show', $action->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($objectif->papaVersion && !$objectif->papaVersion->verrouille)
                                    <a href="{{ route('actions-prioritaires.edit', $action->id) }}" 
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
            @else
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>Aucune action prioritaire enregistrée pour cet objectif</p>
                @if($objectif->papaVersion && !$objectif->papaVersion->verrouille)
                <a href="{{ route('actions-prioritaires.create', ['objectif_id' => $objectif->id]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer la première action
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Vue d'ensemble hiérarchique -->
    @if($objectif->actionsPrioritaires->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i>Vue d'ensemble hiérarchique
            </h5>
        </div>
        <div class="card-body">
            @foreach($objectif->actionsPrioritaires as $action)
            <div class="mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-arrow-right text-warning me-2"></i>
                    <strong>{{ $action->code }} - {{ $action->libelle }}</strong>
                    <span class="badge badge-statut-{{ str_replace('_', '-', $action->statut) }} ms-2">
                        {{ ucfirst(str_replace('_', ' ', $action->statut)) }}
                    </span>
                </div>
                
                @if($action->taches->count() > 0)
                    <div class="ms-4">
                        @foreach($action->taches->whereNull('tache_parent_id') as $tache)
                        <div class="mb-2">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-list-task text-success me-2"></i>
                                <span>{{ $tache->code }} - {{ Str::limit($tache->libelle, 50) }}</span>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $tache->statut) }} ms-2">
                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                </span>
                            </div>
                            
                            @if($tache->sousTaches && $tache->sousTaches->count() > 0)
                                <div class="ms-4 small text-muted">
                                    <i class="bi bi-list-nested me-1"></i>
                                    {{ $tache->sousTaches->count() }} sous-tâche(s)
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="ms-4 text-muted">
                        <i class="bi bi-info-circle me-2"></i>Aucune tâche pour cette action
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>

