<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-download me-2"></i>Export de données
        </h2>
    </x-slot>

    <!-- Messages -->
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Description -->
    <div class="alert alert-info border-start border-info border-4 mb-4">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
            <div>
                <h6 class="alert-heading mb-2">Export de données</h6>
                <p class="mb-0">Exportez les données de votre choix dans différents formats pour analyse, archivage ou partage. Les exports Excel sont disponibles pour tous les modules.</p>
            </div>
        </div>
    </div>

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-arrow-down me-2"></i>Options d'exportation
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('export.export') }}">
                @csrf
                
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

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="module" class="form-label fw-semibold">
                            <i class="bi bi-box-seam me-1"></i>Module à exporter <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-control-ceeac @error('module') is-invalid @enderror" id="module" name="module" required>
                            <option value="">Sélectionner un module</option>
                            <option value="papa" {{ old('module') === 'papa' ? 'selected' : '' }}>
                                PAPA (Plans d'Action Prioritaires)
                            </option>
                            <option value="objectifs" {{ old('module') === 'objectifs' ? 'selected' : '' }}>
                                Objectifs
                            </option>
                            <option value="kpi" {{ old('module') === 'kpi' ? 'selected' : '' }}>
                                KPI (Indicateurs de performance)
                            </option>
                            <option value="alertes" {{ old('module') === 'alertes' ? 'selected' : '' }}>
                                Alertes
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Sélectionnez le module dont vous souhaitez exporter les données.
                        </small>
                        @error('module')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-text me-1"></i>Format d'export <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-control-ceeac @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="excel" {{ old('type', 'excel') === 'excel' ? 'selected' : '' }}>
                                Excel (.xlsx)
                            </option>
                            <option value="pdf" {{ old('type') === 'pdf' ? 'selected' : '' }} disabled>
                                PDF (Bientôt disponible)
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Le format PDF sera disponible prochainement.
                        </small>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-ceeac">
                                <i class="bi bi-download me-2"></i>Exporter les données
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer bg-light">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <small class="text-muted">
                            <strong>Excel (.xlsx)</strong> - Format recommandé pour l'analyse de données
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock text-warning me-2"></i>
                        <small class="text-muted">
                            <strong>PDF</strong> - Disponible prochainement
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations supplémentaires -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card card-ceeac border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightbulb me-2 text-primary"></i>Conseils d'utilisation
                    </h6>
                    <ul class="mb-0 small">
                        <li>Les exports incluent toutes les données du module sélectionné</li>
                        <li>Les fichiers Excel peuvent être ouverts avec Microsoft Excel, LibreOffice ou Google Sheets</li>
                        <li>Les exports sont générés à la demande et incluent les données les plus récentes</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-ceeac border-start border-info border-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-shield-check me-2 text-info"></i>Informations de sécurité
                    </h6>
                    <ul class="mb-0 small">
                        <li>Les exports sont générés de manière sécurisée</li>
                        <li>Seuls les utilisateurs autorisés peuvent exporter des données</li>
                        <li>Les fichiers exportés contiennent uniquement les données auxquelles vous avez accès</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



