<x-app-layout>
    @php
        $tache = $tache ?? null;
        $stats = $stats ?? [];
    @endphp
    
    @if(!$tache)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Tâche non trouvée ou erreur lors du chargement.
        </div>
    @else
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-list-task me-2"></i>{{ $tache->libelle }}
                </h2>
                <small class="text-muted">{{ $tache->code }}</small>
            </div>
            <div class="d-flex gap-2">
                @if($tache->actionPrioritaire)
                <a href="{{ route('actions-prioritaires.show', $tache->action_prioritaire_id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à l'action
                </a>
                @else
                <a href="{{ route('taches.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @endif
                @if($tache->actionPrioritaire && $tache->actionPrioritaire->objectif && $tache->actionPrioritaire->objectif->papaVersion && !$tache->actionPrioritaire->objectif->papaVersion->verrouille)
                <a href="{{ route('taches.edit', $tache->id) }}" class="btn btn-warning">
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
                            <div class="fw-semibold">{{ $tache->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $tache->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $tache->statut)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Criticité:</strong>
                            <div>
                                <span class="badge badge-gravite-{{ $tache->criticite ?? 'normal' }}">
                                    {{ ucfirst($tache->criticite ?? 'Normal') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Priorité:</strong>
                            <div>
                                <span class="badge bg-info">{{ ucfirst($tache->priorite ?? 'Normale') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Jalon:</strong>
                            <div>
                                @if($tache->est_jalon)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-flag me-1"></i>Oui
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Non</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Bloqué:</strong>
                            <div>
                                @if($tache->bloque)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-lock me-1"></i>Oui
                                    </span>
                                    @if($tache->raison_blocage)
                                        <small class="d-block text-muted mt-1">{{ $tache->raison_blocage }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-unlock me-1"></i>Non
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($tache->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $tache->description }}</p>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Action prioritaire:</strong>
                            <div>
                                @if($tache->actionPrioritaire)
                                    <a href="{{ route('actions-prioritaires.show', $tache->actionPrioritaire->id) }}" class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $tache->actionPrioritaire->code }}</span>
                                        <small class="d-block text-muted mt-1">{{ $tache->actionPrioritaire->libelle }}</small>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Responsable:</strong>
                            <div>
                                @if($tache->responsable)
                                    <span class="fw-semibold">{{ $tache->responsable->name }}</span>
                                    <small class="d-block text-muted">{{ $tache->responsable->email }}</small>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($tache->tacheParent)
                    <div class="mb-3">
                        <strong class="text-muted">Tâche parent:</strong>
                        <div>
                            <a href="{{ route('taches.show', $tache->tacheParent->id) }}" class="text-decoration-none">
                                <span class="badge bg-secondary">{{ $tache->tacheParent->code }}</span>
                                <small class="d-block text-muted mt-1">{{ $tache->tacheParent->libelle }}</small>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Avancement:</strong>
                            <div class="d-flex align-items-center">
                                <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $tache->pourcentage_avancement ?? 0 }}%"
                                         aria-valuenow="{{ $tache->pourcentage_avancement ?? 0 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $tache->pourcentage_avancement ?? 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début prévue:</strong>
                            <div>{{ $tache->date_debut_prevue ? $tache->date_debut_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin prévue:</strong>
                            <div>{{ $tache->date_fin_prevue ? $tache->date_fin_prevue->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>
                    @if($tache->date_debut_reelle || $tache->date_fin_reelle)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Date début réelle:</strong>
                            <div>{{ $tache->date_debut_reelle ? $tache->date_debut_reelle->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Date fin réelle:</strong>
                            <div>{{ $tache->date_fin_reelle ? $tache->date_fin_reelle->format('d/m/Y') : '-' }}</div>
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
                            <span class="text-muted small">Sous-tâches</span>
                            <span class="fw-bold text-primary">{{ $stats['sous_taches'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Sous-tâches terminées</span>
                            <span class="fw-bold text-success">{{ $stats['sous_taches_terminees'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Avancements</span>
                            <span class="fw-bold text-info">{{ $stats['avancements'] }}</span>
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

    <!-- Sous-tâches -->
    @if($tache->sousTaches->count() > 0)
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-nested me-2"></i>Sous-tâches
                <span class="badge bg-primary ms-2">{{ $tache->sousTaches->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="120">Statut</th>
                            <th width="120">Criticité</th>
                            <th width="150">Avancement</th>
                            <th width="100">Responsable</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tache->sousTaches as $sousTache)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $sousTache->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($sousTache->libelle, 60) }}</div>
                                @if($sousTache->description)
                                    <small class="text-muted">{{ Str::limit($sousTache->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $sousTache->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $sousTache->statut)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-gravite-{{ $sousTache->criticite ?? 'normal' }}">
                                    {{ ucfirst($sousTache->criticite ?? 'Normal') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress progress-ceeac flex-grow-1 me-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $sousTache->pourcentage_avancement ?? 0 }}%"
                                             aria-valuenow="{{ $sousTache->pourcentage_avancement ?? 0 }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $sousTache->pourcentage_avancement ?? 0 }}%</small>
                                </div>
                            </td>
                            <td>
                                @if($sousTache->responsable)
                                    <small>{{ $sousTache->responsable->name }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('taches.show', $sousTache->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($tache->actionPrioritaire && $tache->actionPrioritaire->objectif && $tache->actionPrioritaire->objectif->papaVersion && !$tache->actionPrioritaire->objectif->papaVersion->verrouille)
                                    <a href="{{ route('taches.edit', $sousTache->id) }}" 
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
        </div>
    </div>
    @endif

    <!-- Avancements -->
    @if($tache->avancements->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Historique des avancements
                <span class="badge bg-primary ms-2">{{ $tache->avancements->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($tache->avancements as $avancement)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="progress progress-ceeac me-3" style="width: 150px; height: 20px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: {{ $avancement->pourcentage_avancement }}%"
                                         aria-valuenow="{{ $avancement->pourcentage_avancement }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $avancement->pourcentage_avancement }}%
                                    </div>
                                </div>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $avancement->statut ?? 'en_attente') }}">
                                    {{ ucfirst(str_replace('_', ' ', $avancement->statut ?? 'En attente')) }}
                                </span>
                            </div>
                            @if($avancement->commentaire)
                                <p class="mb-1">{{ $avancement->commentaire }}</p>
                            @endif
                            <small class="text-muted">
                                <i class="bi bi-calendar me-1"></i>{{ $avancement->date_avancement->format('d/m/Y H:i') }}
                                @if($avancement->soumisPar)
                                    | <i class="bi bi-person me-1"></i>Soumis par: {{ $avancement->soumisPar->name }}
                                @endif
                                @if($avancement->validePar)
                                    | <i class="bi bi-check-circle me-1"></i>Validé par: {{ $avancement->validePar->name }}
                                    @if($avancement->date_validation)
                                        le {{ $avancement->date_validation->format('d/m/Y H:i') }}
                                    @endif
                                @endif
                            </small>
                            @if($avancement->fichier_joint && $avancement->fichierJointExists())
                                <div class="mt-2">
                                    <a href="{{ $avancement->fichier_joint_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-earmark me-1"></i>Voir le fichier joint
                                    </a>
                                </div>
                            @elseif($avancement->fichier_joint)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Fichier joint non disponible (chemin invalide)
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Alertes -->
    @if($tache->alertes->count() > 0)
    <div class="card card-ceeac mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-bell me-2"></i>Alertes ouvertes
                <span class="badge bg-danger ms-2">{{ $tache->alertes->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($tache->alertes as $alerte)
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
    @endif
</x-app-layout>

