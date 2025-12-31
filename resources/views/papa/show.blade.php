<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-file-text me-2"></i>{{ $papa->libelle }}
                </h2>
                <small class="text-muted">{{ $papa->code }} - Année {{ $papa->annee }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('papa.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($papa->statut !== 'verrouille' && $papa->statut !== 'archive')
                <a href="{{ route('papa.edit', $papa->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Modifier
                </a>
                @endif
                <a href="{{ route('gantt.index', ['papa_id' => $papa->id]) }}" class="btn btn-info">
                    <i class="bi bi-diagram-3 me-2"></i>Vue Gantt
                </a>
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
                            <div class="fw-semibold">{{ $papa->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Année:</strong>
                            <div class="fw-semibold">{{ $papa->annee }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $papa->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $papa->statut)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($papa->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $papa->description }}</p>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Date de début:</strong>
                            <div>{{ $papa->date_debut ? $papa->date_debut->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date de fin:</strong>
                            <div>{{ $papa->date_fin ? $papa->date_fin->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
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
                            <span class="text-muted small">Versions</span>
                            <span class="fw-bold text-primary">{{ $stats['versions'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Objectifs</span>
                            <span class="fw-bold text-success">{{ $stats['objectifs'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Actions prioritaires</span>
                            <span class="fw-bold text-warning">{{ $stats['actions'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Tâches</span>
                            <span class="fw-bold text-info">{{ $stats['taches'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Versions du PAPA -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Versions du PAPA
                    <span class="badge bg-primary ms-2">{{ $papa->versions->count() }}</span>
                </h5>
                @if($papa->statut !== 'verrouille' && $papa->statut !== 'archive')
                <a href="#" class="btn btn-sm btn-primary" onclick="alert('Fonctionnalité à venir'); return false;">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter une version
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($papa->versions->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Numéro</th>
                            <th>Libellé</th>
                            <th width="120">Statut</th>
                            <th width="120">Verrouillé</th>
                            <th width="150">Date création</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papa->versions as $version)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">V{{ $version->numero }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $version->libelle }}</div>
                                @if($version->description)
                                    <small class="text-muted">{{ Str::limit($version->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $version->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $version->statut)) }}
                                </span>
                            </td>
                            <td>
                                @if($version->verrouille)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-lock me-1"></i>Verrouillé
                                    </span>
                                    @if($version->date_verrouillage)
                                        <small class="d-block text-muted mt-1">
                                            {{ $version->date_verrouillage->format('d/m/Y') }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-unlock me-1"></i>Non verrouillé
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $version->date_creation ? $version->date_creation->format('d/m/Y H:i') : '-' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('objectifs.index', ['version_id' => $version->id]) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir objectifs">
                                        <i class="bi bi-bullseye"></i>
                                    </a>
                                    @if(!$version->verrouille)
                                    <a href="#" 
                                       class="btn btn-outline-warning" 
                                       onclick="alert('Fonctionnalité à venir'); return false;"
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
                <p>Aucune version enregistrée pour ce PAPA</p>
                @if($papa->statut !== 'verrouille' && $papa->statut !== 'archive')
                <a href="#" class="btn btn-primary" onclick="alert('Fonctionnalité à venir'); return false;">
                    <i class="bi bi-plus-circle me-2"></i>Créer la première version
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Vue d'ensemble hiérarchique -->
    @if($papa->versions->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i>Vue d'ensemble hiérarchique
            </h5>
        </div>
        <div class="card-body">
            @foreach($papa->versions as $version)
            <div class="mb-4 pb-4 border-bottom">
                <h6 class="text-ceeac-blue mb-3">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ $version->libelle }}
                    <span class="badge bg-secondary ms-2">V{{ $version->numero }}</span>
                </h6>
                
                @if($version->objectifs->count() > 0)
                    @foreach($version->objectifs as $objectif)
                    <div class="ms-4 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-bullseye text-success me-2"></i>
                            <strong>{{ $objectif->code }} - {{ $objectif->libelle }}</strong>
                            <span class="badge badge-statut-{{ str_replace('_', '-', $objectif->statut) }} ms-2">
                                {{ ucfirst(str_replace('_', ' ', $objectif->statut)) }}
                            </span>
                        </div>
                        
                        @if($objectif->actionsPrioritaires->count() > 0)
                            @foreach($objectif->actionsPrioritaires as $action)
                            <div class="ms-4 mb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-arrow-right text-warning me-2"></i>
                                    <span>{{ $action->code }} - {{ Str::limit($action->libelle, 50) }}</span>
                                    <span class="badge badge-statut-{{ str_replace('_', '-', $action->statut) }} ms-2">
                                        {{ ucfirst(str_replace('_', ' ', $action->statut)) }}
                                    </span>
                                </div>
                                
                                @if($action->taches->count() > 0)
                                    <div class="ms-4 small text-muted">
                                        <i class="bi bi-list-task me-1"></i>
                                        {{ $action->taches->count() }} tâche(s)
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="ms-4 text-muted">
                        <i class="bi bi-info-circle me-2"></i>Aucun objectif pour cette version
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>

