<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-plus-circle me-2"></i>Créer un rapport
            </h2>
            <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">
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
                <i class="bi bi-file-earmark-text me-2"></i>Informations du rapport
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

            <form method="POST" action="{{ route('rapports.store') }}" id="rapportForm">
                @csrf

                <!-- Code et Titre -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="code" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Code <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('code') is-invalid @enderror" 
                            id="code" 
                            name="code" 
                            value="{{ old('code') }}" 
                            placeholder="Ex: RPT-2025-001"
                            maxlength="32"
                            required
                        >
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Code unique identifiant le rapport.
                        </small>
                    </div>
                    <div class="col-md-8">
                        <label for="titre" class="form-label fw-semibold">
                            <i class="bi bi-card-heading me-1"></i>Titre <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('titre') is-invalid @enderror" 
                            id="titre" 
                            name="titre" 
                            value="{{ old('titre') }}" 
                            placeholder="Titre du rapport"
                            maxlength="255"
                            required
                        >
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Titre descriptif du rapport.
                        </small>
                    </div>
                </div>

                <!-- Description -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Description
                        </label>
                        <textarea 
                            class="form-control form-control-ceeac @error('description') is-invalid @enderror" 
                            id="description" 
                            name="description" 
                            rows="3"
                            placeholder="Description du rapport...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Type et Format -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1"></i>Type de rapport <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="type" 
                            id="type" 
                            class="form-select form-control-ceeac @error('type') is-invalid @enderror" 
                            required
                        >
                            <option value="">-- Sélectionner un type --</option>
                            <option value="papa" {{ old('type') === 'papa' ? 'selected' : '' }}>PAPA</option>
                            <option value="objectif" {{ old('type') === 'objectif' ? 'selected' : '' }}>Objectif</option>
                            <option value="action_prioritaire" {{ old('type') === 'action_prioritaire' ? 'selected' : '' }}>Action Prioritaire</option>
                            <option value="tache" {{ old('type') === 'tache' ? 'selected' : '' }}>Tâche</option>
                            <option value="kpi" {{ old('type') === 'kpi' ? 'selected' : '' }}>KPI</option>
                            <option value="avancement" {{ old('type') === 'avancement' ? 'selected' : '' }}>Avancement</option>
                            <option value="alerte" {{ old('type') === 'alerte' ? 'selected' : '' }}>Alerte</option>
                            <option value="synthese" {{ old('type') === 'synthese' ? 'selected' : '' }}>Synthèse</option>
                            <option value="risques_retards" {{ old('type') === 'risques_retards' ? 'selected' : '' }}>Risques & Retards</option>
                            <option value="personnalise" {{ old('type') === 'personnalise' ? 'selected' : '' }}>Personnalisé</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="format" class="form-label fw-semibold">
                            <i class="bi bi-file-earmark me-1"></i>Format <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="format" 
                            id="format" 
                            class="form-select form-control-ceeac @error('format') is-invalid @enderror" 
                            required
                        >
                            <option value="pdf" {{ old('format', 'pdf') === 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="excel" {{ old('format') === 'excel' ? 'selected' : '' }}>Excel</option>
                            <option value="csv" {{ old('format') === 'csv' ? 'selected' : '' }}>CSV</option>
                            <option value="html" {{ old('format') === 'html' ? 'selected' : '' }}>HTML</option>
                        </select>
                        @error('format')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Période -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="periode" class="form-label fw-semibold">
                            <i class="bi bi-calendar me-1"></i>Période <span class="text-danger">*</span>
                        </label>
                        <select 
                            name="periode" 
                            id="periode" 
                            class="form-select form-control-ceeac @error('periode') is-invalid @enderror" 
                            required
                        >
                            <option value="jour" {{ old('periode') === 'jour' ? 'selected' : '' }}>Jour</option>
                            <option value="semaine" {{ old('periode') === 'semaine' ? 'selected' : '' }}>Semaine</option>
                            <option value="mois" {{ old('periode', 'mois') === 'mois' ? 'selected' : '' }}>Mois</option>
                            <option value="trimestre" {{ old('periode') === 'trimestre' ? 'selected' : '' }}>Trimestre</option>
                            <option value="semestre" {{ old('periode') === 'semestre' ? 'selected' : '' }}>Semestre</option>
                            <option value="annee" {{ old('periode') === 'annee' ? 'selected' : '' }}>Année</option>
                            <option value="personnalise" {{ old('periode') === 'personnalise' ? 'selected' : '' }}>Personnalisé</option>
                        </select>
                        @error('periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4" id="date_debut_field" style="{{ old('periode') === 'personnalise' ? '' : 'display: none;' }}">
                        <label for="date_debut" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date début
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_debut') is-invalid @enderror" 
                            id="date_debut" 
                            name="date_debut" 
                            value="{{ old('date_debut') }}"
                        >
                        @error('date_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4" id="date_fin_field" style="{{ old('periode') === 'personnalise' ? '' : 'display: none;' }}">
                        <label for="date_fin" class="form-label fw-semibold">
                            <i class="bi bi-calendar-event me-1"></i>Date fin
                        </label>
                        <input 
                            type="date" 
                            class="form-control form-control-ceeac @error('date_fin') is-invalid @enderror" 
                            id="date_fin" 
                            name="date_fin" 
                            value="{{ old('date_fin') }}"
                        >
                        @error('date_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Filtres optionnels -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="papa_id" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>PAPA associé
                        </label>
                        <select 
                            name="papa_id" 
                            id="papa_id" 
                            class="form-select form-control-ceeac @error('papa_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucun --</option>
                            @foreach($papas ?? [] as $papa)
                                <option value="{{ $papa->id }}" {{ old('papa_id') == $papa->id ? 'selected' : '' }}>
                                    {{ $papa->code }} - {{ $papa->libelle }} ({{ $papa->annee }})
                                </option>
                            @endforeach
                        </select>
                        @error('papa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="objectif_id" class="form-label fw-semibold">
                            <i class="bi bi-bullseye me-1"></i>Objectif associé
                        </label>
                        <select 
                            name="objectif_id" 
                            id="objectif_id" 
                            class="form-select form-control-ceeac @error('objectif_id') is-invalid @enderror"
                        >
                            <option value="">-- Aucun --</option>
                            @foreach($objectifs ?? [] as $objectif)
                                <option value="{{ $objectif['id'] }}" {{ old('objectif_id') == $objectif['id'] ? 'selected' : '' }}>
                                    {{ $objectif['libelle'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('objectif_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Options avancées -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="est_automatique" name="est_automatique" value="1" {{ old('est_automatique') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="est_automatique">
                                <i class="bi bi-clock-history me-1"></i>Génération automatique
                            </label>
                        </div>
                        <small class="form-text text-muted">Cochez pour générer ce rapport automatiquement selon une fréquence définie.</small>
                    </div>
                    <div class="col-md-6" id="frequence_cron_field" style="{{ old('est_automatique') ? '' : 'display: none;' }}">
                        <label for="frequence_cron" class="form-label fw-semibold">
                            <i class="bi bi-calendar-repeat me-1"></i>Fréquence
                        </label>
                        <select 
                            name="frequence_cron" 
                            id="frequence_cron" 
                            class="form-select form-control-ceeac @error('frequence_cron') is-invalid @enderror"
                        >
                            <option value="">-- Sélectionner --</option>
                            <option value="daily" {{ old('frequence_cron') === 'daily' ? 'selected' : '' }}>Quotidien</option>
                            <option value="weekly" {{ old('frequence_cron') === 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="monthly" {{ old('frequence_cron') === 'monthly' ? 'selected' : '' }}>Mensuel</option>
                        </select>
                        @error('frequence_cron')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="destinataires" class="form-label fw-semibold">
                            <i class="bi bi-envelope me-1"></i>Destinataires (emails)
                        </label>
                        <input 
                            type="text" 
                            class="form-control form-control-ceeac @error('destinataires') is-invalid @enderror" 
                            id="destinataires" 
                            name="destinataires" 
                            value="{{ old('destinataires') }}" 
                            placeholder="email1@example.com, email2@example.com"
                        >
                        @error('destinataires')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Séparez les emails par des virgules.</small>
                    </div>
                </div>

                <!-- Notes -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label for="notes" class="form-label fw-semibold">
                            <i class="bi bi-sticky me-1"></i>Notes internes
                        </label>
                        <textarea 
                            class="form-control form-control-ceeac @error('notes') is-invalid @enderror" 
                            id="notes" 
                            name="notes" 
                            rows="2"
                            placeholder="Notes internes sur ce rapport...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex gap-2 justify-content-end mt-4 pt-4 border-top">
                    <a href="{{ route('rapports.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary btn-ceeac">
                        <i class="bi bi-save me-2"></i>Créer le rapport
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodeSelect = document.getElementById('periode');
            const dateDebutField = document.getElementById('date_debut_field');
            const dateFinField = document.getElementById('date_fin_field');
            const estAutomatiqueCheckbox = document.getElementById('est_automatique');
            const frequenceCronField = document.getElementById('frequence_cron_field');

            if (periodeSelect && dateDebutField && dateFinField) {
                periodeSelect.addEventListener('change', function() {
                    if (this.value === 'personnalise') {
                        dateDebutField.style.display = 'block';
                        dateFinField.style.display = 'block';
                    } else {
                        dateDebutField.style.display = 'none';
                        dateFinField.style.display = 'none';
                    }
                });
            }

            if (estAutomatiqueCheckbox && frequenceCronField) {
                estAutomatiqueCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        frequenceCronField.style.display = 'block';
                    } else {
                        frequenceCronField.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>


