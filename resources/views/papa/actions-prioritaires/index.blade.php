<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-lightning me-2"></i>Actions Prioritaires
            </h2>
            @can('create', \App\Models\ActionPrioritaire::class)
                <a href="{{ route('actions-prioritaires.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer une action prioritaire
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Statistiques -->
    @if(isset($stats))
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-ceeac-blue mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success mb-0">{{ $stats['terminees'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Terminées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary mb-0">{{ $stats['en_cours'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">En cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger mb-0">{{ $stats['en_retard'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">En retard</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('actions-prioritaires.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, libellé, description...">
                </div>
                <div class="col-md-2">
                    <label for="objectif_id" class="form-label">Objectif</label>
                    <select class="form-select" id="objectif_id" name="objectif_id">
                        <option value="">Tous les objectifs</option>
                        @foreach($objectifs ?? [] as $objectif)
                            <option value="{{ $objectif['id'] }}" {{ request('objectif_id') == $objectif['id'] ? 'selected' : '' }}>
                                {{ $objectif['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" {{ request('statut') === 'planifie' ? 'selected' : '' }}>Planifiée</option>
                        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>Terminée</option>
                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="priorite" class="form-label">Priorité</label>
                    <select class="form-select" id="priorite" name="priorite">
                        <option value="">Toutes</option>
                        <option value="faible" {{ request('priorite') === 'faible' ? 'selected' : '' }}>Faible</option>
                        <option value="moyenne" {{ request('priorite') === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                        <option value="elevee" {{ request('priorite') === 'elevee' ? 'selected' : '' }}>Élevée</option>
                        <option value="critique" {{ request('priorite') === 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="criticite" class="form-label">Criticité</label>
                    <select class="form-select" id="criticite" name="criticite">
                        <option value="">Toutes</option>
                        <option value="faible" {{ request('criticite') === 'faible' ? 'selected' : '' }}>Faible</option>
                        <option value="moyenne" {{ request('criticite') === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                        <option value="elevee" {{ request('criticite') === 'elevee' ? 'selected' : '' }}>Élevée</option>
                        <option value="critique" {{ request('criticite') === 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="en_retard" name="en_retard" value="1" 
                               {{ request('en_retard') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="en_retard">
                            En retard
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-ceeac-primary">
                        <i class="bi bi-funnel me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('actions-prioritaires.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des actions -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des actions prioritaires
                    @if(isset($actions) && $actions->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $actions->total() }}</span>
                    @endif
                </h5>
                @can('create', \App\Models\ActionPrioritaire::class)
                    <a href="{{ route('actions-prioritaires.create') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-plus-circle me-2"></i>Créer une action prioritaire
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if($actions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Libellé</th>
                                <th>Objectif</th>
                                <th>Statut</th>
                                <th>Priorité</th>
                                <th>Criticité</th>
                                <th>Échéance</th>
                                <th>Tâches</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($actions as $action)
                                <tr>
                                    <td>
                                        <strong>{{ $action->code }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $action->libelle }}</strong>
                                        @if($action->description)
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($action->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($action->objectif)
                                            <a href="{{ route('objectifs.show', $action->objectif) }}" class="text-decoration-none">
                                                {{ $action->objectif->code }}
                                            </a>
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($action->objectif->libelle, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statutBadge = match($action->statut) {
                                                'termine' => 'bg-success',
                                                'en_cours' => 'bg-primary',
                                                'planifie' => 'bg-info',
                                                'en_attente' => 'bg-warning',
                                                'annule' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $statutBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $action->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $prioriteBadge = match($action->priorite) {
                                                'critique' => 'bg-danger',
                                                'elevee' => 'bg-warning',
                                                'moyenne' => 'bg-info',
                                                'faible' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $prioriteBadge }}">
                                            {{ ucfirst($action->priorite ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $criticiteBadge = match($action->criticite) {
                                                'critique' => 'bg-danger',
                                                'elevee' => 'bg-warning',
                                                'moyenne' => 'bg-info',
                                                'faible' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $criticiteBadge }}">
                                            {{ ucfirst($action->criticite ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($action->date_fin_prevue)
                                            {{ $action->date_fin_prevue->format('d/m/Y') }}
                                            @if($action->date_fin_prevue < now() && $action->statut != 'termine')
                                                <br><small class="text-danger">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>En retard
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $action->taches->whereNull('tache_parent_id')->count() }} tâche(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $action)
                                                <a href="{{ route('actions-prioritaires.show', $action) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $action)
                                                <a href="{{ route('actions-prioritaires.edit', $action) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
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
                {{ $actions->links() }}
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                    <p class="mb-0">Aucune action prioritaire trouvée</p>
                    @if(request()->hasAny(['search', 'statut', 'priorite', 'criticite', 'objectif_id', 'en_retard']))
                        <a href="{{ route('actions-prioritaires.index') }}" class="btn btn-outline-secondary mt-3">
                            <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
