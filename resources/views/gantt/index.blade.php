<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-diagram-3 me-2"></i>Diagramme de Gantt
        </h2>
    </x-slot>

    <div class="card card-ceeac mb-4">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-range me-2"></i>Visualisation temporelle des Plans d'Action Prioritaires
            </h5>
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form id="ganttFilters" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="papa_id" class="form-label fw-semibold">
                        <i class="bi bi-folder me-1"></i>PAPA
                    </label>
                    <select name="papa_id" id="papa_id" class="form-select form-control-ceeac">
                        <option value="">Tous les PAPA</option>
                        @foreach($papas as $papa)
                            <option value="{{ $papa->id }}" {{ $selectedPapaId == $papa->id ? 'selected' : '' }}>
                                {{ $papa->libelle }} ({{ $papa->annee }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="version_id" class="form-label fw-semibold">
                        <i class="bi bi-file-earmark-text me-1"></i>Version
                    </label>
                    <select name="version_id" id="version_id" class="form-select form-control-ceeac">
                        <option value="">Toutes les versions</option>
                        @if($selectedPapaId)
                            @foreach($papas->where('id', $selectedPapaId)->first()->versions ?? [] as $version)
                                <option value="{{ $version->id }}" {{ $selectedVersionId == $version->id ? 'selected' : '' }}>
                                    {{ $version->libelle }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-ceeac w-100">
                        <i class="bi bi-funnel me-2"></i>Filtrer
                    </button>
                </div>
            </form>

            <!-- Légende -->
            <div class="alert alert-light border mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-palette me-2"></i>Légende des couleurs
                        </h6>
                        <div class="d-flex flex-wrap gap-3">
                            <span class="badge badge-gravite-normal">
                                <i class="bi bi-circle-fill me-1" style="color: #0d6efd;"></i>Normal
                            </span>
                            <span class="badge badge-gravite-vigilance">
                                <i class="bi bi-circle-fill me-1" style="color: #ffc107;"></i>Vigilance
                            </span>
                            <span class="badge badge-gravite-critique">
                                <i class="bi bi-circle-fill me-1" style="color: #dc3545;"></i>Critique
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2">
                            <i class="bi bi-info-circle me-2"></i>Types de tâches
                        </h6>
                        <div class="d-flex flex-wrap gap-3">
                            <span><i class="bi bi-square me-1" style="color: #1e40af;"></i>Phase</span>
                            <span><i class="bi bi-square me-1" style="color: #0d6efd;"></i>Tâche</span>
                            <span><i class="bi bi-diamond me-1" style="color: #7c3aed;"></i>Jalon</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barre d'outils Gantt -->
            <div class="card mb-3">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group" id="ganttViewModes">
                                <button type="button" class="btn btn-sm btn-outline-primary active" data-view="Day" title="Vue jour">
                                    <i class="bi bi-calendar-day me-1"></i>Jour
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-view="Week" title="Vue semaine">
                                    <i class="bi bi-calendar-week me-1"></i>Semaine
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-view="Month" title="Vue mois">
                                    <i class="bi bi-calendar-month me-1"></i>Mois
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-view="Quarter Day" title="Vue trimestre">
                                    <i class="bi bi-calendar-range me-1"></i>Trimestre
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="ganttZoomIn" title="Zoom avant">
                                    <i class="bi bi-zoom-in"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="ganttZoomOut" title="Zoom arrière">
                                    <i class="bi bi-zoom-out"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="ganttFit" title="Ajuster à l'écran">
                                    <i class="bi bi-arrows-angle-contract"></i>
                                </button>
                            </div>
                            @if($editable)
                                <span class="badge bg-success ms-2">
                                    <i class="bi bi-pencil me-1"></i>Mode édition activé
                                </span>
                            @else
                                <span class="badge bg-secondary ms-2">
                                    <i class="bi bi-eye me-1"></i>Mode lecture seule
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteneur Gantt -->
            <div id="gantt-container" class="gantt-container" style="min-height: 600px;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-3 text-muted">Chargement du diagramme de Gantt...</p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Frappe Gantt CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">
    <link rel="stylesheet" href="{{ asset('css/gantt.css') }}">
    @endpush

    @push('scripts')
    <!-- Frappe Gantt JS -->
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
    <script>
        // Configuration
        const GANTT_CONFIG = {
            editable: {{ $editable ? 'true' : 'false' }},
            papasData: @json($papasData),
            selectedPapaId: {{ $selectedPapaId ?? 'null' }},
            selectedVersionId: {{ $selectedVersionId ?? 'null' }},
        };
    </script>
    @vite(['resources/js/gantt/index.js'])
    @endpush
</x-app-layout>

