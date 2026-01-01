<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold text-ceeac-blue">
                    <i class="bi bi-graph-up-arrow me-2"></i>{{ $kpi->libelle }}
                </h2>
                <small class="text-muted">{{ $kpi->code }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('kpi.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                </a>
                @if($kpi->actionPrioritaire)
                    <a href="{{ route('actions-prioritaires.show', $kpi->actionPrioritaire) }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-check me-2"></i>Voir l'action
                    </a>
                @endif
                @can('update', $kpi)
                    <a href="{{ route('kpi.edit', $kpi) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-ceeac" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Informations générales -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong class="text-muted">Code:</strong>
                            <div class="fw-semibold">{{ $kpi->code }}</div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Statut:</strong>
                            <div>
                                @php
                                    $statutBadge = match($kpi->statut) {
                                        'actif' => 'bg-success',
                                        'inactif' => 'bg-secondary',
                                        'archive' => 'bg-dark',
                                        default => 'bg-info',
                                    };
                                @endphp
                                <span class="badge {{ $statutBadge }}">
                                    {{ ucfirst($kpi->statut ?? 'N/A') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <strong class="text-muted">Unité:</strong>
                            <div>
                                <span class="badge bg-secondary">{{ $kpi->unite ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($kpi->description)
                    <div class="mb-3">
                        <strong class="text-muted">Description:</strong>
                        <p class="mb-0">{{ $kpi->description }}</p>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong class="text-muted">Date de mesure:</strong>
                            <div>
                                @if($kpi->date_mesure)
                                    <strong>{{ $kpi->date_mesure->format('d/m/Y à H:i') }}</strong>
                                @else
                                    <span class="text-muted">Non définie</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted">Action prioritaire:</strong>
                            <div>
                                @if($kpi->actionPrioritaire)
                                    <a href="{{ route('actions-prioritaires.show', $kpi->actionPrioritaire) }}" class="text-decoration-none">
                                        <span class="badge bg-primary">{{ $kpi->actionPrioritaire->code }}</span>
                                    </a>
                                    <br><small class="text-muted">{{ $kpi->actionPrioritaire->libelle }}</small>
                                    @if($kpi->actionPrioritaire->objectif)
                                        <br><small class="text-muted">
                                            Objectif: {{ $kpi->actionPrioritaire->objectif->code }} - {{ $kpi->actionPrioritaire->objectif->libelle }}
                                        </small>
                                        @if($kpi->actionPrioritaire->objectif->papaVersion)
                                            <br><small class="text-muted">
                                                PAPA: {{ $kpi->actionPrioritaire->objectif->papaVersion->papa->annee ?? 'N/A' }} 
                                                v{{ $kpi->actionPrioritaire->objectif->papaVersion->numero ?? 'N/A' }}
                                            </small>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted">Non associé</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance -->
        <div class="col-md-4">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-speedometer2 me-2"></i>Performance
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $pourcentage = $kpi->pourcentage_realisation ?? ($kpi->valeur_cible > 0 && $kpi->valeur_realisee !== null 
                            ? ($kpi->valeur_realisee / $kpi->valeur_cible) * 100 
                            : 0);
                        $pourcentageClass = $pourcentage >= 100 ? 'bg-success' : ($pourcentage >= 80 ? 'bg-warning' : 'bg-danger');
                        $performanceText = $pourcentage >= 100 ? 'Objectif atteint' : ($pourcentage >= 80 ? 'En bonne voie' : 'Sous objectif');
                    @endphp
                    <div class="text-center mb-3">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar {{ $pourcentageClass }}" 
                                 role="progressbar" 
                                 style="width: {{ min($pourcentage, 100) }}%"
                                 aria-valuenow="{{ $pourcentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <strong>{{ number_format($pourcentage, 1) }}%</strong>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">{{ $performanceText }}</small>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <strong class="text-muted d-block">Cible</strong>
                            <h4 class="mb-0">{{ number_format($kpi->valeur_cible, 2, ',', ' ') }}</h4>
                            <small class="text-muted">{{ $kpi->unite ?? '' }}</small>
                        </div>
                        <div class="col-6">
                            <strong class="text-muted d-block">Réalisé</strong>
                            <h4 class="mb-0 {{ $kpi->valeur_realisee >= $kpi->valeur_cible ? 'text-success' : 'text-danger' }}">
                                {{ number_format($kpi->valeur_realisee ?? 0, 2, ',', ' ') }}
                            </h4>
                            <small class="text-muted">{{ $kpi->unite ?? '' }}</small>
                        </div>
                    </div>
                    @php
                        $ecart = $kpi->valeur_ecart ?? ($kpi->valeur_realisee !== null && $kpi->valeur_cible !== null 
                            ? ($kpi->valeur_realisee - $kpi->valeur_cible) 
                            : 0);
                    @endphp
                    <hr>
                    <div class="text-center">
                        <strong class="text-muted d-block">Écart</strong>
                        <h5 class="mb-0 {{ $ecart >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 2, ',', ' ') }}
                        </h5>
                        <small class="text-muted">{{ $kpi->unite ?? '' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes associées -->
    @if($kpi->alertes->count() > 0)
    <div class="card card-ceeac mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>Alertes associées ({{ $kpi->alertes->count() }})
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Titre</th>
                            <th>Criticité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kpi->alertes as $alerte)
                            <tr>
                                <td>{{ $alerte->date_creation->format('d/m/Y H:i') }}</td>
                                <td>{{ $alerte->titre }}</td>
                                <td>
                                    <span class="badge bg-{{ $alerte->criticite === 'critique' ? 'danger' : ($alerte->criticite === 'elevee' ? 'warning' : 'info') }}">
                                        {{ ucfirst($alerte->criticite) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $alerte->statut === 'resolue' ? 'success' : 'warning' }}">
                                        {{ ucfirst(str_replace('_', ' ', $alerte->statut)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('alertes.show', $alerte) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Historique des valeurs (si disponible) -->
    <div class="card card-ceeac">
        <div class="card-header bg-ceeac-gradient text-white">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>Informations complémentaires
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong class="text-muted">Créé le:</strong>
                    <div>{{ $kpi->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="col-md-6">
                    <strong class="text-muted">Modifié le:</strong>
                    <div>{{ $kpi->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



