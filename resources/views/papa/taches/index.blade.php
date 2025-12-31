<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-list-check me-2"></i>Tâches
            </h2>
            <a href="{{ route('taches.create') }}" class="btn btn-primary btn-ceeac">
                <i class="bi bi-plus-circle me-2"></i>Créer une tâche
            </a>
        </div>
    </x-slot>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-primary">{{ $stats['total'] ?? 0 }}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-warning">{{ $stats['en_cours'] ?? 0 }}</div>
                    <small class="text-muted">En cours</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-danger">{{ $stats['en_retard'] ?? 0 }}</div>
                    <small class="text-muted">En retard</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-success">{{ $stats['terminees'] ?? 0 }}</div>
                    <small class="text-muted">Terminées</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-info">{{ $stats['planifiees'] ?? 0 }}</div>
                    <small class="text-muted">Planifiées</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-secondary">{{ $stats['bloquees'] ?? 0 }}</div>
                    <small class="text-muted">Bloquées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('taches.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="statut" class="form-label small">Statut</label>
                    <select name="statut" id="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="en_retard" {{ request('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                        <option value="bloque" {{ request('statut') == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="criticite" class="form-label small">Criticité</label>
                    <select name="criticite" id="criticite" class="form-select form-select-sm">
                        <option value="">Toutes les criticités</option>
                        <option value="normal" {{ request('criticite') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="vigilance" {{ request('criticite') == 'vigilance' ? 'selected' : '' }}>Vigilance</option>
                        <option value="critique" {{ request('criticite') == 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="action_id" class="form-label small">Action prioritaire</label>
                    <select name="action_id" id="action_id" class="form-select form-select-sm">
                        <option value="">Toutes les actions</option>
                        @foreach($actions ?? [] as $action)
                            <option value="{{ $action['id'] }}" {{ request('action_id') == $action['id'] ? 'selected' : '' }}>
                                {{ $action['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label small">Recherche</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Code, libellé...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des tâches -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Liste des tâches
                <span class="badge bg-primary ms-2">{{ $taches->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($taches->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="150">Action prioritaire</th>
                            <th width="120">Statut</th>
                            <th width="120">Criticité</th>
                            <th width="150">Échéance</th>
                            <th width="150">Avancement</th>
                            <th width="120">Responsable</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taches as $tache)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $tache->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($tache->libelle, 60) }}</div>
                                @if($tache->description)
                                    <small class="text-muted">{{ Str::limit($tache->description, 80) }}</small>
                                @endif
                                @if($tache->est_jalon)
                                    <span class="badge bg-warning ms-2">
                                        <i class="bi bi-flag me-1"></i>Jalon
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($tache->actionPrioritaire)
                                    <a href="{{ route('actions-prioritaires.show', $tache->actionPrioritaire->id) }}" class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $tache->actionPrioritaire->code }}</span>
                                        <small class="d-block text-muted mt-1">{{ Str::limit($tache->actionPrioritaire->libelle, 30) }}</small>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
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
                                @if($tache->date_fin_prevue)
                                    <small>
                                        {{ $tache->date_fin_prevue->format('d/m/Y') }}
                                        @if($tache->date_fin_prevue->isPast() && $tache->statut != 'termine')
                                            <span class="badge bg-danger ms-1">Retard</span>
                                        @endif
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                    @if($tache->actionPrioritaire && $tache->actionPrioritaire->objectif && $tache->actionPrioritaire->objectif->papaVersion && !$tache->actionPrioritaire->objectif->papaVersion->verrouille)
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

            <!-- Pagination -->
            <div class="mt-3">
                {{ $taches->links() }}
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>Aucune tâche trouvée</p>
                @if(request()->anyFilled(['statut', 'criticite', 'action_id', 'search']))
                    <a href="{{ route('taches.index') }}" class="btn btn-sm btn-outline-primary">
                        Réinitialiser les filtres
                    </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>


