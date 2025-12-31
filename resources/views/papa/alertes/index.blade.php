<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-bell me-2"></i>Alertes
            </h2>
            <a href="{{ route('alertes.create') }}" class="btn btn-primary btn-ceeac">
                <i class="bi bi-plus-circle me-2"></i>Créer une alerte
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
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-primary">{{ $stats['total'] ?? 0 }}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-warning">{{ $stats['ouvertes'] ?? 0 }}</div>
                    <small class="text-muted">Ouvertes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-success">{{ $stats['resolues'] ?? 0 }}</div>
                    <small class="text-muted">Résolues</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-danger">{{ $stats['critiques'] ?? 0 }}</div>
                    <small class="text-muted">Critiques</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('alertes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="statut" class="form-label small">Statut</label>
                    <select name="statut" id="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="ouverte" {{ request('statut') == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="resolue" {{ request('statut') == 'resolue' ? 'selected' : '' }}>Résolue</option>
                        <option value="fermee" {{ request('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
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
                    <label for="type" class="form-label small">Type</label>
                    <select name="type" id="type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="echeance_depassee" {{ request('type') == 'echeance_depassee' ? 'selected' : '' }}>Échéance dépassée</option>
                        <option value="retard_critique" {{ request('type') == 'retard_critique' ? 'selected' : '' }}>Retard critique</option>
                        <option value="blocage" {{ request('type') == 'blocage' ? 'selected' : '' }}>Blocage</option>
                        <option value="kpi_non_atteint" {{ request('type') == 'kpi_non_atteint' ? 'selected' : '' }}>KPI non atteint</option>
                        <option value="anomalie" {{ request('type') == 'anomalie' ? 'selected' : '' }}>Anomalie</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label small">Recherche</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Titre, message...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des alertes -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card card-ceeac">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Liste des alertes
                        <span class="badge bg-primary ms-2">{{ $alertes->total() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($alertes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-ceeac table-hover">
                            <thead>
                                <tr>
                                    <th width="120">Type</th>
                                    <th>Titre</th>
                                    <th width="100">Criticité</th>
                                    <th width="120">Statut</th>
                                    <th width="150">Date</th>
                                    <th width="120">Niveau escalade</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alertes as $alerte)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @switch($alerte->type)
                                                @case('echeance_depassee')
                                                    <i class="bi bi-clock me-1"></i>Échéance
                                                    @break
                                                @case('retard_critique')
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Retard
                                                    @break
                                                @case('blocage')
                                                    <i class="bi bi-pause-circle me-1"></i>Blocage
                                                    @break
                                                @case('kpi_non_atteint')
                                                    <i class="bi bi-graph-down me-1"></i>KPI
                                                    @break
                                                @default
                                                    <i class="bi bi-info-circle me-1"></i>{{ ucfirst($alerte->type) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($alerte->titre, 60) }}</div>
                                        @if($alerte->tache)
                                            <small class="text-muted">
                                                <i class="bi bi-list-task me-1"></i>Tâche: {{ $alerte->tache->code }}
                                            </small>
                                        @elseif($alerte->actionPrioritaire)
                                            <small class="text-muted">
                                                <i class="bi bi-bullseye me-1"></i>Action: {{ $alerte->actionPrioritaire->code }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-gravite-{{ $alerte->criticite }}">
                                            {{ ucfirst($alerte->criticite) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-statut-{{ str_replace('_', '-', $alerte->statut) }}">
                                            {{ ucfirst(str_replace('_', ' ', $alerte->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $alerte->date_creation ? $alerte->date_creation->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($alerte->niveau_escalade)
                                            <span class="badge bg-info">
                                                {{ ucfirst($alerte->niveau_escalade) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('alertes.show', $alerte->id) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($alerte->statut !== 'resolue')
                                            <a href="{{ route('alertes.edit', $alerte->id) }}" 
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
                        {{ $alertes->links() }}
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>Aucune alerte trouvée</p>
                        @if(request()->anyFilled(['statut', 'criticite', 'type', 'search']))
                            <a href="{{ route('alertes.index') }}" class="btn btn-sm btn-outline-primary">
                                Réinitialiser les filtres
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


