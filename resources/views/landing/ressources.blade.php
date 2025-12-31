<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-folder me-2"></i>Ressources & Fichiers connexes
            </h2>
            @auth
                @can('create', \App\Models\Ressource::class)
                    <button type="button" class="btn btn-primary btn-ceeac" data-bs-toggle="modal" data-bs-target="#createRessourceModal">
                        <i class="bi bi-plus-circle me-2"></i>Créer une ressource
                    </button>
                @endcan
            @endauth
        </div>
    </x-slot>

    @push('styles')
    <style>
        .resource-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        .resource-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            border-color: var(--ceeac-blue);
        }
        .resource-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .dashboard-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .dashboard-card-icon-blue {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--ceeac-blue);
        }
        .dashboard-card-icon-green {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--ceeac-green);
        }
    </style>
    @endpush

    <div class="container-fluid py-4">
        <!-- Messages d'alerte -->
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Attention :</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-x-circle me-2"></i>
                <strong>Erreur :</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistiques en haut -->
        @if(isset($stats))
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card card-ceeac h-100 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Total</div>
                                <div class="h3 mb-0 text-ceeac-blue">{{ $stats['total'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($stats['par_type'] ?? [] as $type => $count)
            <div class="col-md-3 col-sm-6">
                <div class="card card-ceeac h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="dashboard-card-icon dashboard-card-icon-green me-3">
                                <i class="bi bi-file-earmark-{{ $type === 'pdf' ? 'pdf' : ($type === 'excel' ? 'excel' : 'text') }}"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">{{ ucfirst($type) }}</div>
                                <div class="h3 mb-0 text-success">{{ $count }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Description et Filtres -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card card-ceeac">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-funnel me-2"></i>Filtres de recherche
                            </h5>
                            <p class="text-muted mb-0 small">
                                <i class="bi bi-info-circle me-1"></i>Téléchargez les modèles, guides et documents utiles
                            </p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('ressources') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label fw-semibold">
                                    <i class="bi bi-search me-1"></i>Recherche
                                </label>
                                <input type="text" class="form-control form-control-ceeac" id="search" name="search" 
                                       value="{{ request('search') }}" placeholder="Titre, description...">
                            </div>
                            <div class="col-md-3">
                                <label for="categorie" class="form-label fw-semibold">
                                    <i class="bi bi-tags me-1"></i>Catégorie
                                </label>
                                <select class="form-select form-control-ceeac" id="categorie" name="categorie">
                                    <option value="">Toutes les catégories</option>
                                    <option value="general" {{ request('categorie') === 'general' ? 'selected' : '' }}>Général</option>
                                    <option value="import" {{ request('categorie') === 'import' ? 'selected' : '' }}>Import</option>
                                    <option value="export" {{ request('categorie') === 'export' ? 'selected' : '' }}>Export</option>
                                    <option value="documentation" {{ request('categorie') === 'documentation' ? 'selected' : '' }}>Documentation</option>
                                    <option value="template" {{ request('categorie') === 'template' ? 'selected' : '' }}>Modèles</option>
                                    <option value="autre" {{ request('categorie') === 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label fw-semibold">
                                    <i class="bi bi-filetype-{{ request('type') === 'pdf' ? 'pdf' : 'txt' }} me-1"></i>Type de fichier
                                </label>
                                <select class="form-select form-control-ceeac" id="type" name="type">
                                    <option value="">Tous les types</option>
                                    <option value="excel" {{ request('type') === 'excel' ? 'selected' : '' }}>Excel</option>
                                    <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="zip" {{ request('type') === 'zip' ? 'selected' : '' }}>ZIP</option>
                                    <option value="doc" {{ request('type') === 'doc' ? 'selected' : '' }}>Word</option>
                                    <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Image</option>
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
            </div>
        </div>

        <!-- Liste des ressources -->
        <div class="row g-4 mb-4">
            @forelse($ressources ?? [] as $ressource)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card card-ceeac resource-card h-100 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $ressource->icone ?? 'bi bi-file-earmark' }} fs-4 me-2 text-primary"></i>
                                    <span class="badge bg-secondary small">{{ $ressource->categorie }}</span>
                                </div>
                                <span class="badge bg-info">v{{ $ressource->version }}</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-2">{{ $ressource->titre }}</h6>
                            <p class="card-text text-muted small mb-3 flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($ressource->description ?? 'Aucune description', 80) }}
                            </p>
                            
                            <!-- Métadonnées -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    @if($ressource->taille_fichier)
                                        <small class="text-muted">
                                            <i class="bi bi-hdd me-1"></i>{{ $ressource->taille_formatee }}
                                        </small>
                                    @endif
                                    @if($ressource->date_publication)
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $ressource->date_publication->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                                @if($ressource->nombre_telechargements > 0)
                                    <small class="text-muted d-block">
                                        <i class="bi bi-download me-1"></i>
                                        {{ $ressource->nombre_telechargements }} téléchargement(s)
                                    </small>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-grid gap-2 mt-auto">
                                <a href="{{ route('ressources.download', $ressource) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-download me-2"></i>Télécharger
                                </a>
                                <a href="{{ route('ressources.show', $ressource) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-info-circle me-2"></i>Détails
                                </a>
                                @auth
                                    @can('update', $ressource)
                                        <a href="{{ route('admin.ressources.edit', $ressource) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="bi bi-pencil me-2"></i>Modifier
                                        </a>
                                    @endcan
                                    @can('delete', $ressource)
                                        <form method="POST" action="{{ route('admin.ressources.destroy', $ressource) }}" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    @endcan
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-ceeac">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Aucune ressource disponible</h5>
                            <p class="text-muted mb-0">Aucune ressource ne correspond à vos critères de recherche.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($ressources) && method_exists($ressources, 'hasPages') && $ressources->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $ressources->links() }}
                </div>
            </div>
        </div>
        @endif

        <!-- Note importante -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info border-start border-info border-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">Note importante</h6>
                            <p class="mb-0">Ces ressources sont mises à jour régulièrement. Assurez-vous d'utiliser la dernière version disponible pour bénéficier des dernières fonctionnalités et corrections.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de création de ressource -->
    @auth
        @can('create', \App\Models\Ressource::class)
        <div class="modal fade" id="createRessourceModal" tabindex="-1" aria-labelledby="createRessourceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-ceeac-gradient text-white">
                        <h5 class="modal-title" id="createRessourceModalLabel">
                            <i class="bi bi-folder-plus me-2"></i>Créer une nouvelle ressource
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.ressources.store') }}" enctype="multipart/form-data" id="createRessourceForm">
                        @csrf
                        <input type="hidden" name="from_public" value="1">
                        <div class="modal-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation
                                    </h6>
                                    <ul class="mb-0 small">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="modal_titre" class="form-label fw-semibold">
                                        Titre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-ceeac @error('titre') is-invalid @enderror" 
                                           id="modal_titre" name="titre" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="modal_version" class="form-label fw-semibold">Version</label>
                                    <input type="text" class="form-control form-control-ceeac @error('version') is-invalid @enderror" 
                                           id="modal_version" name="version" value="{{ old('version', '1.0') }}">
                                    @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="modal_description" class="form-label fw-semibold">Description</label>
                                    <textarea class="form-control form-control-ceeac @error('description') is-invalid @enderror" 
                                              id="modal_description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="modal_type" class="form-label fw-semibold">
                                        Type de fichier <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-ceeac @error('type') is-invalid @enderror" id="modal_type" name="type" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="excel" {{ old('type') === 'excel' ? 'selected' : '' }}>Excel</option>
                                        <option value="pdf" {{ old('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                                        <option value="zip" {{ old('type') === 'zip' ? 'selected' : '' }}>ZIP</option>
                                        <option value="doc" {{ old('type') === 'doc' ? 'selected' : '' }}>Word (DOC)</option>
                                        <option value="docx" {{ old('type') === 'docx' ? 'selected' : '' }}>Word (DOCX)</option>
                                        <option value="image" {{ old('type') === 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="autre" {{ old('type') === 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="modal_categorie" class="form-label fw-semibold">
                                        Catégorie <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control-ceeac @error('categorie') is-invalid @enderror" id="modal_categorie" name="categorie" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="general" {{ old('categorie') === 'general' ? 'selected' : '' }}>Général</option>
                                        <option value="import" {{ old('categorie') === 'import' ? 'selected' : '' }}>Import</option>
                                        <option value="export" {{ old('categorie') === 'export' ? 'selected' : '' }}>Export</option>
                                        <option value="documentation" {{ old('categorie') === 'documentation' ? 'selected' : '' }}>Documentation</option>
                                        <option value="template" {{ old('categorie') === 'template' ? 'selected' : '' }}>Modèles</option>
                                        <option value="autre" {{ old('categorie') === 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('categorie')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="modal_fichier" class="form-label fw-semibold">
                                        Fichier <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control form-control-ceeac @error('fichier') is-invalid @enderror" 
                                           id="modal_fichier" name="fichier" required 
                                           accept=".xlsx,.xls,.pdf,.zip,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>Taille maximale : 10 MB
                                    </small>
                                    @error('fichier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="modal_date_publication" class="form-label fw-semibold">Date de publication</label>
                                    <input type="date" class="form-control form-control-ceeac @error('date_publication') is-invalid @enderror" 
                                           id="modal_date_publication" name="date_publication" value="{{ old('date_publication', date('Y-m-d')) }}">
                                    @error('date_publication')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="modal_est_public" name="est_public" value="1" 
                                               {{ old('est_public', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="modal_est_public">
                                            Ressource publique
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="modal_est_actif" name="est_actif" value="1" 
                                               {{ old('est_actif', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="modal_est_actif">
                                            Ressource active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </button>
                            <button type="submit" class="btn btn-primary btn-ceeac">
                                <i class="bi bi-save me-2"></i>Créer la ressource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    @endauth
</x-app-layout>
