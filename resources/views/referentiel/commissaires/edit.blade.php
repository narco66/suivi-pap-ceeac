<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier le commissaire
            </h2>
            <a href="{{ route('commissaires.show', $commissaire) }}" class="btn btn-outline-secondary">
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
                <i class="bi bi-person-badge me-2"></i>Informations du commissaire
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('commissaires.update', $commissaire) }}" id="commissaireForm">
                @csrf
                @method('PUT')

                <!-- Nom et Prénom -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="nom" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Nom <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('nom') is-invalid @enderror" 
                            id="nom" 
                            name="nom" 
                            value="{{ old('nom', $commissaire->nom) }}" 
                            placeholder="Nom du commissaire"
                            required
                        >
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Prénom <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('prenom') is-invalid @enderror" 
                            id="prenom" 
                            name="prenom" 
                            value="{{ old('prenom', $commissaire->prenom) }}" 
                            placeholder="Prénom du commissaire"
                            required
                        >
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Titre et Commission -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="titre" class="form-label fw-semibold">
                            <i class="bi bi-award me-1"></i>Titre
                        </label>
                        <select 
                            name="titre" 
                            id="titre" 
                            class="form-select form-control-ceeac @error('titre') is-invalid @enderror"
                        >
                            <option value="">-- Sélectionner un titre --</option>
                            <option value="M." {{ old('titre', $commissaire->titre) == 'M.' ? 'selected' : '' }}>M.</option>
                            <option value="Mme" {{ old('titre', $commissaire->titre) == 'Mme' ? 'selected' : '' }}>Mme</option>
                            <option value="Dr" {{ old('titre', $commissaire->titre) == 'Dr' ? 'selected' : '' }}>Dr</option>
                            <option value="Prof" {{ old('titre', $commissaire->titre) == 'Prof' ? 'selected' : '' }}>Prof</option>
                            <option value="S.E." {{ old('titre', $commissaire->titre) == 'S.E.' ? 'selected' : '' }}>S.E.</option>
                        </select>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="commission_id" class="form-label fw-semibold">
                            <i class="bi bi-building me-1"></i>Commission
                        </label>
                        <select 
                            name="commission_id" 
                            id="commission_id" 
                            class="form-select form-control-ceeac @error('commission_id') is-invalid @enderror"
                        >
                            <option value="">-- Sélectionner une commission --</option>
                            @foreach($commissions ?? [] as $commission)
                                <option value="{{ $commission['id'] }}" {{ old('commission_id', $commissaire->commission_id) == $commission['id'] ? 'selected' : '' }}>
                                    {{ $commission['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('commission_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Commission à laquelle le commissaire est assigné.</small>
                    </div>
                </div>

                <!-- Pays d'origine et Date de nomination -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="pays_origine" class="form-label fw-semibold">
                            <i class="bi bi-globe me-1"></i>Pays d'origine
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('pays_origine') is-invalid @enderror" 
                            id="pays_origine" 
                            name="pays_origine" 
                            value="{{ old('pays_origine', $commissaire->pays_origine) }}" 
                            placeholder="Ex: Cameroun, Gabon, etc."
                            maxlength="100"
                        >
                        @error('pays_origine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pays d'origine du commissaire.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="date_nomination" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date de nomination
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_nomination') is-invalid @enderror" 
                            id="date_nomination" 
                            name="date_nomination" 
                            value="{{ old('date_nomination', $commissaire->date_nomination ? $commissaire->date_nomination->format('Y-m-d') : '') }}"
                        >
                        @error('date_nomination')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Date à laquelle le commissaire a été nommé.</small>
                    </div>
                </div>

                <!-- Statut -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="actif" class="form-label fw-semibold">
                            <i class="bi bi-toggle-on me-1"></i>Statut
                        </label>
                        <select 
                            name="actif" 
                            id="actif" 
                            class="form-select form-control-ceeac @error('actif') is-invalid @enderror"
                        >
                            <option value="1" {{ old('actif', $commissaire->actif ? '1' : '0') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ old('actif', $commissaire->actif ? '1' : '0') == '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('actif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Statut du commissaire dans le système.</small>
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
                    <a href="{{ route('commissaires.show', $commissaire) }}" class="btn btn-outline-secondary">
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


