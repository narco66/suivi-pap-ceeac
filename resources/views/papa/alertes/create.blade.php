<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-plus-circle me-2"></i>Créer une alerte
            </h2>
            <a href="{{ route('alertes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-bell me-2"></i>Informations de l'alerte
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alertes.store') }}" id="alerteForm">
                @csrf

                <!-- Type et Titre -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Type d'alerte <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="type" 
                            id="type" 
                            class="form-select form-control-ceeac @error('type') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Sélectionner un type --</option>
                            <option value="echeance_depassee" {{ old('type') == 'echeance_depassee' ? 'selected' : '' }}>
                                Échéance dépassée
                            </option>
                            <option value="retard_critique" {{ old('type') == 'retard_critique' ? 'selected' : '' }}>
                                Retard critique
                            </option>
                            <option value="blocage" {{ old('type') == 'blocage' ? 'selected' : '' }}>
                                Blocage
                            </option>
                            <option value="kpi_non_atteint" {{ old('type') == 'kpi_non_atteint' ? 'selected' : '' }}>
                                KPI non atteint
                            </option>
                            <option value="anomalie" {{ old('type') == 'anomalie' ? 'selected' : '' }}>
                                Anomalie
                            </option>
                            <option value="escalade" {{ old('type') == 'escalade' ? 'selected' : '' }}>
                                Escalade
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="titre" class="form-label fw-semibold">
                            <i class="bi bi-card-heading me-1"></i>Titre <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('titre') is-invalid @enderror" 
                            id="titre" 
                            name="titre" 
                            value="{{ old('titre') }}" 
                            placeholder="Titre de l'alerte"
                            maxlength="255"
                            required
                        >
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Message -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="message" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Message <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            class="form-control form-control-ceeac @error('message') is-invalid @enderror" 
                            id="message" 
                            name="message" 
                            rows="4"
                            placeholder="Description détaillée de l'alerte..."
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Décrivez en détail le problème ou la situation nécessitant une alerte.</small>
                    </div>
                </div>

                <!-- Criticité et Statut -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="criticite" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Criticité <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="criticite" 
                            id="criticite" 
                            class="form-select form-control-ceeac @error('criticite') is-invalid @enderror" 
                            required
                        >
                            <option value="normal" {{ old('criticite', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="vigilance" {{ old('criticite') == 'vigilance' ? 'selected' : '' }}>Vigilance</option>
                            <option value="critique" {{ old('criticite') == 'critique' ? 'selected' : '' }}>Critique</option>
                        </select>
                        @error('criticite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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
                            <option value="ouverte" {{ old('statut', 'ouverte') == 'ouverte' ? 'selected' : '' }}>Ouverte</option>
                            <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="resolue" {{ old('statut') == 'resolue' ? 'selected' : '' }}>Résolue</option>
                            <option value="fermee" {{ old('statut') == 'fermee' ? 'selected' : '' }}>Fermée</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Lien avec Tâche ou Action -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tache_id" class="form-label fw-semibold">
                            <i class="bi bi-list-task me-1"></i>Tâche concernée
                        </label>
                        <select 
                            name="tache_id" 
                            id="tache_id" 
                            class="form-select form-control-ceeac @error('tache_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucune tâche --</option>
                            @foreach($taches as $tache)
                                <option value="{{ $tache['id'] }}" {{ old('tache_id') == $tache['id'] ? 'selected' : '' }}>
                                    {{ $tache['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('tache_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Lier l'alerte à une tâche spécifique (optionnel).</small>
                    </div>
                    <div class="col-md-6">
                        <label for="action_prioritaire_id" class="form-label fw-semibold">
                            <i class="bi bi-bullseye me-1"></i>Action prioritaire concernée
                        </label>
                        <select 
                            name="action_prioritaire_id" 
                            id="action_prioritaire_id" 
                            class="form-select form-control-ceeac @error('action_prioritaire_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucune action --</option>
                            @foreach($actions as $action)
                                <option value="{{ $action['id'] }}" {{ old('action_prioritaire_id') == $action['id'] ? 'selected' : '' }}>
                                    {{ $action['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('action_prioritaire_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Lier l'alerte à une action prioritaire (optionnel).</small>
                    </div>
                </div>

                <!-- Niveau d'escalade et Assignation -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="niveau_escalade" class="form-label fw-semibold">
                            <i class="bi bi-arrow-up-circle me-1"></i>Niveau d'escalade
                        </label>
                        <select 
                            name="niveau_escalade" 
                            id="niveau_escalade" 
                            class="form-select form-control-ceeac @error('niveau_escalade') is-invalid @enderror"
                        >
                            <option value="">-- Aucun niveau --</option>
                            <option value="direction" {{ old('niveau_escalade') == 'direction' ? 'selected' : '' }}>Direction</option>
                            <option value="sg" {{ old('niveau_escalade') == 'sg' ? 'selected' : '' }}>Secrétaire Général</option>
                            <option value="commissaire" {{ old('niveau_escalade') == 'commissaire' ? 'selected' : '' }}>Commissaire</option>
                            <option value="presidence" {{ old('niveau_escalade') == 'presidence' ? 'selected' : '' }}>Présidence</option>
                        </select>
                        @error('niveau_escalade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Niveau hiérarchique auquel l'alerte a été escaladée.</small>
                    </div>
                    <div class="col-md-6">
                        <label for="assignee_a_id" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Assigner à
                        </label>
                        <select 
                            name="assignee_a_id" 
                            id="assignee_a_id" 
                            class="form-select form-control-ceeac @error('assignee_a_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucun assigné --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assignee_a_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assignee_a_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Utilisateur responsable du traitement de l'alerte.</small>
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
                    <a href="{{ route('alertes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-check-circle me-2"></i>Créer l'alerte
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validation: au moins une tâche OU une action doit être sélectionnée
            const form = document.getElementById('alerteForm');
            const tacheSelect = document.getElementById('tache_id');
            const actionSelect = document.getElementById('action_prioritaire_id');

            form.addEventListener('submit', function(e) {
                if (!tacheSelect.value && !actionSelect.value) {
                    // Ce n'est pas obligatoire, mais on peut afficher un avertissement
                    // Pour l'instant, on laisse passer
                }
            });

            // Désactiver l'autre select si l'un est sélectionné (optionnel)
            tacheSelect.addEventListener('change', function() {
                if (this.value) {
                    actionSelect.value = '';
                }
            });

            actionSelect.addEventListener('change', function() {
                if (this.value) {
                    tacheSelect.value = '';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>



