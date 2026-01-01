<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-file-earmark-text me-2"></i>Rapports
            </h2>
            @can('create', \App\Models\Rapport::class)
                <a href="{{ route('rapports.create') }}" class="btn btn-primary btn-ceeac">
                    <i class="bi bi-plus-circle me-2"></i>Créer un rapport
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Total</div>
                            <div class="h3 mb-0 text-ceeac-blue">{{ $stats['total'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-green me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Générés</div>
                            <div class="h3 mb-0 text-success">{{ $stats['generes'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-yellow me-3">
                            <i class="bi bi-file-earmark"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Brouillons</div>
                            <div class="h3 mb-0 text-warning">{{ $stats['brouillons'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-ceeac border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Automatiques</div>
                            <div class="h3 mb-0 text-info">{{ $stats['automatiques'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card card-ceeac mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres de recherche
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('rapports.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Recherche
                    </label>
                    <input type="text" class="form-control form-control-ceeac" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Code, titre, description...">
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label fw-semibold">
                        <i class="bi bi-tag me-1"></i>Type
                    </label>
                    <select class="form-select form-control-ceeac" id="type" name="type">
                        <option value="">Tous</option>
                        <option value="papa" {{ request('type') === 'papa' ? 'selected' : '' }}>PAPA</option>
                        <option value="objectif" {{ request('type') === 'objectif' ? 'selected' : '' }}>Objectif</option>
                            <option value="kpi" {{ request('type') === 'kpi' ? 'selected' : '' }}>KPI</option>
                            <option value="alerte" {{ request('type') === 'alerte' ? 'selected' : '' }}>Alerte</option>
                            <option value="synthese" {{ request('type') === 'synthese' ? 'selected' : '' }}>Synthèse</option>
                            <option value="risques_retards" {{ request('type') === 'risques_retards' ? 'selected' : '' }}>Risques & Retards</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="statut" class="form-label fw-semibold">
                        <i class="bi bi-info-circle me-1"></i>Statut
                    </label>
                    <select class="form-select form-control-ceeac" id="statut" name="statut">
                        <option value="">Tous</option>
                        <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="genere" {{ request('statut') === 'genere' ? 'selected' : '' }}>Généré</option>
                        <option value="envoye" {{ request('statut') === 'envoye' ? 'selected' : '' }}>Envoyé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="format" class="form-label fw-semibold">
                        <i class="bi bi-file-earmark me-1"></i>Format
                    </label>
                    <select class="form-select form-control-ceeac" id="format" name="format">
                        <option value="">Tous</option>
                        <option value="pdf" {{ request('format') === 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="excel" {{ request('format') === 'excel' ? 'selected' : '' }}>Excel</option>
                        <option value="csv" {{ request('format') === 'csv' ? 'selected' : '' }}>CSV</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="scope_level" class="form-label fw-semibold">
                        <i class="bi bi-diagram-3 me-1"></i>Périmètre
                    </label>
                    <select class="form-select form-control-ceeac" id="scope_level" name="scope_level">
                        <option value="">Tous</option>
                        <option value="GLOBAL" {{ request('scope_level') === 'GLOBAL' ? 'selected' : '' }}>Global</option>
                        <option value="SG" {{ request('scope_level') === 'SG' ? 'selected' : '' }}>Secrétaire Général</option>
                        <option value="COMMISSAIRE" {{ request('scope_level') === 'COMMISSAIRE' ? 'selected' : '' }}>Commissaire</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-ceeac w-100">
                        <i class="bi bi-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="card card-ceeac">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Liste des rapports
                    <span class="badge bg-primary ms-2">{{ $rapports->total() }}</span>
                </h5>
                @can('create', \App\Models\Rapport::class)
                    <a href="{{ route('rapports.create') }}" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-plus-circle me-2"></i>Créer un rapport
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if($rapports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ceeac table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Périmètre</th>
                                <th>Format</th>
                                <th>Statut</th>
                                <th>Créé par</th>
                                <th>Date génération</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rapports as $rapport)
                                <tr>
                                    <td><strong>{{ $rapport->code }}</strong></td>
                                    <td>{{ $rapport->titre }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($rapport->type) }}</span>
                                    </td>
                                    <td>
                                        @if($rapport->scope_level)
                                            @if($rapport->scope_level === 'GLOBAL')
                                                <span class="badge bg-primary" title="Rapport institutionnel global">Global</span>
                                            @elseif($rapport->scope_level === 'SG')
                                                <span class="badge bg-success" title="Secrétaire Général - Directions d'Appui">SG</span>
                                            @elseif($rapport->scope_level === 'COMMISSAIRE')
                                                <span class="badge bg-warning" title="Commissaire - Département Technique">Commissaire</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ strtoupper($rapport->format) }}</span>
                                    </td>
                                    <td>
                                        @if($rapport->statut === 'genere')
                                            <span class="badge bg-success">Généré</span>
                                        @elseif($rapport->statut === 'brouillon')
                                            <span class="badge bg-warning">Brouillon</span>
                                        @elseif($rapport->statut === 'envoye')
                                            <span class="badge bg-primary">Envoyé</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($rapport->statut) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $rapport->creePar->name ?? '-' }}</td>
                                    <td>
                                        @if($rapport->date_generation)
                                            {{ $rapport->date_generation->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $rapport)
                                                <a href="{{ route('rapports.show', $rapport) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @if($rapport->statut === 'genere' && $rapport->est_disponible)
                                                @can('download', $rapport)
                                                    <a href="{{ route('rapports.download', $rapport) }}" class="btn btn-sm btn-outline-success" title="Télécharger">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endcan
                                            @elseif($rapport->statut === 'brouillon')
                                                @can('generate', $rapport)
                                                    <form action="{{ route('rapports.generate', $rapport) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-info" title="Générer">
                                                            <i class="bi bi-play-circle"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                            @can('update', $rapport)
                                                <a href="{{ route('rapports.edit', $rapport) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $rapport)
                                                <form action="{{ route('rapports.destroy', $rapport) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $rapports->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Aucun rapport trouvé.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


