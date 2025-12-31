<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-pencil me-2"></i>Modifier la tâche
            </h2>
            <a href="{{ route('taches.show', $tache->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la tâche
            </a>
        </div>
    </x-slot>

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-list-task me-2"></i>Informations de la tâche
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('taches.update', $tache->id) }}" id="tacheForm">
                @csrf
                @method('PUT')

                <!-- Action prioritaire -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="action_prioritaire_id" class="form-label fw-semibold">
                            <i class="bi bi-bullseye me-1"></i>Action prioritaire <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="action_prioritaire_id" 
                            id="action_prioritaire_id" 
                            class="form-select form-control-ceeac @error('action_prioritaire_id') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Sélectionner une action --</option>
                            @foreach($actions as $action)
                                <option value="{{ $action['id'] }}" {{ old('action_prioritaire_id', $tache->action_prioritaire_id) == $action['id'] ? 'selected' : '' }}>
                                    {{ $action['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('action_prioritaire_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            value="{{ old('code', $tache->code) }}" 
                            placeholder="TACHE-001"
                            maxlength="32"
                            required
                        >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            value="{{ old('libelle', $tache->libelle) }}" 
                            placeholder="Libellé de la tâche"
                            required
                        >
                        @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            placeholder="Description détaillée de la tâche..."
                        >{{ old('description', $tache->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Statut, Priorité, Criticité -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="statut" class="form-label fw-semibold">
                            <i class="bi bi-flag me-1"></i>Statut <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="statut" 
                            id="statut" 
                            class="form-select form-control-ceeac @error('statut') is-invalid @enderror" 
                            required
                        >
                            <option value="planifie" {{ old('statut', $tache->statut) == 'planifie' ? 'selected' : '' }}>Planifié</option>
                            <option value="en_cours" {{ old('statut', $tache->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('statut', $tache->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="en_retard" {{ old('statut', $tache->statut) == 'en_retard' ? 'selected' : '' }}>En retard</option>
                            <option value="bloque" {{ old('statut', $tache->statut) == 'bloque' ? 'selected' : '' }}>Bloqué</option>
                            <option value="annule" {{ old('statut', $tache->statut) == 'annule' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="priorite" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-circle me-1"></i>Priorité
                        </label>
                        <select 
                            name="priorite" 
                            id="priorite" 
                            class="form-select form-control-ceeac @error('priorite') is-invalid @enderror"
                        >
                            <option value="">-- Sélectionner --</option>
                            <option value="basse" {{ old('priorite', $tache->priorite) == 'basse' ? 'selected' : '' }}>Basse</option>
                            <option value="normale" {{ old('priorite', $tache->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                            <option value="haute" {{ old('priorite', $tache->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                            <option value="critique" {{ old('priorite', $tache->priorite) == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('priorite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="criticite" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Criticité
                        </label>
                        <select 
                            name="criticite" 
                            id="criticite" 
                            class="form-select form-control-ceeac @error('criticite') is-invalid @enderror"
                        >
                            <option value="normal" {{ old('criticite', $tache->criticite ?? 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="vigilance" {{ old('criticite', $tache->criticite) == 'vigilance' ? 'selected' : '' }}>Vigilance</option>
                            <option value="critique" {{ old('criticite', $tache->criticite) == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('criticite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tâche parente et Responsable -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tache_parent_id" class="form-label fw-semibold">
                            <i class="bi bi-list-nested me-1"></i>Tâche parente
                        </label>
                        <select 
                            name="tache_parent_id" 
                            id="tache_parent_id" 
                            class="form-select form-control-ceeac @error('tache_parent_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucune (tâche principale) --</option>
                            @foreach($tachesParentes as $tacheParente)
                                <option value="{{ $tacheParente['id'] }}" {{ old('tache_parent_id', $tache->tache_parent_id) == $tacheParente['id'] ? 'selected' : '' }}>
                                    {{ $tacheParente['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('tache_parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="responsable_id" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Responsable
                        </label>
                        <select 
                            name="responsable_id" 
                            id="responsable_id" 
                            class="form-select form-control-ceeac @error('responsable_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucun responsable --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('responsable_id', $tache->responsable_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('responsable_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dates prévues -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="date_debut_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date début prévue
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_debut_prevue') is-invalid @enderror" 
                            id="date_debut_prevue" 
                            name="date_debut_prevue" 
                            value="{{ old('date_debut_prevue', $tache->date_debut_prevue ? $tache->date_debut_prevue->format('Y-m-d') : '') }}"
                        >
                        @error('date_debut_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin_prevue" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Date fin prévue
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_fin_prevue') is-invalid @enderror" 
                            id="date_fin_prevue" 
                            name="date_fin_prevue" 
                            value="{{ old('date_fin_prevue', $tache->date_fin_prevue ? $tache->date_fin_prevue->format('Y-m-d') : '') }}"
                        >
                        @error('date_fin_prevue')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dates réelles -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="date_debut_reelle" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date début réelle
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_debut_reelle') is-invalid @enderror" 
                            id="date_debut_reelle" 
                            name="date_debut_reelle" 
                            value="{{ old('date_debut_reelle', $tache->date_debut_reelle ? $tache->date_debut_reelle->format('Y-m-d') : '') }}"
                        >
                        @error('date_debut_reelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin_reelle" class="form-label fw-semibold">
                            <i class="bi bi-calendar-check me-1"></i>Date fin réelle
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_fin_reelle') is-invalid @enderror" 
                            id="date_fin_reelle" 
                            name="date_fin_reelle" 
                            value="{{ old('date_fin_reelle', $tache->date_fin_reelle ? $tache->date_fin_reelle->format('Y-m-d') : '') }}"
                        >
                        @error('date_fin_reelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Avancement -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="pourcentage_avancement" class="form-label fw-semibold">
                            <i class="bi bi-percent me-1"></i>Pourcentage d'avancement (%)
                        </label>
                        <input 
                            type="number" 
                            class="form-control form-control-ceeac @error('pourcentage_avancement') is-invalid @enderror" 
                            id="pourcentage_avancement" 
                            name="pourcentage_avancement" 
                            value="{{ old('pourcentage_avancement', $tache->pourcentage_avancement ?? 0) }}"
                            min="0"
                            max="100"
                        >
                        @error('pourcentage_avancement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="progress progress-ceeac mt-2" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ old('pourcentage_avancement', $tache->pourcentage_avancement ?? 0) }}%"
                                 id="avancementBar">
                                {{ old('pourcentage_avancement', $tache->pourcentage_avancement ?? 0) }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blocage -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-check mb-3">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="bloque" 
                                name="bloque" 
                                value="1"
                                {{ old('bloque', $tache->bloque) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="bloque">
                                Tâche bloquée
                            </label>
                        </div>
                        <div id="raisonBlocageContainer" style="display: {{ old('bloque', $tache->bloque) ? 'block' : 'none' }};">
                            <label for="raison_blocage" class="form-label fw-semibold">
                                <i class="bi bi-exclamation-triangle me-1"></i>Raison du blocage
                            </label>
                            <textarea 
                                class="form-control form-control-ceeac @error('raison_blocage') is-invalid @enderror" 
                                id="raison_blocage" 
                                name="raison_blocage" 
                                rows="3"
                                placeholder="Expliquez la raison du blocage..."
                            >{{ old('raison_blocage', $tache->raison_blocage) }}</textarea>
                            @error('raison_blocage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Jalon -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="est_jalon" 
                                name="est_jalon" 
                                value="1"
                                {{ old('est_jalon', $tache->est_jalon) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="est_jalon">
                                <i class="bi bi-flag me-1"></i>Cette tâche est un jalon
                            </label>
                        </div>
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
                    <a href="{{ route('taches.show', $tache->id) }}" class="btn btn-outline-secondary">
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
            // Gestion de l'affichage de la raison de blocage
            const bloqueCheckbox = document.getElementById('bloque');
            const raisonBlocageContainer = document.getElementById('raisonBlocageContainer');
            
            bloqueCheckbox.addEventListener('change', function() {
                raisonBlocageContainer.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    document.getElementById('raison_blocage').value = '';
                }
            });

            // Mise à jour de la barre de progression
            const avancementInput = document.getElementById('pourcentage_avancement');
            const avancementBar = document.getElementById('avancementBar');
            
            avancementInput.addEventListener('input', function() {
                const value = this.value || 0;
                avancementBar.style.width = value + '%';
                avancementBar.textContent = value + '%';
            });
        });
    </script>
    @endpush
</x-app-layout>


