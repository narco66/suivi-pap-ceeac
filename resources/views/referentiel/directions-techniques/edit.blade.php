<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier la direction technique
            </h2>
            <a href="{{ route('directions-techniques.show', $directionTechnique) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour aux détails
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-diagram-3 me-2"></i>Informations de la direction technique
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('directions-techniques.update', $directionTechnique) }}" id="directionTechniqueForm">
                @csrf
                @method('PUT')

                <!-- Code et Libellé -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="code" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Code <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('code') is-invalid @enderror" 
                            id="code" 
                            name="code" 
                            value="{{ old('code', $directionTechnique->code) }}" 
                            placeholder="Ex: DT001"
                            maxlength="32"
                            required
                        >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Code unique identifiant la direction technique.</small>
                    </div>
                    <div class="col-md-8">
                        <label for="libelle" class="form-label fw-semibold">
                            <i class="bi bi-card-heading me-1"></i>Libellé <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('libelle') is-invalid @enderror" 
                            id="libelle" 
                            name="libelle" 
                            value="{{ old('libelle', $directionTechnique->libelle) }}" 
                            placeholder="Nom de la direction technique"
                            maxlength="255"
                            required
                        >
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Nom complet de la direction technique.</small>
                    </div>
                </div>

                <!-- Département -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="departement_id" class="form-label fw-semibold">
                            <i class="bi bi-building me-1"></i>Département
                        </label>
                        <select 
                            name="departement_id" 
                            id="departement_id" 
                            class="form-select form-control-ceeac @error('departement_id') is-invalid @enderror"
                        >
                            <option value="">-- Sélectionner un département --</option>
                            @foreach($departements ?? [] as $departement)
                                <option value="{{ $departement['id'] }}" {{ old('departement_id', $directionTechnique->departement_id) == $departement['id'] ? 'selected' : '' }}>
                                    {{ $departement['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('departement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Département auquel la direction technique est rattachée.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="actif" class="form-label fw-semibold">
                            <i class="bi bi-toggle-on me-1"></i>Statut
                        </label>
                        <select 
                            name="actif" 
                            id="actif" 
                            class="form-select form-control-ceeac @error('actif') is-invalid @enderror"
                        >
                            <option value="1" {{ old('actif', $directionTechnique->actif ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('actif', $directionTechnique->actif ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('actif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Statut de la direction technique dans le système.</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Description
                        </label>
                        <textarea 
                            class="form-control form-control-ceeac @error('description') is-invalid @enderror" 
                            id="description" 
                            name="description" 
                            rows="4"
                            placeholder="Description détaillée de la direction technique..."
                        >{{ old('description', $directionTechnique->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Description détaillée des missions et responsabilités de la direction technique.</small>
                    </div>
                </div>

                <!-- Messages d'erreur généraux -->
                @if($errors->any())
                    <div class="alert alert-danger alert-ceeac">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Boutons d'action -->
                <div class="d-flex gap-2 justify-content-end mt-4 pt-4 border-top">
                    <a href="{{ route('directions-techniques.show', $directionTechnique) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

