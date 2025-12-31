<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold text-ceeac-blue">
            <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
        </h2>
    </x-slot>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-card card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-blue me-3">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">PAPA Actifs</div>
                            <div class="h4 mb-0 text-ceeac-blue">{{ $stats['papas_actifs'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-card card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-green me-3">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Objectifs</div>
                            <div class="h4 mb-0 text-success">{{ $stats['objectifs_total'] ?? 0 }}</div>
                            <small class="text-muted">{{ $stats['objectifs_en_cours'] ?? 0 }} en cours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-card card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-yellow me-3">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Tâches en cours</div>
                            <div class="h4 mb-0 text-warning">{{ $stats['taches_en_cours'] ?? 0 }}</div>
                            <small class="text-muted">{{ $stats['taches_total'] ?? 0 }} au total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="dashboard-card card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dashboard-card-icon dashboard-card-icon-red me-3">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div>
                            <div class="text-muted small mb-1">Alertes</div>
                            <div class="h4 mb-0 text-danger">{{ $stats['alertes_ouvertes'] ?? 0 }}</div>
                            <small class="text-muted">{{ $stats['alertes_critiques'] ?? 0 }} critiques</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-ceeac">
                <div class="card-header bg-ceeac-gradient text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Vue d'ensemble
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">Bienvenue sur votre tableau de bord SUIVI-PAPA CEEAC.</p>
                    <p class="text-muted">Utilisez le menu de navigation pour accéder aux différents modules.</p>
                    
                    @if(count($alertesRecentes ?? []) > 0)
                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle me-2"></i>Alertes récentes
                        </h6>
                        <ul class="mb-0 small">
                            @foreach($alertesRecentes->take(5) as $alerte)
                            <li>
                                <span class="badge badge-gravite-{{ $alerte->criticite }} me-2">
                                    {{ ucfirst($alerte->criticite) }}
                                </span>
                                {{ Str::limit($alerte->titre, 60) }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('alertes.index') }}" class="btn btn-sm btn-warning mt-2">
                            Voir toutes les alertes
                        </a>
                    </div>
                    @endif

                    @if(count($tachesEnRetard ?? []) > 0)
                    <div class="alert alert-danger mt-3">
                        <h6 class="alert-heading">
                            <i class="bi bi-clock-history me-2"></i>Tâches en retard
                        </h6>
                        <ul class="mb-0 small">
                            @foreach($tachesEnRetard->take(5) as $tache)
                            <li>
                                {{ $tache->code }} - {{ Str::limit($tache->libelle, 50) }}
                                <span class="text-muted">
                                    ({{ $tache->date_fin_prevue->diffForHumans() }})
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('taches.index') }}?statut=en_retard" class="btn btn-sm btn-danger mt-2">
                            Voir toutes les tâches en retard
                        </a>
                    </div>
                    @endif
                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-ceeac-blue mb-2">
                                    <i class="bi bi-file-earmark-text me-2"></i>Gestion PAPA
                                </h6>
                                <p class="small text-muted mb-0">Créez et gérez vos Plans d'Action Prioritaires</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-ceeac-blue mb-2">
                                    <i class="bi bi-graph-up me-2"></i>KPI & Indicateurs
                                </h6>
                                <p class="small text-muted mb-0">Suivez vos indicateurs de performance</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-ceeac-blue mb-2">
                                    <i class="bi bi-bell me-2"></i>Alertes
                                </h6>
                                <p class="small text-muted mb-0">Consultez vos alertes et notifications</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-ceeac-blue mb-2">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Rapports
                                </h6>
                                <p class="small text-muted mb-0">Générez et exportez vos rapports</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-ceeac">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2 text-ceeac-blue"></i>Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Utilisateur connecté</small>
                        <strong>{{ Auth::user()->name }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ Auth::user()->email }}</strong>
                    </div>
                    <hr>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-ceeac w-100">
                        <i class="bi bi-person me-2"></i>Modifier mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
