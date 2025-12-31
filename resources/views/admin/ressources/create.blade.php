<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-folder-plus me-2"></i>Créer une ressource
            </h2>
            <a href="{{ route('admin.ressources.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <!-- Messages flash -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.ressources.store') }}" enctype="multipart/form-data">
                @csrf

                @if($errors->any())
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">
                            <i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation
                        </h5>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                               id="titre" name="titre" value="{{ old('titre') }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="version" class="form-label">Version</label>
                        <input type="text" class="form-control @error('version') is-invalid @enderror" 
                               id="version" name="version" value="{{ old('version', '1.0') }}">
                        @error('version')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type de fichier <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
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
                        <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select @error('categorie') is-invalid @enderror" id="categorie" name="categorie" required>
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
                        <label for="fichier" class="form-label">Fichier <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('fichier') is-invalid @enderror" 
                               id="fichier" name="fichier" required accept=".xlsx,.xls,.pdf,.zip,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Taille maximale : 10 MB</small>
                        @error('fichier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_publication" class="form-label">Date de publication</label>
                        <input type="date" class="form-control @error('date_publication') is-invalid @enderror" 
                               id="date_publication" name="date_publication" value="{{ old('date_publication', date('Y-m-d')) }}">
                        @error('date_publication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="est_public" name="est_public" value="1" 
                                   {{ old('est_public', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_public">
                                Ressource publique (accessible sans authentification)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="est_actif" name="est_actif" value="1" 
                                   {{ old('est_actif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_actif">
                                Ressource active
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-save me-2"></i>Créer la ressource
                        </button>
                        <a href="{{ route('admin.ressources.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

