<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0 fw-bold text-ceeac-blue">
                <i class="bi bi-upload me-2"></i>Import de données
            </h2>
            <a href="{{ route('ressources') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download me-2"></i>Télécharger le guide d'import
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

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Description -->
    <div class="alert alert-info border-start border-info border-4 mb-4">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
            <div>
                <h6 class="alert-heading mb-2">Import de données</h6>
                <p class="mb-0">Importez des données depuis un fichier Excel pour mettre à jour ou créer des enregistrements en masse. Assurez-vous d'utiliser le modèle de fichier fourni pour garantir un import réussi.</p>
            </div>
        </div>
    </div>

    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-arrow-up me-2"></i>Options d'importation
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" id="importForm">
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
                            <i class="bi bi-box-seam me-1"></i>Module à importer <span class="text-danger">*</span>
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
                            <option value="taches" {{ old('module') === 'taches' ? 'selected' : '' }}>
                                Tâches
                            </option>
                            <option value="alertes" {{ old('module') === 'alertes' ? 'selected' : '' }}>
                                Alertes
                            </option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Sélectionnez le module pour lequel vous souhaitez importer des données.
                        </small>
                        @error('module')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="file" class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-excel me-1"></i>Fichier Excel <span class="text-danger">*</span>
                        </label>
                        <input type="file" 
                               class="form-control form-control-ceeac @error('file') is-invalid @enderror" 
                               id="file" 
                               name="file" 
                               accept=".xlsx,.xls" 
                               required>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Formats acceptés : .xlsx, .xls (Taille maximale : 10 MB)
                        </small>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="skip_errors" name="skip_errors" value="1" {{ old('skip_errors') ? 'checked' : '' }}>
                            <label class="form-check-label" for="skip_errors">
                                <i class="bi bi-skip-forward me-1"></i>Ignorer les erreurs et continuer l'import
                            </label>
                            <small class="form-text text-muted d-block ms-4">
                                Si cette option est activée, les lignes avec des erreurs seront ignorées et l'import continuera avec les lignes valides.
                            </small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1" {{ old('update_existing') ? 'checked' : '' }}>
                            <label class="form-check-label" for="update_existing">
                                <i class="bi bi-arrow-repeat me-1"></i>Mettre à jour les enregistrements existants
                            </label>
                            <small class="form-text text-muted d-block ms-4">
                                Si cette option est activée, les enregistrements existants (identifiés par leur code ou ID) seront mis à jour au lieu d'être créés.
                            </small>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-ceeac">
                                <i class="bi bi-upload me-2"></i>Importer les données
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
                        <i class="bi bi-file-earmark-excel text-success me-2"></i>
                        <small class="text-muted">
                            <strong>Excel (.xlsx, .xls)</strong> - Format requis pour l'import
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-book text-primary me-2"></i>
                        <small class="text-muted">
                            <strong>Guide d'import</strong> - Téléchargez le modèle et la documentation
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
                        <li>Téléchargez d'abord le modèle de fichier depuis la page Ressources</li>
                        <li>Respectez la structure du modèle (colonnes, formats, etc.)</li>
                        <li>Vérifiez que toutes les données obligatoires sont renseignées</li>
                        <li>Les codes doivent être uniques pour chaque enregistrement</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-ceeac border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Points d'attention
                    </h6>
                    <ul class="mb-0 small">
                        <li>Assurez-vous que le fichier ne dépasse pas 10 MB</li>
                        <li>Vérifiez les formats de dates (JJ/MM/AAAA ou AAAA-MM-JJ)</li>
                        <li>Les champs numériques doivent être au format numérique</li>
                        <li>En cas d'erreur, consultez le rapport d'import pour les détails</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



