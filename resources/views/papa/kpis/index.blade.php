<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-graph-up-arrow me-2"></i>Indicateurs de Performance (KPI)
            </h2>
            @can('create', \App\Models\Kpi::class)
                <a href="{{ route('kpi.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer un KPI
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
                    <p class="text-muted mb-0 small">Total KPI</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success mb-0">{{ $stats['atteints'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Objectifs atteints</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning mb-0">{{ $stats['sous_objectif'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Sous objectif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info mb-0">{{ $stats['depasses'] ?? 0 }}</h3>
                    <p class="text-muted mb-0 small">Objectifs dépassés</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('kpi.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, libellé, action...">
                </div>
                <div class="col-md-2">
                    <label for="action_prioritaire_id" class="form-label">Action</label>
                    <select class="form-select" id="action_prioritaire_id" name="action_prioritaire_id">
                        <option value="">Toutes les actions</option>
                        @foreach($actions ?? [] as $action)
                            <option value="{{ $action['id'] }}" {{ request('action_prioritaire_id') == $action['id'] ? 'selected' : '' }}>
                                {{ $action['libelle'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="archive" {{ request('statut') === 'archive' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="performance" class="form-label">Performance</label>
                    <select class="form-select" id="performance" name="performance">
                        <option value="">Toutes</option>
                        <option value="sous_objectif" {{ request('performance') === 'sous_objectif' ? 'selected' : '' }}>Sous objectif</option>
                        <option value="atteint" {{ request('performance') === 'atteint' ? 'selected' : '' }}>Atteint</option>
                        <option value="depasse" {{ request('performance') === 'depasse' ? 'selected' : '' }}>Dépassé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label">Date début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                           value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Date fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                           value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-ceeac-primary">
                        <i class="bi bi-funnel me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des KPI -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card card-ceeac">
                <div class="card-body">
                    @if($kpis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-ceeac table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Libellé</th>
                                        <th>Action</th>
                                        <th>Valeur cible</th>
                                        <th>Valeur réalisée</th>
                                        <th>Écart</th>
                                        <th>Pourcentage</th>
                                        <th>Date mesure</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kpis as $kpi)
                                        <tr>
                                            <td>
                                                <strong>{{ $kpi->code }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $kpi->libelle }}</strong>
                                                @if($kpi->description)
                                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($kpi->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($kpi->actionPrioritaire)
                                                    <a href="{{ route('actions-prioritaires.show', $kpi->actionPrioritaire) }}" class="text-decoration-none">
                                                        <span class="badge bg-secondary">{{ $kpi->actionPrioritaire->code }}</span>
                                                    </a>
                                                    <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($kpi->actionPrioritaire->libelle, 30) }}</small>
                                                    @if($kpi->actionPrioritaire->objectif)
                                                        <br><small class="text-muted">
                                                            Objectif: {{ $kpi->actionPrioritaire->objectif->code }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ number_format($kpi->valeur_cible, 2, ',', ' ') }}</strong>
                                                <br><small class="text-muted">{{ $kpi->unite ?? 'unité' }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($kpi->valeur_realisee ?? 0, 2, ',', ' ') }}</strong>
                                                <br><small class="text-muted">{{ $kpi->unite ?? 'unité' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $ecart = $kpi->valeur_ecart ?? ($kpi->valeur_realisee - $kpi->valeur_cible);
                                                    $ecartClass = $ecart >= 0 ? 'text-success' : 'text-danger';
                                                @endphp
                                                <span class="{{ $ecartClass }}">
                                                    <strong>{{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 2, ',', ' ') }}</strong>
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $pourcentage = $kpi->pourcentage_realisation ?? ($kpi->valeur_cible > 0 ? ($kpi->valeur_realisee / $kpi->valeur_cible) * 100 : 0);
                                                    $pourcentageClass = $pourcentage >= 100 ? 'bg-success' : ($pourcentage >= 80 ? 'bg-warning' : 'bg-danger');
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                        <div class="progress-bar {{ $pourcentageClass }}" 
                                                             role="progressbar" 
                                                             style="width: {{ min($pourcentage, 100) }}%"
                                                             aria-valuenow="{{ $pourcentage }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            {{ number_format($pourcentage, 1) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($kpi->date_mesure)
                                                    <strong>{{ $kpi->date_mesure->format('d/m/Y') }}</strong>
                                                    <br><small class="text-muted">{{ $kpi->date_mesure->format('H:i') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statutBadge = match($kpi->statut) {
                                                        'actif' => 'bg-success',
                                                        'inactif' => 'bg-secondary',
                                                        'archive' => 'bg-dark',
                                                        default => 'bg-info',
                                                    };
                                                @endphp
                                                <span class="badge {{ $statutBadge }}">
                                                    {{ ucfirst($kpi->statut ?? 'N/A') }}
                                                </span>
                                                @if($kpi->valeur_realisee < $kpi->valeur_cible)
                                                    <br><span class="badge bg-warning mt-1">Sous objectif</span>
                                                @elseif($kpi->valeur_realisee > ($kpi->valeur_cible * 1.1))
                                                    <br><span class="badge bg-info mt-1">Dépassé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('view', $kpi)
                                                        <a href="{{ route('kpi.show', $kpi) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @endcan
                                                    @can('update', $kpi)
                                                        <a href="{{ route('kpi.edit', $kpi) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
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
                        {{ $kpis->links() }}
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p class="mb-0">Aucun KPI enregistré</p>
                            @if(request()->hasAny(['search', 'statut', 'action_prioritaire_id', 'performance', 'date_debut', 'date_fin']))
                                <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary mt-3">
                                    <i class="bi bi-x-circle me-2"></i>Réinitialiser les filtres
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
