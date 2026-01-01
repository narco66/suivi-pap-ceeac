<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-lightning-charge me-2"></i>Créer une action prioritaire
            </h2>
            <a href="{{ route('actions-prioritaires.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
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

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Informations de l'action prioritaire
            </h5>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="alert-heading">
                        <i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation
                    </h6>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('actions-prioritaires.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="objectif_id" class="form-label fw-semibold">
                            <i class="bi bi-bullseye me-1"></i>Objectif <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-control-ceeac @error('objectif_id') is-invalid @enderror" 
                                id="objectif_id" name="objectif_id" required>
                            <option value="">Sélectionner un objectif</option>
                            @foreach($objectifs as $objectif)
                                <option value="{{ $objectif->id }}" {{ old('objectif_id') == $objectif->id ? 'selected' : '' }}>
                                    {{ $objectif->code }} - {{ $objectif->libelle }} 
                                    @if($objectif->papaVersion && $objectif->papaVersion->papa)
                                        ({{ $objectif->papaVersion->papa->annee ?? '' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('objectif_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="code" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Code <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" required 
                               placeholder="Ex: AP001" maxlength="32">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="type" class="form-label fw-semibold">
                            <i class="bi bi-grid me-1"></i>Type
                        </label>
                        <select class="form-select form-control-ceeac @error('type') is-invalid @enderror" id="type" name="type">
                            <option value="technique" {{ old('type', 'technique') === 'technique' ? 'selected' : '' }}>Technique</option>
                            <option value="appui" {{ old('type') === 'appui' ? 'selected' : '' }}>Appui</option>
                            <option value="administratif" {{ old('type') === 'administratif' ? 'selected' : '' }}>Administratif</option>
                            <option value="autre" {{ old('type') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="libelle" class="form-label fw-semibold">
                            <i class="bi bi-card-text me-1"></i>Libellé <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-ceeac @error('libelle') is-invalid @enderror" 
                               id="libelle" name="libelle" value="{{ old('libelle') }}" required 
                               placeholder="Libellé de l'action prioritaire">
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Description
                        </label>
                        <textarea class="form-control form-control-ceeac @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Description détaillée de l'action prioritaire">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="direction_technique_id" class="form-label fw-semibold">
                            <i class="bi bi-gear me-1"></i>Direction Technique
                        </label>
                        <select class="form-select form-control-ceeac @error('direction_technique_id') is-invalid @enderror" 
                                id="direction_technique_id" name="direction_technique_id">
                            <option value="">Sélectionner une direction technique</option>
                            @foreach($directionsTechniques as $direction)
                                <option value="{{ $direction->id }}" {{ old('direction_technique_id') == $direction->id ? 'selected' : '' }}>
                                    {{ $direction->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('direction_technique_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="direction_appui_id" class="form-label fw-semibold">
                            <i class="bi bi-briefcase me-1"></i>Direction d'Appui
                        </label>
                        <select class="form-select form-control-ceeac @error('direction_appui_id') is-invalid @enderror" 
                                id="direction_appui_id" name="direction_appui_id">
                            <option value="">Sélectionner une direction d'appui</option>
                            @foreach($directionsAppui as $direction)
                                <option value="{{ $direction->id }}" {{ old('direction_appui_id') == $direction->id ? 'selected' : '' }}>
                                    {{ $direction->libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('direction_appui_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="statut" class="form-label fw-semibold">
                            <i class="bi bi-info-circle me-1"></i>Statut
                        </label>
                        <select class="form-select form-control-ceeac @error('statut') is-invalid @enderror" id="statut" name="statut">
                            <option value="brouillon" {{ old('statut', 'brouillon') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="planifie" {{ old('statut') === 'planifie' ? 'selected' : '' }}>Planifiée</option>
                            <option value="en_cours" {{ old('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="en_attente" {{ old('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="termine" {{ old('statut') === 'termine' ? 'selected' : '' }}>Terminée</option>
                            <option value="annule" {{ old('statut') === 'annule' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="priorite" class="form-label fw-semibold">
                            <i class="bi bi-flag me-1"></i>Priorité
                        </label>
                        <select class="form-select form-control-ceeac @error('priorite') is-invalid @enderror" id="priorite" name="priorite">
                            <option value="faible" {{ old('priorite', 'normale') === 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="normale" {{ old('priorite', 'normale') === 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="moyenne" {{ old('priorite') === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                            <option value="elevee" {{ old('priorite') === 'elevee' ? 'selected' : '' }}>Élevée</option>
                            <option value="critique" {{ old('priorite') === 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('priorite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="criticite" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Criticité
                        </label>
                        <select class="form-select form-control-ceeac @error('criticite') is-invalid @enderror" id="criticite" name="criticite">
                            <option value="faible" {{ old('criticite', 'normal') === 'faible' ? 'selected' : '' }}>Faible</option>
                            <option value="normal" {{ old('criticite', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="moyenne" {{ old('criticite') === 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                            <option value="elevee" {{ old('criticite') === 'elevee' ? 'selected' : '' }}>Élevée</option>
                            <option value="critique" {{ old('criticite') === 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('criticite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="pourcentage_avancement" class="form-label fw-semibold">
                            <i class="bi bi-percent me-1"></i>Avancement (%)
                        </label>
                        <input type="number" class="form-control form-control-ceeac @error('pourcentage_avancement') is-invalid @enderror" 
                               id="pourcentage_avancement" name="pourcentage_avancement" 
                               value="{{ old('pourcentage_avancement', 0) }}" 
                               min="0" max="100" step="1">
                        @error('pourcentage_avancement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_debut_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date de début prévue
                        </label>
                        <input type="datetime-local" class="form-control form-control-ceeac @error('date_debut_prevue') is-invalid @enderror" 
                               id="date_debut_prevue" name="date_debut_prevue" 
                               value="{{ old('date_debut_prevue') }}">
                        @error('date_debut_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="date_fin_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Date de fin prévue
                        </label>
                        <input type="datetime-local" class="form-control form-control-ceeac @error('date_fin_prevue') is-invalid @enderror" 
                               id="date_fin_prevue" name="date_fin_prevue" 
                               value="{{ old('date_fin_prevue') }}">
                        @error('date_fin_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bloque" name="bloque" value="1" 
                                   {{ old('bloque') ? 'checked' : '' }}>
                            <label class="form-check-label" for="bloque">
                                <i class="bi bi-lock me-1"></i>Action bloquée
                            </label>
                        </div>
                    </div>

                    <div class="col-12" id="raison_blocage_container" style="display: {{ old('bloque') ? 'block' : 'none' }};">
                        <label for="raison_blocage" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-circle me-1"></i>Raison du blocage
                        </label>
                        <textarea class="form-control form-control-ceeac @error('raison_blocage') is-invalid @enderror" 
                                  id="raison_blocage" name="raison_blocage" rows="3" 
                                  placeholder="Expliquez la raison du blocage">{{ old('raison_blocage') }}</textarea>
                        @error('raison_blocage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-ceeac">
                                <i class="bi bi-save me-2"></i>Créer l'action prioritaire
                            </button>
                            <a href="{{ route('actions-prioritaires.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bloqueCheckbox = document.getElementById('bloque');
            const raisonBlocageContainer = document.getElementById('raison_blocage_container');
            
            bloqueCheckbox.addEventListener('change', function() {
                raisonBlocageContainer.style.display = this.checked ? 'block' : 'none';
            });
        });
    </script>
    @endpush
</x-app-layout>


