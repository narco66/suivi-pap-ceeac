<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier l'objectif
            </h2>
            <a href="{{ route('objectifs.show', $objectif) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour aux détails
            </a>
        </div>
    </x-slot>

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-bullseye me-2"></i>Informations de l'objectif
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('objectifs.update', $objectif) }}" id="objectifForm">
                @csrf
                @method('PUT')

                <!-- Version PAPA -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="papa_version_id" class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-text me-1"></i>Version du PAPA <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="papa_version_id" 
                            id="papa_version_id" 
                            class="form-select form-control-ceeac @error('papa_version_id') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Sélectionner une version --</option>
                            @foreach($versions as $version)
                                <option value="{{ $version['id'] }}" {{ old('papa_version_id', $objectif->papa_version_id) == $version['id'] ? 'selected' : '' }}>
                                    {{ $version['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('papa_version_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Sélectionnez la version du PAPA à laquelle cet objectif appartient.</small>
                    </div>
                </div>

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
                            value="{{ old('code', $objectif->code) }}" 
                            placeholder="OBJ-001"
                            maxlength="32"
                            required
                        >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Code unique de l'objectif (max 32 caractères).</small>
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
                            value="{{ old('libelle', $objectif->libelle) }}" 
                            placeholder="Libellé de l'objectif"
                            required
                        >
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Titre descriptif de l'objectif.</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Description
                        </label>
                        <textarea 
                            class="form-control form-control-ceeac @error('description') is-invalid @enderror" 
                            id="description" 
                            name="description" 
                            rows="4"
                            placeholder="Description détaillée de l'objectif..."
                        >{{ old('description', $objectif->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Description complète de l'objectif et de ses enjeux.</small>
                    </div>
                </div>

                <!-- Statut et Priorité -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="statut" class="form-label fw-semibold">
                            <i class="bi bi-flag me-1"></i>Statut <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="statut" 
                            id="statut" 
                            class="form-select form-control-ceeac @error('statut') is-invalid @enderror" 
                            required
                        >
                            <option value="brouillon" {{ old('statut', $objectif->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="planifie" {{ old('statut', $objectif->statut) == 'planifie' ? 'selected' : '' }}>Planifié</option>
                            <option value="en_cours" {{ old('statut', $objectif->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('statut', $objectif->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="annule" {{ old('statut', $objectif->statut) == 'annule' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="priorite" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Priorité <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="priorite" 
                            id="priorite" 
                            class="form-select form-control-ceeac @error('priorite') is-invalid @enderror" 
                            required
                        >
                            <option value="normale" {{ old('priorite', $objectif->priorite ?? 'normale') == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="basse" {{ old('priorite', $objectif->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                            <option value="haute" {{ old('priorite', $objectif->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="critique" {{ old('priorite', $objectif->priorite) == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('priorite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dates prévues -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-semibold text-ceeac-blue mb-3">
                            <i class="bi bi-calendar-range me-2"></i>Dates prévues
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label for="date_debut_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date de début prévue
                        </label>
                        <input 
                            type="datetime-local" 
                            class="form-control form-control-ceeac @error('date_debut_prevue') is-invalid @enderror" 
                            id="date_debut_prevue" 
                            name="date_debut_prevue" 
                            value="{{ old('date_debut_prevue', $objectif->date_debut_prevue ? $objectif->date_debut_prevue->format('Y-m-d\TH:i') : '') }}"
                        >
                        @error('date_debut_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Date de fin prévue
                        </label>
                        <input 
                            type="datetime-local" 
                            class="form-control form-control-ceeac @error('date_fin_prevue') is-invalid @enderror" 
                            id="date_fin_prevue" 
                            name="date_fin_prevue" 
                            value="{{ old('date_fin_prevue', $objectif->date_fin_prevue ? $objectif->date_fin_prevue->format('Y-m-d\TH:i') : '') }}"
                        >
                        @error('date_fin_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Doit être postérieure ou égale à la date de début.</small>
                    </div>
                </div>

                <!-- Dates réelles -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-semibold text-ceeac-blue mb-3">
                            <i class="bi bi-calendar-check me-2"></i>Dates réelles (optionnel)
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <label for="date_debut_reelle" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date de début réelle
                        </label>
                        <input 
                            type="datetime-local" 
                            class="form-control form-control-ceeac @error('date_debut_reelle') is-invalid @enderror" 
                            id="date_debut_reelle" 
                            name="date_debut_reelle" 
                            value="{{ old('date_debut_reelle', $objectif->date_debut_reelle ? $objectif->date_debut_reelle->format('Y-m-d\TH:i') : '') }}"
                        >
                        @error('date_debut_reelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin_reelle" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Date de fin réelle
                        </label>
                        <input 
                            type="datetime-local" 
                            class="form-control form-control-ceeac @error('date_fin_reelle') is-invalid @enderror" 
                            id="date_fin_reelle" 
                            name="date_fin_reelle" 
                            value="{{ old('date_fin_reelle', $objectif->date_fin_reelle ? $objectif->date_fin_reelle->format('Y-m-d\TH:i') : '') }}"
                        >
                        @error('date_fin_reelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Pourcentage d'avancement -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="pourcentage_avancement" class="form-label fw-semibold">
                            <i class="bi bi-percent me-1"></i>Pourcentage d'avancement
                        </label>
                        <div class="input-group">
                            <input 
                                type="number" 
                                class="form-control form-control-ceeac @error('pourcentage_avancement') is-invalid @enderror" 
                                id="pourcentage_avancement" 
                                name="pourcentage_avancement" 
                                value="{{ old('pourcentage_avancement', $objectif->pourcentage_avancement ?? 0) }}" 
                                min="0" 
                                max="100"
                                step="1"
                            >
                            <span class="input-group-text">%</span>
                            @error('pourcentage_avancement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="progress progress-ceeac mt-2" style="height: 8px;">
                            <div 
                                class="progress-bar progress-bar-ceeac" 
                                role="progressbar" 
                                id="progressBar"
                                style="width: {{ old('pourcentage_avancement', $objectif->pourcentage_avancement ?? 0) }}%"
                                aria-valuenow="{{ old('pourcentage_avancement', $objectif->pourcentage_avancement ?? 0) }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100"
                            ></div>
                        </div>
                        <small class="form-text text-muted">Valeur entre 0 et 100.</small>
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
                    <a href="{{ route('objectifs.show', $objectif) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mise à jour de la barre de progression
            const pourcentageInput = document.getElementById('pourcentage_avancement');
            const progressBar = document.getElementById('progressBar');
            
            if (pourcentageInput && progressBar) {
                pourcentageInput.addEventListener('input', function() {
                    const value = parseInt(this.value) || 0;
                    progressBar.style.width = value + '%';
                    progressBar.setAttribute('aria-valuenow', value);
                });
            }

            // Validation des dates
            const dateDebutPrevue = document.getElementById('date_debut_prevue');
            const dateFinPrevue = document.getElementById('date_fin_prevue');
            const dateDebutReelle = document.getElementById('date_debut_reelle');
            const dateFinReelle = document.getElementById('date_fin_reelle');

            if (dateFinPrevue && dateDebutPrevue) {
                dateDebutPrevue.addEventListener('change', function() {
                    if (this.value && dateFinPrevue.value) {
                        if (new Date(dateFinPrevue.value) < new Date(this.value)) {
                            dateFinPrevue.setCustomValidity('La date de fin doit être postérieure à la date de début');
                        } else {
                            dateFinPrevue.setCustomValidity('');
                        }
                    }
                });

                dateFinPrevue.addEventListener('change', function() {
                    if (this.value && dateDebutPrevue.value) {
                        if (new Date(this.value) < new Date(dateDebutPrevue.value)) {
                            this.setCustomValidity('La date de fin doit être postérieure à la date de début');
                        } else {
                            this.setCustomValidity('');
                        }
                    }
                });
            }

            if (dateFinReelle && dateDebutReelle) {
                dateDebutReelle.addEventListener('change', function() {
                    if (this.value && dateFinReelle.value) {
                        if (new Date(dateFinReelle.value) < new Date(this.value)) {
                            dateFinReelle.setCustomValidity('La date de fin doit être postérieure à la date de début');
                        } else {
                            dateFinReelle.setCustomValidity('');
                        }
                    }
                });

                dateFinReelle.addEventListener('change', function() {
                    if (this.value && dateDebutReelle.value) {
                        if (new Date(this.value) < new Date(dateDebutReelle.value)) {
                            this.setCustomValidity('La date de fin doit être postérieure à la date de début');
                        } else {
                            this.setCustomValidity('');
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>



