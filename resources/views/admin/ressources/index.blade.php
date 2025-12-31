<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-folder me-2"></i>Gestion des Ressources
            </h2>
            <div>
                @can('create', \App\Models\Ressource::class)
                    <form action="{{ route('admin.ressources.generate-guide') }}" method="POST" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary" title="Générer le guide d'import PDF">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Générer Guide PDF
                        </button>
                    </form>
                    <a href="{{ route('admin.ressources.create') }}" class="btn btn-ceeac-primary">
                        <i class="bi bi-plus-circle me-2"></i>Nouvelle ressource
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ressources.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Rechercher par titre, description..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="categorie" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <option value="general" {{ request('categorie') === 'general' ? 'selected' : '' }}>Général</option>
                        <option value="import" {{ request('categorie') === 'import' ? 'selected' : '' }}>Import</option>
                        <option value="export" {{ request('categorie') === 'export' ? 'selected' : '' }}>Export</option>
                        <option value="documentation" {{ request('categorie') === 'documentation' ? 'selected' : '' }}>Documentation</option>
                        <option value="template" {{ request('categorie') === 'template' ? 'selected' : '' }}>Modèles</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="excel" {{ request('type') === 'excel' ? 'selected' : '' }}>Excel</option>
                        <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="zip" {{ request('type') === 'zip' ? 'selected' : '' }}>ZIP</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="est_actif" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('est_actif') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('est_actif') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-ceeac-primary w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des ressources -->
    <div class="card">
        <div class="card-body">
            @if($ressources->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Catégorie</th>
                                <th>Version</th>
                                <th>Taille</th>
                                <th>Téléchargements</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ressources as $ressource)
                                <tr>
                                    <td>
                                        <strong>{{ $ressource->titre }}</strong>
                                        @if($ressource->description)
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($ressource->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($ressource->type) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($ressource->categorie) }}</span>
                                    </td>
                                    <td>{{ $ressource->version }}</td>
                                    <td>{{ $ressource->taille_formatee }}</td>
                                    <td>{{ $ressource->nombre_telechargements }}</td>
                                    <td>
                                        @if($ressource->est_actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                        @if($ressource->est_public)
                                            <span class="badge bg-primary ms-1">Public</span>
                                        @else
                                            <span class="badge bg-warning ms-1">Privé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $ressource)
                                                <a href="{{ route('admin.ressources.show', $ressource) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('update', $ressource)
                                                <a href="{{ route('admin.ressources.edit', $ressource) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $ressource)
                                                <form action="{{ route('admin.ressources.destroy', $ressource) }}" method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?');">
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
                {{ $ressources->links() }}
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Aucune ressource trouvée.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

