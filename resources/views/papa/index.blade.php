<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-file-text me-2"></i>Plans d'Action Prioritaires
            </h2>
            <a href="{{ route('papa.create') }}" class="btn btn-primary btn-ceeac">
                <i class="bi bi-plus-circle me-2"></i>Créer un PAPA
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
                    <div class="h4 mb-0 text-success">{{ $stats['actifs'] ?? 0 }}</div>
                    <small class="text-muted">Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-warning">{{ $stats['verrouilles'] ?? 0 }}</div>
                    <small class="text-muted">Verrouillés</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac">
                <div class="card-body text-center">
                    <div class="h4 mb-0 text-secondary">{{ $stats['archives'] ?? 0 }}</div>
                    <small class="text-muted">Archivés</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('papa.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="annee" class="form-label small">Année</label>
                    <select name="annee" id="annee" class="form-select form-select-sm">
                        <option value="">Toutes les années</option>
                        @foreach($annees ?? [] as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                                {{ $annee }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="statut" class="form-label small">Statut</label>
                    <select name="statut" id="statut" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="verrouille" {{ request('statut') == 'verrouille' ? 'selected' : '' }}>Verrouillé</option>
                        <option value="cloture" {{ request('statut') == 'cloture' ? 'selected' : '' }}>Clôturé</option>
                        <option value="archive" {{ request('statut') == 'archive' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label small">Recherche</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" id="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Code, libellé, description...">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des PAPA -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Liste des Plans d'Action Prioritaires
                <span class="badge bg-primary ms-2">{{ $papas->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($papas->count() > 0)
            <div class="table-responsive">
                <table class="table table-ceeac table-hover">
                    <thead>
                        <tr>
                            <th width="100">Code</th>
                            <th>Libellé</th>
                            <th width="100">Année</th>
                            <th width="120">Statut</th>
                            <th width="120">Versions</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($papas as $papa)
                        <tr>
                            <td>
                                <span class="fw-semibold text-ceeac-blue">{{ $papa->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($papa->libelle, 60) }}</div>
                                @if($papa->description)
                                    <small class="text-muted">{{ Str::limit($papa->description, 80) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $papa->annee }}</span>
                            </td>
                            <td>
                                <span class="badge badge-statut-{{ str_replace('_', '-', $papa->statut) }}">
                                    {{ ucfirst(str_replace('_', ' ', $papa->statut)) }}
                                </span>
                            </td>
                            <td>
                                @if($papa->versions && $papa->versions->count() > 0)
                                    <span class="badge bg-secondary">
                                        {{ $papa->versions->count() }} version(s)
                                    </span>
                                    <div class="mt-1">
                                        @foreach($papa->versions->take(2) as $version)
                                            <small class="d-block text-muted">
                                                <i class="bi bi-file-earmark me-1"></i>{{ $version->libelle }}
                                            </small>
                                        @endforeach
                                        @if($papa->versions->count() > 2)
                                            <small class="text-muted">+{{ $papa->versions->count() - 2 }} autre(s)</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">Aucune version</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('papa.show', $papa->id) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($papa->statut !== 'verrouille' && $papa->statut !== 'archive')
                                    <a href="{{ route('papa.edit', $papa->id) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif
                                    @if($papa->statut !== 'archive')
                                    <a href="{{ route('gantt.index', ['papa_id' => $papa->id]) }}" 
                                       class="btn btn-outline-info" 
                                       title="Vue Gantt">
                                        <i class="bi bi-diagram-3"></i>
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
                {{ $papas->links() }}
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p>Aucun PAPA trouvé</p>
                @if(request()->anyFilled(['annee', 'statut', 'search']))
                    <a href="{{ route('papa.index') }}" class="btn btn-sm btn-outline-primary">
                        Réinitialiser les filtres
                    </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>


