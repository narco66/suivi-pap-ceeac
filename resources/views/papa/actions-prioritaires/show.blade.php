<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-list-check me-2"></i>{{ $action->libelle }}
                </h2>
                <small class="text-muted">{{ $action->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('objectifs.show', $action->objectif_id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à l'objectif
                </a>
                @if($action->objectif->papaVersion && !$action->objectif->papaVersion->verrouille)
                <a href="{{ route('actions-prioritaires.edit', $action->id) }}" class="btn btn-warning">
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
                            <div class="fw-semibold">{{ $action->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $action->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $action->statut)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Type:</strong>
                            <div>
                                <span class="badge bg-secondary">{{ ucfirst($action->type ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Priorité:</strong>
                            <div>
                                <span class="badge bg-info">{{ ucfirst($action->priorite ?? 'Normale') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Criticité:</strong>
                            <div>
                                <span class="badge badge-gravite-{{ $action->criticite ?? 'normal' }}">
                                    {{ ucfirst($action->criticite ?? 'Normal') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Bloqué:</strong>
                            <div>
                                @if($action->bloque)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-lock me-1"></i>Oui
                                    </span>
                                    @if($action->raison_blocage)
                                        <small class="d-block text-muted mt-1">{{ $action->raison_blocage }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-unlock me-1"></i>Non
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($action->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $action->description }}</p>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Objectif:</strong>
                            <div>
                                @if($action->objectif)
                                    <a href="{{ route('objectifs.show', $action->objectif->id) }}" class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $action->objectif->code }}</span>
                                        <small class="d-block text-muted mt-1">{{ $action->objectif->libelle }}</small>
                                    </a>
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
                                         style="width: {{ $action->pourcentage_avancement ?? 0 }}%"
                                         aria-valuenow="{{ $action->pourcentage_avancement ?? 0 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $action->pourcentage_avancement ?? 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début prévue:</strong>
                            <div>{{ $action->date_debut_prevue ? $action->date_debut_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin prévue:</strong>
                            <div>{{ $action->date_fin_prevue ? $action->date_fin_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                    @if($action->date_debut_reelle || $action->date_fin_reelle)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début réelle:</strong>
                            <div>{{ $action->date_debut_reelle ? $action->date_debut_reelle->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin réelle:</strong>
                            <div>{{ $action->date_fin_reelle ? $action->date_fin_reelle->format('d/m/Y') : '-' }}</div>
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
                            <span class="text-muted small">Tâches totales</span>
                            <span class="fw-bold text-primary">{{ $stats['taches'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Terminées</span>
                            <span class="fw-bold text-success">{{ $stats['taches_terminees'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">En cours</span>
                            <span class="fw-bold text-warning">{{ $stats['taches_en_cours'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">En retard</span>
                            <span class="fw-bold text-danger">{{ $stats['taches_en_retard'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">KPI</span>
                            <span class="fw-bold text-info">{{ $stats['kpis'] }}</span>
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

    <!-- Tâches -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-task me-2"></i>Tâches
                    <span class="badge bg-primary ms-2">{{ $stats['taches'] }}</span>
                </h5>
                @if($action->objectif->papaVersion && !$action->objectif->papaVersion->verrouille)
                <a href="{{ route('taches.create', ['action_id' => $action->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter une tâche
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($action->taches->whereNull('tache_parent_id')->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="120">Statut</th>
                            <th width="120">Criticité</th>
                            <th width="150">Dates</th>
                            <th width="150">Avancement</th>
                            <th width="100">Responsable</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($action->taches->whereNull('tache_parent_id') as $tache)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $tache->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($tache->libelle, 60) }}</div>
                                @if($tache->description)
                                    <small class="text-muted">{{ Str::limit($tache->description, 80) }}</small>
                                @endif
                                @if($tache->sousTaches && $tache->sousTaches->count() > 0)
                                    <small class="d-block text-info mt-1">
                                        <i class="bi bi-list-nested me-1"></i>{{ $tache->sousTaches->count() }} sous-tâche(s)
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $tache->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-gravite-{{ $tache->criticite ?? 'normal' }}">
                                    {{ ucfirst($tache->criticite ?? 'Normal') }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    @if($tache->date_debut_prevue && $tache->date_fin_prevue)
                                        <div>{{ $tache->date_debut_prevue->format('d/m/Y') }}</div>
                                        <div class="text-muted">→ {{ $tache->date_fin_prevue->format('d/m/Y') }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $tache->pourcentage_avancement ?? 0 }}%"
                                             aria-valuenow="{{ $tache->pourcentage_avancement ?? 0 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $tache->pourcentage_avancement ?? 0 }}%</small>
                                </div>
                            </td>
                            <td>
                                @if($tache->responsable)
                                    <small>{{ $tache->responsable->name }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('taches.show', $tache->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($action->objectif->papaVersion && !$action->objectif->papaVersion->verrouille)
                                    <a href="{{ route('taches.edit', $tache->id) }}" 
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
                <p>Aucune tâche enregistrée pour cette action</p>
                @if($action->objectif->papaVersion && !$action->objectif->papaVersion->verrouille)
                <a href="{{ route('taches.create', ['action_id' => $action->id]) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Créer la première tâche
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- KPI -->
    @if($action->kpis->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-graph-up me-2"></i>Indicateurs de performance (KPI)
                <span class="badge bg-primary ms-2">{{ $action->kpis->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th width="120">Cible</th>
                            <th width="120">Réalisé</th>
                            <th width="120">Unité</th>
                            <th width="120">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($action->kpis as $kpi)
                        <tr>
                            <td>{{ $kpi->libelle }}</td>
                            <td>{{ $kpi->valeur_cible }} {{ $kpi->unite }}</td>
                            <td>{{ $kpi->valeur_realisee ?? '-' }} {{ $kpi->unite }}</td>
                            <td>{{ $kpi->unite }}</td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $kpi->statut ?? 'en_cours') }}">
                                    {{ ucfirst(str_replace('_', ' ', $kpi->statut ?? 'En cours')) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Alertes -->
    @if($action->alertes->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-bell me-2"></i>Alertes ouvertes
                <span class="badge bg-danger ms-2">{{ $action->alertes->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($action->alertes as $alerte)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">
                                <span class="badge badge-gravite-{{ $alerte->criticite }} me-2">
                                    {{ ucfirst($alerte->criticite) }}
                                </span>
                                {{ $alerte->titre }}
                            </h6>
                            <p class="mb-1">{{ $alerte->message }}</p>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $alerte->date_creation->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <a href="{{ route('alertes.show', $alerte->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</x-app-layout>


