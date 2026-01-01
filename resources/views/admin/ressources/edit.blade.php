<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier la ressource : {{ $ressource->titre }}
            </h2>
            <a href="{{ route('admin.ressources.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.ressources.update', $ressource) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                               id="titre" name="titre" value="{{ old('titre', $ressource->titre) }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="version" class="form-label">Version</label>
                        <input type="text" class="form-control @error('version') is-invalid @enderror" 
                               id="version" name="version" value="{{ old('version', $ressource->version) }}">
                        @error('version')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $ressource->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Type de fichier <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="excel" {{ old('type', $ressource->type) === 'excel' ? 'selected' : '' }}>Excel</option>
                            <option value="pdf" {{ old('type', $ressource->type) === 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="zip" {{ old('type', $ressource->type) === 'zip' ? 'selected' : '' }}>ZIP</option>
                            <option value="doc" {{ old('type', $ressource->type) === 'doc' ? 'selected' : '' }}>Word (DOC)</option>
                            <option value="docx" {{ old('type', $ressource->type) === 'docx' ? 'selected' : '' }}>Word (DOCX)</option>
                            <option value="image" {{ old('type', $ressource->type) === 'image' ? 'selected' : '' }}>Image</option>
                            <option value="autre" {{ old('type', $ressource->type) === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select @error('categorie') is-invalid @enderror" id="categorie" name="categorie" required>
                            <option value="">Sélectionner une catégorie</option>
                            <option value="general" {{ old('categorie', $ressource->categorie) === 'general' ? 'selected' : '' }}>Général</option>
                            <option value="import" {{ old('categorie', $ressource->categorie) === 'import' ? 'selected' : '' }}>Import</option>
                            <option value="export" {{ old('categorie', $ressource->categorie) === 'export' ? 'selected' : '' }}>Export</option>
                            <option value="documentation" {{ old('categorie', $ressource->categorie) === 'documentation' ? 'selected' : '' }}>Documentation</option>
                            <option value="template" {{ old('categorie', $ressource->categorie) === 'template' ? 'selected' : '' }}>Modèles</option>
                            <option value="autre" {{ old('categorie', $ressource->categorie) === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="fichier" class="form-label">Nouveau fichier (optionnel)</label>
                        <input type="file" class="form-control @error('fichier') is-invalid @enderror" 
                               id="fichier" name="fichier" accept=".xlsx,.xls,.pdf,.zip,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">
                            Laisser vide pour conserver le fichier actuel. Taille maximale : 10 MB
                            @if($ressource->nom_fichier_original)
                                <br>Fichier actuel : <strong>{{ $ressource->nom_fichier_original }}</strong> ({{ $ressource->taille_formatee }})
                            @endif
                        </small>
                        @error('fichier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_publication" class="form-label">Date de publication</label>
                        <input type="date" class="form-control @error('date_publication') is-invalid @enderror" 
                               id="date_publication" name="date_publication" 
                               value="{{ old('date_publication', $ressource->date_publication?->format('Y-m-d')) }}">
                        @error('date_publication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="est_public" name="est_public" value="1" 
                                   {{ old('est_public', $ressource->est_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_public">
                                Ressource publique
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="est_actif" name="est_actif" value="1" 
                                   {{ old('est_actif', $ressource->est_actif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_actif">
                                Ressource active
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-ceeac-primary">
                            <i class="bi bi-save me-2"></i>Enregistrer les modifications
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



